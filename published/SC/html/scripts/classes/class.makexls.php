<?php

    /**
     * Created by PhpStorm.
     * User: Gololobov
     * Date: 23.10.2015
     * Time: 12:16
     */
    
    class MakeXLS
    {
        const EXTENSION   = '.xls';
        public $filename  = 'report';
        private $_headers = array();
        private $_rows    = array();
        private $_content = '';

        public function __construct(array $headers, array $rows)
        {
            $this->_headers = $headers;
            $this->_rows    = $rows;
            $this->_content = $this->_xlsBOF();

            $colnumber = 0;
            
            foreach ($this->_headers as $header => $name) {
                
                $this->_content .= $this->_xlsWriteLabel(0, $colnumber, $name);
                $rownumber = 0;
                
                foreach ($this->_rows as $row) {
                    $rownumber++;
                    $this->_content .= $this->_xlsWriteLabel($rownumber, $colnumber, $row[$header]);
                }
                
                $colnumber++;
            }

            $this->_content .= $this->_xlsEOF();
            
            $filename = $this->filename.self::EXTENSION;
            $handle = fopen($filename, 'a');
            fwrite($handle, $this->_content);
            fclose($handle);
            $filename = $this->_translitCyr($filename);


            if ($this->_content) {
                header('Content-Type: application/xls');
                header("Content-Disposition: attachment; filename=$filename");
                readfile($filename);
            }
        }

        protected function __destruct()
        {
            unlink($this->filename.self::EXTENSION);
        }
        
        protected function _xlsBOF()
        {
            return pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
        }

        protected function _xlsEOF()
        {
            return pack("ss", 0x0A, 0x00);
        }

        protected function _xlsWriteNumber($Row, $Col, $Value)
        {
            return pack("sssss", 0x203, 14, $Row, $Col, 0x0).pack("d", $Value);
        }

        protected function _xlsWriteLabel($Row, $Col, $Value)
        {
            $Value = $this->_utfToWin($Value);
            $L = strlen($Value);

            return pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L).$Value;
        }

        protected function _utfToWin($p)
        {
            return iconv('UTF-8', 'WINDOWS-1251//IGNORE', $p);
        }

        protected function _translitCyr($cyr_str)
        {
            $tr = array("Ґ" => "G", "Ё" => "YO", "Є" => "E", "Ї" => "YI", "І" => "I",
                        "і" => "i", "ґ" => "g", "ё" => "yo", "№" => "_", "є" => "e",
                        "ї" => "yi", "А" => "A", "Б" => "B", "В" => "V", "Г" => "G",
                        "Д" => "D", "Е" => "E", "Ж" => "ZH", "З" => "Z", "И" => "I",
                        "Й" => "Y", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N",
                        "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T",
                        "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "TS", "Ч" => "CH",
                        "Ш" => "SH", "Щ" => "SCH", "Ъ" => "'", "Ы" => "YI", "Ь" => "",
                        "Э" => "E", "Ю" => "YU", "Я" => "YA", "а" => "a", "б" => "b",
                        "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "zh",
                        "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
                        "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
                        "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
                        "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "",
                        "ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya",
                        " " => "_", "," => "", "#" => "_"
            );

            return strtr($cyr_str, $tr);
        }
    }