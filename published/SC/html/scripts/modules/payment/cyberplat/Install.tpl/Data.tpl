package Data;

#������ �������
$Version		=	'2.0';
$EnterpriseName		= 	'%enterprise_name%';
$MerchantName		=	'%merchant_name%';
$OperationType		= 	'e-commerce';
$HelpDesk		= 	'%help_desk%';

$SessionExt		=	".sta";	# Extension for 'session status' files
$SessionStatus		=	0;	# Session status
$OperationStatus	=	0;	# Operation status
%ErrorLog		=	();	# List of errors
$LogLevel		=	1;	# The detailed elaboration for the log file (0-4, 2 is recommended for operation, 0 - for debugging)
$WillConvert		=	1;	# Will or not the summary file(checks.inf) be led (has format: SessionID|OrderID|Status)

$SessionID		=	0;	# Session ID
$TransactionID		=	'';	# Transaction ID (is returned only for successful transaction)
$Currency		=	'';	# Type of currency (presently 'RUR' or 'USD')
$PaymentDetails		=	'';	# Payment Description 

$TerminalID		=	0;	# Terminal ID

$ManyTerminals		=	1;	# Is the shop configured with several terminals
$ManyChecks             =	1;	# Should transaction results be saved in separate directories
$ManyTemplates          =	1;	# Should separate templates be used for each terminal

$CheckExt		=	'.res';	# Extension for 'check' files
$Language		=	'';
$CheckParams		=	1;
$SaveSignedRequest	=	0;
$SaveChecks		=	1;	# Should checks be saved
$SaveSession		=	1;

%ERRORS_RUS			=
(
	'E002', 	"�� ������ ������������ �������� OrderID",
	'E003',		"������ �� ������� ��� ������� �������: <<Param1>>",
	'E004',		"����� � ������ �� ������� ��� ������� �������: <<Param1>>",
	'E005',		"������������ ������ e-mail �������: <<Param1>>",
	'E006',         "������������ ������ ����� �������: <<Param1>>",
	'E007',         "������������ ������ ������� �������: <<Param1>>",
	'E008',		"���������� ��������� ������ ��������/��������� ������� ������ �������: <<Param1>>",
	'E009',		"��������� ������: <<Param1>>",
	'E010',		"����� ���������(��) ������ ����������. ������ ����: <<Param1>>", 	
	
	'F001',		"���������� ������� ���� ������ <<Param1>>: <<Param2>>",
	'F002',		"���������� �������� ���� ������ <<Param1>> � ����������� ������: <<Param2>>",
	'F003',		"���������� �������� ������ � ���� ������ <<Param1>>: <<Param2>>",
	'F004',		"���������� ������ ���� ������ <<Param1>> � ����� ������: <<Param2>>",
	'F005',		"���������� ������� ���� ������ <<Param1>>: <<Param2>>",
	
);

%ERRORS_ENG			=
(
	'E002', 	"Obligatory parameter OrderID is not indicated",
	'E003',		"Currency is not indicated or indicated incorrectly: <<Param1>>",
	'E004',		"The sum of payment is not indicated or indicated incorrectly: <<Param1>>",
	'E005',		"Invalid format of client's email : <<Param1>>",
	'E006',         "Invalid format of client's first name : <<Param1>>",
	'E007',         "Invalid format of client's last name : <<Param1>>",
	'E008',		"Can't sign shop's request or check the signature under the server response: <<Param1>>",
	'E009',		"An error occurred: <<Param1>>",
	'E010', 	"The following parameters' length is above the limit. The maximum length is: <<Param1>>",	
	
	'F001',		"Can't open session file <<Param1>>: <<Param2>>",
	'F002',		"Cannot get <<Param1>> file for exclusive access: <<Param2>>",
	'F003',		"Cannot write data to a session file <<Param1>>: <<Param2>>",
	'F004',		"Cannot grant session file <<Param1>> for public access: <<Param2>>",
	'F005',		"Cannot close a session file <<Param1>>: <<Param2>>",
	
);

%LANG_SETTINGS	=
(
	ru	=> ['������������ �����������', '������������ �����', 'URL ��������', '���������� ����������', 
		   '����� � ������ ����������', '������ �������', '���� �������', '��� ����������', '��� �����������',
		   '��� �������', '��� ��������'],

	en	=> ['Enterprize name', 'Merchant name', 'Shop URL', 'Help desk', 'Transaction amount', 
		   'Transaction currency', 'Date of payment', 'Transaction ID',  'Auth code',  
		    'Client name', 'Operation type'],
);

%PARAM_LENGTH	= 
(
	OrderID		=> [255,\$OrderID],
	PaymentDetails	=> [255,\$PaymentDetails], 		
	FirstName	=> [70,	\$FirstName],  
	MiddleName	=> [70,	\$MiddleName],
	LastName	=> [70,	\$LastName],
	Email		=> [70, \$Email],
	Phone		=> [70, \$Phone],
	Address		=> [70,	\$Address],
	POS		=> [5,	\$POS],
	Terminal	=> [80,	\$TerminalID],
);

use strict;
use Exporter;
use vars qw(@ISA @EXPORT

			$OrderID $Amount $Currency $CardType $Email $Phone $Address $POS $FirstName 
			$MiddleName $LastName $Language  $Request $SignedRequest $Registered $LengthError 


				%PARAM_LENGTH
				$Version
				$EnterpriseName
				$MerchantName
				$HelpDesk
				$OperationType
				%LANG_SETTINGS

				%ERRORS
				%ERRORS_RUS
				%ERRORS_ENG
				%ErrorLog

                                $WillConvert
				$SessionExt
				$SessionStatus
				$OperationStatus
				$LogLevel
				$SessionID
				$TransactionID
				
				$Currency
				$Payment
				$PaymentDetails
				$OrderID
		
				$TerminalID
				$ManyTerminals
				$ManyChecks
				$ManyTemplates
				$CheckExt
				$SaveChecks
				
				$Language
                                $CheckParams
				$SaveSignedRequest
				$SaveSession
			);
@ISA		=	qw(Exporter);
@EXPORT		=	qw
			(

			$OrderID $Amount $Currency $CardType $Email $Phone $Address $POS $FirstName 
			$MiddleName $LastName $Language  $Request $SignedRequest $Registered $LengthError



				%PARAM_LENGTH
				$Version
				$EnterpriseName
				$MerchantName
				$HelpDesk
				$OperationType
				%LANG_SETTINGS
		
				%ERRORS
				%ERRORS_RUS
				%ERRORS_ENG		
				%ErrorLog

				
                                $WillConvert
				$SessionExt
				$SessionStatus
				$OperationStatus
				$LogLevel

				$SessionID
				$TransactionID
				$Language
				$Currency
				$PaymentDetails
				$OrderID
				
				$TerminalID
				$ManyTerminals
				$ManyChecks
				$ManyTemplates

				$CheckExt
				$CheckParams
				$SaveSignedRequest
				$SaveChecks
				$SaveSession
			);

1;