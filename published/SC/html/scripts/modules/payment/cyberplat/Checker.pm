package Checker;

use Win32::OLE;
use Paths;

use vars qw(%checker_config);

# ���������� ������ checker.exe (checker.ini)
sub swallow_config
{
	open ( my $in_f, "<$SIGN_INI" );
	while ( <$in_f> ) {
		if ( /^([A-Za-z]+)=([^;]*);$/ ) {
			$checker_config { $1 } = $2;
		}
	}
	close ( $in_f );
}

# ��������� ��������� ��������� ������ ��������
sub sign
{
	my $message = shift;
	my $secret_key = Win32::OLE->new('Libipriv.SecretKey');
	my $signer = Win32::OLE->new('Libipriv.Signer');

	swallow_config();
	$secret_key->LoadFromFile(
		$checker_config { 'keypath' } . $checker_config { 'seckeyfile'},
		$checker_config { 'password' }
	);
	return 'SessionStatus=���������� ��������� ��������� ���� ��������' if ! $secret_key;

	my $signmessage = $signer->Sign( $message, $secret_key );
	return 'SessionStatus=������ ��� ������� ���������' if !$signmessage;

	return $signmessage;
}

# ��������� ������� � ���������
sub verify
{
	my $signmessage = shift;
	my $public_key = Win32::OLE->new('Libipriv.PublicKey');
	my $signer = Win32::OLE->new('Libipriv.Signer');

	swallow_config();
	$public_key->LoadFromFile(
		$checker_config { 'keypath' } . $checker_config { 'pubkeyfile' },
		$checker_config { 'bankkey' }
	);
	return 'SessionStatus=���������� ��������� ��������� ���� CyberCardServer' if ! $public_key;

	my $message = $signer->Verify( $signmessage, $public_key );
	return 'SessionStatus=������ ��� �������� ������� ���������' if ! $message;

	return $message;
}

1;

