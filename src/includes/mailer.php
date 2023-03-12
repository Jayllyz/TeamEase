<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'teameasepa@gmail.com';
$mail->Password = $_ENV['MYSQL_ROOT_PASSWORD'];
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->setFrom('teameasepa@gmail.com'); // Adresse mail du site
$mail->addAddress($email);
$mail->Subject = $subject;
$mail->msgHTML($msgHTML);
$mail->CharSet = 'UTF-8';
if (!$mail->send()) {
  echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
  header("location: $destination?message=Un mail viens de vous etre envoyé !&type=success");
  exit();
}
