<?php session_start(); ?>
<?php include 'includes/db.php'; ?>

<!DOCTYPE html>
<html>
<?php
if (!isset($_SESSION['rights'])) {
  header('Location: login.php');
  exit();
}
$linkCss = 'css-js/style.css';
$linkLogo = 'images/logo.png';
$title = 'Catalogue des activités';
include 'includes/head.php';
?>
<script>
		function openPopupprofile() {
			var choix = confirm("Voulez-vous valider votre choix ?");
			if (choix == true) {

			} else {
                event.preventDefault();

			}
		}
	</script>

<body>
    <?php include 'includes/header.php'; ?>
    <div class="container col-md-6">
        <?php include 'includes/msg.php'; ?>
    </div>

    <main>
        <?php if (isset($_SESSION['siret'])) { ?>
        <div class="container section-about-us border border-2 border-secondary rounded">
            <div>
                <div class="row">
                    <div class="col-8">
                        <h5>Vos informations :</h5>
                    </div>


                    <br>

                    <?php
                    $sql = 'SELECT * FROM COMPANY WHERE siret = :siret';
                    $stmt = $db->prepare($sql);
                    $stmt->execute([
                      'siret' => $_SESSION['siret'],
                    ]);
                    $company = $stmt->fetch();
                    ?>
                    <br>
                    <br>
                    <br>

                    <div class="text-center">
                        <?php
                        echo 'Siret :   n°';
                        echo $company['siret'];
                        //Ne pas changer
                        echo '<br>';
                        echo '<br>';
                        echo '<br>';
                        echo "Nom de l'entreprise :   ";
                        echo $company['companyName'];
                        // Ne pas Changer
                        echo '<br>';
                        echo '<br>';
                        echo '<br>';
                        echo 'Email :    ';
                        echo $company['email'];
                        // Changer
                        echo '<br>';
                        echo '<br>';
                        echo '<br>';
                        echo 'Adresse :   ';
                        echo $company['address'];
                        // Ne pas changer
                        echo '<br>';
                        ?>

                        <br>
                        <button class="btn btn-success">
                            <a href="modifyCompany.php?siret=<?= $company['siret'] ?>&name=<?= $company[
  'companyName'
] ?>&email=<?= $company['email'] ?>&rights=<?= $company['rights'] ?>" class="btn ms-2 me-2">Modifier</a>
                        </button>
                        <button class="btn btn-success">
                            <a href="modifyCompanyPassword.php?siret=<?= $company['siret'] ?>&rights=<?= $company[
  'rights'
] ?>" class="btn ms-2 me-2">Modifier son mot de passe</a>
                        </button>
                        <br>
                        <br>
                        <button class="btn btn-danger" onclick="openPopupprofile()">
                            <a href="verifications/deleteCompany.php?siret=<?= $company[
                              'siret'
                            ] ?>" class="btn ms-2 me-2">Supprimer mon compte</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $sql = 'SELECT * FROM RESERVATION WHERE siret = :siret and status=:status ORDER BY id DESC LIMIT 4';
        $stmt = $db->prepare($sql);
        $stmt->execute([
          'siret' => $_SESSION['siret'],
          'status' => 0,
        ]);
        $reservations = $stmt->fetchAll();
        if (count($reservations) != 0) { ?>

        <div class="container section-about-us border border-2 border-secondary rounded">
            <div class="container rounded align-text-bottom">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h5>Dernière Réservations :</h5>
                    </div>
                    <div class="col-4 d-grid gap-2 d-md-flex justify-content-md-end">
                        <a class="btn btn-read" type="submit" href="clients/reservations.php">Voir plus</a>
                    </div>
                </div>
            </div>

            <?php
            $id_activity = [];
            for ($i = 0; $i < count($reservations); $i++) {
              $id_activity[] = $reservations[$i]['id_activity'];
            }
            ?>
            <table class="table text-center table-bordered table-hover" id="active">
                <thead>
                    <tr>
                        <th>Activité</th>
                        <th>Nb de participant</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Localisation</th>
                        <th>Salle</th>
                    </tr>
                </thead>
                <?php for ($i = 0; $i < count($reservations); $i++) { ?>
                <tbody>
                    <tr>
                        <?php
                        $sql = 'SELECT name,id_room FROM ACTIVITY WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute([
                          'id' => $id_activity[$i],
                        ]);
                        $activity = $stmt->fetch();
                        $sql = 'SELECT id_location, name FROM ROOM  WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute([
                          'id' => $activity['id_room'],
                        ]);
                        $room = $stmt->fetch();
                        $sql = 'SELECT name,address FROM LOCATION WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute([
                          'id' => $room['id_location'],
                        ]);
                        $location = $stmt->fetch();
                        ?>
                        <td><?= $activity['name'] ?></td>
                        <td><?= $reservations[$i]['attendee'] ?></td>
                        <td><?= $location['name'] ?> <br> <?= $location['address'] ?></td>
                        <td><?= $room['name'] ?></td>
                        <?php
                        $date = explode('-', $reservations[$i]['date']);
                        $date = $date[2] . '/' . $date[1] . '/' . $date[0];
                        ?>
                        <td><?= $date ?></td>
                        <?php
                        $reservations[$i]['time'] = explode(':', $reservations[$i]['time']);
                        $reservations[$i]['time'] = $reservations[$i]['time'][0] . 'h' . $reservations[$i]['time'][1];
                        ?>
                        <td><?= $reservations[$i]['time'] ?></td>

                    </tr>
                </tbody>
                <?php } ?>
            </table>
            <br>
        </div>

        </div>
        </div>
        <?php }
        ?>
        <?php
        $sql = 'SELECT * FROM RESERVATION WHERE siret = :siret and status=:status ORDER BY id DESC LIMIT 4';
        $stmt = $db->prepare($sql);
        $stmt->execute([
          'siret' => $_SESSION['siret'],
          'status' => 1,
        ]);
        $reservations = $stmt->fetchAll();
        if (count($reservations) != 0) { ?>

        <div class="container section-about-us border border-2 border-secondary rounded">
            <div>
                <div class="row">
                    <h1>Dernière Activités réalisées</h1>
                    <br>
                </div>


                <?
                $id_activity = [];
                for ($i = 0; $i < count($reservations); $i++) { $id_activity[]=$reservations[$i]['id_activity']; } ?>
                <table class="table text-center table-bordered table-hover" id="active">
                    <thead>
                        <tr>
                            <th>Activité</th>
                            <th>Nb de participant</th>
                            <th>Localisation</th>
                            <th>Salle</th>
                            <th>Date</th>
                            <th>Heure</th>
                        </tr>
                    </thead>
                    <?php for ($i = 0; $i < count($reservations); $i++) { ?>
                    <tbody>
                        <tr>
                            <?php
                            $sql = 'SELECT name,id_room FROM ACTIVITY WHERE id = :id';
                            $stmt = $db->prepare($sql);
                            $stmt->execute([
                              'id' => $id_activity[$i],
                            ]);
                            $activity = $stmt->fetch();
                            $sql = 'SELECT id_location, name FROM ROOM  WHERE id = :id';
                            $stmt = $db->prepare($sql);
                            $stmt->execute([
                              'id' => $activity['id_room'],
                            ]);
                            $room = $stmt->fetch();
                            $sql = 'SELECT name,address FROM LOCATION WHERE id = :id';
                            $stmt = $db->prepare($sql);
                            $stmt->execute([
                              'id' => $room['id_location'],
                            ]);
                            $location = $stmt->fetch();
                            ?>
                            <td><?= $activity['name'] ?></td>
                            <td><?= $reservations[$i]['attendee'] ?></td>
                            <td><?= $location['name'] ?> <br> <?= $location['address'] ?></td>
                            <td><?= $room['name'] ?></td>
                            <?php
                            $date = explode('-', $reservations[$i]['date']);
                            $date = $date[2] . '/' . $date[1] . '/' . $date[0];
                            ?>
                            <td><?= $date ?></td>
                            <?php
                            $reservations[$i]['time'] = explode(':', $reservations[$i]['time']);
                            $reservations[$i]['time'] =
                              $reservations[$i]['time'][0] . 'h' . $reservations[$i]['time'][1];
                            ?>
                            <td><?= $reservations[$i]['time'] ?></td>

                        </tr>
                    </tbody>
                    <?php } ?>
                </table>
                <br>
            </div>

        </div>
        </div>
        <?php }
        ?>

        <div class="container section-about-us border border-2 border-secondary rounded">
            <div class="container rounded align-text-bottom">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h5>Planning</h5>
                    </div>
                </div>
            </div>

            <?php
            $sql = 'SELECT * FROM RESERVATION WHERE siret = :siret';
            $stmt = $db->prepare($sql);
            $stmt->execute(['siret' => $_SESSION['siret']]);
            $reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>


            <table style="border: 1px solid black;" class="table text-center table-bordered table-hover" id="active">
                <thead>
                    <tr>
                        <th>Jours</th>
                        <th>Activité</th>
                        <th>L'Heure</th>
                        <th>Adresse</th>
                        <th>Salle</th>
                        <th>Nombre de participants</th>
                    </tr>
                </thead>
                <tbody>

                    <?php for ($i = 0; $i < 7; $i++) {
                      echo '<tr>';
                      $sql = 'SELECT count(*) FROM RESERVATION WHERE siret = :siret';
                      $stmt = $db->prepare($sql);
                      $stmt->execute([
                        'siret' => $_SESSION['siret'],
                      ]);
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
                      $sql = 'SELECT * FROM RESERVATION WHERE siret = :siret';
                      $stmt = $db->prepare($sql);
                      $stmt->execute(['siret' => $_SESSION['siret']]);
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
                            $reservations[$k]['time'] == $reservations[$j]['time']
                          ) {
                            //si l'activité est déjà affichée, on stoppe l'affichage
                            if (
                              $activity[0]['id'] == $activities[0]['id'] &&
                              $reservations[$k]['id'] != $reservations[$j]['id']
                            ) {
                              break;
                            } else {
                              echo '<br>' . $activity[0]['name'] . '<br>';
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
                          if (
                            $datereservation == $dateplanning &&
                            $reservations[$k]['time'] == $reservations[$j]['time']
                          ) {
                            if (
                              $activity[0]['id'] == $activities[0]['id'] &&
                              $reservations[$k]['id'] != $reservations[$j]['id']
                            ) {
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
                        $sql = 'SELECT id,id_room FROM ACTIVITY WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute(['id' => $reservations[$k]['id_activity']]);
                        $activity = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $sql = 'SELECT name,id_location FROM ROOM WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute(['id' => $activity[0]['id_room']]);
                        $room = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $sql = 'SELECT name,address FROM LOCATION WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute(['id' => $room[0]['id_location']]);
                        $location = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $configdate = explode('-', $reservations[$k]['date']);
                        $datereservation = $configdate[2] . '/' . $configdate[1] . '/' . $configdate[0];
                        for ($j = 0; $j < count($reservations); $j++) {
                          $sql = 'SELECT * FROM ACTIVITY WHERE id= :id';
                          $stmt = $db->prepare($sql);
                          $stmt->execute(['id' => $reservations[$k]['id_activity']]);
                          $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
                          if (
                            $datereservation == $dateplanning &&
                            $reservations[$k]['time'] == $reservations[$j]['time']
                          ) {
                            if (
                              $activity[0]['id'] == $activities[0]['id'] &&
                              $reservations[$k]['id'] != $reservations[$j]['id']
                            ) {
                              break;
                            } else {
                              echo '<br>' . $room[0]['name'];
                              echo '<br>' . $location[0]['address'] . '<br>';
                            }
                          }
                        }
                      }
                      echo '</td>';
                      echo '<td>';
                      for ($k = 0; $k < count($reservations); $k++) {
                        $sql = 'SELECT id,id_room FROM ACTIVITY WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute(['id' => $reservations[$k]['id_activity']]);
                        $activity = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $sql = 'SELECT name,id_location FROM ROOM WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute(['id' => $activity[0]['id_room']]);
                        $room = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $configdate = explode('-', $reservations[$k]['date']);
                        $datereservation = $configdate[2] . '/' . $configdate[1] . '/' . $configdate[0];
                        for ($j = 0; $j < count($reservations); $j++) {
                          $sql = 'SELECT * FROM ACTIVITY WHERE id= :id';
                          $stmt = $db->prepare($sql);
                          $stmt->execute(['id' => $reservations[$k]['id_activity']]);
                          $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
                          if (
                            $datereservation == $dateplanning &&
                            $reservations[$k]['time'] == $reservations[$j]['time']
                          ) {
                            if (
                              $activity[0]['id'] == $activities[0]['id'] &&
                              $reservations[$k]['id'] != $reservations[$j]['id']
                            ) {
                              break;
                            } else {
                              echo '<br>' . $room[0]['name'] . '<br>';
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
                          if (
                            $datereservation == $dateplanning &&
                            $reservations[$k]['time'] == $reservations[$j]['time']
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

        <?php } ?>
        <?php if ($_SESSION['rights'] == 1) { ?>
        <?php
          // PARTIE PROVIDER
          ?>

        <br>

        <?php
        $sql = 'SELECT * FROM PROVIDER WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $_SESSION['id']]);
        $provider = $stmt->fetch();
        ?>
        <br>
        <br>
        <br>

        <div class="container section-about-us border border-2 border-secondary rounded">
            <div>
                <div class="row">
                    <h1>Mes Informations</h1>

                    <br>

                    <?php
                    $sql = 'SELECT * FROM PROVIDER WHERE id = :id';
                    $stmt = $db->prepare($sql);
                    $stmt->execute(['id' => $_SESSION['id']]);
                    $provider = $stmt->fetch();
                    ?>
                    <br>
                    <br>
                    <br>

                    <div class="text-center">
                        <div class="fs-2 my-3">
                            Nom : <?= $provider['firstName'] . ' ' . $provider['lastName'] ?>
                        </div>
                        <div class="fs-3 my-3">
                            Email : <?= $provider['email'] ?>
                        </div>
                        <?php
                        $query = $db->prepare('SELECT day FROM AVAILABILITY WHERE id_provider = :id_provider');
                        $query->execute(['id_provider' => $_SESSION['id']]);
                        $days = $query->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div>

                            <div class="fs-3 my-3">
                                Disponibilités :
                            </div>
                            <div class="fs-5 my-3 row">
                                <?php
                                $dayOfWeek = [
                                  'monday',
                                  'tuesday',
                                  'wednesday',
                                  'thursday',
                                  'friday',
                                  'saturday',
                                  'sunday',
                                ];
                                $frenchDay = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                                for ($k = 0; $k < count($frenchDay); $k++) {
                                  if (in_array($dayOfWeek[$k], array_column($days, 'day'))) { ?>
                                <div class="card bg-primary text-white fs-6 text-center col mx-2"
                                    onclick="changeAvailability(this, <?= $k ?>)">
                                    <div class="card-body"><?= $frenchDay[$k] ?></div>
                                </div>
                                <?php } else { ?>
                                <div class="card bg-secondary text-white fs-6 text-center col mx-2"
                                    onclick="changeAvailability(this, <?= $k ?>)">
                                    <div class="card-body"><?= $frenchDay[$k] ?></div>
                                </div>
                                <?php }
                                }
                                ?>
                            </div>
                        </div>
                        <a href="modifyProvider.php?id=<?= $_SESSION['id'] ?>&firstName=<?= $provider[
  'firstName'
] ?>&lastName=<?= $provider['lastName'] ?>&email=<?= $provider['email'] ?>&rights=<?= $provider[
  'rights'
] ?>" class="btn btn-success p-3">Modifier</a>
                        <a href="modifyProviderPassword.php?id=<?= $_SESSION['id'] ?>&rights=<?= $provider[
  'rights'
] ?>" class="btn btn-success p-3">Modifier son mot de passe</a>
                        <button class="btn btn-success p-3" id="saveAvailability" style="display:none"
                            onclick="saveAvailability(this)">
                            Sauvegarder les nouvelles disponibilités
                        </button>
                        <br>
                        <br>
                        <button class="btn btn-danger" onclick="openPopupprofile()">
                            <a href="verifications/deleteProvider.php?id=<?= $provider[
                              'id'
                            ] ?>" class="btn ms-2 me-2">Supprimer mon compte</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>

        <div class="container section-about-us border border-2 border-secondary rounded">
            <div class="container rounded align-text-bottom">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h5>Vos Activité</h5>
                    </div>
                </div>
            </div>
            <?php
            $sql = 'SELECT id_activity FROM ANIMATE WHERE id_provider = :id_provider';
            $stmt = $db->prepare($sql);
            $stmt->execute(['id_provider' => $_SESSION['id']]);
            $animate = $stmt->fetchAll(); // mettre les id des activités dans un tableau
            $id_animate = [];
            for ($i = 0; $i < count($animate); $i++) {
              $id_animate[] = $animate[$i]['id_activity'];
            }

          // faire une tableau avec les données
          ?>


            <table class="table text-center table-bordered table-hover" id="active">
                <thead>
                    <tr>
                        <th>Activité</th>
                        <th>Nb de participant<br> Maximun</th>
                        <th>durée</th>
                        <th>Prix par Participant</th>
                        <th>Lieu</th>
                        <th>Salle</th>
                    </tr>
                </thead>
                <?php for ($i = 0; $i < count($animate); $i++) { ?>
                <tbody>
                    <tr>
                        <?php
                        $sql = 'SELECT * FROM ACTIVITY WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute([
                          'id' => $id_animate[$i],
                        ]);
                        $activity = $stmt->fetch();
                        $sql = 'SELECT id_location,name FROM ROOM WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute([
                          'id' => $activity['id_room'],
                        ]);
                        $room = $stmt->fetch();
                        $sql = 'SELECT name,address FROM LOCATION WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute([
                          'id' => $room['id_location'],
                        ]);
                        $location = $stmt->fetch();
                        ?>
                        <td><?= $activity['name'] ?></td>
                        <td><?= $activity['maxAttendee'] ?></td>
                        <td><?= $activity['duration'] ?> minutes</td>
                        <td><?= $activity['priceAttendee'] ?>€/Participant</td>
                        <td><?= $location['name'] ?> <br> <?= $location['address'] ?> </td>
                        <td><?= $room['name'] ?></td>

                    </tr>
                </tbody>
                <?php } ?>
            </table>
            <?php  ?>
            <br>
        </div>

        </div>
        </div>

        <div class="container section-about-us border border-2 border-secondary rounded">
            <div class="container rounded align-text-bottom">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h5>Planning</h5>
                    </div>
                </div>
            </div>

            <?php if (isset($animate[0]['id_activity']) && !empty($animate[0]['id_activity'])) {
              $sql = 'SELECT * FROM RESERVATION WHERE id_activity = :id_activity';
              $stmt = $db->prepare($sql);
              $stmt->execute(['id_activity' => $animate[0]['id_activity']]);
              $reservation = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } ?>


            <table style="border: 1px solid black;" class="table text-center table-bordered table-hover" id="active">
                <thead>
                    <tr>
                        <th>Jours</th>
                        <th>Activité</th>
                        <th>L'Heure</th>
                        <th>Adresse</th>
                        <th>Salle</th>
                        <th>Nombre de participants</th>
                    </tr>
                </thead>
                <tbody>

                    <?php for ($i = 0; $i < 7; $i++) {
                      echo '<tr>';
                      $sql = 'SELECT id_activity FROM ANIMATE WHERE id_provider = :id_provider';
                      $stmt = $db->prepare($sql);
                      $stmt->execute(['id_provider' => $_SESSION['id']]);
                      $animate = $stmt->fetchAll(PDO::FETCH_ASSOC);
                      $sql =
                        'SELECT count(*) FROM RESERVATION WHERE id_activity IN (SELECT id_activity FROM ANIMATE WHERE id_provider = :id_provider)';
                      $stmt = $db->prepare($sql);
                      $stmt->execute(['id_provider' => $_SESSION['id']]);
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
                        'SELECT * FROM RESERVATION WHERE id_activity IN (SELECT id_activity FROM ANIMATE WHERE id_provider = :id_provider)';
                      $stmt = $db->prepare($sql);
                      $stmt->execute(['id_provider' => $_SESSION['id']]);
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
                            $reservations[$k]['time'] == $reservations[$j]['time']
                          ) {
                            //si l'activité est déjà affichée, on stoppe l'affichage
                            if (
                              $activity[0]['id'] == $activities[0]['id'] &&
                              $reservations[$k]['id'] != $reservations[$j]['id']
                            ) {
                              break;
                            } else {
                              echo '<br>';
                              echo '<a class="nav-link" href="AbsentProvider.php?id=' .
                                $activity[0]['id'] .
                                '&date=' .
                                $datereservation .
                                '">' .
                                $activity[0]['name'] .
                                '</a>';
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
                          if (
                            $datereservation == $dateplanning &&
                            $reservations[$k]['time'] == $reservations[$j]['time']
                          ) {
                            if (
                              $activity[0]['id'] == $activities[0]['id'] &&
                              $reservations[$k]['id'] != $reservations[$j]['id']
                            ) {
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
                        $sql = 'SELECT id,id_room FROM ACTIVITY WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute(['id' => $reservations[$k]['id_activity']]);
                        $activity = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $sql = 'SELECT name,id_location FROM ROOM WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute(['id' => $activity[0]['id_room']]);
                        $room = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $sql = 'SELECT name,address FROM LOCATION WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute(['id' => $room[0]['id_location']]);
                        $location = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $configdate = explode('-', $reservations[$k]['date']);
                        $datereservation = $configdate[2] . '/' . $configdate[1] . '/' . $configdate[0];
                        for ($j = 0; $j < count($reservations); $j++) {
                          $sql = 'SELECT * FROM ACTIVITY WHERE id= :id';
                          $stmt = $db->prepare($sql);
                          $stmt->execute(['id' => $reservations[$k]['id_activity']]);
                          $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
                          if (
                            $datereservation == $dateplanning &&
                            $reservations[$k]['time'] == $reservations[$j]['time']
                          ) {
                            if (
                              $activity[0]['id'] == $activities[0]['id'] &&
                              $reservations[$k]['id'] != $reservations[$j]['id']
                            ) {
                              break;
                            } else {
                              echo '<br>' . $room[0]['name'];
                              echo '<br>' . $location[0]['address'] . '<br>';
                            }
                          }
                        }
                      }
                      echo '</td>';
                      echo '<td>';
                      for ($k = 0; $k < count($reservations); $k++) {
                        $sql = 'SELECT id,id_room FROM ACTIVITY WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute(['id' => $reservations[$k]['id_activity']]);
                        $activity = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $sql = 'SELECT name,id_location FROM ROOM WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        $stmt->execute(['id' => $activity[0]['id_room']]);
                        $room = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $configdate = explode('-', $reservations[$k]['date']);
                        $datereservation = $configdate[2] . '/' . $configdate[1] . '/' . $configdate[0];
                        for ($j = 0; $j < count($reservations); $j++) {
                          $sql = 'SELECT * FROM ACTIVITY WHERE id= :id';
                          $stmt = $db->prepare($sql);
                          $stmt->execute(['id' => $reservations[$k]['id_activity']]);
                          $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
                          if (
                            $datereservation == $dateplanning &&
                            $reservations[$k]['time'] == $reservations[$j]['time']
                          ) {
                            if (
                              $activity[0]['id'] == $activities[0]['id'] &&
                              $reservations[$k]['id'] != $reservations[$j]['id']
                            ) {
                              break;
                            } else {
                              echo '<br>' . $room[0]['name'] . '<br>';
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
                          if (
                            $datereservation == $dateplanning &&
                            $reservations[$k]['time'] == $reservations[$j]['time']
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
        <?php } ?>

    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="css-js/scripts.js?"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>