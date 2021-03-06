<?php 

class WbsDateTime 
{
    /**
     * @var CTimeZone
     */
    public static $serverTimeZone;
    /**
     * @var CTimeZone
     */    
    public static $displayTimeZone;
    /**
     * @var string
     */
    public static $dateFormat;
    
    /**
     * @var Date Formats
     */
    public static $dateFormats = array(
			"MM/DD/YYYY" => array ("phpFormat" => "m/d/Y"),
			"MM.DD.YYYY" => array ("phpFormat" => "m.d.Y"),
			"DD.MM.YYYY" => array ("phpFormat" => "d.m.Y")
		);
    
    
    public static function init($timezone = false)
    {
        self::$dateFormat = self::$dateFormats[Wbs::getDbkeyObj()->getDateFormat()];
        self::$serverTimeZone = Wbs::getSystemObj()->getTimeZone();
        self::$displayTimeZone = $timezone ? $timezone : self::$serverTimeZone;  
    }
    
    public static function setTimeZone($timeZone)
    {
        self::$displayTimeZone = $timeZone;
    }
    
   
    /**
     * Convert timestamp to date with timezone
     * 
     * @param $timestamp - unixtime
     * @param CTimeZone|bool $timezone - use $timezone (object CTimeZone) or false
     * @return string
     */
    public static function getDate($timestamp, $timezone = false)
    {
        if (!$timezone) {
            $timezone = self::$displayTimeZone;
        }
        if ($timezone) {
	        $fromOffset = self::$serverTimeZone->getOffset($timestamp);
			$toOffset = $timezone->getOffset($timestamp);
	        $timestamp = $timestamp - ($fromOffset - $toOffset);
        }
        return date(self::$dateFormat['phpFormat'], $timestamp);
    }
    
    /**
     * Convert timestamp to datetime with timezone
     * 
     * @param $timestamp - unixtime
     * @param CTimeZone|bool $timezone - use $timezone (object CTimeZone) or false
     * @param $timeFormat - php format of the time, default H:i
     * @return string
     */    
    public static function getTime($timestamp, $timezone = false, $timeFormat = "H:i")
    {
        if (!$timezone) {
            $timezone = self::$displayTimeZone;
        } 
        if (!$timezone && CurrentUser::getInstance()) {
            $timezone = CurrentUser::getInstance()->getTimeZone();       
        }
        if ($timezone) {
	        $fromOffset = self::$serverTimeZone->getOffset($timestamp);
			$toOffset = $timezone->getOffset($timestamp);
	        $timestamp = $timestamp - ($fromOffset - $toOffset);
        } 
        return date(self::$dateFormat['phpFormat']. " ".$timeFormat, $timestamp);
     }    
	 
    public static function getTimeStamp($timestamp, $timezone = false, $toServer = false)
    {
        if (!$timezone) {
            $timezone = self::$displayTimeZone;
        } 
        if (!$timezone) {
            $timezone = CurrentUser::getInstance()->getTimeZone();       
        }
        if ($timezone) {
	        $fromOffset = $toServer ? $timezone->getOffset($timestamp) : self::$serverTimeZone->getOffset($timestamp);
			$toOffset = $toServer ? self::$serverTimeZone->getOffset($timestamp) : $timezone->getOffset($timestamp);
			
	        $timestamp = $timestamp - ($fromOffset - $toOffset);
        } 
        return $timestamp;
     }    
	 
    
     public static function getServerTime($timestamp, $timezone = false, $timeFormat = "Y-m-d H:i:s") 
     {
        if (!$timezone) {
            $timezone = self::$displayTimeZone;
        } 
        if (!$timezone) {
            $timezone = CurrentUser::getInstance()->getTimeZone();       
        }
        if ($timezone) {
	        $fromOffset = self::$serverTimeZone->getOffset($timestamp);
			$toOffset = $timezone->getOffset($timestamp);
	        $timestamp = $timestamp + ($fromOffset - $toOffset);
        } 
        return date($timeFormat, $timestamp);
     }
     
    /**
     * Return current datetime of the current user or in the timezone
     * 
     * @param $timeZone - if false used timezone of the current user
     * @return string
     */ 
    public static function now($timeZone = false)
    {
        if (!$timeZone) {
            $timeZone = CurrentUser::getInstance()->getTimeZone();
        }
        return self::getTime(time(), $timeZone);
    }
    
    
    public static function unixtime($date)
    {
        $regexp = self::$dateFormat['phpFormat'];
        $regexp = preg_replace("/(d|m|Y)/u", "([dmY])", $regexp);
        $regexp = str_replace("/", "\/", $regexp);
        if (!preg_match("!".$regexp."!u", self::$dateFormat['phpFormat'], $pos)) {
            return false;
        }
        
        $regexp = str_replace(array("d", "m", "Y"), "([0-9]+)", self::$dateFormat['phpFormat']);
        $regexp = str_replace("/", "\/", $regexp);
        if (!preg_match("!".$regexp."!u", $date, $matches)) {
            return false;
        }

        $keys = array();
        foreach ($pos as $key => $value) {
            if ($value == "d") {
                $keys["d"] = $matches[$key];
            }
            if ($value == "m") {
                $keys["m"] = $matches[$key];
            }
            if ($value == "Y") {
                $keys["Y"] = $matches[$key];
            }            
        }
        
        $parts = explode(" ", $date);
       	$time_parts = isset($parts[1]) ?  explode(":", $parts[1]) : array();
       	
        $hour = isset($time_parts[0]) ? $time_parts[0] : 0;
        $min = isset($time_parts[1]) ? $time_parts[1] : 0;
        $sec = isset($time_parts[2]) ? $time_parts[2] : 0;        
        return mktime($hour, $min, $sec, $keys["m"], $keys["d"], $keys["Y"]);

    }
   
}

?>