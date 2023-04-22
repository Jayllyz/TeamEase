<?php
session_start();
if (!isset($_SESSION['siret'])) {
  header('Location: ../index.php');
  exit();
}
include_once '../includes/db.php';

$cart = $db->prepare('SELECT * FROM CART WHERE siret = :siret');
$cart->execute(['siret' => $_SESSION['siret']]);
$cart = $cart->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<?php
$linkLogo = '../images/logo.png';
$linkCss = '../css-js/style.css';
$title = 'Vos réservations';
$siret = $_SESSION['siret'];
include '../includes/head.php';
?>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container col-md-6">
        <?php include '../includes/msg.php'; ?>
    </div>
    <main>
        <h1 class="mt-4">Mon panier</h1>

        <?php if ($cart) { ?>
        <?php foreach ($cart as $item) {

          $query = $db->prepare(
            'SELECT ACTIVITY.*, SCHEDULE.startHour, SCHEDULE.endHour, SCHEDULE.day FROM ACTIVITY INNER JOIN SCHEDULE ON ACTIVITY.id = SCHEDULE.id_activity WHERE ACTIVITY.id = :id',
          );
          $query->execute([
            'id' => $item['id_activity'],
          ]);
          $activity = $query->fetch(PDO::FETCH_ASSOC);
          $idActivity = $item['id_activity'];
          $name = $activity['name'];
          $price = $activity['priceAttendee'];
          var_dump($activity);
          ?>


        <h3><?php echo $activity['name']; ?></h3>
        <p><?php echo $activity['description']; ?></p>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#reservModal-<?php echo $idActivity; ?>">
            Renseignements</button>

        <div class="modal fade" id="reservModal-<?= $idActivity ?>" tabindex="-1" data-bs-backdrop="static"
            data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId"><?= $name ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">


                        <label for="attendee" class="form-label">
                            <h4>Nombre de participants</h4>
                        </label>

                        <?php $elementDate = 'date' . $idActivity; ?>

                        <input type="number" class="form-control" min="1" max="<?= $activity[
                          'maxAttendee'
                        ] ?>" id="attendee" name="attendee"
                            onchange="selectedDateReservation(<?= $elementDate ?>, <?= $idActivity ?>)" required>
                        <span class="input-group-text" id="priceDisplay">0.00</span>

                        <label for="<?= $elementDate ?>" class="form-label">
                            <h4>Date de votre réservation</h4>
                        </label>
                        <div style="display:none" class="<?= $activity['day'] ?>">
                        </div>
                        <input type="text" class="form-control" name="<?= $elementDate ?>" id="<?= $elementDate ?>"
                            onchange="selectedDateReservation(this, <?= $idActivity ?>)" required>

                        <p id="ifempty" style="display:none" class="mt-2"></p>
                        <div id="slot" style="display:none">
                            <label for="time" class="form-label">
                                <h4>Heure de votre créneau</h4>
                            </label>
                            <select class="form-control" name="slot" id="container-slot" onchange="selectSlot(this)">
                            </select>
                        </div>
                        <input type="hidden" name="price" id="price" value="<?= $price ?>">

                        <div class="mt-4 mb-4" id="present-provider">

                        </div>




                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>


        <?php
        } ?>





        <?php } else { ?>
        <div class="container col-md-6">
            <div class="alert alert-warning" role="alert">
                Votre panier est vide
            </div>
        </div>
        <?php } ?>


    </main>
    <?php include '../includes/footer.php'; ?>
    <script src="../css-js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>