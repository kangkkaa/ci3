<?php /* vim: set ts=4 sw=4 syntax=php fdm=marker: */

/* ===================================================================
* PATH		: /ssd/html/application/helpers/common_helper.php
* DESC		: 공통함수
* URL		:
* CODE		: @function
* --------------------------------------------------------------------
* COMMENTS TODO::새파일
* --------------------------------------------------------------------
* 2020-02-13
* ================================================================= */
defined('BASEPATH') or exit('No direct script access allowed');

/* ===================================================================
* DESC		: 파일 로그 기록하기
* INPUT		: string	$filename	파일명
*			: mixed		$msg		메시지
*			: string	$folder		폴더
*			: string	$time_type	파일명에 추가할 시간타입
* OUTPUT	: string	$data		필터링된 텍스트
* --------------------------------------------------------------------
* COMMENTS
* --------------------------------------------------------------------
* 2020-02-08
* ================================================================= */
if (!function_exists('write_log')) {
    function write_log($filename, $msg = '', $folder = '', $time_type = 'D')
    {
        if (empty($folder) === false) {
            exec_mkdir(PATH_LOG.rtrim($folder, DIRECTORY_SEPARATOR));
        }

        switch ($time_type) {
            case 'Y':
                $write_path = PATH_LOG.rtrim($folder, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$filename.'_'.date('Y', strtotime('NOW')).CNF_SUFFIX_LOG;
                break;
            case 'M':
                $write_path = PATH_LOG.rtrim($folder, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$filename.'_'.date('Ym', strtotime('NOW')).CNF_SUFFIX_LOG;
                break;
            case 'D':
                $write_path = PATH_LOG.rtrim($folder, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$filename.'_'.date('Ymd', strtotime('NOW')).CNF_SUFFIX_LOG;
                break;
            case 'H':
                $write_path = PATH_LOG.rtrim($folder, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$filename.'_'.date('YmdH', strtotime('NOW')).CNF_SUFFIX_LOG;
                break;
            default:
                $write_path = PATH_LOG.rtrim($folder, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$filename.CNF_SUFFIX_LOG;
                break;
        }

        /*
        $debug		= debug_backtrace();
        $file		= $debug[0]['file'];
        $line		= $debug[0]['line'];
        $logs	= array();
        $logs[] = '==========================================='."\n";
        $logs[] = 'TIME : '.date("Y-m-d H:i:s", strtotime('NOW'))."\n";
        $logs[] = 'FILE : '.$file."\n";
        $logs[] = 'LINE : '.$line."\n";
        $logs[] = var_export($msg, true)."\n";
        $logs[] = '==========================================='."\n";
        $log_msg= implode("", $logs);
        */

        if ( is_array($msg) )
        {
            $log_msg = var_export($msg, true);
        }
        else
        {
            $log_msg = $msg;
        }

        return @error_log($log_msg."\n", 3, $write_path);
    }
}

/*====================================================================
* DESC		: 디렉토리 생성
* INPUT		: string	$dir_name	디렉토리
*			: integer	$chmod		권한
* OUTPUT	: void
* --------------------------------------------------------------------
* COMMENTS
* --------------------------------------------------------------------
* 2020-02-08
* ==================================================================*/
if (!function_exists('exec_mkdir')) {
    function exec_mkdir($dir_name = '', $chmod = 0777)
    {
        if (empty($dir_name) === true) {
            return false;
        }

        // 절대경로부터 시작함
        $dirs = explode('/', $dir_name);
        $d = '/';
        foreach ($dirs as $v) {
            if ($v === '') {
                continue;
            }

            $d .= $v . '/';
            if (!is_dir($d) && strlen($d) > 0) {
                umask(0);
                if (!mkdir($d, $chmod)) {
                    return false;
                }
            }
        }

        @chmod($dir_name, $chmod);
        return true;
    }
}

/*====================================================================
* DESC		: 텔레그램 푸쉬 API
* INPUT		: string	$msg		메시지
* OUTPUT	: void
* --------------------------------------------------------------------
* COMMENTS
* --------------------------------------------------------------------
* 2020-02-13
* ==================================================================*/
if (! function_exists('telegramApiRequest')) {
    function telegramApiRequest($msg, $ids=null)
    {
        $CI =& get_instance();
        $CI->config->load('config_telegram');
        $token = $CI->config->item('token');
        $chat_id_arr = $CI->config->item('chat_id');
        $chat_id_key = array_keys($chat_id_arr);

        // 받을 아이디만 배열로
        if ( empty($ids) === false )
        {
            $id_arr = array();
            if ( strpos($ids, ',') !== false )
            {
                $arr = explode(',', $ids);
                foreach ( $arr AS $v )
                {
                    $v = trim($v);
                    if ( in_array($v, $chat_id_key) )
                    {
                        $id_arr[] = $chat_id_arr[$v];
                    }
                }
            }
            else
            {
                if ( in_array($ids, $chat_id_key) )
                {
                    $ids = trim($ids);
                    $id_arr[] = $chat_id_arr[$ids];
                }
            }

            $chat_ids = $id_arr;
        }
        else
        {
            $chat_ids = $chat_id_arr;
        }

        // 푸시발송
        if ( empty($chat_ids) === false && count($chat_ids) > 0 )
        {
            foreach ( $chat_ids AS $chat_id )
            {
                $parameters = array(
                    'chat_id'	=> $chat_id,
                    'text'		=> $msg,
                );

                if ( !$parameters )
                {
                    $parameters = array();
                }
                elseif ( !is_array($parameters) )
                {
                    error_log("Parameters must be an array\n");
                    return false;
                }

                $url = 'https://api.telegram.org/bot'.$token.'/sendMessage?'.http_build_query($parameters);
                $handle = curl_init($url);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($handle, CURLOPT_TIMEOUT, 60);

                $response = curl_exec($handle);
                if ( $response === false )
                {
                    $errno = curl_errno($handle);
                    $error = curl_error($handle);
                    curl_close($handle);

                    // 로그기록
                    $log = array();
                    $log['ERROR']	= 'Curl error: '.$error.' ('.$errno.')';
                    $log['MESSAGE'] = $msg;
                    write_log('push_error', $log, 'telegram', 'D');
                }

                $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
                curl_close($handle);

                $response = json_decode($response, true);
                if ( $http_code != 200 )
                {
                    // 로그기록
                    $log = array();
                    $log['ERROR']	= 'HTTP_CODE: '.$http_code;
                    $log['MESSAGE'] = "Request has failed with error ".$response['error_code'].": ".$response['description']."\n";
                    $log['DATA']	= var_export($parameters, true)."\n";
                    write_log('push_error', $log, 'telegram', 'D');
                }
            }
        }
        else
        {
            return NULL;
        }
    }
}

/*====================================================================
* DESC		: 텔레그램 푸시 알림 : 백그라운드로 실행 (단문 알림 메시지)
* INPUT		: string	$msg		메시지
* OUTPUT	: void
* --------------------------------------------------------------------
* COMMENTS
* --------------------------------------------------------------------
* 2020-02-08
* ==================================================================*/
if (!function_exists('push_alarm')) {
    function push_alarm($msg, $ids=null)
    {
        if ( is_array($msg) === true )
        {
            $msg = var_export($msg, true);
        }

        // 캐릭터셋 설정
        $is_utf8 = mb_detect_encoding($msg, 'UTF-8', true);
        if ($is_utf8 === false) {
            $charset = mb_detect_encoding($msg, 'auto');
            $msg = $charset.'||'.mb_convert_encoding($msg, "UTF-8");
        }

        $temp_str = @iconv('UTF-8', 'UTF-8', $msg);
        if ($msg !== $temp_str) {
            $msg = iconv('CP949', 'UTF-8', $msg);
        }

        $debug	= debug_backtrace();
        $file	= $debug[0]['file'];
        $line	= $debug[0]['line'];

        $push_msg_arr = array();
        $push_msg_arr[] = '===========================';
        $push_msg_arr[] = 'FILE : '.$file;
        $push_msg_arr[] = 'LINE : '.$line;
        $push_msg_arr[] = 'TIME : '.date('Y-m-d H:i:s');
        $push_msg_arr[] = '===========================';
        // 약 4000자까지만 발송되는 듯 (파일명이 길수도 있으니, 3800자까지만 보는 걸로 => file path 때문에 3700으로 줄였습니다.
        $push_msg_arr[] = mb_substr($msg, 0, 3700);

        $push_msg = @implode("\n", $push_msg_arr);

        // 텔레그램 푸시 발송
        telegramApiRequest($push_msg, $ids);
    }
}

/*====================================================================
* DESC		: 텔레그램 푸시 알림 : 백그라운드로 실행 (단문 알림 메시지)
* INPUT		: string	$msg		메시지
* OUTPUT	: void
* --------------------------------------------------------------------
* COMMENTS
* --------------------------------------------------------------------
* 2020-02-08
* ==================================================================*/
if (!function_exists('add_info')) {
    function add_info($msg)
    {
        if ( is_array($msg) === true )
        {
            $msg = var_export($msg, true);
        }

        $debug	= debug_backtrace();
        $file	= $debug[0]['file'];
        $line	= $debug[0]['line'];

        $uid = md5(uniqid(rand(), true));

        $push_msg_arr = array();
        $push_msg_arr[] = '============================ [START : '.$uid.']';
        $push_msg_arr[] = 'FILE : '.$file;
        $push_msg_arr[] = 'LINE : '.$line;
        $push_msg_arr[] = 'TIME : '.date('Y-m-d H:i:s');
        $push_msg_arr[] = '-----------------------------------------------------------------------';
        $push_msg_arr[] = $msg;
        $push_msg_arr[] = '============================ [END : '.$uid.']';

        $push_msg = @implode("\n", $push_msg_arr);
        return "\n".$push_msg;
    }
}

function ms_escape_string($data) {
    if ( !isset($data) or empty($data) ) return '';
    if ( is_numeric($data) ) return $data;

    $non_displayables = array(
        '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
        '/%1[0-9a-f]/',             // url encoded 16-31
        '/[\x00-\x08]/',            // 00-08
        '/\x0b/',                   // 11
        '/\x0c/',                   // 12
        '/[\x0e-\x1f]/'             // 14-31
    );
    foreach ( $non_displayables as $regex )
        $data = preg_replace( $regex, '', $data );
    $data = str_replace("'", "''", $data );
    return $data;
}

/*====================================================================
* DESC		: script 태그 제거
* INPUT		: string		$item		메시지
* INPUT		: array			$array		배열
* INPUT		: array/string	$default	메시지/배열
* OUTPUT	: void
* --------------------------------------------------------------------
* COMMENTS
* --------------------------------------------------------------------
* 2020-02-08
* ==================================================================*/

if ( ! function_exists('element_xss')){

	function element_xss($item, array $array, $default = NULL){
		return array_key_exists($item, $array) ? script_tag($array[$item]) : $default;
	}
}
