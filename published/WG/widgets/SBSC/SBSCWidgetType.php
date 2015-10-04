<?
	class SBSCWidgetType extends WidgetType {
		
		function SBSCWidgetType () {
			$this->id = "SBSC";
			$this->applications = array ("CM");
			$this->rights = array (array("app_id" => "CM", "name" => "CANTOOLS"));
			
			$this->fieldsData = 
			array (
				"WIDTH" => array ("type" => "width", "default" => 180, "min" => 100, "max" => 800, "place" => "size"), 
				"TITLE" => array ("type" => "string",  "place" => "text"),
				"TITLE_bgcolor" => array ("type" => "color", "default" => "#999999", "place" => "color"),
				"TITLE_color" => array ("type" => "color", "default" => "#FFFFFF", "place" => "color"),
				"BGCOLOR" => array ("type" => "color", "default" => "#F0F0F0", "place" => "shortform", "place" => "color"),
				"SAVEBTN" => array ("type" => "string", "place" => "text"),
				"SIGNUPTEXT" => array ("type" => "text", "rows" => 3, "place" => "text"),
				"CMFIELDS" => array ("type" => "subtype", "file" => "custom.htm", "default" => "C_FIRSTNAME,C_LASTNAME,C_EMAILADDRESS,C_COMPANY,C_DEPARTMENT,C_JOBTITLE" , "place" => "fields"),
				"CMFIELDSLABELS" => array ("type" => "subtype", "place" => "fields"),
				"PHOTOFIELDS" => array ("type" => "subtype", "file" => "photo.htm", "place" => "fields"),
				"FOLDER" => array("type" => "subtype", "file" => "folder.htm", "place" => "shortform", "place" => "contacts"),
				"DOPTIN" => array ("type" => "checkbox",
					//"subfields" => array ("emailText" => array ("type" => "text"), "confirmPageText" => array("type" => "text"))
					"place" => "shortform", "place" => "contacts"
				)
			);
					
			parent::WidgetType ();
			
			$this->fieldsPlaces = array (
				"general" => array("title" => $this->strings["place_general_title"]),
				"text" => array("title" => $this->strings["place_text_title"]),
				"color" => array("title" => $this->strings["place_color_title"]),
				"size" => array("title" => $this->strings["place_size_title"]),
				"contacts" => array("title" => $this->strings["place_contacts_title"]),
				"fields" => array("title" => $this->strings["place_fields_title"]),
			);
			
			$this->addSubtype(new SBSCSimpleSubtype($this));
			$this->addSubtype(new SBSCMainSubtype($this));
			$this->addSubtype(new SBSCPhotoSubtype($this));
			$this->addSubtype(new SBSCCustomSubtype($this));
		}
	}
?>