<?php
class configuration extends Module
{
    function initInterfaces()
    {
        $this->Interfaces = array(
            'req_setting' => array(
				'name' => '������ (�����������������)',
				'type' => INTDIVAVAILABLE,
            )
           ,'check_gmapi_key' => array(
				'name' => '������ (�����������������)',
				'type' => INTDIVAVAILABLE,
           )
        );
    }
    
    function getSettingValue($setting_name)
    {
        return constant($setting_name);
    }
    
    function setSettingValue($setting_name, $settings_value)
    {
        $sql = "update ".SETTINGS_TABLE." set settings_value = '".addslashes($settings_value)."' where settings_constant_name = '{$setting_name}'";
        db_query($sql);
    }
};
?>