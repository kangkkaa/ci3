<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


defined('BOARD_CT')      OR define('BOARD_CT',
		array(
				0 => "안전학교"
			,	1 => "초등 1학년 학부모반"
			,	2 => "방학숙제교실"
			,	3 => "어린이 통학버스교실"
			,	4 => "초등돌봄교실"
			,	5 => "녹색어머니교실"
			,	6 => "어린이집 안전교실"
			,	7 => "유치원 안전교실"
		)
);


defined('BOARD_GB')      OR define('BOARD_GB',
array(
	//안전학교
	0 =>	array(
				'교장선생님 인사말'=>'principalGreeting',
				'교통안전교실'=>'trafficSafety',
				'학교안전교실'=>'schoolSafety',
				'수상안전교실'=>'waterSafety',
				'생활안전교실'=>'lifeSafety',
				'가정안전교실'=>'homeSafety',
				'미아/유괴 안전교실'=>'missingSafety',
				'재난 안전교실'=>'disasterSafety',
				'응급처치교실'=>'firstAid',
				'보건감염예방교실'=>'healthInfection',
				'생명사랑교실'=>'lifeLove',
				'중독·질식 예방교실'=>'addictionPrevention',
				'선진국어린이 안전교실'=>'advancedCountry',

			)
	,
	//초등 1학년 학부모반
	1 =>	array(
		'학부모당부말씀'=>'schoolParent',
		'교통안전'=>'parent_trafficSafety',
		'유괴·미아 예방'=>'kidnapChild',
		'가정안전'=>'parent_homeSafety',

	)
	,
	//방학숙제교실
	2 =>	array(
		'1~5차시 동영상필수교육'=>'winterVacation',
		'6~10차시 동영상선택교육'=>'winterVacation2',

	)
	,
	//어린이 통학버스교실
	3 =>	array(
		'통학버스사고사례보기'=>'schooBust',
		'시설장 역할'=>'facilityRole',
		'운전자 역할'=>'driverRole',
		'인솔교육 역할'=>'leadingRole',


	)
	,
	//초등돌봄교실
	4 =>	array(
		'방과 후 돌봄'=>'primaryCare',
		'게임 중심 안전 놀이 방법'=>'gameSafety',
		'안전지도방법'=>'guidanceMethod',
	)
	,
	//녹색어머니교실
	5 =>	array(
		'안전교육이 최고의 보약'=>'greenMother',
		'녹색지도방법안내'=>'greenGuidance',
		'통학로 사고유형과 예방법'=>'schoolzoneWay',
		'3가지 습관 기르기'=>'threeHabits',

	)
	,
	//어린이집 안전교실
	6 =>	array(
		'교사용'=>'nurserySafety_teacher',
		'부모님용'=>'nurserySafety_parent',
		'어린이용'=>'nurserySafety_kid',

	)
	,
	//유치원 안전교실
	7 =>	array(
		'교사용'=>'kindergartenSafety_teacher',
		'부모님용'=>'kindergartenSafety_parent',
		'어린이용'=>'kindergartenSafety_kid',

	)


)

); // highest automatically-assigned error code
