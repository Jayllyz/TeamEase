<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php
$linkLogo = 'images/logo.png';
$linkCss = 'css-js/style.css';
$title = 'Reservation';
include 'includes/head.php';
?>

<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <h1 style="margin-top: 1rem; margin-bottom: 1rem;">Reservation</h1>
        <div class="container col-md-6">
            <?php if (!isset($_GET['input'])) {
              include 'includes/msg.php';
            } ?>
        </div>

        <form action="">
            <div class="container col-md-4" id="form">
                <label for="attendee" class="form-label"><h4>Nombre de participants</h4></label>
                <input type="number" class="form-control" id="attendee" name="attendee" value="<?= isset(
                  $_COOKIE['attendee']
                )
                  ? $_COOKIE['attendee']
                  : '' ?>" required>
                <label for="date" class="form-label"><h4>Date de votre réservation</h4></label>
                <input type="date" class="form-control" id="date" name="date" value="<?= isset($_COOKIE['date'])
                  ? $_COOKIE['date']
                  : '' ?>" required>
                <label for="time" class="form-label"><h4>Heure de votre réservation</h4></label>
                <select class="form-control" id="time" name="time" required></select>
                <div class="d-flex justify-content-center mt-3">
                    <input class="btn btn-success" type="submit" value="Valider">
                </div>
            </div>
        </form>
    </main>
    <script src="css-js/scripts.js"></script>
    <?php include 'includes/footer.php'; ?>
</body>

</html>