<?php
session_start();
if(!isset($_SESSION['rights']) || $_SESSION['rights'] != 2) {
  header('location: ../index.php');
  exit();
}
include '../includes/db.php';
$date = date('d/m/Y H:i:s');
$siret = htmlspecialchars($_GET['siret']);
$name = $_POST['name'];
$companyName = htmlspecialchars($_GET['name']);
$rights = htmlspecialchars($_GET['rights']);

if ($name == $companyName) {
  if ($rights != -1) {
    $del = $db->prepare('UPDATE COMPANY SET rights = -1 WHERE siret = :siret');
    $del->execute([
      'siret' => $siret,
    ]);

    header('location: ../admin.php?message=Entreprise banni avec succès&type=success');
    exit();
  } else {
    $del = $db->prepare('UPDATE COMPANY SET rights = 0 WHERE siret = :siret');
    $del->execute([
      'siret' => $siret,
    ]);

    header('location: ../admin.php?message=Entreprise débanni avec succès&type=success');
    exit();
  }
} else {
  header('location: ../admin.php?message=Le nom d\'entreprise saisi est incorrect ! Veuillez réessayer.&type=danger');
  exit();
}