<?php
session_start();
include '../includes/db.php';
$siret = htmlspecialchars($_GET['siret']);
if ($_SESSION['rights'] == 2 && isset($_SESSION['siret'])) { ?>
<!DOCTYPE html>
<html lang="fr">
<?php
$linkLogo = '../images/logo.png';
$linkCss = '../css-js/style.css';
$title = 'Consultation';
include '../includes/head.php';
?>

<body>
    <?php include '../includes/header.php'; ?>

    <?php
    $req = $db->prepare(
      'SELECT companyName, email, address, confirm_signup , rights FROM COMPANY WHERE siret = :siret',
    );
    $req->execute([
      'siret' => $siret,
    ]);
    $result = $req->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $select) { ?>

    <h1 class="mt-4">Informations de <?= $select['companyName'] ?></h1>
    <div class="container info_user">
        <table class="table text-center table-bordered">
            <tr>
                <th>SIRET</th>
                <th>Nom de l'entreprise</th>
                <th>Email</th>
                <th>Adresse</th>
                <th>Status du compte</th>
                <th>Droits</th>
            </tr>
            <tr>
                <td><?= $siret ?></td>
                <td><?= $select['companyName'] ?></td>
                <td><?= $select['email'] ?></td>
                <td><?= $select['address'] ?></td>
                <td><?= $select['confirm_signup'] == 1 ? 'Confirmé' : 'En attente' ?></td>
                <td><?= $select['rights'] == 0 ? 'Entreprise (0)' : 'Entreprise banni (-1)' ?></td>
            </tr>
        </table>
    </div>
    <h1 class="mt-4">Historiques des réservations</h1>

    <?php
    $req = $db->prepare('SELECT * FROM RESERVATION WHERE siret = :siret');
    $req->execute([
      'siret' => $siret,
    ]);
    $result = $req->fetchAll(PDO::FETCH_ASSOC);

    if (empty($result)) { ?>
    <div class="container mt-4">
        <p class="text-center">Aucune réservations</p>
    </div>
    <?php } else { ?>
    <div class="container">
        <table class="table text-center table-bordered">
            <tr>
                <th>Nom de l'activité</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Nombre de participants</th>
                <th>Statut du paiement</th>
                <th>Action</th>
            </tr>
            <?php foreach ($result as $select) { ?>
            <?php
            $date = explode('-', $select['date']);
            $date = $date[2] . '/' . $date[1] . '/' . $date[0];
            $select['date'] = $date;

            $select['time'] = substr($select['time'], 0, 5);

            $req = $db->prepare('SELECT name FROM ACTIVITY WHERE id = :id');
            $req->execute([
              'id' => $select['id_activity'],
            ]);
            $name = $req->fetch(PDO::FETCH_ASSOC);
            ?>

            <tr>
                <td><?= $name['name'] ?></td>
                <td><?= $select['date'] ?></td>
                <td><?= $select['time'] ?></td>
                <td><?= $select['attendee'] ?></td>
                <td><?php if ($select['status'] == 0) {
                  echo 'Pas encore réglée <svg width="46" height="46" fill="none" stroke="#df1111" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M18 6 6 18"></path>
                                  <path d="m6 6 12 12"></path>
                                </svg>';
                } else {
                  echo 'Réservation réglée <svg width="46" height="46" fill="none" stroke="#0c9234" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M20 6 9 17l-5-5"></path>
                                </svg>';
                } ?>
                </td>
                <td>
                    <a href="../activity.php?id=<?= $select[
                      'id_activity'
                    ] ?>" class="btn btn-primary">Voir l'activité</a>
            </tr>
            <?php } ?>
        </table>
    </div>
    <?php }
    ?>

    <?php }
    ?>


    <?php include '../includes/footer.php'; ?>
    <script src="css-js/scripts.js"></script>
</body>

</html>
<?php } else {header('location: ../index.php');
  exit();} ?>
