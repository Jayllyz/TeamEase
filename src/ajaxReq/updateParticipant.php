<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['siret'])) {
  header('Location: ../index.php');
  exit();
}

$id = htmlspecialchars($_POST['id']);
$lastname = htmlspecialchars($_POST['lastname']);
$firstname = htmlspecialchars($_POST['firstname']);
$mail = htmlspecialchars($_POST['email']);

$select = $db->prepare('SELECT * FROM ATTENDEE WHERE id = :id');
$select->execute([
  'id' => $id,
]);

$participant = $select->fetch(PDO::FETCH_ASSOC);

if (
  $participant['lastName'] === $lastname &&
  $participant['firstName'] === $firstname &&
  $participant['email'] === $mail
) {
  echo 'false';
  exit();
}

if ($participant['email'] !== $mail) {
  if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    echo 'Email invalide';
    exit();
  }
  $password = bin2hex(random_bytes(5));

  $req = $db->prepare(
    'UPDATE ATTENDEE SET lastName = :lastName, firstName = :firstName, email = :email, password = :password WHERE id = :id',
  );
  $req->execute([
    'lastName' => $lastname,
    'firstName' => $firstname,
    'email' => $mail,
    'password' => hash('sha512', $password),
    'id' => $id,
  ]);

  $email = $mail;
  $subject = 'Votre compte utilisateur TeamEase';
  $msgHTML =
    '<p>Bienvenue chez Together&Stronger. Votre compte a été créé, vous pouvez vous connecter à votre compte sur mobile avec votre email et le mot de passe suivant :<br></p>' .
    $password;

  include_once '../includes/mailer.php';
}

if ($participant['lastName'] !== $lastname || $participant['firstName'] !== $firstname) {
  $req = $db->prepare('UPDATE ATTENDEE SET lastName = :lastName, firstName = :firstName WHERE id = :id');
  $req->execute([
    'lastName' => $lastname,
    'firstName' => $firstname,
    'id' => $id,
  ]);
}

echo 'Participant modifié';
exit();
