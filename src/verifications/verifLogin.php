<?php
session_start();
date_default_timezone_set('Europe/Paris');
$date = date('d/m/Y H:i:s');

include '../includes/db.php';

if (isset($_POST['submit'])) {
  if (isset($_POST['login']) && !empty($_POST['login'])) {
    setCookie('login', $_POST['login'], time() + 24 * 3600);
  }

  if (empty($_POST['login']) || !filter_var($_POST['login'], FILTER_VALIDATE_EMAIL)) {
    header('location: ../login.php?message=Email invalide !&type=danger&input=email&valid=invalid');
    exit();
  }

  if (!isset($_POST['password']) || empty($_POST['password'])) {
    header('location: ../login.php?message=Mot de passe manquant !&type=danger&input=password&valid=invalid');
    exit();
  }

  $req = $db->prepare('SELECT siret FROM COMPANY WHERE email = :email');
  $req->execute([
    'email' => $_POST['login'],
  ]);

  $reponse = $req->fetch();

  if (!$reponse) {
    $req = $db->prepare('SELECT id FROM PROVIDER WHERE email = :email');
    $req->execute([
      'email' => $_POST['login'],
    ]);

    $reponse = $req->fetch();

    if (!$reponse) {
      header('location: ../login.php?message=Email incorrect !&type=danger&input=email&valid=invalid');
      exit();
    }
  }

  $req = $db->prepare(
    'SELECT siret, rights,email,confirm_signup FROM COMPANY WHERE email = :email AND password = :password',
  );
  $req->execute([
    'email' => $_POST['login'],
    'password' => hash('sha512', $_POST['password']),
  ]);

  $reponse = $req->fetchAll(PDO::FETCH_ASSOC);

  if ($reponse) {
    foreach ($reponse as $select) {
      if ($select['confirm_signup'] == 0) {
        header('location: ../login.php?message=Votre compte n\'est pas encore activé !&type=danger');
        exit();
      }
      if ($select['rights'] != -1) {
        $_SESSION['siret'] = $select['siret'];
        $_SESSION['rights'] = $select['rights'];
        $_SESSION['email'] = $select['email'];
        setcookie('email', $_POST['login'], time() + 3600);
        $login = $_POST['login'];
        header('location: ../index.php?message=Vous êtes connecté&type=success');
        exit();
      }
    }
  } else {
    $req = $db->prepare(
      'SELECT id, rights,email,confirm_signup FROM PROVIDER WHERE email = :email AND password = :password',
    );
    $req->execute([
      'email' => $_POST['login'],
      'password' => hash('sha512', $_POST['password']),
    ]);

    $reponse = $req->fetchAll(PDO::FETCH_ASSOC);

    if ($reponse) {
      foreach ($reponse as $select) {
        if ($select['confirm_signup'] == 0) {
          header('location: ../login.php?message=Votre compte n\'est pas encore activé !&type=danger');
          exit();
        }
        if ($select['rights'] != -1) {
          $_SESSION['id'] = $select['id'];
          $_SESSION['rights'] = $select['rights'];
          $_SESSION['email'] = $select['email'];
          setcookie('email', $_POST['login'], time() + 3600);
          $login = $_POST['login'];
          header('location: ../index.php?message=Vous êtes connecté&type=success');
          exit();
        }
      }
    } else {
      header('location: ../login.php?message=Mot de passe incorrect !&type=danger&input=password&valid=invalid');
      exit();
    }
  }
}
