<?php
include '../includes/db.php';

$query = $db->prepare('SELECT SUM(quantity) FROM MATERIAL_ROOM WHERE id_material = :id');
$query->execute([
  ':id' => $_POST['id'],
]);
$roomQuantity = $query->fetch(PDO::FETCH_COLUMN);
if ($roomQuantity == null) {
  $roomQuantity = 0;
}

$query = $db->prepare('SELECT SUM(quantity) FROM MATERIAL_LOCATION WHERE id_material = :id');
$query->execute([
  ':id' => $_POST['id'],
]);
$locationQuantity = $query->fetch(PDO::FETCH_COLUMN);
if ($locationQuantity == null) {
  $locationQuantity = 0;
}

$query = $db->prepare('SELECT quantity FROM MATERIAL WHERE id = :id');
$query->execute([
  ':id' => $_POST['id'],
]);
$quantity = $query->fetch(PDO::FETCH_COLUMN);

$available = $quantity - $roomQuantity - $locationQuantity;
echo $available;
?>
