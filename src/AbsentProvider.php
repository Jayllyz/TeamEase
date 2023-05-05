<?php session_start();
if (!isset($_SESSION['id'])) {
  header('Location: ../index.php');
  exit();
}
include 'includes/db.php';
$id = htmlspecialchars($_GET['id']);
$date = htmlspecialchars($_GET['date']);
?>
<!DOCTYPE html>
<html lang="fr">
<?php
$siteKey = $_ENV['CAPTCHA_SITE'];
$linkLogo = 'images/logo.png';
$linkCss = 'css-js/style.css';
$title = 'Provider Absent';
include 'includes/head.php';
?>

<body>
    <?php include 'includes/header.php'; ?>

    <script>
    function openPopup() {
        var choix = confirm("Voulez-vous valider votre choix ?");
        if (choix == true) {

        } else {
            event.preventDefault();

        }
    }
    </script>

    <main>
        <h1 class="text-center mt-5">Modifier vos Informations</h1>
        <form action="verifications/absence.php?id=<?= $id ?>&date=<?= $date ?>" method="post">
            <div class="container col-md-6">
                <?php include 'includes/msg.php'; ?>
            </div>
            <div class="container col-md-4" id="form">
                <div class="mb-3">
                    <label class="form-label"><strong>Raison d'absence</strong></label>
                    <select id="raison" name="raison">
                        <option value="maladie">Maladie</option>
                        <option value="urgence">Urgence personnelle</option>
                        <option value="rendez-vous">Rendez-vous médical</option>
                        <option value="famille">Problème familial</option>
                        <option value="autre">Autre raison</option>
                    </select>
                    <br>
                    <button type="submit" name="submit" onclick="openPopup()"
                        class="btn mt-3 btn-submit">Confirmer</button>
                </div>
            </div>
        </form>
    </main>

    <script src="css-js/scripts.js"></script>
    <?php include 'includes/footer.php'; ?>
</body>

</html>