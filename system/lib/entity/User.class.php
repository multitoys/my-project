<?php

class User
{
	/**
	 * Return name of the user 
	 * 
	 * Format
	 * F - First Name
	 * m, M - Middle Name
	 * L - Last Name
	 * n, N - Nick Name
	 * e, E - E-mail
	 * 
	 * @param $info
	 * @param $format
	 * @return unknown_type
	 */
	public static function getName($info, $format = "F m L n e")
	{
		$codes = preg_split("/([FmMLNnEe])/u", $format, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
		$result = "";
		$flag = false;
		$delete_space = false;
		foreach ($codes as $code) {
			if ($delete_space && $code == ' ') {
				$delete_space = false;
				continue;
			}
			$delete_space = false;
			$part = self::getPartName($info, $code, $flag);
			if (in_array($code, array("F", "m", "M", "L"))) {
				$flag = $flag | strlen(trim($part)) > 0;	
			}			
			if (!$part) {
				$delete_space = true;
			}
			$result .= $part;
		}
		return trim(preg_replace("!(<[a-z]+>[\s\t]*</[a-z]+>)!", "", $result));
	}
	
	protected static function getPartName($info, $code, $flag = false)
	{
		switch ($code) {
			case 'F':
				return $info['C_FIRSTNAME'];
			case 'm':
				return $info['C_MIDDLENAME'] ? mb_substr($info['C_MIDDLENAME'], 0, 1)."." : "";
			case 'M':
				return $info['C_MIDDLENAME'];
			case 'L':
				return $info['C_LASTNAME'];
			case 'n':
				return $flag ? ($info['C_NICKNAME'] ? "(".$info['C_NICKNAME'].")" : "") : $info['C_NICKNAME'];
			case 'N':
				return $info['C_NICKNAME'];
			case 'e':
				return $info['C_EMAILADDRESS'] ? "<".$info['C_EMAILADDRESS'].">" : "";
			case 'E':
				return $info['C_EMAILADDRESS'];
			default: 
				return $code;
		}
	}
	
}


?>