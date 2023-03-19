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
        <?php
        $query = $db->prepare(
          'SELECT ACTIVITY.*, SCHEDULE.startHour, SCHEDULE.endHour FROM ACTIVITY INNER JOIN SCHEDULE ON ACTIVITY.id = SCHEDULE.id_activity WHERE ACTIVITY.id = :id'
        );
        $query->execute([
          'id' => htmlspecialchars($_GET['id']),
        ]);
        $activity = $query->fetch(PDO::FETCH_ASSOC);
        ?>
        <form action="">
            <div class="container col-md-4" id="form">
                <label for="attendee" class="form-label"><h4>Nombre de participants</h4></label>
                <input type="number" class="form-control" min="1" max="<?= $activity[
                  'maxAttendee'
                ] ?>" id="attendee" name="attendee" value="<?= isset($_COOKIE['attendee'])
  ? $_COOKIE['attendee']
  : '' ?>" required>
                <label for="date" class="form-label"><h4>Date de votre réservation</h4></label>
                <input type="date" class="form-control" id="myDate" min="<?php echo date(
                  'Y-m-d'
                ); ?>" max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" value = "<?php echo date(
  'Y-m-d'
); ?>" onchange="selectedDateReservation(this)" required>
                <div id="container-slot">
                    <label for="time" class="form-label"><h4>Heure de votre créneau</h4></label>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <input type="submit" class="btn btn-success">
                </div>
            </div>
        </form>
    </main>

    <script>
    $('#myDate').datepicker({
        beforeShowDay: function(date) {
            var dayOfWeek = date.getDay();
            return [
            dayOfWeek !== 0 && dayOfWeek !== 1 && dayOfWeek !== 4 && dayOfWeek !== 6,
            ''
            ];
        }
        });
    </script>
    <script src="css-js/scripts.js"></script>
    <?php include 'includes/footer.php'; ?>
</body>

</html>