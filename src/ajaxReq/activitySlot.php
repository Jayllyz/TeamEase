<?php session_start();
include '../includes/db.php';

if (isset($_POST['id']) && isset($_POST['date']) && isset($_POST['day'])) {
  $idActivity = htmlspecialchars($_POST['id']);
  $day = htmlspecialchars($_POST['day']);

  $query = $db->prepare('SELECT id_room FROM ACTIVITY WHERE id = :id');
  $query->execute(['id' => $idActivity]);
  $id_room = $query->fetch(PDO::FETCH_ASSOC);
  $id_room = $id_room['id_room'];

  $query = $db->prepare(
    'SELECT DATE_FORMAT(startHour, \'%H:%i\') AS startHour, DATE_FORMAT(endHour, \'%H:%i\') AS endHour, duration, maxAttendee FROM SCHEDULE INNER JOIN ACTIVITY ON ACTIVITY.id = SCHEDULE.id_activity WHERE id_activity = :id AND day = :day',
  );
  $query->execute([
    'id' => $idActivity,
    'day' => $day,
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

    for ($i = 0; $i < $totalSlots; $i++) {
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
    }
    for ($j = 0; $j < count($startSlotArray); $j++) {
      $timeFormat = $startSlotArray[$j] . ':00';
      $timeFormatEnd = $endSlotArray[$j] . ':00';

      $checkRoom = $db->prepare('SELECT id FROM ACTIVITY WHERE id_room = :id_room AND id != :id');
      $checkRoom->execute(['id_room' => $id_room, 'id' => $_POST['id']]);
      $checkRoom = $checkRoom->fetchAll(PDO::FETCH_ASSOC);

      $query = $db->prepare(
        'SELECT date, time, attendee, siret FROM RESERVATION WHERE id_activity = :id AND date = DATE(:date) AND time = :startHour',
      );
      $query->execute(['id' => $_POST['id'], 'date' => $date, 'startHour' => $timeFormat]);
      $reponse = $query->fetch(PDO::FETCH_ASSOC);

      $query = $db->prepare(
        'SELECT id FROM PROVIDER WHERE id IN (SELECT id_provider FROM ANIMATE WHERE id_activity = :id)',
      );
      $query->execute(['id' => $idActivity]);
      $providers = $query->fetch(PDO::FETCH_ASSOC);

      $isOccupied = 0;

      foreach ($providers as $provider) {
        $query = $db->prepare(
          'SELECT RESERVATION.id FROM RESERVATION INNER JOIN ANIMATE ON RESERVATION.id_activity = ANIMATE.id_activity WHERE RESERVATION.date = :date AND ANIMATE.id_provider = :provider;',
        );
        $query->execute(['date' => $date, 'provider' => $providers['id']]);
        $hasReservation = $query->fetch(PDO::FETCH_ASSOC);

        if ($hasReservation) {
          $query = $db->prepare('SELECT id, id_activity FROM RESERVATION WHERE date = DATE(:date)');
          $query->execute(['date' => $date]);
          $reservations = $query->fetchAll(PDO::FETCH_ASSOC);

          foreach ($reservations as $reservation) {
            $query = $db->prepare('SELECT
              IF(TIME(:start) BETWEEN time AND ADDTIME(time, (SELECT SEC_TO_TIME((duration+30)*60) FROM ACTIVITY WHERE id=:idActivity)) AND TIME(:end) BETWEEN time AND ADDTIME(time, (SELECT SEC_TO_TIME((duration+30)*60) FROM ACTIVITY WHERE id=:idActivity)), 1, 0) AS inside1,
              IF(time BETWEEN TIME(:start) AND TIME(:end), 1, 0) AS inside2 FROM RESERVATION WHERE id = :id;');
            $query->execute([
              'start' => $startSlotArray[$j],
              'end' => $endSlotArray[$j],
              'idActivity' => $reservation['id_activity'],
              'id' => $reservation['id'],
            ]);
            $inside = $query->fetch(PDO::FETCH_ASSOC);

            if ($inside['inside1'] == 1 || $inside['inside2'] == 1) {
              $isOccupied = 1;
            }
          }
        }
      }

      if (is_array($reponse) && !empty($reponse)) {
        if ($attendee + $reponse['attendee'] <= $slot['maxAttendee'] && $reponse['siret'] != $_SESSION['siret']) {
          if (empty($checkRoom) && $isOccupied == 0) {
            echo '<option value=' .
              $startSlotArray[$j] .
              '>' .
              $startSlotArray[$j] .
              ' - ' .
              $endSlotArray[$j] .
              '</option>';
          }

          foreach ($checkRoom as $room) {
            $query = $db->prepare(
              'SELECT id FROM RESERVATION WHERE date = DATE(:date) AND time >= :startHour AND time <= :endHour AND id_activity = :id',
            );
            $query->execute([
              'id' => $room['id'],
              'date' => $date,
              'startHour' => $timeFormat,
              'endHour' => $timeFormatEnd,
            ]);
            $roomDate = $query->fetch(PDO::FETCH_ASSOC);

            if ((empty($roomDate) || empty($room)) && $isOccupied == 0) {
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
      } elseif ($attendee <= $slot['maxAttendee']) {
        if (empty($checkRoom) && $isOccupied == 0) {
          echo '<option value=' .
            $startSlotArray[$j] .
            '>' .
            $startSlotArray[$j] .
            ' - ' .
            $endSlotArray[$j] .
            '</option>';
        }

        foreach ($checkRoom as $room) {
          $query = $db->prepare(
            'SELECT id FROM RESERVATION WHERE date = DATE(:date) AND time >= :startHour AND time <= :endHour AND id_activity = :id',
          );
          $query->execute([
            'id' => $room['id'],
            'date' => $date,
            'startHour' => $timeFormat,
            'endHour' => $timeFormatEnd,
          ]);
          $roomDate = $query->fetch(PDO::FETCH_ASSOC);

          if (empty($roomDate) && $isOccupied == 0) {
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
  }
  $query = $db->prepare(
    'SELECT PROVIDER.firstName, PROVIDER.lastName, OCCUPATION.name FROM PROVIDER INNER JOIN AVAILABILITY ON PROVIDER.id = AVAILABILITY.id_provider INNER JOIN OCCUPATION ON PROVIDER.id_occupation = OCCUPATION.id WHERE AVAILABILITY.day = :day AND PROVIDER.id IN (SELECT id_provider FROM ANIMATE WHERE id_activity = :id)',
  );
  $query->execute([
    'id' => $idActivity,
    'day' => $day,
  ]);
  $providers = $query->fetchAll(PDO::FETCH_ASSOC);
}
?>
