<?php

session_start();

include '../includes/db.php';

if (!isset($_SESSION['siret'])) {
  header('Location: ../login.php');
  exit();
}

if (!isset($_GET['code'])) {
  header('Location: ../index.php');
  exit();
}

if ($_GET['code'] !== hash('sha256', 'SananesLeMarabout')) {
  header('Location: ../index.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<?php
$linkLogo = '../images/logo.png';
$linkCss = '../css-js/style.css';
$title = 'Activation bon de réduction';
include '../includes/head.php';
?>

<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <div class="container">
            <?php
            $query = $db->prepare('SELECT nfc FROM COMPANY WHERE siret = :siret');
            $query->execute(['siret' => $_SESSION['siret']]);
            $nfc = $query->fetch(PDO::FETCH_COLUMN);

            if ($nfc === '0') {
            $query = $db->prepare('UPDATE COMPANY SET nfc = 1 WHERE siret = :siret');
            $query->execute(['siret' => $_SESSION['siret']]);
            echo '<div class="container"><div class="alert alert-success" role="alert">Votre compte a bien été activé !</div></div>';
            } else {
            echo '<div class="container"><div class="alert alert-danger" role="alert">Votre compte est déjà activé !</div></div>';
            }
            ?>

            <div class="d-flex justify-content-center">
                <a href="../index.php" class="btn btn-primary">Retour à l'accueil</a>
            </div>
        </div>
    </main>

    <script src="../css-js/scripts.js"></script>
    <?php include '../includes/footer.php'; ?>
</body>

</html>