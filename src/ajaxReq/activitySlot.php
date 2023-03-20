<?php
session_start();
include '../includes/db.php';

if (isset($_POST['id']) && isset($_POST['date']) && isset($_POST['day'])) {
  $query = $db->prepare(
    'SELECT DATE_FORMAT(startHour, \'%H:%i\') AS startHour, DATE_FORMAT(endHour, \'%H:%i\') AS endHour, duration, maxAttendee FROM SCHEDULE INNER JOIN ACTIVITY ON ACTIVITY.id = SCHEDULE.id_activity WHERE id_activity = :id AND day = :day'
  );
  $query->execute([
    'id' => htmlspecialchars($_POST['id']),
    'day' => htmlspecialchars($_POST['day']),
  ]);
  $schedule = $query->fetchAll(PDO::FETCH_ASSOC);

  $date = htmlspecialchars($_POST['date']);
  $attendee = htmlspecialchars($_POST['attendee']);

  foreach ($schedule as $slot) {
    $startHour = explode(':', $slot['startHour']);
    $endHour = explode(':', $slot['endHour']);
    $startHour = $startHour[0] * 60 + $startHour[1];
    $endHour = $endHour[0] * 60 + $endHour[1];
    $total = $endHour - $startHour;

    $duration = (int) $slot['duration'];
    $durationWithPause = $duration + 15;

    $totalSlots = $total / $durationWithPause;
    $totalSlots = floor($totalSlots);

    $startSlotArray = [];
    $endSlotArray = [];

    for ($i = 0; $i < $totalSlots; $i++) { ?>
        <?php
        $hour = floor($startHour / 60);
        $minute = $startHour % 60;
        if ($hour < 10) {
          $hour = '0' . $hour;
        }
        if ($minute < 10) {
          $minute = '0' . $minute;
        }
        $startSlot = $hour . ':' . $minute;
        $startSlotArray = array_merge($startSlotArray, [$startSlot]);

        $startHour += $duration;
        $hour = floor($startHour / 60);
        $minute = $startHour % 60;
        if ($hour < 10) {
          $hour = '0' . $hour;
        }
        if ($minute < 10) {
          $minute = '0' . $minute;
        }
        $endSlot = $hour . ':' . $minute;
        $endSlotArray = array_merge($endSlotArray, [$endSlot]);

        $startHour -= $duration;
        $startHour += $durationWithPause;
        ?>
  <?php }

    for ($j = 0; $j < count($startSlotArray); $j++) {
      $timeFormat = $startSlotArray[$j] . ':00';

      $query = $db->prepare(
        'SELECT date, time, attendee, siret FROM RESERVATION WHERE id_activity = :id AND date = DATE(:date) AND time = :startHour'
      );
      $query->execute([
        'id' => $_POST['id'],
        'date' => $date,
        'startHour' => $timeFormat,
      ]);
      $reponse = $query->fetch(PDO::FETCH_ASSOC);

      if ($reponse == true) {
        if ($attendee + $reponse['attendee'] <= $slot['maxAttendee'] && $reponse['siret'] != $_SESSION['siret']) {
          echo '<option value=' .
            $startSlotArray[$j] .
            '>' .
            $startSlotArray[$j] .
            ' - ' .
            $endSlotArray[$j] .
            '</option>';
        }
      }
      if ($reponse == false && $attendee <= $slot['maxAttendee'] && $reponse['siret'] != $_SESSION['siret']) {
        echo '<option value=' .
          $startSlotArray[$j] .
          '>' .
          $startSlotArray[$j] .
          ' - ' .
          $endSlotArray[$j] .
          '</option>';
      }
    }
  }
}

?>
