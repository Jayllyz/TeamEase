<?php
session_start();
include '../includes/db.php';
$email = $_POST['email'];
$companyName = $_POST['companyName'];
$address = $_POST['address'];
$rights = htmlspecialchars($_GET['rights']);
$siret = htmlspecialchars($_GET['siret']);

if (isset($email) && !empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header('location: ../modifyCompany.php?message=Email invalide !&type=danger');
  exit();
}

if (
  (isset($rights) && !empty($rights)) ||
  (isset($companyName) && !empty($companyName)) ||
  (isset($email) && !empty($email)) ||
  (isset($address) && !empty($address))
) {
  setcookie('rights', $rights, time() + 3600, '/');

  $compare = $db->prepare('SELECT email, companyName, address, siret FROM COMPANY');
  $compare->execute();
  $compare = $compare->fetchAll();

  for ($i = 0; $i < count($compare); $i++) {
    if ($compare[$i]['email'] == $email && $compare[$i]['siret'] != $siret) {
      header('location: ../profile.php?message=Email déjà utilisé !&type=danger');
      exit();
    }
    if ($compare[$i]['companyName'] == $companyName && $compare[$i]['siret'] != $siret) {
      header("location: ../profile.php?message=Nom d'entreprise déjà utilisé !&type=danger");
      exit();
    }
    if ($compare[$i]['address'] == $address && $compare[$i]['siret'] != $siret) {
      header('location: ../profile.php?message=Adresse déjà utilisé !&type=danger');
      exit();
    }
  }

  $req = $db->prepare('SELECT email FROM COMPANY WHERE siret = :siret');
  $req->execute([
    'siret' => $siret,
  ]);
  $oldemail = $req->fetch();
  $oldemail = $oldemail['email'];

  if ($oldemail != $email) {
    $token = uniqid();

    $update = $db->prepare(
      'UPDATE COMPANY SET email = :email, companyName = :companyName, rights = :rights, address = :address, token= :token, confirm_signup= :confirm_signup WHERE siret = :siret'
    );
    $update->execute([
      'email' => $email,
      'companyName' => $companyName,
      'rights' => $rights,
      'address' => $address,
      'siret' => $siret,
      'token' => $token,
      'confirm_signup' => 0,
    ]);

    $subject = 'Confirmation de changement de mail';
    $msgHTML =
      '<img src="localhost/images/logo.png" class="logo float-left m-2 h-75 me-4" width="95" alt="Logo">
                  <p class="display-2">Bienvenue chez Together&Stronger. Veuillez cliquer sur le lien ci-dessous pour confirmer votre changement de mail :<br></p>
        <a href="localhost/includes/confemail.php?' .
      'token=' .
      $token .
      '&email=' .
      $email .
      '&type=' .
      'company">Confirmation changement de mail !</a>';
    $destination = '../login.php';
    include '../includes/mailer.php';
  } else {
    $update = $db->prepare(
      'UPDATE COMPANY SET email = :email, companyName = :companyName, rights = :rights, address = :address WHERE siret = :siret'
    );
    $update->execute([
      'email' => $email,
      'companyName' => $companyName,
      'rights' => $rights,
      'address' => $address,
      'siret' => $siret,
    ]);
    header('location: ../profile.php?message=Modification effectuée !&type=success');
    exit();
  }
}
