<?php
ob_start();
session_start();
require_once '../includes/db.php';

var_dump($_POST);

$siret = $_SESSION['siret'];
$idReserv = htmlspecialchars($_GET['id']);
$date = htmlspecialchars($_POST['date']);
$date = date('Y-m-d', strtotime($date));
$select = htmlspecialchars($_POST['slot']);

$update=$db->prepare('UPDATE RESERVATION SET date = :date, time = :time, attendee = :attendee WHERE id = :id AND siret = :siret');
$update->execute([
  'id' => $idReserv,
  'date' => $date,
  'time' => $select,
  'attendee' => $_POST['attendee'],
  'siret' => $siret,
]);

$udapteEstimate=$db->prepare('UPDATE ESTIMATE SET amount = :amount WHERE id_reservation = :id_reservation');
$udapteEstimate->execute([
  'id_reservation' => $idReserv,
  'amount' => $_POST['price'] * $_POST['attendee'],
]);

header('Location: ../clients/reservations.php?message=Votre réservation à bien été modifié!&type=success');
exit();