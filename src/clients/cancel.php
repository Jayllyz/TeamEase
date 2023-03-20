<?php

session_start();

include '../includes/db.php';

$id = htmlspecialchars($_GET['id']);

$req = $db->prepare('DELETE FROM RESERVATION WHERE id = :id');
$req->execute([
  'id' => $id,
]);

if ($req === false) {
  header(
    'Location: ../clients/reservations.php?message=Une erreur est survenue lors de l\'annulation de votre réservation.&type=error'
  );
  exit();
}

header('Location: ../clients/reservations.php?message=Votre réservation a bien été annulée.&type=success');
exit();
