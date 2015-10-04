<?
	class SBSCSubtype extends WidgetSubtype {
		var $commonFields = array ();
		var $id;
		var $minHeight = 110;
		
		function SBSCSubtype  (&$type) {
			$this->commonFields = array ("WIDTH", "TITLE", "TITLE_bgcolor", "TITLE_color", "BGCOLOR", "SIGNUPTEXT", "SAVEBTN", "DOPTIN", "FOLDER");
			$this->embType = "inplace";
			$this->hidePlaces = array ("fields");
			$this->minFolderRights = 4;
			parent::WidgetSubtype ($type);
		}
		
		var $typeDescription;
		function getTypeDescription () {
			if ($this->typeDescription)
				return $this->typeDescription;
			
			global $language;
			global $kernelStrings;
			$typeDescription = getContactTypeDescription( CONTACT_BASIC_TYPE, $language, $kernelStrings, false );
			if (PEAR::isError($typeDescription) )
				return $typeDescription;
			$this->typeDescription = $typeDescription;
			return $typeDescription;
		}
		
		var $tdFields;
		function getTypeDescriptionFields () {
			if ($this->tdFields)
				return $this->tdFields;
			$typeDescription = $this->getTypeDescription();
			$fields = array ();
			foreach ($typeDescription as $cSection) {
				$fields = array_merge($fields, $cSection["FIELDS"]);
			}
			$this->tdFields = $fields;
			return $this->tdFields;
		}
		
		
		
		function prepareConstructorPage (&$preproc, &$pageState, $widgetData = array ()) {
			global $cm_groupClass;
			$access = null;
			$hierarchy = null;
			$deletable = null;
			global $cm_groupClass;
			global $currentUser;
			$folders = $cm_groupClass->listFolders( $currentUser, TREE_ROOT_FOLDER, $pageState->kernelStrings, 0, false,
																	$access, $hierarchy, $deletable, TREE_WRITEREAD );
			if ( PEAR::isError($folders))
				return $folders;
			$visibleFolders = array();
			foreach ( $folders as $fCF_ID=>$folderData ) {
				$encodedID = base64_encode($fCF_ID);
				$folderData->curID = $encodedID;
				$folderData->OFFSET_STR = str_replace( " ", "&nbsp;&nbsp;", $folderData->OFFSET_STR);

				$visibleFolders[$fCF_ID] = $folderData;
			}
			$folders = $visibleFolders;
			
			parent::prepareConstructorPage($preproc, $pageState, $widgetData);
			
			$preproc->assign( "folders", $folders );
			//$preproc->assign  ("typeFormFile", array("general" => $this->type->getHTMLPath() . "folder.htm"));
			//$preproc->assign  ("typeBeforeFormFile", array("contacts" => $this->type->getHTMLPath() . "folder.htm"));
		}
		
		function prepare (&$preproc, &$widgetData) {
			global $kernelStrings;
			global $language;
			
			$contentFilename = "signup_form.htm";
			$result = "";
			if ($this->pageState->getParam("action") == "signup") {
				
				do {
					if (pear::isError($res = $this->checkEmail($this->pageState->getParam("email")))) {
						$this->pageState->addError ($res);
						break;
					}
					$contactData = array ("C_EMAILADDRESS" => $this->pageState->getParam("email"));
					if ($this->id != "SIMPLE")
						list($contactData["C_FIRSTNAME"], $contactData["C_LASTNAME"]) = split(" ", $this->pageState->getParam("name"),2);
					
					$result = $this->addContact ($contactData, $language, $kernelStrings, $widgetData);
					if (PEAR::isError($result))
						break;
					
					$contentFilename = "after_signup.htm";
					$result = "success";
				} while (false);
			}
			$preproc->assign ("contentFilename", $contentFilename);
			$preproc->assign ("result", $result);
			
			return array("tplFilename" => "sbsc_widget.htm");
		}
		
		function checkFieldsValues (&$params) {
			if (!$params["FOLDER"])
				return PEAR::raiseError ($this->type->strings["wg_emptyfolder_error"]);
		}
		
		function prepareFieldsValues (&$params) {
			if (!empty($params["FOLDER"]))
				$params["FOLDER"] = base64_decode($params["FOLDER"]);
		}
		
		function checkEmail ($email) {
			if (!$email)
				return PEAR::raiseError ($this->type->strings["swg_emptymail_message"]);
			if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,5})$", $email))
				return PEAR::raiseError ($this->type->strings["swg_wrongmailformat_message"]);
			return true;
		}
		
		function addContact ($contactData, $language, &$kernelStrings, $widgetData) {
			
			$valRes = validateContactData(0, $contactData, $language, $kernelStrings, false, null, true );
			if (PEAR::isError($valRes)) {
				//if ($valRes->getUserInfo () == "C_EMAILADDRESS|CONTACT")
				//	return $this->pageState->addError ($this->type->strings["swg_accountalreadyregistred_message"]);
				//else
				
				$tdFields = $this->getTypeDescriptionFields();
				
				$errorFieldStr = $valRes->getUserInfo ();
				$message = $valRes->getMessage ();
				list ($fieldId, $section) = split("\|", $errorFieldStr);
				if ($fieldId) {
					$fieldInfo = $tdFields[$fieldId];
					if ($fieldInfo && !strpos($message, $fieldInfo["LONG_NAME"])) 
						$message .= ": " . $fieldInfo["LONG_NAME"];
				}
				return $this->pageState->addError($message);
			}
						
			//if ($this->pageState->getParam("mode") != "preview") {
				//$username = "Signup Form #" . $widgetData["WG_ID"];
				$username = "widget";
				$lists = array();
				$doptin = $widgetData["params"]["DOPTIN"];
				$folder = $widgetData["params"]["FOLDER"];
				//if (!$folder) 
					//$folder = "1.";
				
				$contactData["C_CREATEUSERNAME"] = $username;
				$contactData["C_CREATEDATETIME"] = date("YmdHis");
				
				global $cm_groupClass;
				$folders = $cm_groupClass->listFolders( null, TREE_ROOT_FOLDER, $pageState->kernelStrings, 0, false, 
														$access, $hierarchy, $deletable );
				if ( PEAR::isError($folders))
					return $this->pageState->addError($folders);
				if (empty($folders[$folder]))
					return $this->pageState->addError(PEAR::raiseError("Folder not found"));
				
					
				global $cmStrings;
				global $DB_KEY;
				global $userContactMode;
				
				$res = contactAddingPermitted( $kernelStrings, ACTION_NEW );
				if (PEAR::isError($res)) {
					$this->pageState->addError($res);
					return $res;
				}
				
				$action = ACTION_NEW;
				if ($doptin) {
					
					// Dirty hack, must be fixed asap
					pageUserAuthorization( "UC", "CM", true, true );
					//loadWBSSettings();
					global $html_encoding;
					$html_encoding = "UTF-8";
					
					$preContactData = array ();
					$preContactData["C_FIRSTNAME"] = $contactData["C_FIRSTNAME"];
					$preContactData["C_LASTNAME"] = $contactData["C_LASTNAME"];
					$preContactData["C_EMAILADDRESS"] = $contactData["C_EMAILADDRESS"];
					
					$cid = cm_signupContact( $DB_KEY, $folder, $preContactData, $lists, $doptin, $kernelStrings, $cmStrings, $username );
					if (PEAR::isError($cid)) {
						$this->pageState->addError($cid);
						return $cid;
					}
					$contactData["C_ID"] = $cid;
					$action = ACTION_EDIT;
				}	
				
				$cid = addmodContact( $contactData, $folder,$action, $kernelStrings);
				
				$metric = metric::getInstance();
				$metric->addAction($DB_KEY, '', 'CM', 'ADDCONTACT', 'WG-SIGNUP');

				if (PEAR::isError($cid)) {
					$this->pageState->addError($cid);
					return $cid;
				}
				
				/*$usql = new CUpdateSqlQuery ("CONTACT");
				$params = array ("C_SUBSCRIBER" => 1);
				$usql->addFields ($params, array_keys($params));
				$usql->addConditions ("C_ID", $cid);
				$res = db_query( $usql->getQuery());
				if ( PEAR::isError($res))
					return $res;
				*/
			//}
			return true;
		}
	}
	
	
	
	class SBSCSimpleSubtype extends SBSCSubtype {
		function SBSCSimpleSubtype (&$type) {
			$this->id = "SIMPLE";
			$this->fields = array ();
			parent::SBSCSubtype ($type);
		}
		
		function getEmbInfo ($widgetData) {
			return array("height" => $this->minHeight);
		}
	}
	
	class SBSCMainSubtype extends SBSCSubtype {
		function SBSCMainSubtype (&$type) {
			$this->id = "MAIN";
			$this->fields = array ();
			parent::SBSCSubtype ($type);
		}
		
		function getEmbInfo ($widgetData) {
			return array("height" => $this->minHeight+70);
		}
	}
	
	
	
	class SBSCCustomSubtype extends SBSCSubtype {
		function SBSCCustomSubtype (&$type) {
			$this->id = "CUSTOM";
			$this->fields = array ("CMFIELDS", "CMFIELDSLABELS");
			parent::SBSCSubtype ($type);
			$this->hidePlaces = array ();
		}
		
		
		function getEmbInfo ($widgetData) {
			$tdFields = $this->getTypeDescriptionFields ();
			
			$widgetParams = $this->getRealParams($widgetData);
			
			$height = 85;
			$cmfieldsIDs = array ();
			if ($widgetParams && !empty($widgetParams["CMFIELDS"]))
				$cmfieldsIDs = split (",", $widgetParams["CMFIELDS"]);
			if (!$widgetData)
				$cmfieldsIDs = split (",", $this->type->fieldsData["CMFIELDS"]["default"]);
			foreach ($cmfieldsIDs as $cId) {
				$field = $tdFields[$cId];
				if ($field["TYPE"] == "IMAGE") 
					$cheight = 160;
				elseif ($field["TYPE"] == "MEMO")
					$cheight = 75;
				else
					$cheight = 44.5;
				$height += $cheight;
			}
			if ($height<$this->minHeight)
				$height = $this->minHeight;			
				
			return array("height" => round($height));
		}
		
		function prepare (&$preproc, &$widgetData, $formFilename = "custom_form.htm") {
			global $kernelStrings;
			global $language;
			
			$contentFilename = $formFilename;
			$result = "";
			
			$typeDescription = getContactTypeDescription( CONTACT_BASIC_TYPE, $language, $kernelStrings, false );
			if (PEAR::isError($typeDescription) )
				return $typeDescription;
			
			$cmfields = array ();
			$cmfieldsLabels = array ();
			$widgetParams = $this->getRealParams($widgetData);
			if ($widgetParams && !empty($widgetParams["CMFIELDS"])) {
				
				if (!empty($widgetParams["CMFIELDSLABELS"])) {
					$labelsVals = split (";", $widgetParams["CMFIELDSLABELS"]);
					$cmfieldsLabels = array ();
					foreach($labelsVals as $cLabelStr) {
						list ($field, $label) = split ("=", $cLabelStr, 2);
						$cmfieldsLabels[$field] = $label;
					}
				}
				
				$cmfieldsIDs = split (",", $widgetParams["CMFIELDS"]);
				foreach ($typeDescription as $cSection) {
					foreach ($cSection["FIELDS"] as $cField) {
						if (!in_array($cField["ID"], $cmfieldsIDs))
							continue;
						$cmfields[$cField["ID"]] = $cField;
					}
				}
			}
			
			
			if ($this->pageState->getParam("action") == "signup") {
				do {
					$contactData = array ();
					$hasError = false;
					foreach ($cmfields as $field) {
						if($field["TYPE"] == "EMAIL" && $this->pageState->getParam($field["ID"]) && pear::isError($res = $this->checkEmail($this->pageState->getParam($field["ID"])))) {
							$this->pageState->addError ($field["LONG_NAME"] . ":" . $res->getMessage());
							$hasError = true;
							break;
						}
						$contactData[$field["ID"]] = $this->pageState->getParam($field["ID"]);
					}
					
					if ($hasError)
						break;
					
					foreach ($cmfields as $field) {
						if($field["TYPE"] == "IMAGE") {
							$desc = $this->saveFile ($field["ID"]);
							$contactData[$field["ID"]] = $desc;
							if (PEAR::isError($desc)) {
								$hasError = true;
								break;
							}
						}
						if ($field["TYPE"] == "DATE") {
							$param = $contactData[$field["ID"]];
							$m = intval($param["m"]);
							$d = intval($param["d"]);
							$y = intval($param["y"]);
							if ($y == 0) {
								$this->pageState->addError ($this->type->strings["wrong_dateformat_error"]);
								$hasError = true;
								break;
							}
							elseif ($y < 10)
								$y = 2000 + intval($y);
							elseif ($y <= date("y"))
								$y = 2000 + $y;
							elseif ($y < 100)
								$y = 1900 + $y;
							elseif ($y < 1800)
							{
								$this->pageState->addError ($this->type->strings["wrong_dateformat_error"]);
								$hasError = true;
								break;								
							}
								
							$val = DATE_DISPLAY_FORMAT;
							$val = str_replace("m", $m, $val);
							$val = str_replace("d", $d, $val);
							$val = str_replace("Y", $y, $val);
							$val = str_replace("y", $y, $val);
							$contactData[$field["ID"]] = $val;
						}
					}
					
					if ($hasError)
						break;
					
					$result = $this->addContact ($contactData, $language, $kernelStrings, $widgetData);
					if (PEAR::isError($result))
						break;
					$contentFilename = "after_signup.htm";
					$result = "success";
					
				} while (false);
			}
			
			$preproc->assign ("contentFilename", $contentFilename);
			$preproc->assign ("result", $result);
			$preproc->assign ("cmfields", $cmfields);
			$preproc->assign ("labelsCMFields", $cmfieldsLabels);
			
			return array("tplFilename" => "sbsc_widget.htm");
		}
		
		function saveFile ($imgFieldEdited) {
			global $imgfiles;

			if ( strlen($imgfiles['name'][$imgFieldEdited]) ) {
				// Move image file to the temporary directory
				//
				$fileName = uniqid( TMP_FILES_PREFIX );
				$destPath = WBS_TEMP_DIR."/".$fileName;
				$srcPath =  $imgfiles['tmp_name'][$imgFieldEdited];
				if ( !move_uploaded_file($srcPath, $destPath) ) {
					$errorStr = $cmStrings['amc_erroruploadingfile_message'];
					break;
				}

				// Process image
				//
				$originalName = $imgfiles['name'][$imgFieldEdited];
				$fileType = $imgfiles['type'][$imgFieldEdited];

				$fieldDescription = $contactData[$imgFieldEdited];

				$thumbnailError = null;
				$res = processImageFieldFile( $destPath, $originalName, $fileType, $thumbnailError, $kernelStrings, $fieldDescription );
				if ( PEAR::isError($res) ) {
					$errorStr = $res->getMessage();
					break;
				}

				if ( PEAR::isError($thumbnailError) ) {
					$errorStr = $thumbnailError->getMessage();
					$isWarning = 1;
				}

				return $fieldDescription;
			}
		}
		
		function prepareConstructorPage (&$preproc, &$pageState, $widgetData = array ()) {
			$typeDescription = getContactTypeDescription( CONTACT_BASIC_TYPE, $pageState->language, $pageState->kernelStrings, false );
			if (PEAR::isError($typeDescription) )
				return $pageState->fatalError ($typeDescription);
			//print_r($typeDescription);
			$preproc->assign ("typeDesc", $typeDescription);
			
			$cmfields = array ();
			$widgetParams = $this->getRealParams($widgetData);
			if ($widgetParams && !empty($widgetParams["CMFIELDS"]))
				$cmfields = split (",", $widgetParams["CMFIELDS"]);
			if (!$widgetParams)
				$cmfields = split(",", $this->type->fieldsData["CMFIELDS"]["default"]);
			$preproc->assign ("incCMFields", $cmfields);
			
			
			if ($widgetData && !empty($widgetParams["CMFIELDSLABELS"])) {
				$labelsVals = split (";", $widgetParams["CMFIELDSLABELS"]);
				$cmfieldsLabels = array ();
				foreach($labelsVals as $cLabelStr) {
					list ($field, $label) = split ("=", $cLabelStr, 2);
					$cmfieldsLabels[$field] = $label;
				}
				$preproc->assign ("incCMFields", $cmfields);
				$preproc->assign ("labelsCMFields", $cmfieldsLabels);
			}
			
			parent::prepareConstructorPage ($preproc, $pageState, $widgetData);
			
			//$customFilename = $this->type->getHTMLPath() . "/custom.htm";
			//$preproc->assign ("subtypeAfterFormFile", array ("fields" => $customFilename));
		}
		
		function checkFieldsValues(&$params) {
			if (!$params["CMFIELDS"]["C_EMAILADDRESS"] && $params["DOPTIN"]) {
				return PEAR::raiseError($this->type->strings["custom_emaildoptin_error"]);
			}
			return parent::checkFieldsValues ($params);
		}
		
		function prepareFieldsValues (&$params) {
			if (isset($params["CMFIELDS"]) && is_array($params["CMFIELDS"]))
				$params["CMFIELDS"] = join (",", array_keys($params["CMFIELDS"]));
			if (isset($params["CMFIELDSLABELS"]) && is_array($params["CMFIELDSLABELS"])) {
				$labelStrs = array ();
				foreach ($params["CMFIELDSLABELS"] as $cId => $cLabel)
					$labelStrs[] = $cId . "=" . str_replace(";","",$cLabel);
				$labelsStr = join (";", $labelStrs);
				$params["CMFIELDSLABELS"] = $labelsStr;
			}
			
			parent::prepareFieldsValues ($params);
		}
	}
	
	class SBSCPhotoSubtype extends SBSCCustomSubtype {
		function SBSCPhotoSubtype (&$type) {
			$this->id = "PHOTO";
			$this->fields = array ("PHOTOFIELDS");
			parent::SBSCSubtype ($type);
		}		
		
		function prepare (&$preproc, &$widgetData) {
			global $language;
			$typeDescription = getContactTypeDescription( CONTACT_BASIC_TYPE, $language, $kernelStrings, false );
			$tdFields = $this->getTypeDescriptionFields ();
			$hasPhotoField = !empty($tdFields["C_X_PHOTO"]);
			$preproc->assign ("hasPhotoField", $hasPhotoField);
			
			if ($this->pageState->getParam("name"))
				list($this->pageState->params["C_FIRSTNAME"], $this->pageState->params["C_LASTNAME"]) = split(" ", $this->pageState->getParam("name"),2);
			$widgetData["params"]["CMFIELDS"] = "C_FIRSTNAME,C_LASTNAME,C_EMAILADDRESS,C_X_PHOTO";
			
			
			parent::prepare ($preproc, $widgetData, "photo_form.htm");	
			return array("tplFilename" => "sbsc_widget.htm");
		}
		
		function checkFieldsValues(&$params) {
			//if ($params["PHOTOFIELDS"] == "name" && $params["DOPTIN"]) {
			//	return PEAR::raiseError($this->type->strings["photo_emaildoptin_error"]);
			//}
			return SBSCSubtype::checkFieldsValues ($params);
		}
		
		
		function prepareConstructorPage (&$preproc, &$pageState, $widgetData = array ()) {
			$customFilename = $this->type->getHTMLPath() . "/photo.htm";
			//$preproc->assign ("subtypeAfterFormFile", array("contacts" => $customFilename));
			
			$tdFields = $this->getTypeDescriptionFields	 ();
			$hasPhotoField = !empty($tdFields["C_X_PHOTO"]);
			
			if (!$hasPhotoField)
				$pageState->addError($this->type->strings["wg_nophotofield_error"]);
			
			SBSCSubtype::prepareConstructorPage ($preproc, $pageState, $widgetData);
		}
		
		function getEmbInfo ($widgetData) {
			$height = 320;
			return array("height" => $height, "min_width" => 220);
		}
	}
?>