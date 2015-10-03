<?php

    class RightsMask
    {
        public static function getRightsStr($rightsValue, $short = false)
        {
            Locale::loadFile(Wbs::getPublishedPath("common/templates/localization"), "common");
            switch ($rightsValue) {
                case 0:
                    return ($short) ? "N" : Locale::getStr("common", "rights_no");
                case 1:
                    return ($short) ? "R" : Locale::getStr("common", "rights_read");
                case 3:
                    return ($short) ? "RW" : Locale::getStr("common", "rights_write");
                case 7:
                    return ($short) ? "RWF" : Locale::getStr("common", "rights_full");
                default:
                    return "Unknown rights: " + rightsValue;
            }
        }

        public static function hasReadRights($rightsValue)
        {
            return $rightsValue > 0;
        }

        public static function hasWriteRights($rightsValue)
        {
            return $rightsValue >= 3;
        }

        public static function hasFolderRights($rightsValue)
        {
            return $rightsValue == 7;
        }
    }

?>