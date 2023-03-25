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

<body>
    <?php include 'includes/header.php'; ?>
    <div class="container col-md-6">
        <?php include 'includes/msg.php'; ?>
    </div>

    <main>
        <?php if ( isset($_SESSION['siret']) ) { ?>
        <div class="container section-about-us border border-2 border-secondary rounded">
            <div>
                <div class="row">
                    <div class="col-8">
                        <h5>Vos information</h5>
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
            echo $company['siret']; //Ne pas changer
            echo '<br>';
            echo '<br>';
            echo '<br>';
            echo "Nom de l'entreprise :   ";
            echo $company['companyName']; // Ne pas Changer
            echo '<br>';
            echo '<br>';
            echo '<br>';
            echo 'Email :    ';
            echo $company['email']; // Changer
            echo '<br>';
            echo '<br>';
            echo '<br>';
            echo 'Adresse :   ';
            echo $company['address']; // Ne pas changer
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
                    </div>
                </div>
            </div>
        </div>

        <div class="container section-about-us border border-2 border-secondary rounded">
            <div class="container rounded align-text-bottom">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h5>Dernière Réservation</h5>
                    </div>
                    <div class="col-4 d-grid gap-2 d-md-flex justify-content-md-end">
                        <a class="btn btn-read" type="submit" href="clients/reservations.php">Voir plus</a>
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
          $reservations = $stmt->fetchAll(); // mettre les id des activités dans un tableau
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

        <div class="container section-about-us border border-2 border-secondary rounded">
            <div>
                <div class="row">
                    <h1>Dernière Activités fait</h1>
                    <br>
                </div>
                <?php
      $sql = 'SELECT * FROM RESERVATION WHERE siret = :siret and status=:status ORDER BY id DESC LIMIT 4';
      $stmt = $db->prepare($sql);
      $stmt->execute([
        'siret' => $_SESSION['siret'],
        'status' => 1,
      ]);
      $reservations = $stmt->fetchAll();
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

                </tr>
                </thead>
                <?php for ($i = 0; $i < count($reservations); $i++) { ?>
                <tbody>
                    <tr>
                        <?php
                                $sql = 'SELECT name FROM ACTIVITY WHERE id = :id';
                                $stmt = $db->prepare($sql);
                                $stmt->execute([
                                  'id' => $id_activity[$i],
                                ]);
                                $activity = $stmt->fetch();
                                $id_location = [];
                                $id_room = [];
                                $sql = 'SELECT id_room FROM ACTIVITY WHERE id = :id';
                                $stmt = $db->prepare($sql);
                                $stmt->execute([
                                  'id' => $id_activity[$i],
                                ]);
                                $id_room[] = $stmt->fetch();
                                $sql = 'SELECT id_location, name FROM ROOM WHERE id = :id';
                                $stmt = $db->prepare($sql);
                                $stmt->execute(['id' => $id_room[$i]['id_room']]);
                                $id_room = $stmt->fetch();
                                $id_location[] = $id_room['id_location'];
                                $sql = 'SELECT name, address FROM LOCATION WHERE id = :id';
                                $stmt = $db->prepare($sql);
                                $stmt->execute([
                                  'id' => $id_location[$i],
                                ]);
                                $id_location = $stmt->fetch();
                                $date = explode('-', $reservations[$i]['date']);
                                $date = $date[2] . '/' . $date[1] . '/' . $date[0];
                                $time = explode(':', $reservations[$i]['time']);
                                $time = $time[0] . 'h' . $time[1];
                                ?>
                        <td><?= $activity['name'] ?></td>
                        <td><?= $reservations[$i]['attendee'] ?></td>
                        <td><?= $date ?></td>
                        <td><?= $time ?></td>
                        <td><?= $id_location['name'] ?><br><?= $id_location['address'] ?></td>
                        <td><?= $id_room['name'] ?></td>

                    </tr>
                </tbody>
                <?php } ?>
                </table>
            </div>
        </div>
        <?php } ?>
        <?php if ($_SESSION['rights'] == 1) { ?>

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
                        <?php
            echo 'Prénom :   ';
            echo $provider['firstName']; //Ne pas changer
            echo '<br>';
            echo '<br>';
            echo 'Nom de famille :   ';
            echo $provider['lastName']; //Ne pas changer
            echo '<br>';
            echo '<br>';
            echo 'Email :    ';
            echo $provider['email'];
            // Changer
            echo '<br>';
            echo '<br>';
            // Ne pas changer
            echo '<br>';
            ?>

                        <br>
                        <button class="btn btn-success">
                            <a href="modifyProvider.php?id=<?= $_SESSION['id'] ?>&firstName=<?= $provider[
  'firstName'
] ?>&lastName=<?= $provider['lastName'] ?>&email=<?= $provider['email'] ?>&rights=<?= $provider[
  'rights'
] ?>" class="btn ms-2 me-2">Modifier</a>
                        </button>
                        <button class="btn btn-success">
                            <a href="modifyProviderPassword.php?id=<?= $_SESSION['id'] ?>&rights=<?= $provider[
  'rights'
] ?>" class="btn ms-2 me-2">Modifier son mot de passe</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container section-about-us border border-2 border-secondary rounded">
            <div>
                <div class="row">
                    <h1>Vos Activités </h1>
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
                                <td><?= $activity['duration'] ?>Heure</td>
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

        <?php } ?>

    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>