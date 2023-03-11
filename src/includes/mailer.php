<?php

$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = 'teameasepa@gmail.com';
$mail->Password = 'q7D9cg#)Gsm@8U"2';
$mail->setFrom('teameasepa@gmail.com'); // Adresse mail du site
$mail->addAddress($email);
$mail->Subject = $subject;
$mail->Message = $mailMsg;
$mail->msgHTML($msgHTML);
$mail->CharSet = 'UTF-8';
if (!$mail->send()) {
  echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
  header("location: $destination?message=Un mail viens de vous etre envoyé !&type=success");
  exit();
}
