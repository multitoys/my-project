<?php
    require_once("../../../common/html/includes/httpinit.php");
    pageUserAuthorization("UC", "CM", true);
    require_once("../../../AA/aa.php");
    $kernelStrings = $loc_str[$language];

    do {
        aa_getViewOptions($currentUser, $selectedObjectId, $visibleColumns, $viewMode, $sorting, $recordsPerPage, $showSharedPanel,
            $imgFieldsViewMode, $folderViewMode, $listViewImage, $kernelStrings, $readOnly, false, $currentObjectType);
        $typeDescription = getContactTypeDescription(CONTACT_BASIC_TYPE, $language, $kernelStrings, false);
        $fieldsPlainDesc = getContactTypeFieldsSummary($typeDescription, $kernelStrings, true, $canManageUsers);
        $systemUsers = listSystemUsers($statusList, $kernelStrings);

        $Contact = new Contact($kernelStrings, $language, $typeDesc, $fieldsPlainDesc);

        $res = $Contact->loadEntry($systemUsers[$currentUser]["C_ID"], $kernelStrings);
        if (PEAR::isError($res)) {
            $errorStr = $res->getMessage();

            break;
        }

        $userIsDeleted = $Contact->U_STATUS == RS_DELETED;
        $contactData = (array)$Contact;

        $contactData = applyContactTypeDescription($contactData, array(), $fieldsPlainDesc, $kernelStrings, UL_LIST_VIEW);
        $contactName = df_contactname($contactData, false);
        $curCF_ID = base64_encode($Contact->CF_ID);

        $cmFilesPath = getContactsAttachmentsDir();

        if ($listViewImage == "NOIMG" && $fieldsPlainDesc["C_X_PHOTO"])
            $listViewImage = "C_X_PHOTO";

        $fieldId = $listViewImage;
        $fieldData = $fieldsPlainDesc[$listViewImage];
        if ($fieldData[CONTACT_FIELD_TYPE] == CONTACT_FT_IMAGE && isset($contactData[$fieldId])) {
            $contactFieldData = $contactData[$fieldId];
            if ($imgFieldsViewMode == CM_IMAGESVIEW_THUMBNAILS || $listViewImage != CM_LISTVIEW_NOIMG && $contactFieldData[CONTACT_IMGF_DISKFILENAME]) {
                $thumbPath = $cmFilesPath."/".base64_decode($contactFieldData[CONTACT_IMGF_DISKFILENAME]);

                $thumbParams = array();
                $srcExt = null;
                $thumbParams['nocache'] = getThumbnailModifyDate($thumbPath, 'win', $srcExt);
                $thumbParams['basefile'] = base64_encode($cmFilesPath."/".base64_decode($contactFieldData[CONTACT_IMGF_DISKFILENAME]));
                $thumbParams['ext'] = base64_encode($contactFieldData[CONTACT_IMGF_TYPE]);

                $thumbUrl = prepareURLStr(PAGE_GETFILETHUMB, $thumbParams);
            }
        }

    } while (false);

    if ($thumbUrl)
        header("Location: $thumbUrl");
    else
        header("Location: ../res/images/empty-contact.gif");
?>