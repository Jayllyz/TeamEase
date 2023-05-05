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

$idString = '';
foreach ($cart as $item) {
  $idString .= $item['id_activity'] . ',';
}
$idString = substr($idString, 0, -1);
?>
<!DOCTYPE html>
<html lang="fr">
<?php
$linkLogo = '../images/logo.png';
$linkCss = '../css-js/style.css';
$title = 'Vos réservations';
$siret = $_SESSION['siret'];
$stripePublicKey = $_ENV['STRIPE_PUBLIC'];
include '../includes/head.php';
if (isset($_GET['amount'])) {
  $amount = $_GET['amount'];
}
?>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container col-md-6">
        <?php include '../includes/msg.php'; ?>
    </div>
    <main>
        <?php if (isset($_GET['amount'])) { ?>

        <div class="container col-md-6 ">
            <?php
            echo '<h1 class="mt-4">Réglement de votre panier</h1>';
            echo '<h2 class="mt-4 d-flex justify-content-center mb-4">Montant total : ' . $amount . '€</h2>';
            ?>

            <div class="row d-flex justify-content-center">
                <form action="validPayment.php" class="mb-4 col-2 btn-update btn btn-lg"
                    style="color: #eee; background-color: green !important;" method="POST">
                    <button type="submit" class="p-0" data-key="<?= $stripePublicKey ?>"
                        style="background-color: transparent; border: none; outline: none; color: white;"
                        data-amount="<?= $amount * 100 ?>" data-locale="auto" data-currency="eur" data-name="Panier"
                        data-description="Paiement du panier" data-image="../images/logo.png">
                        <i class="bi bi-currency-euro"></i>
                    </button>


                    <script src="https://checkout.stripe.com/checkout.js"></script>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.js">
                    </script>
                    <script>
                    $(document).ready(function() {
                        $(':submit').on('click', function(event) {
                            event.preventDefault();

                            var $button = $(this),
                                $form = $button.parents('form');

                            var opts = $.extend({}, $button.data(), {
                                token: function(result) {
                                    $form.append($('<input>').attr({
                                        type: 'hidden',
                                        name: 'stripeToken',
                                        value: result.id
                                    })).append($('<input>').attr({
                                        type: 'hidden',
                                        name: 'stripeEmail',
                                        value: result.email
                                    })).submit();


                                }
                            });

                            StripeCheckout.open(opts);
                        });
                    });
                    </script>
                    <input type="hidden" name="price" value="<?= $amount * 100 ?>">
                </form>
            </div>
        </div>

        <?php } else { ?>
        <h1 class="mt-4" id="cartTitle">Mon panier</h1>

        <div id="idList" style="display:none;"><?= $idString ?></div>

        <form method="POST" id="formCart">

            <?php if ($cart) { ?>
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <?php foreach ($cart as $item) {

                      $query = $db->prepare(
                        'SELECT ACTIVITY.*, SCHEDULE.startHour, SCHEDULE.endHour, SCHEDULE.day FROM ACTIVITY INNER JOIN SCHEDULE ON ACTIVITY.id = SCHEDULE.id_activity WHERE ACTIVITY.id = :id',
                      );
                      $query->execute([
                        'id' => $item['id_activity'],
                      ]);
                      $activity = $query->fetchAll(PDO::FETCH_ASSOC);
                      $idActivity = $item['id_activity'];
                      $name = $activity[0]['name'];
                      $price = $activity[0]['priceAttendee'];
                      $maxAttendee = $activity[0]['maxAttendee'];
                      ?>
                    <div class="col-4 p-2">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title row">
                                    <div class="col-10">
                                        <?php echo $activity[0]['name']; ?>
                                    </div>
                                    <div class="col-2 d-flex justify-content-end pe-3">
                                        <button type="button" class="btn-close btn-danger me-2" id="<?= $idActivity ?>"
                                            onclick="rmCart(this)"></button>
                                    </div>
                                </h3>
                                <hr size="5">
                                <div class="form-group">

                                    <div>
                                        <label for="attendee">
                                            <h4>Nombre de participants</h4>
                                        </label>
                                        <?php $attendeeId = 'attendee' . $idActivity; ?>
                                        <?php $dateId = 'date' . $idActivity; ?>
                                        <?php $pricedisplayId = 'priceDisplay' . $idActivity; ?>

                                        <div class="input-group">
                                            <input type="number" class="form-control" min="1" max="<?= $maxAttendee ?>"
                                                id="<?= $attendeeId ?>" name="<?= $attendeeId ?>"
                                                onchange="selectedDateReservation(<?= $dateId ?>, <?= $idActivity ?>)"
                                                required>
                                            <span class="input-group-text" id="<?= $pricedisplayId ?>">0.00</span>
                                            <span class="input-group-text">€</span>
                                        </div>
                                        <div id="priceHelp" class="form-text mb-2"><?= $price .
                                          ' € par personne | ' .
                                          $maxAttendee .
                                          ' places disponibles' ?></div>
                                    </div>

                                    <div>
                                        <?php foreach ($activity as $day) { ?>
                                        <div style="display:none" class="<?= $day['day'] ?>">
                                        </div>
                                        <?php } ?>
                                        <label for="date">
                                            <h4>Date</h4>
                                        </label>
                                        <input type="text" class="form-control input-date" name="<?= $dateId ?>"
                                            id="<?= $dateId ?>"
                                            onchange="selectedDateReservation(this, <?= $idActivity ?>)" required>
                                    </div>

                                    <div>
                                        <?php $slotId = 'slot' . $idActivity; ?>
                                        <?php $emptyId = 'empty' . $idActivity; ?>

                                        <p id="<?= $emptyId ?>" style="display: none;" class="mt-2"></p>
                                        <div id="<?= $slotId ?>" style="display:none">
                                            <label for="time" class="form-label">
                                                <h4>Heure de votre créneau</h4>
                                            </label>
                                            <?php $containerId = 'container-slot' . $idActivity; ?>
                                            <select class="form-control" name="<?= $containerId ?>"
                                                id="<?= $containerId ?>">
                                            </select>
                                        </div>

                                        <?php $priceId = 'price' . $idActivity; ?>
                                        <input type="hidden" name="<?= $priceId ?>" id="<?= $priceId ?>"
                                            value="<?= $price ?>">

                                        <?php $providerId = 'present-provider' . $idActivity; ?>
                                        <div class="mt-4 mb-4" id="<?= $providerId ?>"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    } ?>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-5">
                <button type="submit" class="btn btn-primary me-3" name="submit" onclick="valid()">Valider</button>
                <button type="submit" class="btn btn-primary me-3" name="submit" onclick="pay()">Valider et
                    payer</button>
                <button type="submit" class="btn btn-secondary" name="submit" onclick="estimate()">Générer un
                    devis</button>
            </div>

        </form>


        <div class="container align text-center mt-4" style="font-size: 20px; outline: 1px solid #D3D3D3;">
            Le règlement des réservations se fait ultérieurement dans "Mes réservations". Toute réservation peut
            être
            annulée ou modifier avant la date. <br> Merci de remplir la liste des participants pour chacune de vos
            réservations
            pour
            aider nos équipes.
        </div>




        <?php } else { ?>
        <div class="container col-md-6">
            <div class="alert alert-warning border border-2 border-secondary rounded" role="alert">
                Votre panier est vide
            </div>
        </div>
        <?php } ?>

        <?php } ?>

    </main>
    <?php include '../includes/footer.php'; ?>
    <script src="../css-js/scripts.js"></script>
    <script>
    form = document.getElementById("formCart");

    function valid() {
        form.action = "validCart.php";
        form.submit();
    }

    function pay() {
        form.action = "validCart.php?pay=true";
        form.submit();
    }

    function estimate() {
        form.action = "estimate.php";
        form.submit();
    }

    function rmCart(button) {
        let id = button.id;

        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                alert(this.responseText);
                location.reload();
            }
        };
        xhr.open('POST', '../ajaxReq/rmCart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('id=' + id);
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>