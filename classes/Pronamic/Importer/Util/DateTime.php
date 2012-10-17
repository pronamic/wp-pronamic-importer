<?php

class Pronamic_Importer_Util_DateTime {
	public static function convert_timestamp( $timestamp, $timezone_from, $timezone_to ) {
		$timezone_from = new DateTimeZone( $timezone_from );
		$timezone_to   = new DateTimeZone( $timezone_to );

		$date = new DateTime( '@' . $timestamp, $timezone_from );
		$date->setTimezone( $timezone_to );

		return $date;
	}
}
