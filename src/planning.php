<?php session_start();
if (!isset($_SESSION['rights']) || $_SESSION['rights'] != 2) {
  header('location: index.php');
  exit();
}
?>

<?php include 'includes/db.php'; ?>

<!DOCTYPE html>
<html>
<?php
$linkCss = 'css-js/style.css';
$linkLogo = 'images/logo.png';
$title = 'Accueil';
include 'includes/head.php';
?>

<body>
    <?php include 'includes/header.php'; ?>
    <div class="container col-md-6">
        <?php include 'includes/msg.php'; ?>
    </div>
    <main>
        <?php
        $sql = 'SELECT * FROM ROOM';
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $animates = $stmt->fetchAll(PDO::FETCH_ASSOC);

        for ($b = 0; $b < count($animates); $b++) {

          $sql = 'SELECT * FROM ROOM';
          $stmt = $db->prepare($sql);
          $stmt->execute();
          $animatees = $stmt->fetchAll(PDO::FETCH_ASSOC);

          $sql = 'SELECT * FROM LOCATION WHERE id = :id';
          $stmt = $db->prepare($sql);
          $stmt->execute(['id' => $animates[$b]['id_location']]);
          $locationzone = $stmt->fetchAll(PDO::FETCH_ASSOC);
          ?>

        <div class="container rounded main-text">
            <div class="container section-about-us border border-2 border-secondary rounded">
                <div class="container rounded align-text-bottom">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <?php echo '<h5 class="row align-items-center">' .
                              'Nom de la salle : ' .
                              $animates[$b]['name'] .
                              '<br>' .
                              $locationzone[0]['name'] .
                              ' <br> ' .
                              'Addresse : ' .
                              $locationzone[0]['address'] .
                              '</h5>'; ?>
                        </div>
                    </div>
                </div>

                <?php if (isset($animates[$b]['id']) && !empty($animate[$b]['id'])) {
                  $sql =
                    'SELECT * FROM RESERVATION WHERE id_activity IN (SELECT id_activity FROM ACTIVITY WHERE id_room = :id_room)';
                  $stmt = $db->prepare($sql);
                  $stmt->execute(['id_room' => $animate[$b]['id']]);
                  $reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } ?>


            <table style="border: 1px solid black;" class="table text-center table-bordered table-hover" id="active">
                <thead>
                    <tr>
                        <th>Jours</th>
                        <th>Activité</th>
                        <th>L'Heure</th>
                        <th>Nombre de participants</th>
                    </tr>
                </thead>
                <tbody>

                    <?php for ($i = 0; $i < 7; $i++) {
                      echo '<tr>';
                      $sql = 'SELECT id FROM ACTIVITY WHERE id_room = :id_room';
                      $stmt = $db->prepare($sql);
                      $stmt->execute(['id_room' => $animatees[$b]['id']]);
                      $animate = $stmt->fetchAll(PDO::FETCH_ASSOC);

                      $sql =
                        'SELECT * FROM RESERVATION WHERE id_activity IN (SELECT id_activity FROM ACTIVITY WHERE id_room = :id_room)';
                      $stmt = $db->prepare($sql);
                      $stmt->execute(['id_room' => $animatees[$b]['id']]);
                      $reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);
                      $date = date('Y-m-d');
                      $dayOfWeek = date('w', strtotime($date));
                      $dayOfWeek -= 1;
                      $monday = date('Y-m-d', strtotime("-$dayOfWeek day", strtotime($date)));
                      $day = date('Y-m-d', strtotime("+$i day", strtotime($monday)));
                      $dayOfWeek = date('w', strtotime($day));
                      if ($dayOfWeek == 1) {
                        $dayofWeeks = 'Lundi';
                      }
                      if ($dayOfWeek == 2) {
                        $dayofWeeks = 'Mardi';
                      }
                      if ($dayOfWeek == 3) {
                        $dayofWeeks = 'Mercredi';
                      }
                      if ($dayOfWeek == 4) {
                        $dayofWeeks = 'Jeudi';
                      }
                      if ($dayOfWeek == 5) {
                        $dayofWeeks = 'Vendredi';
                      }
                      if ($dayOfWeek == 6) {
                        $dayofWeeks = 'Samedi';
                      }
                      if ($dayOfWeek == 0) {
                        $dayofWeeks = 'Dimanche';
                      }
                      $configdate = explode('-', $day);
                      $dateplanning = $configdate[2] . '/' . $configdate[1] . '/' . $configdate[0];
                      echo "<td>$dayofWeeks <br> $dateplanning</td>";
                      $sql =
                        'SELECT * FROM RESERVATION WHERE id_activity IN (SELECT id_activity FROM ACTIVITY WHERE id_room = :id_room)';
                      $stmt = $db->prepare($sql);
                      $stmt->execute(['id_room' => $animatees[$b]['id']]);
                      $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

                      echo '<td>';
                      for ($j = 0; $j < count($reservations); $j++) {
                        $configdate = explode('-', $reservations[$j]['date']);
                        $datereservation = $configdate[2] . '/' . $configdate[1] . '/' . $configdate[0];
                        $sql = 'SELECT * FROM ACTIVITY WHERE id= :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute(['id' => $reservations[$j]['id_activity']]);
                        $activities = $stmt->fetchAll(PDO::FETCH_ASSOC); // afficher les activités sans doublons
                        for ($k = 0; $k < count($reservations); $k++) {
                          $sql = 'SELECT * FROM ACTIVITY WHERE id= :id';
                          $stmt = $db->prepare($sql);
                          $stmt->execute(['id' => $reservations[$k]['id_activity']]);
                          $activity = $stmt->fetchAll(PDO::FETCH_ASSOC);
                          if (
                            $datereservation == $dateplanning &&
                            $reservations[$k]['time'] == $reservations[$j]['time'] &&
                            $reservations[$k]['id'] == $reservations[$j]['id'] &&
                            $activity[0]['id_room'] == $animatees[$b]['id']
                          ) {
                            //si l'activité est déjà affichée, on stoppe l'affichage
                            if (
                              $activity[0]['id'] == $activities[0]['id'] &&
                              $reservations[$k]['id'] != $reservations[$j]['id']
                            ) {
                              break;
                            } else {
                              echo '<br>';
                              echo $activity[0]['name'];
                              echo '<br>';
                            }
                          }
                        }
                      }
                      echo '</td>';
                      echo '<td>';
                      for ($k = 0; $k < count($reservations); $k++) {
                        $configdate = explode('-', $reservations[$k]['date']);
                        $datereservation = $configdate[2] . '/' . $configdate[1] . '/' . $configdate[0];
                        for ($j = 0; $j < count($reservations); $j++) {
                          $sql = 'SELECT duration FROM ACTIVITY WHERE id = :id';
                          $stmt = $db->prepare($sql);
                          $stmt->execute(['id' => $reservations[$j]['id_activity']]);
                          $duration = $stmt->fetch();

                          $sql = 'SELECT * FROM ACTIVITY WHERE id= :id';
                          $stmt = $db->prepare($sql);
                          $stmt->execute(['id' => $reservations[$k]['id_activity']]);
                          $activity = $stmt->fetchAll(PDO::FETCH_ASSOC);
                          if (
                            $datereservation == $dateplanning &&
                            $reservations[$k]['time'] == $reservations[$j]['time'] &&
                            $reservations[$k]['id'] == $reservations[$j]['id'] &&
                            $activity[0]['id_room'] == $animatees[$b]['id']
                          ) {
                            if (
                              $activity[0]['id'] == $activities[0]['id'] &&
                              $reservations[$k]['id'] != $reservations[$j]['id']
                            ) {
                              var_dump($reservations[$k]);
                              var_dump($reservations[$j]);
                              exit();
                              break;
                            } else {
                              $time = explode(':', $reservations[$k]['time']);
                              $time = $time[0] . 'h' . $time[1];
                              $duration = intval($duration[0]);
                              $duration = $duration * 60;
                              $durations = gmdate('H:i', $duration);
                              $time_heure = substr($time, 0, 2);
                              $time_minutes = substr($time, 3, 2);
                              $times = $time_heure . ':' . $time_minutes;
                              $times = strtotime($times);
                              $times = date('H:i', $times + $duration);
                              $times = explode(':', $times);
                              $times = $times[0] . 'h' . $times[1];
                              echo '<br>' . $time . ' - ' . $times . '<br>';
                            }
                          }
                        }
                      }
                      echo '</td>';

                      echo '<td>';
                      for ($k = 0; $k < count($reservations); $k++) {
                        $configdate = explode('-', $reservations[$k]['date']);
                        $datereservation = $configdate[2] . '/' . $configdate[1] . '/' . $configdate[0];

                        $sql = 'SELECT * FROM ACTIVITY WHERE id= :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute(['id' => $reservations[$k]['id_activity']]);
                        $activity = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        for ($j = 0; $j < count($reservations); $j++) {
                          if (
                            $datereservation == $dateplanning &&
                            $reservations[$k]['time'] == $reservations[$j]['time'] &&
                            $reservations[$k]['id'] == $reservations[$j]['id'] &&
                            $activity[0]['id_room'] == $animatees[$b]['id']
                          ) {
                            if (
                              $activity[0]['id'] == $activities[0]['id'] &&
                              $reservations[$k]['id'] != $reservations[$j]['id']
                            ) {
                              break;
                            } else {
                              $sql =
                                'SELECT SUM(attendee) AS total_attendee FROM RESERVATION WHERE id_activity = :id_activity;';
                              $stmt = $db->prepare($sql);
                              $stmt->execute(['id_activity' => $reservations[$j]['id_activity']]);
                              $total_attendee = $stmt->fetchAll(PDO::FETCH_ASSOC);
                              echo '<br>' . $total_attendee[0]['total_attendee'] . '<br>';
                            }
                          }
                        }
                      }
                      echo '</td>';
                      echo '</tr>';
                    } ?>

                </tbody>
            </table>
            </div>
        </div>
        <?php
        }
        ?>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>