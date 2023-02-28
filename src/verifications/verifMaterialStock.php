<?php
include '../includes/db.php';

$query = $db->prepare('SELECT quantity FROM MATERIAL WHERE id = :id');
$query->execute([
  ':id' => $_POST['id'],
]);
$quantity = $query->fetch(PDO::FETCH_COLUMN);
$query = $db->prepare('SELECT SUM(quantity) FROM MATERIAL_ACTIVITY WHERE id_material = :id');
$query->execute([
  ':id' => $_POST['id'],
]);
$used = $query->fetch(PDO::FETCH_COLUMN);
if ($used == null) {
  $used = 0;
}
$available = $quantity - $used;
echo $available;
?>
