<?php
require_once '/home/php/src/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = $_ENV['EMAIL'];
$mail->Password = $_ENV['EMAIL_PASSWORD'];
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;

$mail->setFrom($_ENV['EMAIL']); // Adresse mail du site
$mail->addAddress($email);

if (isset($invoice)) {
  $mail->addStringAttachment($invoice, 'Facture.pdf');
}

$mail->isHTML(true);
if (isset($invoice)) {
  $mail->Subject = 'Facture de votre réservation';
} else {
  $mail->Subject = $subject;
}
$mail->Body = $msgHTML;
$mail->CharSet = 'UTF-8';
if (!$mail->send()) {
  echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
  if (!isset($invoice)) {
    header(
      "location: $destination?message=Un mail viens de vous etre envoyé pour confirmer votre compte!&type=success",
    );
    exit();
  }
}