<?php
session_start();
if (!isset($_SESSION['rights']) || $_SESSION['rights'] != 2) {
  header('location: ../index.php');
  exit();
}
include '../includes/db.php';
$id = htmlspecialchars($_GET['id']);
if ($_SESSION['rights'] == 2) { ?>
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
      'SELECT lastName, firstName, email,rights, id_occupation, confirm_signup FROM PROVIDER WHERE id = :id',
    );
    $req->execute([
      'id' => $id,
    ]);
    $result = $req->fetchAll(PDO::FETCH_ASSOC);

    $id_occupation = $result[0]['id_occupation'];

    $req = $db->prepare('SELECT name, salary FROM OCCUPATION WHERE id = :id_occupation');
    $req->execute([
      'id_occupation' => $id_occupation,
    ]);
    $occupation = $req->fetchAll(PDO::FETCH_ASSOC);

    $result[0]['id_occupation'] = $occupation[0]['name'];
    $result[0]['salary'] = $occupation[0]['salary'];

    $req = $db->prepare(
      'SELECT name, priceAttendee, duration, maxAttendee FROM ACTIVITY WHERE id IN (SELECT id_activity FROM ANIMATE WHERE id_provider = :id_provider)',
    );
    $req->execute([
      'id_provider' => $id,
    ]);
    $result_activity = $req->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $select) { ?>

    <h1 class="mt-auto">Informations de <?= $select['lastName'] . ' ' . $select['firstName'] ?></h1>
    <div class="container info_user">
        <table class="table text-center table-bordered">
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Métier</th>
                <th>Salaire</th>
                <th>Email</th>
                <th>Status du compte</th>
                <th>Droits</th>
            </tr>
            <tr>
                <td><?= $select['lastName'] ?></td>
                <td><?= $select['firstName'] ?></td>
                <td><?= $select['id_occupation'] ?></td>
                <td><?= $select['salary'] . '€/h' ?></td>
                <td><?= $select['email'] ?></td>
                <td><?= $select['confirm_signup'] ? 'Compte confirmé' : 'Compte non confirmé' ?></td>
                <td><?= $select['rights'] ? 'Prestataire (1)' : 'Prestataire banni (-1)' ?></td>
            </tr>
        </table>
    </div>

    <?php if ($result_activity != null) { ?>
    <h1>Activités affectées</h1>
    <div class="container">
        <table class="table text-center table-bordered table-hover">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Durée</th>
                    <th>Nombre max de participants</th>
                </tr>
            </thead>

            <?php foreach ($result_activity as $select_activity) { ?>
            <tr>
                <td><?= $select_activity['name'] ?></td>
                <td><?= $select_activity['priceAttendee'] . '€' ?></td>
                <td><?= $select_activity['duration'] . ' mins' ?></td>
                <td><?= $select_activity['maxAttendee'] ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
    <?php } ?>

    <div class="text-center mt-auto">
        <a href="../admin.php?check=provider" class="btn col-1 btn-dark">Retour</a>
    </div>

    <?php }
    ?>

    <?php include '../includes/footer.php'; ?>
    <script src="css-js/scripts.js"></script>
</body>

</html>
<?php } else {header('location: togetherandstronger.site');
  exit();} ?>
