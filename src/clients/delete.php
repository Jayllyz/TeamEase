<?php
session_start();
include '../includes/db.php';
$date = date('d/m/Y H:i:s');
$siret = htmlspecialchars($_GET['siret']);

$del = $db->prepare('DELETE FROM COMPANY WHERE siret = :siret');

$del->execute([
  'siret' => $siret,
]);

header('location: ../admin.php?message=Entreprise supprimée avec succès&type=success');
exit();
