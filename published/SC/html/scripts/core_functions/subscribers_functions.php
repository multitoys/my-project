<?php
// *****************************************************************************
// Purpose	verifies email address. F.g. search invalid symbol
// Inputs   
// Remarks		
// Returns	
function subscrVerifyEmailAddress( $email )
{
	if ( trim($email) == "" )
		return translate("err_input_email"); 

	if ( !_testStrInvalidSymbol($email) )
		return translate("err_input_email");

	if (!eregi("^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$", $email) )
		return translate("err_input_email");
	
	return "";
}


// *****************************************************************************
// Purpose	get all subscribers
// Inputs     	
// Remarks		
// Returns	
function subscrGetAllSubscriber( $callBackParam, &$count_row, $navigatorParams = null )
{
	if ( $navigatorParams != null )
	{
		$offset		= $navigatorParams["offset"];
		$CountRowOnPage	= $navigatorParams["CountRowOnPage"];
	}
	else
	{
		$offset = 0;
		$CountRowOnPage = 0;
	}
    
    //	$sql = '
    //		SELECT mtbl.Email, mtbl.customerID, ctbl.ActivationCode FROM '.MAILING_LIST_TABLE.' as mtbl
    //		LEFT JOIN '.CUSTOMERS_TABLE.' as ctbl ON ctbl.customerID = mtbl.customerID AND  ctbl.ActivationCode="" OR ctbl.ActivationCode IS NULL
    //		ORDER BY mtbl.Email
    //	';
    $sql = '
		SELECT mtbl.Email, mtbl.customerID, ctbl.ActivationCode FROM '.MAILING_LIST_TABLE.' as mtbl
		LEFT JOIN '.CUSTOMERS_TABLE.' as ctbl ON ctbl.customerID = mtbl.customerID WHERE subscribed4news=1 AND (ctbl.unlimited_order=1 OR ctbl.token OR ctbl.1C>0)
		ORDER BY mtbl.Email
	';

	$q = db_query( $sql );

	$data = array();
	$i=0;
	while( $row = db_fetch_row($q) )
	{
		if ( ($i >= $offset && $i < $offset + $CountRowOnPage) || 
			$navigatorParams == null  )
			$data[] = $row;
		$i++;
	}
	$count_row = $i;
	return $data;
}



function _subscriberIsSubscribed( $email )
{
	$q = db_query( "select count(*) from ".MAILING_LIST_TABLE." where Email='".$email."'" );
	$countSubscribers = db_fetch_row($q);	
	$countSubscribers = $countSubscribers[0];

	return ($countSubscribers != 0);	
}


// *****************************************************************************
// Purpose	subscribe unregistered customer
// Inputs     	
// Remarks		
// Returns	
function subscrAddUnRegisteredCustomerEmail( $email )
{
	if ( !_subscriberIsSubscribed($email) )
	{
		$q = db_query( "select customerID from ".CUSTOMERS_TABLE." where Email='".$email."'" );
		if ( $row = db_fetch_row($q) )
		{
			db_query( "update ".CUSTOMERS_TABLE." set subscribed4news=1 ".	
				" where customerID=".$row["customerID"] );
			db_query( "insert into ".MAILING_LIST_TABLE." ( Email, customerID ) ".
				" values ( '".$email."', ".$row["customerID"]." )" );
		}
		else
			db_query( "insert into ".MAILING_LIST_TABLE." ( Email ) values ( '".$email."' )" );
	}
}


// *****************************************************************************
// Purpose	subscribe registered customer
// Inputs     	
// Remarks		
// Returns	
function subscrAddRegisteredCustomerEmail( $customerID )
{
	$q = db_query( "select Email from ".CUSTOMERS_TABLE." where customerID=".$customerID );
	$customer = db_fetch_row( $q );
	if ( $customer )
	{
		db_query( "update ".CUSTOMERS_TABLE." set subscribed4news=1 where customerID=".$customerID );

		if (  _subscriberIsSubscribed($customer["Email"])  )
		{
			db_query( "update ".MAILING_LIST_TABLE.
				" set customerID=".$customerID.
				" where Email='".$customer["Email"]."'" );
		
		}
		else
			db_query( "insert into ".MAILING_LIST_TABLE.
				" ( Email, customerID ) ".
				" values( '".$customer["Email"]."', '".$customerID."'  ) " );
	}
}

// *****************************************************************************
// Purpose	
// Inputs     	
// Remarks		
// Returns	
function subscrUnsubscribeSubscriberByCustomerId( $customerID )
{
	db_query( "delete from ".MAILING_LIST_TABLE." where customerID=$customerID " );
	db_query( "update ".CUSTOMERS_TABLE." set subscribed4news=0 where customerID=".$customerID );	
}


// *****************************************************************************
// Purpose	
// Inputs     	
// Remarks		
// Returns	
function subscrUnsubscribeSubscriberByEmail( $email )
{
	$email = base64_decode($email);
	$email = xEscapeSQLstring($email);
	db_query( "update ".CUSTOMERS_TABLE." set subscribed4news=0  where Email='".$email."'" );
	db_query( "delete from ".MAILING_LIST_TABLE." where Email='".$email."'" );
}


?>