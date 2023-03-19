<?php
include '../includes/db.php';

if (isset($_POST['id']) && isset($_POST['date']) && isset($_POST['day'])) {
  $query = $db->prepare(
    'SELECT startHour, endHour, duration FROM SCHEDULE INNER JOIN ACTIVITY ON ACTIVITY.id = SCHEDULE.id_activity WHERE id_activity = :id AND day = :day'
  );
  $query->execute([
    'id' => htmlspecialchars($_POST['id']),
    'day' => htmlspecialchars($_POST['day']),
  ]);
  $schedule = $query->fetchAll(PDO::FETCH_ASSOC);

  foreach ($schedule as $slot) {
  }
}

?>
