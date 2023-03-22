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
$price = htmlspecialchars($_GET['price']);

$req = $db->prepare(
  'SELECT * FROM RESERVATION WHERE id_activity = :id_activity AND date = :date AND time = :time AND siret = :siret',
);
$req->execute([
  'id_activity' => $idActivity,
  'siret' => $siret,
  'date' => $date,
  'time' => $select,
]);
$response = $req->fetch(PDO::FETCH_ASSOC);

if ($response) {
  header('Location: ../reservation.php?&id=' . $idActivity . '&message=Vous avez déjà réservé ce créneau!&type=danger');
  exit();
}

$req = $db->prepare(
  'INSERT INTO RESERVATION (id_activity, siret, date, time, attendee) VALUES (:id_activity, :siret, :date, :time, :attendee)',
);
$result = $req->execute([
  'id_activity' => $idActivity,
  'siret' => $siret,
  'date' => $date,
  'time' => $select,
  'attendee' => $attendee,
]);

if ($result) {
  $getId = $db->prepare('SELECT MAX(id) as id FROM RESERVATION');
  $getId->execute();
  $id = $getId->fetch(PDO::FETCH_ASSOC);

  $req = $db->prepare(
    'INSERT INTO ESTIMATE (amount, creationDate, id_reservation) VALUES (:amount, :creationDate, :id_reservation)',
  );
  $result = $req->execute([
    'amount' => $price * $attendee,
    'creationDate' => date('Y-m-d'),
    'id_reservation' => $id['id'],
  ]);

  if ($result) {
    header('Location: ../activity.php?&id=' . $idActivity . '&message=Réservation bien enregistré!&type=success');
    exit();
  }
} else {
  header('Location: ../reservation.php?&id=' . $idActivity . '&message=Erreur lors de la réservation!&type=danger');
  exit();
}
