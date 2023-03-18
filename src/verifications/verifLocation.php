<?php

include '../includes/db.php';

if (isset($_POST['delete'])) {
  $query = $db->prepare('DELETE FROM LOCATION WHERE id = :id');
  $result = $query->execute([
    'id' => $_POST['delete'],
  ]);
  if ($result) {
    echo 'success';
  } else {
    echo 'Une erreur est survenue';
  }
  exit();
}

$query = $db->prepare('SELECT name FROM LOCATION WHERE name=:name');
$query->execute([
  'name' => $_POST['name'],
]);
$locations = $query->fetchAll(PDO::FETCH_COLUMN);

if (count($locations) > 0) {
  echo 'Ce nom de site est déjà utilisé';
  exit();
}

$query = $db->prepare('SELECT address FROM LOCATION WHERE address=:address');
$query->execute([
  'address' => $_POST['address'],
]);
$locations = $query->fetchAll(PDO::FETCH_COLUMN);

if (count($locations) > 0) {
  echo 'Cette adresse est déjà utilisée';
  exit();
}

$query = $db->prepare('INSERT INTO LOCATION (name, address) VALUES (:name, :address)');
$result = $query->execute([
  'name' => $_POST['name'],
  'address' => $_POST['address'],
]);

if ($result) {
  echo 'success';
} else {
  echo 'Une erreur est survenue';
}
?>
