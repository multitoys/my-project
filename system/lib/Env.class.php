<?php

class Env
{
    const TYPE_INT = 1;
    const TYPE_STRING = 2;
    const TYPE_STRING_TRIM = 3;
    const TYPE_BASE64_INT = 4;
    const TYPE_BASE64 = 5;
    const TYPE_ARRAY_INT = 6;

    protected function __construct ()
    {}

    /**
     * Cast value to the type
     * 
     * @param unknown $val
     * @param int $type
     * 
     */
    static function Cast ($val, $type = false)
    {
        switch ($type) {
            case self::TYPE_INT: {
                return intval($val);
            }
            case self::TYPE_BASE64: {
                return base64_decode($val);
            }
            case self::TYPE_BASE64_INT: {
                return intval(base64_decode($val));
            }
            case self::TYPE_STRING_TRIM: {
                return trim($val); 
            }
            case self::TYPE_ARRAY_INT: {
            	foreach ($val as &$v) {
            		$v = self::Cast($v, self::TYPE_INT);
            	} 
            	return $val;
            }
            case self::TYPE_STRING:
            default: {
                return $val;
            }
        }
    }

    /**
     * @param string $name
     * @param int $type
     * @param unknown $default
     */
    static function Get ($name = false, $type = false, $default = false)
    {
        if (!$name) {
            return $_GET;
        }
        $value = isset($_GET[$name]) ? $_GET[$name] : $default;
        return $type ? self::Cast($value, $type) : $value;
    }

    
    /**
     * @param string $name
     * @param int $type
     * @param unknown $default
     */    
    static function Post ($name = false, $type = false, $default = false)
    {
        if (!$name) {
            return $_POST;
        }
        $value = isset($_POST[$name]) ? $_POST[$name] : $default;
        return $type ? self::Cast($value, $type) : $value;
    }
    
    /**
     * @param string $name
     * @param int $type
     * @param unknown $default
     */    
    static function Cookie ($name = false, $type = false, $default = false)
    {
       if (!$name) {
            return $_COOKIE;
        }
        $value = isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
        return $type ? self::Cast($value, $type) : $value;
    }

    
    static function setCookie ($name, $value, $expires = 0, $path = '/', $domain = '.', $secure = false, $httponly = false)
    {
        setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
    }
    
    
    /**
     * @param string $name 
     * @param unknown $default
     */
    static function Session ($name, $default = false)
    {
        if (! $name) {
            return $_SESSION;
        }
        return isset($_SESSION[$name]) ? $_SESSION[$name] : $default;
    }

    static function setSession ($name, $value)
    {
        $_SESSION[$name] = $value;
        return $value;
    }

    static function unsetSession ($name = false)
    {
        if ($name) {
            unset($_SESSION[$name]);
        } else {
            $_SESSION = array();
            session_destroy();
        }
    }
    
    static function Server($name = false, $default = false)
    {
        if (!$name) {
            return $_SERVER;
        }
        return isset($_SERVER[$name]) ? $_SERVER[$name] : $default;
    }
    
}
?>