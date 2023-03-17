<?php
include '../includes/db.php';

if (isset($_POST['location']) && isset($_POST['add'])) {
  $query = $db->prepare('INSERT INTO ROOM (name, id_location) VALUES (:name, :id_location)');
  $result = $query->execute([
    'name' => 'Nouvelle salle',
    'id_location' => $_POST['location'],
  ]);

  if ($result) {
    $query = $db->prepare('SELECT id FROM ROOM ORDER BY id DESC LIMIT 1');
    $query->execute();
    $id = $query->fetch(PDO::FETCH_COLUMN);
    echo 'success' . $id;
  } else {
    echo 'Une erreur est survenue';
  }
  exit();
}

if (isset($_POST['delete']) && isset($_POST['id'])) {
  $query = $db->prepare('DELETE FROM ROOM WHERE id = :id');
  $result = $query->execute([
    'id' => $_POST['id'],
  ]);

  if ($result) {
    echo 'success';
  } else {
    echo 'Une erreur est survenue';
  }
  exit();
}

if (isset($_POST['id']) && isset($_POST['name'])) {
  $query = $db->prepare('UPDATE ROOM SET name = :name WHERE id = :id');
  $result = $query->execute([
    'name' => $_POST['name'],
    'id' => $_POST['id'],
  ]);

  if ($result) {
    echo 'success';
  } else {
    echo 'Une erreur est survenue';
  }
}
?>
