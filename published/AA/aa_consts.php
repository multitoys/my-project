<?php
	//
	// Account Administrator constants
	//

	// Folder view options
	//
	define( "AA_FLDVIEW_GLOBAL", 'global' );
	define( "AA_FLDVIEW_LOCAL", 'local' );

	// Image fields view options
	//
	define( "AA_IMAGESVIEW_THUMBNAILS", 'thumbnails' );
	define( "AA_IMAGESVIEW_LINKS", 'links' );
	define( "AA_SEARCH_RESULT", 'searchresult' );

	define( "AA_LISTVIEW_NOIMG", 'NOIMG' );
	
	define ("PAGE_AA_ACCESSRIGHTS_USERS", "rep_acessrights_users.php");
	define ("PAGE_AA_ACCESSRIGHTS_GROUPS", "rep_acessrights_groups.php");

	define ("PAGE_AA_DOMAINS", "domains.php");
	define ("PAGE_AA_DOMAINNAMES", "domainnames.php");
	define ("PAGE_AA_ADDMODDOMAIN", "addmoddomain.php");
	define ("PAGE_AA_ADDMODDOMAINNAME", "addmoddomainname.php");

	// Limit for creating extra mails for customer
	//
	define ("AA_EXTRA_MAIL_LIMIT", 10);

	define ("AA_DOMAIN_REGEXP", "/^([a-z0-9\-]+)\.([a-z]{2,4})$/i");
	define ("AA_DOMAINFULL_REGEXP", "/^([a-z0-9]+[\.|\-]?[a-z0-9]+){1,4}\.[a-z]{2,4}$/i");

?>