<?php

    /**
     * Created by PhpStorm.
     * User: Gololobov
     * Date: 23.10.2015
     * Time: 12:16
     */
    
    class MakeXLS
    {
        const   EXTENSION  = '.xls';
        private $_filename = 'report';
        private $_headers  = array();
        private $_rows     = array();
        private $_row;
        private $_col;
        private $_content  = '';

        public function __construct(array $headers, array $rows, $title = '')
        {
            $this->_headers = $headers;
            $this->_rows    = $rows;
            $this->_content = $this->_xlsBOF();

            $this->_col = 0;
            
            foreach ($this->_headers as $header => $name) {

                $this->_row = 0;
                $this->_content .= $this->_xlsWriteLabel($name);
                
                foreach ($this->_rows as $row) {
                    $this->_row++;
                    $this->_content .= $this->_xlsWriteLabel($row[$header]);
                }
                
                $this->_col++;
            }

            $this->_content .= $this->_xlsEOF();
            
            if ($title) {
                $this->_fileTitle($title);
            }
            
            $filename = $this->_filename.self::EXTENSION;
            $handle = fopen($filename, 'a');
            fwrite($handle, $this->_content);
            fclose($handle);

            if ($this->_content) {
                header('Content-Type: application/xls');
                header("Content-Disposition: attachment; filename=$filename");
                header("Content-Transfer-Encoding: binary ");
                readfile($filename);
            }
        }

        protected function __destruct()
        {
            unlink($this->_filename.self::EXTENSION);
        }

        protected function _fileTitle($title)
        {
            $result = $this->_translitCyr($title);
            $result = strtolower(trim($result));
            $result = str_replace("'", '', $result);
            $result = preg_replace('#[^a-z0-9_]+#', '-', $result);
            $result = preg_replace('#\-{2,}#', '-', $result);
            $result = preg_replace('#(^\-+|\-+$)#D', '', $result);
            
            $this->_filename = $result;
        }

        protected function _xlsBOF()
        {
            return pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
        }

        protected function _xlsEOF()
        {
            return pack("ss", 0x0A, 0x00);
        }

        protected function _xlsWriteNumber($Value)
        {
            return pack("sssss", 0x203, 14, $this->_row, $this->_col, 0x0).pack("d", $Value);
        }

        protected function _xlsWriteLabel($Value)
        {
            $Value = $this->_utfToWin($Value);
            $length = strlen($Value);

            return pack("ssssss", 0x204, 8 + $length, $this->_row, $this->_col, 0x0, $length).$Value;
        }

        protected function _utfToWin($string)
        {
            return iconv('UTF-8', 'WINDOWS-1251//IGNORE', $string);
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