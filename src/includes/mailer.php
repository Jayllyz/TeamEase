<?php
$emailEnv = $_ENV['EMAIL'];

$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = $emailEnv;
$mail->Password = $_ENV['MYSQL_ROOT_PASSWORD'];
$mail->setFrom($emailEnv); // Adresse mail du site
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
