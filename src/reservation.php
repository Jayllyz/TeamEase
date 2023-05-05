<?php session_start();
if (!isset($_SESSION['siret'])) {
  header('Location: ../index.php');
  exit();
}
include 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<?php
$siteKey = $_ENV['CAPTCHA_SITE'];
$linkLogo = 'images/logo.png';
$linkCss = 'css-js/style.css';
$title = 'Reservation';
include 'includes/head.php';
?>

<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <h1 style="margin-top: 1rem; margin-bottom: 1rem;" class="lang-title-reservation">Reservation</h1>
        <div class="container col-md-6">
            <?php include 'includes/msg.php'; ?>
        </div>
        <?php
        $query = $db->prepare(
          'SELECT ACTIVITY.*, SCHEDULE.startHour, SCHEDULE.endHour, SCHEDULE.day FROM ACTIVITY INNER JOIN SCHEDULE ON ACTIVITY.id = SCHEDULE.id_activity WHERE ACTIVITY.id = :id',
        );
        $query->execute([
          'id' => htmlspecialchars($_GET['id']),
        ]);
        $activities = $query->fetchAll(PDO::FETCH_ASSOC);
        $idActivity = htmlspecialchars($_GET['id']);
        $price = $activities[0]['priceAttendee'];
        ?>
        <form action="verifications/reservation.php?id=<?= $idActivity ?>&price=<?= $price ?>"
            onsubmit="return validateForm(this.name)" method="POST">
            <div class="container col-md-3" id="form">
                <label for="attendee" class="form-label">
                    <h4 class="lang-reservation-number-participant">Nombre de participants</h4>
                </label>
                <div class="input-group">
                    <input type="number" class="form-control" min="1" max="<?= $activities[0][
                      'maxAttendee'
                    ] ?>" id="attendee" name="attendee" value="<?= isset($_COOKIE['attendee'])
  ? $_COOKIE['attendee']
  : '' ?>" onchange="selectedDateReservation(date, <?= $idActivity ?>)" required>
                    <span class="input-group-text" id="priceDisplay">0.00</span>
                    <span class="input-group-text">€</span>
                </div>
                <div id="priceHelp" class="form-text mb-2"><?= $price .
                  ' € par personne | ' .
                  $activities[0]['maxAttendee'] .
                  ' places disponibles' ?></div>
                <label for="date" class="form-label">
                    <h4 class="lang-reservation-date">Date de votre réservation</h4>
                </label>
                <?php foreach ($activities as $activity) { ?>
                <div style="display:none" class="<?= $activity['day'] ?>">
                </div>
                <?php } ?>
                <input type="text" class="form-control" name="date" id="date"
                    onchange="selectedDateReservation(this, <?= $idActivity ?>)" required>

                <p id="ifempty" style="display: none;" class="mt-2"></p>
                <div id="slot" style="display:none">
                    <label for="time" class="form-label">
                        <h4 class="lang-reservation-time">Heure de votre créneau</h4>
                    </label>
                    <select class="form-control" name="slot" id="container-slot">
                    </select>
                </div>
                <input type="hidden" name="price" id="price" value="<?= $price ?>">

                <div class="mt-4 mb-4" id="present-provider">

                </div>

                <div class="g-recaptcha mb-4 mt-4" id="captcha" data-sitekey="<?= $siteKey ?>"
                    data-callback="validCaptcha"></div>

                <div class="d-flex justify-content-center mt-3">
                    <input type="submit" class="btn btn-success" style="display: none" id="submit">
                </div>
            </div>
        </form>
        <div class="container align text-center mt-4 lang-reservations-CGU"
            style="font-size: 20px; outline: 1px solid #D3D3D3;">
            Le règlement des réservations se fait ultérieurement dans "Mes réservations". Toute réservation peut
            être
            annulée ou modifier avant la date. <br> Merci de remplir la liste des participants pour chacune de vos
            réservations
            pour
            aider nos équipes.
        </div>
    </main>
    <script src="css-js/scripts.js"></script>
    <script>
    var validCaptcha = function(response) {
        const state = (grecaptcha.getResponse()) ? true : false;

        if (state === true) {
            document.getElementById('submit').style.display = 'block';
        }
    };
    </script>

    <?php include 'includes/footer.php'; ?>
</body>

</html>