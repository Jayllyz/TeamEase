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
  }
  $id = $existToken['id'];
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
      echo "
  <div class='text-center'>
  <div class='alert alert-success' role='alert'>
    <img src='../images/logo.png' class='logo float-left m-2 h-75 me-4' width='95' alt='Logo'>
    <h4 class='alert-heading'><strong>Congratulations !</strong></h4>
    <p>Votre compte a été confirmé ! Vous pouvez retourner sur notre site en cliquant sur ce lien :
    <a href='localhost/login.php' class='text-decoration-none'><em>Home</em></a> pour vous connecter et fermer
    cette page.
    Together&Stronger, vous souhaite la bienvenue !</p>
  </div>
  </div>
  ";
    } else {
      session_destroy();
      echo "Email doesn't confirmed !";
    }
  } else {
    header('location: localhost/?message=Le liens à expiré !&type=danger');
    exit();
  }
}
