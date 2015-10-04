<?php

/**
 * Class for read user rights
 * 
 * @author WebAsyst Team
 * @since Jan 2009
 * @version 1.0 
 *
 */
class Rights
{
	const FUNCTIONS = 'FUNCTIONS';
	const FOLDERS = 'FOLDERS';
	const MESSAGES = 'MESSAGES';

	const USER = 0;
	const GROUP = 1;
	
	const RIGHT_READ = 1;
	const RIGHT_WRITE = 3;
	const RIGHT_FOLDER = 7;
	
	public static $rights = array();
	public static $inherit_rights = array();
	
	public static $apps = array();

	protected $id;
	protected $type;
	/**
	 * Constructor 
	 * 
	 * @param $id - U_ID of the user or UG_ID of the group
	 */
	public function __construct($id, $type = self::USER)
	{
		$this->id = $id;
		$this->type = $type;
	}
	
	
	public function getApps()
	{
		$users_rights_model = new UsersRightsModel();
		$app_ids = $users_rights_model->getAvailableApps($this->id);
		$apps = array();
		foreach ($app_ids as $app_id) {
			if ($app_info = $this->getApplicationInfo($app_id)) {
				$apps[$app_id] = $app_info['TITLE'];
			}
		}
		return $this->sortApps($apps);
	}
	
	public function sortApps($apps)
	{
		$sort = array();
		foreach ($apps as $app_id => $v) {
			$app_info = $this->getApplicationInfo($app_id);
			$sort[$app_id] = $app_info['ORDER'];
		}
		asort($sort);
	    $result = array();
    	foreach ($sort as $app_id => $n) {
    		$result[$app_id] = $apps[$app_id];
    	}		
    	return $result;
	}
	
	public function getApplicationInfo($app_id)
	{
		if (isset(self::$apps[$app_id])) {
			return self::$apps[$app_id];
		} else {
    		$filename = AppPath::APP_PATH($app_id)."/_rights.php";
    		if (file_exists($filename)) {
				include($filename);
				if (isset($rights[$app_id])) {
					self::$apps[$app_id] = array(
						'TITLE' => $rights[$app_id]['TITLE'],
						'ORDER' => $rights[$app_id]['ORDER'],
					);
				} else {
					self::$apps[$app_id] = false;
				}
    		} else {
    			self::$apps[$app_id] = false;
    		}
		}
		return self::$apps[$app_id];
	}
	
    /**
     * Returns right (code) of the object in section of the application
     * 
     * @example
     * 		// Check access of the user to reports in Files  
     * 		$rights = new Rights(CurrentUser::getId());
     * 		$right = $rights->get('DD', Rights::FUNCTIONS, 'CANREPORTS');
     * @param $app_id
     * @param $section_id
     * @param $object_id
     * @return array(ALL_RIGHT, USER_RIGHT, USER_GROUPS_RIGHT)
     */
    public function get($app_id, $section_id, $object_id, $app_only = false)
    {
    	// @todo: Use session for save current app rights   

    	if (!isset(self::$rights[$this->id]) || ($app_only && !isset(self::$rights[$this->id][$app_id]))) {
			$rights_model = $this->getModel();
			if ($app_only) {
				self::$rights[$this->id][$app_id] = $rights_model->getRights($this->id, $app_only ? $app_id : false);
			} else {
				self::$rights[$this->id] = $rights_model->getRights($this->id, false);
				if (!isset(self::$rights[$this->id][$app_id])) {
					self::$rights[$this->id][$app_id] = array();
				}
			}    		
		} 
    	return isset(self::$rights[$this->id][$app_id][$section_id][$object_id]) ? 
    		   self::$rights[$this->id][$app_id][$section_id][$object_id] : 
    		   array(0, 0, 0);
    }
    
    /**
     * Returns Rights Model
     * 
     * @return UsersRightsModel|GroupsRightsModel
     */
    public function getModel()
    {
    	if ($this->type == self::GROUP) {
    		return new GroupsRightsModel();
    	} else {
    		return new UsersRightsModel();
    	}
    }
    
    /**
     * Return inherit rights 
     * 
     * @param $app_id
     * @param $section_id
     * @param $object_id
     * @param $app_only
     * @return array - array(RIGHT, USER_RIGHT, GROUP_RIGHT)
     */
    public function getInherit($app_id, $section_id, $object_id, $app_only = false) 
    {
    	if (!isset(self::$inherit_rights[$app_id])) {
			$rights_model = $this->getModel();
			if ($app_only) {
				self::$inherit_rights[$app_id] = $rights_model->getInheritRights($app_only ? $app_id : false);
			} else {
				self::$inherit_rights = $rights_model->getInheritRights(false);
				if (!isset(self::$inherit_rights[$app_id])) {
					self::$inherit_rights[$app_id] = array();
				}
			}    		
       	}
       	if (isset(self::$inherit_rights[$app_id][$section_id][$object_id])) {
       		$link = self::$inherit_rights[$app_id][$section_id][$object_id];
       		return $this->get($link['app_id'], $link['section_id'], $link['object_id'], $app_only);
       	} else {
       		return array(0, 0, 0);
       	}
    } 
    
    
    /**
     * Return array with all rights of the user
     * Use in rights reports etc.
     * Optimized for JSON response
     * 		array(
     * 			APP_ID,
     * 			APP_TITLE,
     * 			SCREEN_ID,
     * 			SCREEN_RIGHT,
     * 			SECTIONS, // array
     * 			FOLDERS // array
     * 
     * @return array()
     */
    public function getAll()
    {    	
    	$apps = array();
    	$sort = array();
    	foreach (Wbs::getDbkeyObj()->getApplicationsList() as $app_id) {
    		$filename = AppPath::APP_PATH($app_id)."/_rights.php";
    		if (file_exists($filename)) {
				include($filename);
				if (isset($rights[$app_id])) {
					$app_rights = $rights[$app_id];
					$sort[$app_id] = isset($app_rights['ORDER']) ? $app_rights['ORDER'] : 0;
					$apps[$app_id] = array(
						$app_rights['APP_ID'], // 0
						$app_rights['TITLE'], // 1
						$app_rights['SCREEN_ID'], //2
						$this->get($app_id, 'SCREENS', $app_rights['SCREEN_ID']), // 3
					);

					// Sections
					
					// Shopping Cart save rights description in database 
					if ($app_id == 'SC') {
						$db_model = new DbModel();
				      	$admin_id = $db_model->query("SELECT xID FROM `SC_divisions` WHERE xUnicKey = 'admin'")->fetchField('xID');
						// Get id of the language
				      	$lang = mb_substr(CurrentUser::getLanguage(), 0, 2);
					    $lang_id = $db_model->prepare("SELECT id FROM `SC_language` WHERE iso2 = s:lang AND enabled = 1")->query(array('lang' => $lang))->fetchField('id');
					    if (!$lang_id) {
							$lang_id = $db_model->query("SELECT id FROM `SC_language` WHERE enabled = 1")->fetchField('id'); 
					    }
					    
					    $sql = 'SELECT D1.xID ID, IF(SL.value IS NULL, D1.xName, SL.value) NAME, IF(D1.xParentID = i:admin_id, "ROOT", D1.xParentID) PARENT
				      			FROM `SC_divisions` D1 JOIN  
				      				 `SC_divisions` D2 ON D1.xParentID = D2.xID LEFT JOIN
				      				 `SC_local` SL ON D1.xName = SL.id AND SL.lang_id = i:lang_id
								WHERE (D2.xID = i:admin_id || D2.xParentID = i:admin_id) AND D1.xEnabled = 1
								ORDER BY D2.xPriority DESC, D1.xPriority DESC';
					    
					    $data = $db_model->prepare($sql)->query(array('admin_id' => $admin_id, 'lang_id' => $lang_id));

					    foreach ($app_rights['SECTIONS'] as &$section) {
							foreach ($section['OBJECTS'] as &$object) {
								// Read right of the object
								$object[] = $section['ID'] ? $this->get($app_id, $section['ID'], $object[0]) : array(0, 0, 0);
							}
							$section = array_values($section);
						}
						$sections = $app_rights['SECTIONS'];
					    
					    foreach ($data as $row) {
					    	if ($row['PARENT'] == 'ROOT') {
					    		if (isset($sections[$row['ID']])) {
					    			$sections[$row['ID']] = array(Rights::FUNCTIONS, $row['NAME'], $sections[$row['ID']][2]);
					    		} else {
					    			$sections[$row['ID']] = array(Rights::FUNCTIONS, $row['NAME'], array());
					    		}
					    	} else {
					    		if (!isset($sections[$row['PARENT']][2])) {
					    			$sections[$row['PARENT']][2] = array();
					    		} 
					    		$sections[$row['PARENT']][2][] = array('SC__'.$row['ID'], $row['NAME'], $this->get($app_id, Rights::FUNCTIONS, 'SC__'.$row['ID']));
					    	}
					    }
						$apps[$app_id][] = array_values($sections);
					}
					elseif (isset($app_rights['SECTIONS'])) {
						foreach ($app_rights['SECTIONS'] as &$section) {
							foreach ($section['OBJECTS'] as &$object) {
								// Read right of the object
								$object[] = $this->get($app_id, $section['ID'], $object[0]);
							}
							$section = array_values($section);
						}
						$apps[$app_id][] = $app_rights['SECTIONS'];
					}
					else {
						$apps[$app_id][] = array();
					}
					// Folders
					if (isset($app_rights['FOLDERS'])) {
						 $folders = array(
							'title' => $app_rights['FOLDERS']['TITLE'],
						 	'comment' => isset($app_rights['FOLDERS']['COMMENT']) ? $app_rights['FOLDERS']['COMMENT'] : "", 	 
							'icons' => $app_rights['FOLDERS']['ICONS'], 
							'folders' => $this->getFolders($app_id, $app_rights['FOLDERS'])
						);
						if (isset($app_rights['FOLDERS']['TITLE_INHERIT'])) { 
							$folders['title_inherit'] = $app_rights['FOLDERS']['TITLE_INHERIT'];
						}
						$apps[$app_id][] = $folders;// 5
					} else {
						$apps[$app_id][] = array();
					}
				}    					
    		}
    	}
    	asort($sort);
    	$result = array();
    	foreach ($sort as $app_id => $n) {
    		$result[] = $apps[$app_id];
    	}
    	return $result;
    }
    
   
    /**
     * Returns folders by table info (from rights file) 
     * 
     * @param $app_id
     * @param $info
     * @return array
     */
    public function getFolders($app_id, $info = false, $get_rights = true) 
    {   	
    	// Include file (rights descriptor), if info is null
    	if (!$info) {
    		$filename = AppPath::APP_PATH($app_id)."/_rights.php";
    		if (file_exists($filename)) {
				include($filename);
				if (isset($rights[$app_id]['FOLDERS'])) {
					$info = $rights[$app_id]['FOLDERS'];
				} else {
					return array();
				}
    		} else {
    			return array();
    		}
    	}
    	$sql = "SELECT ".$info['ID']." ID, ".$info['PARENT']." PARENT, ".$info['NAME']." NAME, 0 RIGHTS, NULL AS CHILDREN";
    	if (isset($info['INHERIT'])) {
    		$sql .= ", (".$info['INHERIT'].") INHERIT";
    	}
    	$sql .= " FROM ".$info['TABLE'];
    	if (isset($info['STATUS'])) {
    		$sql .= " WHERE ".$info['STATUS']." >= 0";
    	} elseif (isset($info['WHERE'])) {
    		$sql .= " WHERE ".$info['WHERE'];
    	}
    	if (isset($info['ORDER'])) {
    		$sql .= " ORDER BY ".$info['ORDER'];
    	}
    	// Execute SQL - query
    	$model = new DbModel();
    	$folders = $model->prepare($sql)->query(array('U_ID' => $this->type == self::USER ? $this->id : ""))->fetchAll('ID');
    	// Returns folders, if not need get rights of them
    	if (!$get_rights) {
    		return $folders;
    	}
    	$folders['ROOT'] = array('CHILDREN' => array());
    	foreach ($folders as $folder_id => &$folder) {
    		if ($folder_id != 'ROOT') {
    			if (isset($info['INHERIT']) && $folder['INHERIT']) {
    				$folder['RIGHTS'] = $this->getInherit($app_id, "FOLDERS", $folder_id);
    			} else {
    				$folder['RIGHTS'] = $this->get($app_id, "FOLDERS", $folder_id);
    			}
    			$folders[$folder['PARENT']]['CHILDREN'][] = $folder;
    		}  
    	}
    	return $this->getChildren($folders, 'ROOT', true);
    }
    
    public function getChildren($folders, $folder_id, $onlychildren = false) 
    {
    	$folder = $folders[$folder_id];
		if (!isset($folder['CHILDREN']) || !is_array($folder['CHILDREN'])) {
			$folder['CHILDREN'] = array();
			return $onlychildren ? array() : array_values($folder);
		}    	
		foreach ($folder['CHILDREN'] as &$child) {
			$child = $this->getChildren($folders, $child['ID']);
		}
		return $onlychildren ? $folder['CHILDREN'] : array_values($folder);
    } 
    
    
	
}

?>