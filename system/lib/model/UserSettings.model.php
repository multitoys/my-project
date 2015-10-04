<?php
class UserSettingsModel extends DbModel
{
	protected $table = 'USER_SETTINGS';
	
	public function set($user_id, $app_id, $name, $value)
	{
		$sql = "REPLACE INTO USER_SETTINGS 
				SET U_ID = s:user_id, APP_ID = s:app_id, name = s:name, value = s:value";
		return $this->prepare($sql)->exec(array(
			'user_id' => $user_id,
			'app_id' => $app_id,
			'name' => $name,
			'value' => $value
		));
	}
	
	public function get($user_id, $app_id, $name)
	{
		$sql = "SELECT VALUE FROM ".$this->table." WHERE U_ID = s:user_id AND APP_ID = s:app_id AND name = s:name";
		return $this->prepare($sql)->query(array(
			'user_id' => $user_id,
			'app_id' => $app_id,
			'name' => $name
		))->fetchField('VALUE');
	}
	
	public function getAll($user_id, $app_id)
	{
		$sql = "SELECT NAME, VALUE FROM ".$this->table.' WHERE (U_ID = s:user_id OR U_ID = "") AND APP_ID = s:app_id';
		$data = $this->prepare($sql)->query(array('user_id' => $user_id, 'app_id' => $app_id));
		$settings = array();
		foreach ($data as $row) {
			$settings[$row['NAME']] = $row['VALUE'];
		}
		return $settings;
	}
}
?>