#! C:/perl/bin/perl -w

##########################################################################################
#	Script for unapplette technology
##########################################################################################

##########################################################################################
#	����������� �����
##########################################################################################
use strict;

##########################################################################################
#	����������� ������������ ���������
##########################################################################################

use IPC::Open2;						# ��������������� pipes (�������/����������)
use CGI		':cgi';					# CGI
use Fcntl	':flock';				# ����������� ������ � ������
use CGI::Carp qw(fatalsToBrowser);

##########################################################################################
#	����������� ���������� ���������
##########################################################################################

use Paths;							# ���� � ������
use Data;							# ����������

use Util;							# �������������� �������
use Log;							# ������ ������ � log-������


##########################################################################################
#	�������������;
##########################################################################################
use vars qw (%in);


#������� ���-���� � ���������� � ���� ��� ���������� (��������� ��������, $LogLevel, ����� ���������
#�������� �� 0 �� 4, ��� $LogLevel = 4 � ��� ����� ������������ ������ �������� ������ ����������)

my $LogFileName = $Bin.'/cybercrd.log';

&OpenLog($LogFileName, $LogLevel);

&parseInput();

$OrderID	= $in{orderid}  || '';

&WriteLog("OID:$OrderID ���������� ���������� �� ������ $OrderID", 2);

$FORM_URL	        =	'https://' .$FORM_URL;

$OrderID		=	$in{'orderid'};
$TerminalID		=	$in{'terminalid'} || $in{'terminal'};

if (!isValidID($TerminalID))
{
		$ManyTerminals	=	0;
}

if ( $ManyTerminals && $ManyTemplates )
{
	my $TP = $TemplatesPath . "_".$TerminalID;
	$TemplatesPath = $TP if -e $TP;
}

$TemplatesPath .= '/';
if ( $ManyTerminals && $ManyChecks )
{
	$SessionsPath .= "_".$TerminalID;
}
$SessionsPath .= '/';

##########################################################################################
#	������ ������
##########################################################################################

#&WriteLog ("BaseURL:$ENV{'SERVER_NAME'}$ENV{'SCRIPT_NAME'};", 2);

#remove '&' symbols from input parameters if any
foreach (keys %in) {
	$in{$_} =~ s/&//go;
}

($in{amount}) ? ($Amount	= $in{amount}) : ($Amount 	= $in{payment} );

#check if amount contains ',' or '.', if so remove it. Then it should include only digits.
if ($Amount)
{
    if ($Amount =~ /^\d+[.,]{1}\d{1,2}$/)
    {
        $Amount    =~  s/,/\./gi;
	$Amount    *=  100;
    } elsif ($Amount    =~ /^\d+$/) {
	$Amount    *=  100;
    }

}

#to meet the old test.cgi parameters check their values too(second column)
$PaymentDetails 	=   $in {'paymentdetails'} 	|| $in {'description'} 	|| $in {'dsc'}   || '';
$Currency       	=   $in {'currency'};
$CardType		=   $in {'cardtype'}		|| '';
$Language       	=   $in {'language'}		|| $in {'lang'}		|| $Language;
$POS			=   $in {'pos'}			|| '';
$Registered		=   $in {'registered'}		|| $in {'shopperreg'}	|| '';

$Email      		=   $in {'email'} 		|| $in {'shoppermail'}	|| '';
$FirstName      	=   $in {'firstname'}		|| $in {'shopperfname'}	|| '';
$MiddleName     	=   $in {'middlename'}		|| $in {'shoppersname'}	|| '';
$LastName       	=   $in {'lastname'}		|| $in {'shopperlname'}	|| '';
$Phone     		=   $in {'phone'}		|| $in {'shopperphone'}	|| '';
$Address   		=   $in {'address'}		|| $in {'shopperaddr'}	|| '';


# check if parameters have old values and replace them with the new ones.
if 	($Currency 	== 1)   	{   $Currency = 'USD';	}
elsif 	($Currency 	== 2)		{   $Currency = 'RUR';  }
elsif 	($Currency 	== 3)		{   $Currency = 'EUR';  }
if 	($Language	eq 'rus')	{   $Language = 'ru';	}
elsif 	($Language	eq 'eng')	{   $Language = 'en';	}

#in case not allowed value is passed, language = 'ru'
if ($Language !~ /^ru|en$/i )		{   $Language = 'ru';	}

#form PaymentDetails by default if not passed by the client
unless ($PaymentDetails)        {
        $PaymentDetails = sprintf "payment for Order %s, %0.2f %s", $OrderID, $Amount/100, $Currency;
}

#select errors language
($Language eq 'ru') 	?	(   %ERRORS	= %ERRORS_RUS ) :  ( %ERRORS 	= %ERRORS_ENG );

#check the length of client parameters. In case some length is above the limit $LengthError will be defined
$LengthError	=	CheckParamLength();
if ($LengthError) {
	&WriteLog ("Params length error: $LengthError", 1);
	$LengthError	= "<br>". $LengthError;
	&AddError ('E010', $LengthError);
}

&WeAreOK();

if  (!isValidID($Amount))		{   &AddError('E004', $Amount);		}

if ($CheckParams) 			{
	if  (!$OrderID)			{   &AddError('E002', $OrderID);	}
	if  ($Currency !~ /^RUR|USD|EUR$/i) {   &AddError('E003', $Currency); 	}
	if  (!isValidEmail ($Email)) 	{   &AddError('E005', $Email);    	}
	if  (!isValidName($FirstName))  {   &AddError('E006', $FirstName);   	}
	if  (!isValidName($LastName))  	{   &AddError('E007', $LastName);   	}

}

if  (&WeAreOK())
{
#form a request string
	$Request	= "OrderID=$OrderID&Amount=$Amount&Currency=$Currency&PaymentDetails=$PaymentDetails";
	if ($Registered =~ /^on|1$/i)
				{ 	$Request	.= "&Registered=true";			}
	if ($CardType) 		{ 	$Request	.= "&cardtype=$CardType"; 		}

	$Request       .= "&Email=$Email&FirstName=$FirstName&LastName=$LastName";
	if ($MiddleName)	{	$Request	.= "&MiddleName=$MiddleName";		}
	if ($Phone)		{	$Request	.= "&Phone=$Phone";			}
	if ($Address)		{	$Request	.= "&Address=$Address";			}

	$Request       .= "&Language=$Language&return_url=$ResultURL&ShopIP=$SHOP_IP";
	if ($TerminalID)	{	$Request	.= "&Terminal=$TerminalID";		}
	if ($POS)		{	$Request	.= "&POS=$POS";				}

	# sign the shop request and check if signing was successful
	# Under Win32 we use corresponding OLE automation server instead of checker.exe
	$SignedRequest = $^O =~ /MSWin32/
		? eval { require Checker; return Checker::sign( $Request ); }
		: &Sign( $Request );
	if ($SignedRequest	!~ /Begin(.*)End/si) {
		&AddError ('E008');
		&WriteLog ($SignedRequest, 3);
	}
	&WeAreOK();

	#if save request option is turned on, save request to a session file with a name $OrderID.in
	if ($SaveSignedRequest) {
		&SaveSignedRequest($SignedRequest, $OrderID);
	}

	if (&WeAreOK()) {
		&ShowForm($SignedRequest);
	}

}

#������� ���
CloseLog();

#print		"\n<hr>from cybercard v$Version: last script line processed. stopping with: $!\n";
exit;

##########################################################################################
#	����� ������
##########################################################################################



##########################################################################################
#	������������
##########################################################################################

#��������� ��������� � ������� �������, ������������� ��������� ������� � ����������� ������� ��� ��������
#���������:
#��� �����������/���������
#������������� ����� ��� ����������� (0 - �������, 1 - ��������)
#�� ������ - ��������� ������ �������
sub Sign()
{
	my $text			=	shift;		#��� �����������/���������
	my $mode			=	0;		#������������� ����� ��� ����������� (0 - �������, 1 - ��������)
	my $stext			=	'';

	my $pid;

	my $filename			=	"$SIGN_TOOL -s -f $SIGN_INI";	#���� � �������

	#die $filename;
	#������� ��������������� pipe.
	$pid				=	open2(\*Reader, \*Writer, $filename);

	#�������� ������ � ������� �����.
	print Writer $text;
	close Writer;
	#������� ������ � ������� �����.
	while (my $line		= <Reader>)
	{
		$stext			.=	$line;
	}
	close Reader;

	#��������� ���������� ��������.
	waitpid($pid, 0);

	return $stext;						#������� ��������� ������ �������.
}

#�������� � ��� ������
#�� ����� ��� ������ � �� ���� �������������� ����������
sub AddError()
{
	$OperationStatus	=	1;
	&WriteLog ("errorName=$_[0], error_value=$_[1]", 1);
	my $num				=	shift;
	my ($Param1, $Param2, $Param3)	=	(shift, shift, shift);
	$ErrorLog{$num}		=	$ERRORS{$num};
	$ErrorLog{$num}		=~	s/<<Param1>>/$Param1/gi;
	$ErrorLog{$num}		=~	s/<<Param2>>/$Param2/gi;
	$ErrorLog{$num}		=~	s/<<Param3>>/$Param3/gi;
}

#����� ���� ������
#������� �� ����� ���������� ����
sub ShowErrors()
{
	my $ErrorTemplate;
	($Language eq 'ru')  	? ($ErrorTemplate = $ErrorRusTemplate) : ($ErrorTemplate = $ErrorEngTemplate);
	my $template	=	&getform($TemplatesPath.$ErrorTemplate);
	my $ErrorsList	=	"";
	foreach my $Error (sort keys %ErrorLog)
	{
		$ErrorsList	.=	"<li><b>$Error</b>: $ErrorLog{$Error}</li>\n"
	}
	&WriteLog ("ErrorsList:\n$ErrorsList", 2);
	$template		=~	s/<<Reason>>/$ErrorsList/gi;
	$template		=~	s/<<back>>/$MainPage/gi;
	print			$template;

}

#���� � ��� ������, ���������� ��� ������, ����� ���� �� ��������.
sub WeAreOK()
{
	if ($OperationStatus)
	{
		&WriteLog ("We are not OK", 1);
		&ShowErrors();
		exit;
	}
	return 1;
}

sub ShowForm()
{

	my $request		= shift;
	if (!$PaymentDetails)	#���� �� �������� �������� �������, ��������� �������� �� ���������.
	{
		my $CurrentTime =	localtime;
		$PaymentDetails		=	"$CurrentTime: ������ ������ #$OrderID";
	}

    &WriteLog("OID:$OrderID ����������� ����� ����� ������", 2);

print "Content-type: text/html\n\n";
print
'










































































<HTML>
<HEAD>
</HEAD>
<body onLoad="document.forms[0].submit();">

<form method=post action="' . $FORM_URL . '">

<input type=hidden name=version     value="' . $Version         .  '">
<input type=hidden name=message     value="' . $request         .  '">

</form>
</body>
</html>
';

}


############################################################################
sub SaveSignedRequest
############################################################################
{
	my ($request, $orderID)	= @_;
	my $PreviousRequest;
	my $ext			= '.in';
	unless (-e $SessionsPath) {
		mkdir $SessionsPath, 0755 or &AddError ('E009', "Can't create $SessionsPath directory: $!");
	}
	if (WeAreOK()) {
		my $Filename		= $SessionsPath.$orderID.$ext;
		if (-s $Filename) {
			open (FH, "<".$Filename) 	or &AddASError('F001', $Filename, $!);
			while (<FH>) {
				$PreviousRequest .= $_;
			}
			close FH 	or &AddASError('F005', $Filename, $!);
			$request	= $PreviousRequest ."\n\nNEXT ATTEMPT->\n". $request;
		}

	open  FH, ">".$Filename		or &AddError('F001', $Filename, $!);
	binmode (FH);
	print FH $request		or &AddError('F003', $Filename, $!);
	close FH			or &AddError('F005', $Filename, $!);
	}
}

############################################################################
sub CheckParamLength
############################################################################
{
	my ($error, $key, $key_len, $max_len);

	foreach $key (keys %PARAM_LENGTH) {
		$key_len	= length ( ${ $PARAM_LENGTH {$key}[1] } );
		$max_len  	= $PARAM_LENGTH{$key}[0];
		if ($key_len > $max_len) {
			$error	.= "$key : $max_len <BR>";
		}
	}
	return $error;
}



############################################################################


sub parseInput
############################################################################
{

    if ($ENV{'REQUEST_METHOD'} eq 'POST')
    {
	my ($buffer, $name, $value, $i, @fvalues);
	read (STDIN, $buffer, $ENV{'CONTENT_LENGTH'});
	if ($buffer eq "")
	{
	   die "��� ������ �� �������� ������/Your query doesn't contain any data";
	}
	@fvalues = split (/&/, $buffer);
	foreach $i (0..$#fvalues)
	{
	    ($name, $value)	=	split (/=/, $fvalues[$i], 2);
	    $name 	=~ tr/+/ /;
	    $name 	=~ s/%([A-Fa-f0-9][A-Fa-f0-9])/pack ("c", hex ($1))/ge;
	    $name 	=~ s/\n//g;
	    $value 	=~ tr/+/ /;
    	    $value	=~ s/%([A-Fa-f0-9][A-Fa-f0-9])/pack ("c", hex ($1))/ge;
	    $value	=~ s/&//g;
	    $value	=~ s/\n//g;
            if ($value) { $in{lc($name)}  = $value;  }
	    else  { $in{lc($name)}  = undef; }
	}
    } else {
	die "��� ������ cybercrd.cgi ����������� ����� 'POST'/to call cybercrd.cgi use the 'POST' method";
    }
}
