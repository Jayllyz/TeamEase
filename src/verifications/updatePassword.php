<?php
session_start();
include '../includes/db.php';

$email = $_POST['email'];

$req = $db->prepare('SELECT siret FROM COMPANY WHERE email = :email');
$req->execute([
  'email' => $email,
]);

$response = $req->fetch();

if (!$response) {
  $req = $db->prepare('SELECT id FROM PROVIDER WHERE email = :email');
  $req->execute([
    'email' => $email,
  ]);

  $response = $req->fetch();

  if (!$response) {
    header('location: ../lostPassword.php?message=Cet email n\'existe pas !&type=danger&input=email&valid=invalid');
    exit();
  }
  $company = false;
} else {
  $company = true;
}

$token = uniqid();
if ($company) {
  $req = $db->prepare('UPDATE COMPANY SET token = :token WHERE email = :email');
} else {
  $req = $db->prepare('UPDATE PROVIDER SET token = :token WHERE email = :email');
}

$req->execute([
  'token' => $token,
  'email' => $email,
]);

if ($company) {
  $req = $db->prepare('SELECT email FROM COMPANY WHERE email = :email');
} else {
  $req = $db->prepare('SELECT email FROM PROVIDER WHERE email = :email');
}

$req->execute([
  'email' => $email,
]);

$result = $req->fetch(PDO::FETCH_ASSOC);
if ($result) {
  $subject = 'Réinitialisation du mot de passe';
  $mailMsg = 'Modifier votre mot de passe';
  $msgHTML =
    '<p>Bonjour,</p>
    <p class="display-2">Pour réinitialiser votre mot de passe, veuillez <a href="localhost/includes/changePassword.php?email=' .
    $email .
    '&token=' .
    $token .
    '&company=' .
    $company .
    '">cliquez ici</a></p>';
  include '../includes/mailer.php';
  header('location: ../login.php?message=Un email vous a été envoyé !&type=success&input=email&valid=valid');
  exit();
} else {
  header('location: ../lostPassword.php?message=Cet email n\'existe pas !&type=danger&input=email&valid=invalid');
  exit();
}
