// ������������ ���������
var doc="Hello world";


// ��������� ������ ��������� ����� (��� ������������ �������)
var UserSecretKey=new ActiveXObject ("Libipriv.SecretKey");
// ��������� ������ ��������� ����� (��� �������� �������)
var UserPublicKey=new ActiveXObject ("Libipriv.PublicKey");
// ��������� ������ ��� ������ � ���
var Signer=new ActiveXObject ("Libipriv.Signer");

// ��������� �������� ���� �� ����� "secret.key", ������� ����� - "1111111111"
if(UserSecretKey.LoadFromFile("secret.key","1111111111"))
{
// ��������� �������� ���� �� ����� "pubkeys.key" � �������� ������� "17033"
	if(UserPublicKey.LoadFromFile("pubkeys.key",17033))
	{
// ����������� ������������ ��������� �������� ������
		var signmessage=Signer.Sign(doc,UserSecretKey);
		if(signmessage!="")
		{
			WScript.Echo(signmessage);
// ��������� ������� �������� ������
			var message=Signer.Verify(signmessage,UserPublicKey);
			if(message!="")
				WScript.Echo(message);
			else WScript.Echo(Signer.ErrMsg);
		}else WScript.Echo(Signer.ErrMsg);
	}else WScript.Echo(UserPublicKey.ErrMsg);
}else WScript.Echo(UserSecretKey.ErrMsg);
