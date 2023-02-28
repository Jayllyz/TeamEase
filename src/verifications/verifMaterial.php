<?php
include '../includes/db.php';

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
  $delete = $db->prepare('DELETE FROM MATERIAL WHERE type=:type');
  $result = $delete->execute([
    'type' => $_POST['material'],
  ]);
  if ($result) {
    echo 'success';
  } else {
    echo 'error';
  }
  exit();
}

$seek = $db->prepare('SELECT id FROM MATERIAL WHERE type=:type');
$seek->execute([
  'type' => $_POST['material'],
]);

if ($seek->rowCount() > 0) {
  $id = $seek->fetch();
  $update = $db->prepare('UPDATE MATERIAL SET quantity = :quantity WHERE id = :id');
  $result = $update->execute([
    'quantity' => $_POST['quantity'],
    'id' => $id[0],
  ]);
} else {
  $insert = $db->prepare('INSERT INTO MATERIAL (type, quantity) VALUES (:type, :quantity)');
  $result = $insert->execute([
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
