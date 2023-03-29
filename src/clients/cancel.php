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

header('Location: ../clients/reservations.php?message=Votre réservation a bien été annulée.&type=success');
exit();
