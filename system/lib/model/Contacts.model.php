<?php 

class ContactsModel extends DbModel 
{
    protected $table = 'CONTACT';
    
    
    public function get($contact_id, $field = false)
    {
    	$sql = "SELECT ".($field ? $field : "*")." FROM ".$this->table."
    			WHERE C_ID = i:contact_id";
    	$q = $this->prepare($sql)->query(array('contact_id' => $contact_id));
    	return $field ? $q->fetchField($field) : $q->fetch(); 	
    }
    
    
    public function set($contact_id, $field, $value)
    {
    	$sql = "UPDATE ".$this->table." SET ".$field." = s:value WHERE C_ID = i:contact_id";
    	return $this->prepare($sql)->exec(array('contact_id' => $contact_id, 'value' => $value));
    }
    
    
	public static function parseImageXML($xml)
	{
		$result = array(
			"FILENAME" => "",
			"SIZE" => "",
			"DISKFILENAME" => "",
			"TYPE" => "",
			"DATETIME" => "",
			"MIMETYPE" => "",
			"MODIFIED" => "",
			"PREVFILENAME" => ""
		);
		if ( !$xml ) {
			return $result;
		}
		$dom = new DOMDocument("1.0", "UTF-8");
		$dom->loadXML($xml); 
		if ( !$dom ) {
			return $result;
		}
		$root = $dom->documentElement;
	
		if ( !$root ) {
			return $result;
		}

		$result["FILENAME"] = base64_decode( @$root->getAttribute("FILENAME") );
		$result["SIZE"] = @$root->getAttribute("SIZE");
		$result["DISKFILENAME"] = @$root->getAttribute("DISKFILENAME");
		$result["TYPE"] = @$root->getAttribute("TYPE");
		$result["DATETIME"] = @$root->getAttribute("DATETIME");
		$result["MIMETYPE"] = @$root->getAttribute("MIMETYPE");
		$result["PREVFILENAME"] = @$root->getAttribute("DISKFILENAME");

		return $result;
	}    
    
    
    public function getFolders($with_right = false)
    {
    	$sql = "SELECT CF_ID ID, CF_ID_PARENT PARENT, CF_NAME NAME
    			FROM CFOLDER WHERE CF_STATUS >= 0";
    	$data = $this->query($sql);
    	$result = array();
    	// @todo: Fix Current User
    	$rights = new Rights(CurrentUser::getId());
    	foreach ($data as $row) {
    		if ($with_right) {
    			$row['RIGHT'] = $rights->get('CM', Rights::FOLDERS, $row['ID'], true);
    			if ($row['RIGHT'][0] >= $with_right) {
    				$result[$row['ID']] = $row;
    			}	
    		} else {
    			$result[$row['ID']] = $row;
    		}
	   	}
    	return $result;	
    }
    
    /**
     * Returns next available id for the folder
     * Instead auto_increment
     * 
     * @param $parent
     * @return string
     */
    public function getNextFolderId($parent = 'ROOT')
    {
    	$sql = "SELECT MAX(CF_ID) ID FROM CFOLDER WHERE CF_ID_PARENT = s:parent";
    	$id = $this->prepare($sql)->query(array('parent' => $parent))->fetchField('ID');
    	if (!$id) {
    		$id  = '1.';
    	}
    	return ($parent != 'ROOT') ? $id : $parent.$id;
    }
    
    /**
     * Create folder and returns id of the new folder
     * 
     * @param $name
     * @param $parent
     * @param $type
     * @param $status
     * @return string
     */
    public function addFolder($name, $parent = 'ROOT', $type = 'CON', $status = 0)     	
    {
    	$sql = "INSERT INTO CFOLDER SET CF_ID = s:id, CD_ID_PARENT = s:parent, CF_NAME = s:name, CT_ID = s:type, CF_STATUS = i:status";
    	$folder_id = $this->getNextFolderId($parent);
    	$data = array(
    		'id' => $folder_id,
    		'parent' => $parent, 
    		'name' => $name,
    		'type' => $type,
    		'status' => $status
    	);
    	if ($this->prepare($sql)->exec($data)) {
    		return $folder_id;
    	} else {
    		throw new Exception(_("Error on create folder"));
    	}
    }
    
    /**
     * Returns id of the _Unsorted folder
     * If it is not exists, this function create it
     * 
     * @return string
     */
    public function getUnsortedFolder() 
    {
    	$sql = "SELECT CF_ID FROM CFOLDER WHERE CF_NAME='_Unsorted' AND CF_ID_PARENT='ROOT'";
    	$folder_id = $this->query($sql)->fetchField('CF_ID');
    	if ($folder_id) {
    		// Set full rights 
    		$rights_model = new UsersRightsModel();
    		// @todo Check this code!!!
    		if (!$rights_model->get(CurrentUser::getId(), 'CM', 'FOLDERS', $folder_id)->isFull()) {
				$rights_model->save(CurrentUser::getId(), '/ROOT/CM/FOLDERS', $folder_id, 7);    			
    		}
    		return $folder_id;	
    	} else {
    		return $this->addFolder('_Unsorted');	 
    	}
    	
    }
    
    /**
     * Add new contact and returns id of the new contact
     * 
     * @param $first_name
     * @param $last_name
     * @param $email
     * @param $folder_id
     * @return int
     */
    public function add($first_name, $last_name, $email, $folder_id = false) 
    {
    	if ($email && !preg_match("/^[a-zа-я0-9_\.-]{1,30}\@[a-zа-я0-9_\.-]{1,30}\.[a-z]{1,4}$/ui", $email)) {
    		throw new Exception('Incorrect E-Mail');
    	}    	
        $sql = "INSERT INTO {$this->table} 
                SET CF_ID = s:folder_id, C_FIRSTNAME = s:first_name, C_LASTNAME = s:last_name, C_EMAILADDRESS = s:email";
        if (!$folder_id) {
        	$folder_id = $this->getUnsortedFolder();
        }
        $data = array(
        	'folder_id' => $folder_id,
        	'first_name' => $first_name, 
        	'last_name' => $last_name, 
        	'email' => $email
        );                
        return $this->prepare($sql)->query($data)->lastInsertId();        
    }
    
    /**
     * Create contact by data
     * 
     * @param $data
     * @param $folder_id
     * @return int
     */
    public function addByData($data, $folder_id = false, &$errors = array())
    {
    	if (!$data) {
    		$errors[] = _("Empty data");
    		return false;
    	}
    	
    	$contact_type = new ContactType("CON");
    	$fields = $contact_type->getFields(false, false);
    
    	$values = array();
    	foreach ($data as $field => $value) {
    		if (!isset($fields[$field])) {
    			continue;
    		}
    		$result = $contact_type->validateField($fields[$field], $value, false);
    		if ($result === true) {
    			$values[] = $field."='".$this->escape($value)."'";
    		} else {
    			$errors[] = sprintf($result, $fields[$field]['LONG_NAME']);
    		} 	
    	}
    	if (!$errors) {
    		$sql = "INSERT INTO {$this->table} SET CF_ID = s:folder_id, " . implode(", ", $values);
	    	if (!$folder_id) {
	        	$folder_id = $this->getUnsortedFolder();
	        }
	    	return $this->prepare($sql)->query(array('folder_id' => $folder_id))->lastInsertId();
    	} else {
    		return false;
    	}
    }
            
    /**
     * Returns contact type description
     * 	array(
     * 		CT_ID => ...,
     * 		CT_SETTINGS => ...,
     * 		CT_NAME => ...
     * 	)
     * 
     * @param $id - id of the type
     * @return array
     */
    public function getType($id)
    {
    	$sql = "SELECT * FROM CTYPE WHERE CT_ID = s:id";
    	return $this->prepare($sql)->query(array('id' => $id))->fetchAssoc();
    }
    
    /**
     * Delete contact
     *  
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
    	$sql = "DELETE FROM ".$this->table." WHERE C_ID = i:id";
    	return $this->prepare($sql)->exec(array('id' => $id));
    }
    
}

?>