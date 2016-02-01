<?php

    class ClassManager
    {

        /**
         * Get reference to new object of class
         *
         * @param string $ClassName
         *
         * @return mixed
         */
        static public function &getInstance($ClassName)
        {
        
            $Object = null;
        
            if (ClassManager::includeClass($ClassName)) {
                eval('$Object = new '.$ClassName.'();');
            }
        
            return $Object;
        }
    
        /**
         * Include class file
         *
         * @param string $ClassName
         *
         * @return bool
         */
        static public function includeClass($ClassName)
        {

            if (!class_exists(strtolower($ClassName), false)) {

                if (!file_exists(DIR_CLASSES.'/class.'.strtolower($ClassName).'.php')) {
                    return false;
                    //                    die('Class '.$ClassName.' doesnt exist!');
                }
                
                include_once(DIR_CLASSES.'/class.'.strtolower($ClassName).'.php');

                if (isset($_GET['debug']) && ($_GET['debug'] === 'files')) {
                    $backtrace = debug_backtrace();
                    $backtrace = $backtrace[2];
                    $backtrace = str_replace(realpath(DIR_ROOT).DIRECTORY_SEPARATOR, '', $backtrace['file']).':'.$backtrace['line'];
                    print "<pre>\ninclude {$ClassName}\n{$backtrace}\n</pre>";
//                    print "<pre>\ninclude ".ClassManager::generateCallTrace()."</pre>";
                }
            }

            return true;
        }

        private function generateCallTrace()
        {
            $e = new Exception();
            $trace = explode("\n", $e->getTraceAsString());
            // reverse array to make steps line up chronologically
            $trace = array_reverse($trace);
            array_shift($trace); // remove {main}
            array_pop($trace); // remove call to this method
            $length = count($trace);
            $result = array();

            for ($i = 0; $i < $length; $i++) {
                $result[] = ($i + 1) . ')' . substr($trace[$i], strpos($trace[$i], ' ')); // replace '#someNum' with '$i)', set the right ordering
            }

            return "\t" . implode("\n\t", $result);
        }
    }