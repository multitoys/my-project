<?php
    
    class Crypt
    {
         //CC number
        // Purpose	encrypts cc_number field ( see ORDERS_TABLE in database_structure.xml )
        public static function CCNumberCrypt($cc_number)
        {
            return base64_encode($cc_number);
        }
        
        // Purpose	decrypts cc_number field ( see ORDERS_TABLE in database_structure.xml )
        public static function CCNumberDeCrypt($cifer)
        {
            return base64_decode($cifer);
        }
        
        //Holder name
        // Purpose	encrypts cc_holdername field ( see ORDERS_TABLE in database_structure.xml )
        public static function CCHoldernameCrypt($cc_holdername)
        {
            return base64_encode($cc_holdername);
        }
        
        // Purpose	decrypts cc_holdername field ( see ORDERS_TABLE in database_structure.xml )
        public static function CCHoldernameDeCrypt($cifer)
        {
            return base64_decode($cifer);
        }
        
        //CCExpires
        public static function CCExpiresCrypt($cc_expires)
        {
            return base64_encode($cc_expires);
        }
        
        // Purpose	decrypts cc_expires field ( see ORDERS_TABLE in database_structure.xml )
        public static function CCExpiresDeCrypt($cifer)
        {
            return base64_decode($cifer);
        }
        
        //Password
        
        // Purpose	encrypts customer ( and admin ) password field
        //					( see ORDERS_TABLE in database_structure.xml )
        public static function PasswordCrypt($password)
        {
            return base64_encode($password);
        }
        
        // Purpose	decrypts customer ( and admin ) password field ( see ORDERS_TABLE in database_structure.xml )
        public static function PasswordDeCrypt($cifer)
        {
            return base64_decode($cifer);
        }
        
        //FileParam
        
        // Purpose	encrypts getFileParam
        // Remarks	see also get_file.php
        public static function FileParamCrypt($getFileParam)
        {
            return base64_encode($getFileParam);
        }
        
        // Purpose	decrypt getFileParam
        // Remarks	see also get_file.php
        public static function FileParamDeCrypt($cifer)
        {
            return base64_decode($cifer);
        }
    }