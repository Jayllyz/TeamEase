<?php
session_start();
include '../includes/db.php';

$days = explode(',', $_POST['days']);

foreach ($days as $day) {
  switch ($day) {
    case '0':
      $day = 'monday';
      break;
    case '1':
      $day = 'tuesday';
      break;
    case '2':
      $day = 'wednesday';
      break;
    case '3':
      $day = 'thursday';
      break;
    case '4':
      $day = 'friday';
      break;
    case '5':
      $day = 'saturday';
      break;
    case '6':
      $day = 'sunday';
      break;
  }
  $query = $db->prepare('SELECT day FROM AVAILABILITY WHERE day = :day AND id_provider = :id_provider');
  $query->execute([
    'day' => $day,
    'id_provider' => $_SESSION['id'],
  ]);
  $exist = $query->fetch();
  if (!$exist) {
    $query = $db->prepare('INSERT INTO AVAILABILITY (id_provider, day) VALUES (:id_provider, :day)');
    $query->execute([
      'id_provider' => $_SESSION['id'],
      'day' => $day,
    ]);
  } else {
    $query = $db->prepare('DELETE FROM AVAILABILITY WHERE id_provider = :id_provider AND day = :day');
    $query->execute([
      'id_provider' => $_SESSION['id'],
      'day' => $day,
    ]);

    $query = $db->prepare(
      'DELETE FROM ANIMATE WHERE id_provider = :id_provider AND id_activity IN (SELECT id FROM ACTIVITY WHERE id IN (SELECT id_activity FROM SCHEDULE WHERE day = :day))',
    );
    $query->execute([
      'id_provider' => $_SESSION['id'],
      'day' => $day,
    ]);
  }
}

?>
