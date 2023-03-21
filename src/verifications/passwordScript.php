<?php
session_start();
include '../includes/db.php';
if (isset($_POST['submit'])) {
  $password = $_POST['password'];
  $conf_password = $_POST['confPassword'];
  $email = htmlspecialchars($_GET['email']);
  $token = htmlspecialchars($_GET['token']);
  $company = htmlspecialchars($_GET['company']);
  if (strlen($password) < 6 || strlen($password) > 15) {
    header(
      'location: ../includes/changePassword.php?message=Mot de passe invalide. Il doit etre compris entre 6 et 15 caractères !&type=danger',
    );
    exit();
  }
  if ($password == $conf_password) {
    if ($company) {
      $req = $db->prepare('UPDATE COMPANY set token = "" WHERE email = :email');
    } else {
      $req = $db->prepare('UPDATE PROVIDER set token = "" WHERE email = :email');
    }
    $req->execute([
      'email' => $email,
    ]);

    if ($company) {
      $req = $db->prepare('UPDATE COMPANY SET password = :password WHERE email = :email');
    } else {
      $req = $db->prepare('UPDATE PROVIDER SET password = :password WHERE email = :email');
    }

    $req->execute([
      'password' => hash('sha512', $password),
      'email' => $email,
    ]);
    header('location: ../login.php?message=Votre mot de passe a bien été réinitialisé !&type=success');
    exit();
  } else {
    header(
      "location: ../includes/changePassword.php?message=Les mots de passes ne sont pas identiques !&type=warning&email=$email&token=$token",
    );
    exit();
  }
}
