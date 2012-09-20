<?php

/**
 * Title: String
 * Description: This object contains info about an string
 * Copyright: Copyright (c) 2005 - 2010
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class String {
	/**
	 * Tests if this string starts with the specified prefix.
	 * 
	 * @param prefix the prefix
	 */
	public static function startsWith($string, $prefix = null) {
		return strncmp($string, $prefix, strlen($prefix)) == 0;
	}

	/**
	 * Tests if this string ends with the specified suffix.
	 * 
	 * @param suffix the suffix
	 */
	public static function endsWith($string, $suffix = null) {
		return substr($string, strlen($string) - strlen($suffix)) === $suffix;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Find the string between the first occurence of start end the first occurence of end
	 * 
	 * @param $str
	 * @param $start
	 * @param $end
	 * @return unknown_type
	 */
	public static function between($string, $start, $end = null) {
		// @todo false check is probaly not good
		$start = strpos($string, $start) + strlen($start);
		if($start === false) {
			return false;
		}

		$string = substr($string, $start);

		$end = strpos($string, $end);
		if($end === false) {
			return false;
		}

		return substr($string, 0, $end);
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Omit
	 * 
	 * @param string $string the string to omit
	 * @param int $length the desired length
	 * @param string $end the string to suffic the trimmed string with
	 * http://en.wikipedia.org/wiki/Ellipsis
	 * @return string
	 */
	public static function omit($string, $length = null, $end = '…') {
		if(!isset($length)) {
			return $string;
		} elseif(strlen($string) > $length) {
			return substr($string, 0, $length) . $end;
		} else {
			return $string;
		}
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Return the string until $break is found
	 * 
	 * @param string $string
	 * @param string $break
	 * @return string
	 */
	public static function before($string, $break) {
		$break = strpos($string, $break);

		if($break !== false) {
			return substr($string, 0, $break);
		} else {
			return $string;
		}
	}
}
