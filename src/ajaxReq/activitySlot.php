<?php
include '../includes/db.php';

if (isset($_POST['id']) && isset($_POST['date']) && isset($_POST['day'])) {
  $query = $db->prepare(
    'SELECT DATE_FORMAT(startHour, \'%H:%i\') AS startHour, DATE_FORMAT(endHour, \'%H:%i\') AS endHour, duration FROM SCHEDULE INNER JOIN ACTIVITY ON ACTIVITY.id = SCHEDULE.id_activity WHERE id_activity = :id AND day = :day'
  );
  $query->execute([
    'id' => htmlspecialchars($_POST['id']),
    'day' => htmlspecialchars($_POST['day']),
  ]);
  $schedule = $query->fetchAll(PDO::FETCH_ASSOC);

  foreach ($schedule as $slot) {
    //convert in minute $slot['startHour'] and $slot['endHour']
    $startHour = explode(':', $slot['startHour']);
    $endHour = explode(':', $slot['endHour']);
    $startHour = $startHour[0] * 60 + $startHour[1];
    $endHour = $endHour[0] * 60 + $endHour[1];
    $total = $endHour - $startHour;

    $duration = (int) $slot['duration'];
    $durationWithPause = $duration + 15;

    $totalSlots = $total / $durationWithPause;
    $totalSlots = floor($totalSlots);

    for ($i = 0; $i < $totalSlots; $i++) { ?>
    <option value="<?php $i; ?>">
        <?php
        $hour = floor($startHour / 60);
        $minute = $startHour % 60;
        if ($hour < 10) {
          $hour = '0' . $hour;
        }
        if ($minute < 10) {
          $minute = '0' . $minute;
        }
        echo $hour . ':' . $minute;
        echo ' - ';
        $startHour += $duration;
        $hour = floor($startHour / 60);
        $minute = $startHour % 60;
        if ($hour < 10) {
          $hour = '0' . $hour;
        }
        if ($minute < 10) {
          $minute = '0' . $minute;
        }
        echo $hour . ':' . $minute;
        $startHour -= $duration;
        $startHour += $durationWithPause;
        ?></p>
      </option>
  <?php }
  }
}

?>
