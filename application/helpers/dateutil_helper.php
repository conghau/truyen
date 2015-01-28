<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * CodeIgniter DateUtil Helpers
 *
 * date util helpers.
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author Takeo Noda Sevenmedia Inc.
 * @link
 */

// --------------------------------------------------------------------
setlocale (LC_TIME, "ja_JP");

/**
*/
if ( ! function_exists('time2date'))
{
	function time2date( $time, $splitter="-") {
		$dp = getdate( intval($time) );
		return sprintf("%04d$splitter%02d$splitter%02d",$dp["year"],$dp["mon"],$dp["mday"]);
	}
}

// --------------------------------------------------------------------

/**
*/
if ( ! function_exists('time2datetime'))
{
	function time2datetime( $time, $dsplitter="-", $tsplitter=":") {
		$dp = getdate( $time );
		return sprintf("%04d$dsplitter%02d$dsplitter%02d %02d$tsplitter%02d$tsplitter%02d",$dp["year"],$dp["mon"],$dp["mday"],$dp["hours"],$dp["minutes"],$dp["seconds"]);
	}
}


// --------------------------------------------------------------------

/**
 */
if ( ! function_exists('date2time'))
{
	function date2time( $date ) {
		$splitted = preg_split( '/[\/: \-]/', $date );
		$matches = array();
		if (empty($date)) {
			return time();
        // strtotime should handle it
		} else if ($date > "2038-01-19") {
			return date2time("2038-01-19");
		} else if (count($splitted) == 3 && preg_match('/^[0-9]+([\/: -][0-9]+)+$/', $date)) {
			list( $year, $month, $day) = $splitted;
			if (checkdate($month, $day, $year)) {
				return strtotime(sprintf("%04d-%02d-%02d", min(2038,$year), $month, $day));
			} else {
				return time();
			}
		} else if (preg_match('/^(\d{4})(\d{2})(\d{2})$/', $date, $matches)) {
			list($all, $year, $month, $day) = $matches;
			if (checkdate($month, $day, $year)) {
				return strtotime(sprintf("%04d-%02d-%02d", min(2038,$year), $month, $day));
			} else {
				return time();
			}
		} else {
			return time();
		}
	}
}


// --------------------------------------------------------------------
/**
 */
if ( ! function_exists('time2thisweek_date'))
{
	function time2thisweek_date( $time, $wday = 1 ) {
		$dinfo = getdate($time);
		$diff  = $dinfo['wday'] - $wday;
		return prev_date(time2date($time), $diff);
	}
}

// --------------------------------------------------------------------
/**
 */
if ( ! function_exists('date2wday'))
{
	function date2wday( $date ) {
		return time2wday(date2time($date));
	}
}

if ( ! function_exists('time2wday'))
{
	function time2wday( $datetime ) {
		$dinfo = getdate(intval($datetime));
		return $dinfo['wday'];
	}
}

// --------------------------------------------------------------------
/**
 */
if ( ! function_exists('calendar_start_time'))
{
	// $time は Y-m-01　の timestamp を指定する
	function calendar_start_time( $time ) {
		$wday = time2wday($time);
		$time = $time - $wday * 86400;
		return $time;
	}
}
 
/**
 */
if ( ! function_exists('calendar_end_time'))
{
	function calendar_end_time( $time ) {
		$time = calendar_start_time($time) + 86400 * 42;
		return $time;
	}
}
 
 
// --------------------------------------------------------------------
/**
 */
if ( ! function_exists('prev_date'))
{
	function prev_date( $date, $diff = 1 ) {
		return time2date(date2time($date) - 86400 * $diff);
	}
}


// --------------------------------------------------------------------

/**
 */
if ( ! function_exists('next_date'))
{
	function next_date( $date, $diff = 1 ) {
		return time2date(date2time($date) + 86400 * $diff);
	}
}

// --------------------------------------------------------------------

/**
 */	
if ( ! function_exists('next_month'))
{	
	function next_month( $date, $ct=1, $splitter="-" ) {
		$recordset = preg_split('/[-\/]/', $date);
		if (count($recordset) < 2) {
			return "";
		}
		$yyyy = $recordset[0];
		$mm = $recordset[1];
		$bm = $ct % 12;
		$by = (int) ($ct / 12);
	    list($yyyy, $mm) = ($mm + $bm <= 12 ? array($yyyy + $by , $mm + $bm) : array($yyyy + $by + 1, $mm + $bm - 12));
		return sprintf ("%04d$splitter%02d", $yyyy, $mm);
	}
}

if ( ! function_exists('prev_month'))
{	
	function prev_month( $date, $ct=1, $splitter="-" ) {
		$recordset = preg_split('/[-\/]/', $date);
		if (count($recordset) < 2) {
			return "";
		}
		$yyyy = $recordset[0];
		$mm = $recordset[1];
		$bm = $ct % 12;
		$by = (int) ($ct / 12);
	    list($yyyy, $mm) = ($mm - $bm >= 1 ? array($yyyy - $by , $mm - $bm) : array($yyyy - $by - 1, $mm - $bm + 12));
		return sprintf ("%04d$splitter%02d", $yyyy, $mm);
	}
}

// --------------------------------------------------------------------
/**
*/
if ( ! function_exists('round_week_date'))
{
	// 0: 日曜日 1: 月曜日 2: 火曜日 3: 水曜日 4: 木曜日 5: 金曜日 6: 土曜日
	function round_week_date( $wday, $date) {
		$dp = getdate( date2time($date) );
		$wayback = $dp['wday'] >= $wday ? $dp['wday'] - $wday : $dp['wday'] + 7 - $wday ; // 直近のターゲット日までの差分を調べる
		return prev_date($date, $wayback);
	}
}

/**
 */
if ( ! function_exists('date2yearmonth'))
{
	/**
	 * 指定した日付からY-mを取得する
	 * @param 	String		$time		タイムスタンプ
	 * @return	Integer		$date		日時
	 */
	function date2yearmonth( $date, $splitter = '-' ) {
		$splitted = preg_split('/[\/: \-]/', $date );
		if (count($splitted) >= 2 && checkdate($splitted[1], 1, $splitted[0])) {
			return sprintf("%04d$splitter%02d", $splitted[0], $splitted[1]);
		} else if (preg_match('/^(\d{4})(\d{2})(\d{2})$/', $date, $matches)) {
			list($all, $year, $month, $day) = $matches;
			if (checkdate($month, $day, $year)) {
				return sprintf("%04d$splitter%02d", $year, $month);
			}
			return "";
		} else if (preg_match('/^(\d{4})(\d{2})$/', $date, $matches)) {
			list($all, $year, $month) = $matches;
			if (checkdate($month, 1, $year)) {
				return sprintf("%04d$splitter%02d", $year, $month);
			}
			return "";
		} else {
			return "";
		}
	}
}

if ( ! function_exists('date2year'))
{
	/**
	 * 指定した日付からYを取得する
	 * @param 	String		$time		タイムスタンプ
	 * @return	Integer		$date		日時
	 */
	function date2year( $date, $splitter = '-' ) {
		/* EDIT - 2014/07/29 - FJN)HAU-TC - STA */
		//$splitted = split( '[/: -]', $date );
		$splitted = preg_split( '/[\/: \-]/', $date );
		/* EDIT - 2014/07/29 - FJN)HAU-TC- END */
		if (count($splitted) > 0) {
			return sprintf("%04d", $splitted[0]);
		} else {
			return "";
		}
	}
}

if ( ! function_exists('date2month'))
{
	/**
	 * 指定した日付からYを取得する
	 * @param 	String		$time		タイムスタンプ
	 * @return	Integer		$date		日時
	 */
	function date2month( $date, $splitter = '-' ) {
		$splitted = preg_split( '/[\/: \-]/', $date );
		if (count($splitted) > 1) {
			return sprintf("%02d", $splitted[1]);			
		} else {
			return "";
		}
	}
}


// --------------------------------------------------------------------

/**
 */
if ( ! function_exists('datetime2time'))
{
	function datetime2time( $datetime ) {
		$splitted = preg_split( '/[\/: \-]/', $datetime );
		if (count($splitted) == 6 && preg_match('/^[0-9]+([\/: -][0-9]+)+$/', $datetime)) {
			list( $year, $month, $day, $hour, $min, $sec) = $splitted;
			if (checkdate($month, $day, $year)) {
				return strtotime(sprintf("%04d-%02d-%02d %02d:%02d:%02d", min(2038,$year), $month, $day, $hour, $min, $sec));
			} else {
				return time();
			}
		} else {
			return time();
		}
	}
}

// --------------------------------------------------------------------

/**
 */
if ( ! function_exists('parse_date'))
{
	function parse_date( $date, $splitter = '/[\/\-_\s]/' ) {
		if (preg_match('/^\d{8}$/', $date)) {
			$yy = substr($date,0,4);
			$mm = substr($date,4,2);
			$dd = substr($date,6,2);
		} else if ($date == '') {
			return false;
		} else {
			$recordset = preg_split($splitter, $date);
			if (count($recordset) >= 3) {
				list($yy, $mm, $dd) = $recordset;
			} else {
				return false;
			}
		}
		return array($yy, $mm, $dd);
	}
}
// --------------------------------------------------------------------

/**
 */
if ( ! function_exists('format_date'))
{
	function format_date( $date, $format ) {
		list($yy, $mm, $dd) = $date;
		return sprintf($format, $yy, $mm, $dd);
	}
}
// --------------------------------------------------------------------

/**
 */
if ( ! function_exists('date_to_age'))
{
	function date_to_age( $date, $splitter = '/[\/\-_\s]/' ) {
		if ($date == '') {
			return '';
		}
		list($yy, $mm, $dd) = parse_date($date, $splitter);
		$age = (int)((date('Ymd') - sprintf("%04d%02d%02d", $yy, $mm, $dd)) / 10000);
		return $age;
	}
}
// --------------------------------------------------------------------

/**
 */
if ( ! function_exists('injection_date_converter'))
{
	function injection_date_converter( $date, $format1, $format2 ) {
		$splitted = preg_split( '/[\/: \-]/', $date );
		if (count($splitted) == 3) {
			if ($splitted[2] == '96') {
				return sprintf("%s上旬", date($format2, date2time(sprintf("%04d-%02d-01", $splitted[0], $splitted[1]))));
			} else if ($splitted[2] == '97') {
				return sprintf("%s中旬", date($format2, date2time(sprintf("%04d-%02d-01", $splitted[0], $splitted[1]))));
			} else if ($splitted[2] == '98') {
				return sprintf("%s下旬", date($format2, date2time(sprintf("%04d-%02d-01", $splitted[0], $splitted[1]))));
			} else if ($splitted[2] >= 1 && $splitted[2] <= 31) {
				return date($format1, date2time(sprintf("%04d-%02d-%02d", $splitted[0], $splitted[1], $splitted[2])));
			} else {
				return sprintf("%s未定", date($format2, date2time(sprintf("%04d-%02d-01", $splitted[0], $splitted[1]))));
			}
		} else if (count($splitted) == 2) {
			return sprintf("%s中", date($format2, date2time(sprintf("%04d-%02d-01", $splitted[0], $splitted[1]))));
		}
		return "ERROR";
	}
}

// --------------------------------------------------------------------

/**
 */
if ( ! function_exists('date2astro'))
{
	function date2astro( $datetime ) {
		$splitted = preg_split( '/[\/: \-]/', $datetime );
		if (preg_match('/^[0-9]+([\/: -][0-9]+)+$/', $datetime)) {
			list( $year, $month, $day, $hour, $min, $sec) = $splitted;
			if (checkdate($month, $day, $year)) {
				$t = sprintf("%02d%02d", $month, $day);
				if ($t >= "0321" && $t <= "0419") {
					return "おひつじ座";
				} else if ($t >= "0420" && $t <= "0520") {
					return "おうし座";
				} else if ($t >= "0521" && $t <= "0621") {
					return "ふたご座";
				} else if ($t >= "0622" && $t <= "0722") {
					return "かに座";
				} else if ($t >= "0723" && $t <= "0822") {
					return "しし座";
				} else if ($t >= "0823" && $t <= "0922") {
					return "おとめ座";
				} else if ($t >= "0923" && $t <= "1023") {
					return "てんびん座";
				} else if ($t >= "1024" && $t <= "1121") {
					return "さそり座";
				} else if ($t >= "1122" && $t <= "1221") {
					return "いて座";
				} else if (($t >= "1222" && $t <= "1231") || ($t >= "0101" && $t <="0119")) {
					return "やぎ座";
				} else if ($t >= "0120" && $t <= "0218") {
					return "みずがめ座";
				} else if ($t >= "0219" && $t <= "0220") {
					return "うお座";
				}
			} else {
				return "";
			}
		} else {
			return "";
		}
	}
}

// --------------------------------------------------------------------

/**
 */
if ( ! function_exists('time2youbi'))
{
	function time2youbi( $time ) {
		return strftime("%w", $time);
	}
}

if (! function_exists('end_of_month')) {
	function end_of_month( $date ) {
		list($yyyy, $mm) = preg_split('/[\-\/]/', next_month( $date ));
		return prev_date(sprintf ("%04d-%02d-%02d", $yyyy, $mm, 1));
	}
}
/* End of file inflector_helper.php */
/* Location: ./application/helpers/inflector_helper.php */