<?php
session_start();
if (!isset($_SESSION['siret'])) {
  header('location: ../index.php');
  exit();
}
include '../includes/db.php';

$id = htmlspecialchars($_GET['id']);

if (!isset($id)) {
  header(
    'Location: ../clients/reservations.php?message=Une erreur est survenue lors de l\'annulation de votre réservation.&type=error',
  );
  exit();
}

$getReservation = $db->prepare('SELECT * FROM RESERVATION WHERE id = :id AND siret = :siret');
$getReservation->execute([
  'id' => $id,
  'siret' => $_SESSION['siret'],
]);

$getReservation = $getReservation->fetch(PDO::FETCH_ASSOC);

$getEmail = $db->prepare('SELECT email FROM COMPANY WHERE siret = :siret');
$getEmail->execute([
  'siret' => $_SESSION['siret'],
]);

$getEmail = $getEmail->fetch(PDO::FETCH_ASSOC);

$req = $db->prepare('DELETE FROM RESERVATION WHERE id = :id AND siret = :siret');
$req->execute([
  'id' => $id,
  'siret' => $_SESSION['siret'],
]);

if ($req === false) {
  header(
    'Location: ../clients/reservations.php?message=Une erreur est survenue lors de l\'annulation de votre réservation.&type=error',
  );
  exit();
}

if ($getReservation['status'] === 1) {
  $getReservation['date'] = date('d/m/Y', strtotime($getReservation['date']));
  $email = $getEmail['email'];
  $subject = 'Remboursement de votre réservation';
  $msgHTML =
    ' <p>Bonjour,</p>
  <p>Votre réservation du ' .
    $getReservation['date'] .
    ' a bien été annulée.</p>
  <p>Vous allez être remboursé dans les plus brefs délais à hauteur de 90%.</p>
  <p>Cordialement,</p>
  <p>Together&Stronger</p>';

  require_once '../includes/mailer.php';
  header(
    'Location: ../clients/reservations.php?message=Votre réservation a bien été annulée, vous allez recevoir un mail pour le remboursement.&type=success',
  );
  exit();
}

header('Location: ../clients/reservations.php?message=Votre réservation a bien été annulée.&type=success');
exit();
