<?php
session_start();
include '../includes/db.php';
$email = $_POST['email'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$rights = htmlspecialchars($_GET['rights']);
$id = htmlspecialchars($_GET['id']);



if (isset($email) && !empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header('location: ../modifyProvider.php?message=Email invalide !&type=danger');
  exit();
}

if (
  (isset($rights) && !empty($rights)) ||
  (isset($firstName) && !empty($firstName)) ||
  (isset($email) && !empty($email)) ||
  (isset($lastName) && !empty($lastName))
) {
  setcookie('rights', $rights, time() + 3600, '/');

  $compare = $db->prepare('SELECT email, firstName, lastName, id FROM PROVIDER');
  $compare->execute();
  $compare = $compare->fetchAll();

  for ($i = 0; $i < count($compare); $i++) {
    if ($compare[$i]['email'] == $email && $compare[$i]['id'] != $id) {
      header('location: ../profile.php?message=Email déjà utilisé !&type=danger');
    }
    if ($compare[$i]['firstName'] == $firstName && $compare[$i]['lastName'] == $lastName && $compare[$i]['id'] != $id) {
      header("location: ../profile.php?message=Nom et Prénom déjà utilisé !&type=danger");
    }
  }

 
  $update = $db->prepare(
    'UPDATE PROVIDER SET email = :email, firstName = :firstName, rights = :rights, lastName = :lastName WHERE id = :id'
  );
  $update->execute([
    'email' => $email,
    'firstName' => $firstName,
    'rights' => $rights,
    'lastName' => $lastName,
    'id' => $id,
  ]);
  header('location: ../profile.php?message=Modification effectué !&type=success');
  exit();
}