<?php
require_once '../includes/db.php';

$idReserv = htmlspecialchars($_POST['idReserv']);
$attendee = htmlspecialchars((int) $_POST['attendees']);
$participants = $_POST['participants'];

$checkParticipants = $db->prepare('SELECT id_attendee FROM RESERVED WHERE id_reservation = :id');
$checkParticipants->execute([
  'id' => $idReserv,
]);
$checkParticipants = $checkParticipants->fetchAll(PDO::FETCH_ASSOC);

if (!empty($checkParticipants)) {
  foreach ($checkParticipants as $id) {
    $req = $db->prepare('DELETE FROM ATTENDEE WHERE id = :id');
    $req->execute([
      'id' => $id['id_attendee'],
    ]);

    $req = $db->prepare('DELETE FROM RESERVED WHERE id_attendee = :id');
    $req->execute([
      'id' => $id['id_attendee'],
    ]);
  }
}

$array = explode(';', $participants);
$array = array_values($array);

for ($i = 0; $i < $attendee * 3; $i += 3) {
  $array[$i] = trim($array[$i]);
  $array[$i + 1] = trim($array[$i + 1]);
  $array[$i + 2] = trim($array[$i + 2]);

  $req = $db->prepare('SELECT id FROM ATTENDEE WHERE email = :email');
  $req->execute([
    'email' => htmlspecialchars($array[$i + 2]),
  ]);
  $idAttendee = $req->fetch(PDO::FETCH_ASSOC);
  $password = '';

  if (!$idAttendee) {
    $password = bin2hex(random_bytes(5));
    $req = $db->prepare(
      'INSERT INTO ATTENDEE (lastName, firstName, email, password) VALUES (:lastName, :firstName, :email, :password)',
    );
    $req->execute([
      'lastName' => htmlspecialchars($array[$i]),
      'firstName' => htmlspecialchars($array[$i + 1]),
      'email' => htmlspecialchars($array[$i + 2]),
      'password' => hash('sha512', $password),
    ]);
  }

  $email = $array[$i + 2];
  $subject = 'Votre compte utilisateur TeamEase';
  $msgHTML =
    '<p>Bienvenue chez Together&Stronger. Votre compte a été créé, vous pouvez vous connecter à votre compte sur mobile avec votre email et le mot de passe suivant :<br></p>' .
    $password;

  include '../includes/mailer.php';

  $req = $db->prepare(
    'SELECT id FROM ATTENDEE WHERE lastName = :lastName AND firstName = :firstName AND email = :email',
  );
  $req->execute([
    'lastName' => htmlspecialchars($array[$i]),
    'firstName' => htmlspecialchars($array[$i + 1]),
    'email' => htmlspecialchars($array[$i + 2]),
  ]);
  $idAttendee = $req->fetch(PDO::FETCH_ASSOC);

  $req = $db->prepare('INSERT INTO RESERVED (id_attendee, id_reservation) VALUES (:id_attendee, :id_reservation)');
  $req->execute([
    'id_attendee' => $idAttendee['id'],
    'id_reservation' => $idReserv,
  ]);
}

echo 'La liste des participants a bien été mise à jour';
exit();