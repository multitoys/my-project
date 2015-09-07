package Log;

use strict;
use integer;

#use CGI::Carp qw(fatalsToBrowser);
use Fcntl ':flock';
use POSIX qw(strftime);
use Exporter;

use	vars						qw(@ISA @EXPORT $Version $LogFilename $LogTolerance);
@ISA						=	qw(Exporter);

$Version					=	1.1;

#������ ������� ���-�����:
#0 - �������� ��������� � ��������� � ������ � ����� ���-������
#1 - ��������� � ��������� ����������� ��������, ������� 1
#2 - ��������� � ��������� ����������� ��������, ������� 2
#3 - �������������� ������ � ��������
#4 - ��������� ������
#5 - �������� ������ � ������ �������
my @LogLevels				=	('.', '-', '+', '*', '^', '!');

@EXPORT						=	qw(
								OpenLog
								CloseLog
								WriteLog
								SetLogTolerance
							);

$LogTolerance				=	0;

#���������� '����������' � ������������ ����������. ��� ���������� 0 ������������ ��� ���������,
#��� '����������' 5 - ������ ����� ������.
sub SetLogTolerance()
{
	$LogTolerance			=	shift;
}

#������� ���-����. �� ����� �������� ��� ����� � �������������.
sub OpenLog
{
	if (!$LogFilename)
	{
		$LogFilename		=	shift;
	}
	my $LT					=	shift;
	if ($LT)	{
		&SetLogTolerance($LT);
	}

	&WriteLog("Log '$LogFilename' opened", 0);
}

#������� ���-����. �� ����� �������� ��������� � ��� �������.
sub CloseLog
{
	&WriteLog('Log closed', 0);
}

#�������� ��������� � ���-����.
sub WriteLog()
{
	my $LogMessage			=	shift;
	my $LogLevel			=	shift;
	my $LevelSign			=	$LogLevels[$LogLevel];
	if (!$LevelSign){
			$LogLevel		=	0;
			$LevelSign		=	$LogLevels[$LogLevel];
	}

	if ($LogLevel < $LogTolerance)	{
		return;
	}

	my $TimeStamp			= strftime "%c", localtime;

	$LogMessage			= $LevelSign . $TimeStamp . ': ' . $LogMessage;

	open(LOGFILE, '>>'.$LogFilename)   	or die "can't open LOGFILE:$LogFilename: $!";
	flock(LOGFILE, LOCK_EX)		   	or die "can't flock LOGFILE: $!";

	(print LOGFILE $LogMessage, "\n")	or die "can't write LOGFILE: $!";

	flock(LOGFILE, LOCK_UN)			or die "can't flock LOGFILE: $!";
	close LOGFILE				or die "can't close LOGFILE: $!";
}

1;

