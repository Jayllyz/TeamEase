<?php
include '../includes/db.php';

if (!isset($_POST['id'])) {
  exit();
}
if (!isset($_POST['material'])) {
  exit();
}
if (!isset($_POST['quantity'])) {
  exit();
}
if ($_POST['quantity'] < 0) {
  exit();
}
if ($_POST['material'] == '') {
  exit();
}

if ($_POST['delete'] == 'true') {
  $delete = $db->prepare('DELETE FROM MATERIAL WHERE id=:id');
  $result = $delete->execute([
    'id' => $_POST['id'],
  ]);
  $delete = $db->prepare('DELETE FROM MATERIAL_ACTIVITY WHERE id_material=:id');
  $result2 = $delete->execute([
    'id' => $_POST['id'],
  ]);
  if ($result && $result2) {
    echo 'success';
  } else {
    echo 'error';
  }
  exit();
}

$seek = $db->prepare('SELECT type FROM MATERIAL WHERE id=:id');
$seek->execute([
  'id' => $_POST['id'],
]);

if ($seek->rowCount() > 0) {
  $update = $db->prepare('UPDATE MATERIAL SET quantity = :quantity, type=:type WHERE id = :id');
  $result = $update->execute([
    'type' => $_POST['material'],
    'quantity' => $_POST['quantity'],
    'id' => $_POST['id'],
  ]);
} else {
  $insert = $db->prepare('INSERT INTO MATERIAL (id, type, quantity) VALUES (:id, :type, :quantity)');
  $result2 = $insert->execute([
    'id' => $_POST['id'],
    'type' => $_POST['material'],
    'quantity' => $_POST['quantity'],
  ]);
}

if ($result) {
  echo 'success';
} else {
  echo 'error';
}
?>
