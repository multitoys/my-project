<?php

  ini_set('display_errors', true);
  define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT']);
  include(DIR_ROOT.'/popup/class.phpmailer.php');

	$thm = "тест";
	$msg = 'Тестовое письмо';

	// Отправляем почтовое сообщение
	$o = send_mail2('carolsoft@mail.ru', $thm, $msg, '','');


  function send_mail2($mail_to, $thema, $html, $filename, $file_content)
  {
    $mailer = new PHPMailer();
    $mail_from = 'carolsoft@mail.ru';

    $mailer->Priority = 1;

    $mailer->From = $mail_from;
    $mailer->FromName = '';

    $mailer->AddAddress($mail_to);
    $mailer->Subject = $thema;
    $mailer->Body = $html;

    $mailer->CharSet = 'utf-8';
    $mailer->IsHTML(true);

    if (strlen($file_content)>20)
    {
      $mailer->AddStringAttachment($file_content, $filename);
    }

//    $mailer->Mailer = 'sendmail';
//    $mailer->Sendmail = 'D:\server\sendmail\sendmail.exe -t';

    echo('result: '.$mailer->Send().'<br>');
    print_r($mailer->ErrorInfo);
    echo('<br>');
  }

?>