<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['siret'])) {
  header('Location: ../index.php');
  exit();
}

if (!isset($_POST['id'])) {
  echo 'Erreur';
  exit();
}

$id = htmlspecialchars($_POST['id']);

$req = $db->prepare('DELETE FROM RESERVED WHERE id_attendee = :id_attendee');
$result = $req->execute([
  'id_attendee' => $id,
]);

if ($result) {
  echo 'Participant supprimé';
} else {
  echo 'Erreur';
}

exit();
