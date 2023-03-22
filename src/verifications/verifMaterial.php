<?php
include '../includes/db.php';

if (isset($_POST['type'])) {
  if (isset($_POST['delete']) && isset($_POST['id']) && isset($_POST['idPosition'])) {
    if ($_POST['type'] == 'location') {
      $query = $db->prepare('DELETE FROM MATERIAL_LOCATION WHERE id_material = :id AND id_location = :idPosition');
      $result = $query->execute([
        ':id' => $_POST['id'],
        ':idPosition' => $_POST['idPosition'],
      ]);
      if ($result) {
        echo 'success';
      } else {
        echo 'error';
      }
      exit();
    } elseif ($_POST['type'] == 'room') {
      $query = $db->prepare('DELETE FROM MATERIAL_ROOM WHERE id_material = :id AND id_room = :idPosition');
      $result = $query->execute([
        ':id' => $_POST['id'],
        ':idPosition' => $_POST['idPosition'],
      ]);
      if ($result) {
        echo 'success';
      } else {
        echo 'error';
      }
      exit();
    }
  }
  if (!isset($_POST['id'])) {
    echo 'error';
    exit();
  }
  if (!isset($_POST['quantity'])) {
    echo 'error';
    exit();
  }
  if (!isset($_POST['idPosition'])) {
    echo 'error';
    exit();
  }

  if ($_POST['type'] == 'location') {
    $query = $db->prepare(
      'SELECT id_material FROM MATERIAL_LOCATION WHERE id_material = :id AND id_location = :idPosition',
    );
    $query->execute([
      ':id' => $_POST['id'],
      ':idPosition' => $_POST['idPosition'],
    ]);
    $exist = $query->fetch(PDO::FETCH_COLUMN);
    if ($exist) {
      $update = $db->prepare(
        'UPDATE MATERIAL_LOCATION SET quantity = :quantity WHERE id_material = :id AND id_location = :idPosition',
      );
      $result = $update->execute([
        ':id' => $_POST['id'],
        ':idPosition' => $_POST['idPosition'],
        ':quantity' => $_POST['quantity'],
      ]);
      if ($result) {
        echo 'success';
      } else {
        echo 'error';
      }
      exit();
    } else {
      $insert = $db->prepare(
        'INSERT INTO MATERIAL_LOCATION (id_material, id_location, quantity) VALUES (:id, :idPosition, :quantity)',
      );
      $result = $insert->execute([
        ':id' => $_POST['id'],
        ':idPosition' => $_POST['idPosition'],
        ':quantity' => $_POST['quantity'],
      ]);
      if ($result) {
        echo 'success';
      } else {
        echo 'error';
      }
      exit();
    }
  } elseif ($_POST['type'] == 'room') {
    $query = $db->prepare('SELECT id_material FROM MATERIAL_ROOM WHERE id_material = :id AND id_room = :idPosition');
    $query->execute([
      ':id' => $_POST['id'],
      ':idPosition' => $_POST['idPosition'],
    ]);
    $exist = $query->fetch(PDO::FETCH_COLUMN);
    if ($exist) {
      $update = $db->prepare(
        'UPDATE MATERIAL_ROOM SET quantity = :quantity WHERE id_material = :id AND id_room = :idPosition',
      );
      $result = $update->execute([
        ':id' => $_POST['id'],
        ':idPosition' => $_POST['idPosition'],
        ':quantity' => $_POST['quantity'],
      ]);
      if ($result) {
        echo 'success';
      } else {
        echo 'error';
      }
      exit();
    } else {
      $insert = $db->prepare(
        'INSERT INTO MATERIAL_ROOM (id_material, id_room, quantity) VALUES (:id, :idPosition, :quantity)',
      );
      $result = $insert->execute([
        ':id' => $_POST['id'],
        ':idPosition' => $_POST['idPosition'],
        ':quantity' => $_POST['quantity'],
      ]);
      if ($result) {
        echo 'success';
      } else {
        echo 'error';
      }
      exit();
    }
  }
}

if (isset($_POST['delete']) && isset($_POST['id'])) {
  $query = $db->prepare('DELETE FROM MATERIAL WHERE id = :id');
  $result = $query->execute([
    ':id' => $_POST['id'],
  ]);
  if ($result) {
    echo 'success';
  } else {
    echo 'error';
  }
  exit();
}

if (!isset($_POST['id'])) {
  echo 'error';
  exit();
}
if (!isset($_POST['material'])) {
  echo 'error';
  exit();
}
if (!isset($_POST['quantity'])) {
  echo 'error';
  exit();
}

$query = $db->prepare('SELECT id FROM MATERIAL WHERE id = :id');
$query->execute([
  ':id' => $_POST['id'],
]);
$exist = $query->fetch(PDO::FETCH_COLUMN);

if ($exist) {
  $update = $db->prepare('UPDATE MATERIAL SET type = :type, quantity = :quantity WHERE id = :id');
  $result = $update->execute([
    ':id' => $_POST['id'],
    ':type' => $_POST['material'],
    ':quantity' => $_POST['quantity'],
  ]);

  if ($result) {
    echo 'success';
  } else {
    echo 'error';
  }
  exit();
} else {
  $insert = $db->prepare('INSERT INTO MATERIAL (id, type, quantity) VALUES (:id, :type, :quantity)');
  $result = $insert->execute([
    ':id' => $_POST['id'],
    ':type' => $_POST['material'],
    ':quantity' => $_POST['quantity'],
  ]);

  if ($result) {
    echo 'success';
  } else {
    echo 'error';
  }
}
?>
