<?php
session_start();
if(!isset($_SESSION['rights']) || $_SESSION['rights'] != 2) {
  header('location: ../index.php');
  exit();
}
include '../includes/db.php';
$date = date('d/m/Y H:i:s');
$id = htmlspecialchars($_GET['id']);
$lastName = $_POST['name'];
$name = htmlspecialchars($_GET['name']);
$rights = htmlspecialchars($_GET['rights']);

if ($name == $lastName) {
  if ($rights != -1) {
    $del = $db->prepare('UPDATE PROVIDER SET rights = -1 WHERE id = :id');
    $del->execute([
      'id' => $id,
    ]);

    header('location: ../admin.php?message=Prestataire banni avec succès&type=success');
    exit();
  } else {
    $del = $db->prepare('UPDATE PROVIDER SET rights = 1 WHERE id = :id');
    $del->execute([
      'id' => $id,
    ]);

    header('location: ../admin.php?message=Prestataire débanni avec succès&type=success');
    exit();
  }
} else {
  header('location: ../admin.php?message=Le nom du prestataire saisi est incorrect ! Veuillez réessayer.&type=danger');
  exit();
}