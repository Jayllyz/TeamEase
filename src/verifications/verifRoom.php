<?php
include '../includes/db.php';

if (isset($_POST['location']) && isset($_POST['name'])) {
  if (isset($_POST['delete'])) {
    $query = $db->prepare('DELETE FROM ROOM WHERE name = :name AND id_location = :id_location');
    $result = $query->execute([
      'name' => $_POST['name'],
      'id_location' => $_POST['location'],
    ]);
    if ($result) {
      echo 'success';
    } else {
      echo 'Une erreur est survenue';
    }
    exit();
  }

  $query = $db->prepare('SELECT name FROM ROOM WHERE name=:name AND id_location=:id_location');
  $query->execute([
    'name' => $_POST['name'],
    'id_location' => $_POST['location'],
  ]);
  $rooms = $query->fetchAll(PDO::FETCH_COLUMN);
  if (count($rooms) > 0) {
    echo 'Ce nom de salle est déjà utilisé';
    exit();
  }

  $query = $db->prepare('INSERT INTO ROOM (name, id_location) VALUES (:name, :id_location)');
  $result = $query->execute([
    'name' => $_POST['name'],
    'id_location' => $_POST['location'],
  ]);

  if ($result) {
    echo 'success';
  } else {
    echo 'Une erreur est survenue';
  }
}
?>
