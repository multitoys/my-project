<?php

    /**
     * Class for the works with contact's types
     *
     * @author WebAsyst Team
     *
     */
    class ContactType
    {
        protected static $store = array();

        protected $id;
        protected $data = array();

        /**
         * Constructor
         *
         * @param $id - id of the contact type
         */
        public function __construct($id)
        {
            $this->id = $id;
            if (isset(self::$store[$id])) {
                $this->data = self::$store[$id];
            } else {
                $contacts_model = new ContactsModel();
                $data = $contacts_model->getType($id);
                if (!$data) {
                    throw new Exception(_("Requested contact type is not found"));
                }
                $this->data = self::$store[$id] = $this->parse($data['CT_SETTINGS']);
            }
        }

        /**
         * Returns description of the type (groups and fields)
         *
         * @return array
         */
        public function get($group_id = false)
        {
            return $group_id ? $this->data[$group_id] : $this->data;
        }

        /**
         * Returns all fields of the type (without groups of the fields)
         *
         * @return array
         */
        public function getFields($group_id = false, $add_group_info = true)
        {
            $result = array();
            foreach ($this->data as $group_data) {
                if (!$group_id || $group_data["ID"] == $group_id) {
                    foreach ($group_data["FIELDS"] as $field_id => $field_data) {
                        if ($add_group_info) {
                            $field_data["GROUPID"] = $group_data["ID"];
                            $field_data["GROUPNAME"] = $group_data["LONG_NAME"];
                        }
                        $result[$field_id] = $field_data;
                    }
                }
            }

            return $result;

        }

        public function getFieldsNames($group_id = false, $add_group_name = false, $images = true)
        {
            $result = array();
            foreach ($this->data as $group_data) {
                if (!$group_id || $group_data['ID'] == $group_id) {
                    foreach ($group_data["FIELDS"] as $field_id => $field_data) {
                        if ($images || $field_data['TYPE'] != 'IMAGE') {
                            $result[$field_id] = ($add_group_name !== false ? $group_data['LONG_NAME'].$add_group_name : "").$field_data['LONG_NAME'];
                        }
                    }
                }
            }

            return $result;
        }

        /**
         * Parse xml and returns array of the data
         *
         * @param $xml
         *
         * @return array
         */
        protected static function parse($xml)
        {
            // Get language of the current user
            $lang = CurrentUser::getLanguage();
            $result = array();
            // Try read xml
            $dom = new DOMDocument("1.0", "utf-8");
            try {
                $dom->loadXML($xml);
            } catch (Exception $e) {
                throw new RuntimeException(_("Error processing XML data"));
            }
            if (!$dom) {
                throw new RuntimeException(_("Error processing XML data"));
            }

            $xpath = new DOMXPath($dom);
            $groups = $xpath->query("/TYPE/FIELDGROUP");

            foreach ($groups as $group) {
                $groupDesc = self::getElementDescription($xpath, $group, $lang);
                $groupFields = array();

                $fields = $xpath->query("FIELD", $group);
                foreach ($fields as $field) {

                    $fieldDesc = self::getElementDescription($xpath, $field, $lang);
                    if (!isset($fieldDesc ["REQUIRED"])) {
                        $fieldDesc["REQUIRED"] = false;
                    }
                    if (!isset($fieldDesc ["REQUIRED_GROUP"])) {
                        $fieldDesc["REQUIRED_GROUP"] = null;
                    }
                    $groupFields[$fieldDesc["ID"]] = $fieldDesc;
                }

                $groupDesc["FIELDS"] = $groupFields;

                $result[$groupDesc['ID']] = $groupDesc;
            }

            return $result;
        }

        protected static function getElementDescription(&$xpath, &$elem, $lang)
        {
            $elemDesc = self::getAttributes($elem);
            // Decode menu items
            if (isset($elemDesc['MENU']) && $elemDesc['MENU']) {
                $elemDesc['MENU'] = explode("^&^", base64_decode($elemDesc['MENU']));
            }
            // Check if field name elements exists
            $longNameElement = $xpath->query("LONG_NAME", $elem);
            if (!$longNameElement->length) {
                // Load name from localization strings
                $elemDesc["LONG_NAME"] = _($elemDesc["LONG_NAME"]);
                $elemDesc["SHORT_NAME"] = _($elemDesc ["SHORT_NAME"]);
            } else {
                // Load name from field description
                $elemDesc["LONG_NAME"] = _(self::getElementName($xpath, $elem, "LONG_NAME", $lang));
                $elemDesc["SHORT_NAME"] = _(self::getElementName($xpath, $elem, "SHORT_NAME", $lang));
                if (!$elemDesc["SHORT_NAME"]) {
                    $elemDesc["SHORT_NAME"] = $elemDesc ["LONG_NAME"];
                }
            }

            return $elemDesc;
        }

        /**
         * Returns values of all node attributes as associative array
         *
         * @param  $node - XML document node
         *
         * @return array
         */
        protected static function getAttributes($node)
        {
            $result = array();
            foreach ($node->attributes as $name => $value) {
                $result[$name] = $value->nodeValue;
            }

            return $result;
        }

        /**
         * Returns contact description group or field name
         *
         * @param $xpath        - xpath Object
         * @param $element      - element Object
         * @param $nameNodeName - name of the element
         * @param $language     - User's language
         *
         * @return string or null
         */
        protected static function getElementName(&$xpath, &$element, $nameNodeName, $language)
        {
            // Find name element
            $nameNodeElement = $xpath->query($nameNodeName, $element);
            if (!$nameNodeElement->length) {
                return null;
            }
            $nameNodeElement = $nameNodeElement->item(0);
            // Find name language element
            $languageElement = $xpath->query($language, $nameNodeElement);

            // Return language element value, if language element exists
            $langElementExists = $languageElement->length;
            if ($langElementExists) {
                $languageElement = $languageElement->item(0);
                $fieldNameExists = strlen($languageElement->attributes->getNamedItem("VALUE")->nodeValue);
            }

            if ($langElementExists && $fieldNameExists) {
                return base64_decode($languageElement->attributes->getNamedItem("VALUE")->nodeValue);
            } else {
                // Find English name element
                $languageElement = $xpath->query(DEF_LANGUAGE, $nameNodeElement);

                if ($languageElement->length) {
                    $languageElement = $languageElement->item(0);

                    return base64_decode($languageElement->attributes->getNamedItem("VALUE")->nodeValue);
                } else {
                    return null;
                }

            }
        }

        /**
         * Validate value of the field
         * Returns true or error description
         *
         * @param $field
         * @param $value
         *
         * @return true | string
         */
        public function validateField($field, &$value, $check_required = false)
        {
            $value = trim($value);
            if (mb_strlen($value) == 0 && $field["TYPE"] != "IMAGE") {
                if ($field["MANDATORY"] && $check_required) {
                    return _("Field %s is required");
                } else {
                    return true;
                }
            }

            switch ($field_desc["TYPE"]) {
                case "TEXT":
                    if ($field["MAXLEN"] && mb_strlen($value) > $field["MAXLEN"]) {
                        return _("Field %s is very long");
                    }
                    break;

                case "URL":
                    try {
                        $url = parse_url($value);
                        $scheme = isset($url['scheme']) ? $url['scheme'] : 'http';
                        if (isset($url['host'])) {
                            $host = $url['host'];
                            $path = isset($url['path']) ? $url['path'] : '/';
                        } elseif (isset($url['path'])) {
                            $urls = explode("/", $url['path'], 2);
                            $host = $urls[0];
                            $path = isset($urls[1]) ? "/".$urls[1] : "/";
                        } else {
                            return _("Incorrect URL");
                        }
                        if (!preg_match("/^[a-z0-9\._-]{1,30}\.[a-z]{2,4}$/ui", $host, $matches)) {
                            return _("Incorrect URL");
                        }
                        $query = isset($url['query']) ? "?".$url['query'] : '';
                        $fragment = isset($url['fragment']) ? "#".$url['fragment'] : "";
                        $value = $scheme."://".$host.$path.$query.$fragment;
                    } catch (Exception $e) {
                        return _("Incorrect URL in the field %s");
                    }
                    break;

                case "EMAIL":
                    if (!preg_match("/^[a-zа-я0-9_\.-]{1,30}\@[a-zа-я0-9_\.-]{1,30}\.[a-z]{1,4}$/ui", $value, $matches)) {
                        return _("Incorrect E-MAIL in the field %s");;
                    }
                    break;
                case "MEMO":
                    break;

                case "IMAGE":
                    break;

                case "DATE":
                    if ($time = WbsDateTime::unixtime($value)) {
                        $value = ($value) ? date("Y-m-d", $time) : "";
                    } else {
                        return _("Incorrect date in field %s");
                    }
                    break;

                case "NUMERIC":
                    if (!is_numeric($value)) {
                        return _("Field % must be numeric");
                    } else {
                        $value = round($value, $field_desc[CONTACT_DECPLACES]);
                    }
                    break;

                case "MENU":
                    if (!in_array($value, $field['MENU'])) {
                        return _("Unknown item of the menu in field %s");
                    }
                    break;
            }

            return true;
        }

    }

?>