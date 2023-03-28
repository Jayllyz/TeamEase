<?php
session_start();
if (!isset($_SESSION['rights']) || $_SESSION['rights'] != 2) {
  header('location: ../index.php');
  exit();
}
include '../includes/db.php';
$date = date('d/m/Y H:i:s');
$id = htmlspecialchars($_GET['id']);

$del = $db->prepare('DELETE FROM PROVIDER WHERE id = :id');

$del->execute([
  'id' => $id,
]);

header('location: ../admin.php?message=Prestataire supprimée avec succès&type=success');
exit();
