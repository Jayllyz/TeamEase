<?php
session_start();

include 'db.php';

$type = htmlspecialchars($_GET['type']);

if ($type == 'company') {
  $req = $db->prepare('SELECT siret, token FROM COMPANY WHERE email = :email');
}
if ($type == 'provider') {
  $req = $db->prepare('SELECT id, token FROM PROVIDER WHERE email = :email');
}
$req->execute([
  'email' => htmlspecialchars($_GET['email']),
]);
$result = $req->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $existToken) {
  if ($type == 'company') {
    $id = $existToken['siret'];
  } else {
    $id = $existToken['id'];
  }
  if ($existToken != '') {
    $token = htmlspecialchars($_GET['token']);
    $email = htmlspecialchars($_GET['email']);
    if (isset($token)) {
      if ($type == 'company') {
        $q = $db->prepare('UPDATE COMPANY SET confirm_signup = 1 WHERE email = :email AND token = :token');
      }
      if ($type == 'provider') {
        $q = $db->prepare('UPDATE PROVIDER SET confirm_signup = 1 WHERE email = :email AND token = :token');
      }
      $q->execute([
        'email' => $email,
        'token' => $token,
      ]);
      if ($type == 'company') {
        $req = $db->prepare('UPDATE COMPANY set token = "" WHERE email = :email');
      }

      if ($type == 'provider') {
        $req = $db->prepare('UPDATE PROVIDER set token = "" WHERE email = :email');
      }

      $req->execute([
        'email' => $email,
      ]);

      $_SESSION['email'] = $email;
      session_destroy();
      header('location: ../login.php?message=Votre compte à bien été confirmé !&type=success');
      exit();
    } else {
      session_destroy();
      echo "Email doesn't confirmed !";
    }
  } else {
    header('location: localhost/?message=Le liens à expiré !&type=danger');
    exit();
  }
}
