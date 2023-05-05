<?php
include '../includes/db.php';
$id = htmlspecialchars($_GET['id']);

if (!isset($id) || empty($id)) {
  header('location: ../index.php');
  exit();
} else {
  $sql = 'DELETE FROM PROVIDER WHERE id = :id';
  $query = $db->prepare($sql);
  $query->execute([
    'id' => $id,
  ]);

  header('location: ../logout.php');
  exit();
}
