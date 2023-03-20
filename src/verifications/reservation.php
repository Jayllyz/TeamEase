<?php
ob_start();
session_start();
include '../includes/db.php';

$attendee = htmlspecialchars($_POST['attendee']);
$date = htmlspecialchars($_POST['date']);
$date = date('Y-m-d', strtotime($date));

$select = htmlspecialchars($_POST['slot']);
$siret = $_SESSION['siret'];
$idActivity = htmlspecialchars($_GET['id']);

$req = $db->prepare('SELECT id_room FROM ACTIVITY WHERE id = :id');
$req->execute([
  'id' => $idActivity,
]);
$idRoom = $req->fetch(PDO::FETCH_ASSOC);

$req = $db->prepare(
  'SELECT * FROM RESERVATION WHERE id_activity = :id_activity AND id_room = :id_room AND date = :date AND time = :time AND siret = :siret'
);
$req->execute([
  'id_activity' => $idActivity,
  'id_room' => $idRoom['id_room'],
  'siret' => $siret,
  'date' => $date,
  'time' => $select,
]);
$response = $req->fetch(PDO::FETCH_ASSOC);

if ($response) {
  header('Location: ../reservation.php?&id=' . $idActivity . '&message=Vous avez déjà réservé ce créneau!');
  exit();
}

$req = $db->prepare(
  'INSERT INTO RESERVATION (id_activity, id_room,siret,  date, time, attendee) VALUES (:id_activity, :id_room, :siret, :date, :time, :attendee)'
);
$result = $req->execute([
  'id_activity' => $idActivity,
  'id_room' => $idRoom['id_room'],
  'siret' => $siret,
  'date' => $date,
  'time' => $select,
  'attendee' => $attendee,
]);

if ($result) {
  header('Location: ../activity.php?&id=' . $idActivity . '&message=Réservation bien enregistré!&type=success');
  exit();
} else {
  header('Location: ../reservation.php?&id=' . $idActivity . '&message=Erreur lors de la réservation!&type=error');
  exit();
}

exit();
