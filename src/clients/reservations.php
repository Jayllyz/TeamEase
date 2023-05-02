<?php
session_start();
if (!isset($_SESSION['siret'])) {
  header('Location: ../index.php');
  exit();
}
include '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<?php
$linkLogo = '../images/logo.png';
$stripePublicKey = $_ENV['STRIPE_PUBLIC'];
$linkCss = '../css-js/style.css';
$title = 'Vos réservations';
include '../includes/head.php';
$siret = $_SESSION['siret'];
?>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container col-md-6">
        <?php include '../includes/msg.php'; ?>
    </div>
    <main>
        <h1 class="mt-4">Mes réservations</h1>

        <?php
        $req = $db->prepare(
          'SELECT id, id_activity, attendee, date, DATE_FORMAT(time, \'%H:%i\') AS time , status FROM RESERVATION WHERE siret = :siret',
        );
        $req->execute([
          'siret' => $siret,
        ]);
        $result = $req->fetchAll(PDO::FETCH_ASSOC);

        if (empty($result)) {
          echo '<div class="container col-md-6">
          <h2 class="text-center">Vous n\'avez pas encore réservé d\'activité</h2>
          </div>';
        }

        if (!empty($result)) { ?>


        <div class="container info_user">
            <table class="table text-center table-bordered table-hover">
                <tr>
                    <th>Nom de l'activité</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Nombre de participants</th>
                    <th>Adresse et salle</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php foreach ($result as $select) { ?>

                <?php
                $date = explode('-', $select['date']);
                $date = $date[2] . '/' . $date[1] . '/' . $date[0];
                $select['date'] = $date;

                $req = $db->prepare('SELECT id_room FROM ACTIVITY WHERE id = :id');
                $req->execute([
                  'id' => $select['id_activity'],
                ]);
                $id_room = $req->fetch(PDO::FETCH_ASSOC);
                ?>

                <tr>
                    <td><?php
                    $req = $db->prepare('SELECT name FROM ACTIVITY WHERE id = :id');
                    $req->execute([
                      'id' => $select['id_activity'],
                    ]);
                    $result = $req->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $name) {
                      echo $name['name'];
                    }
                    ?></td>
                    <td><?= $date ?></td>

                    <td><?php
                    $req = $db->prepare('SELECT duration FROM ACTIVITY WHERE id = :id');
                    $req->execute([
                      'id' => $select['id_activity'],
                    ]);

                    $result = $req->fetch(PDO::FETCH_ASSOC);
                    if ($result['duration'] > 60) {
                      $hour = floor($result['duration'] / 60);
                      $min = $result['duration'] % 60;
                      $duration = $hour . 'h' . $min;
                      echo $select['time'] . ' <br> ' . 'durée : ' . $duration;
                    } else {
                      echo $select['time'] . ' <br> ' . 'durée : ' . $result['duration'] . 'min';
                    }
                    ?></td>



                    <td><?= $select['attendee'] ?></td>
                    <td>
                        <?
                      $req = $db->prepare('SELECT id, id_location FROM ROOM WHERE id = :id');
                      $req->execute([
                        'id' => $id_room['id_room'],
                      ]);
                      $resultRoom = $req->fetch(PDO::FETCH_ASSOC);
                      
                      $req = $db->prepare('SELECT address FROM LOCATION WHERE id = :id');
                      $req->execute([
                        'id' => $resultRoom['id_location'],
                      ]);
                      $result = $req->fetch(PDO::FETCH_ASSOC);
                      echo 'Adresse : ' . '<br>' . $result['address'] . '<br>';
                      echo 'Salle : ' . $resultRoom['id'];
                    ?>
                    </td>
                    <td><?php if ($select['status'] == 0) {
                      echo 'Pas encore réglée <svg width="46" height="46" fill="none" stroke="#df1111" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path d="M18 6 6 18"></path>
                      <path d="m6 6 12 12"></path>
                    </svg>';
                    } else {
                      echo 'Réservation réglée';
                    } ?></td>
                    <td><?php if ($select['status'] == 0) { ?>
                        <?php
                        $req = $db->prepare('SELECT name FROM ACTIVITY WHERE id = :id');
                        $req->execute([
                          'id' => $select['id_activity'],
                        ]);
                        $name = $req->fetch(PDO::FETCH_ASSOC);

                        $req = $db->prepare('SELECT amount FROM ESTIMATE WHERE id_reservation = :id');
                        $req->execute([
                          'id' => $select['id'],
                        ]);
                        $price = $req->fetch(PDO::FETCH_ASSOC);
                        $price = $price['amount'] * $select['attendee'];
                        ?>

                        <div class="button_profil">
                            <a href="validParticipants.php?id=<?= $select['id'] ?>"
                                class="btn-update btn ms-2 me-2">Saisir
                                les participants</a>
                            <br>
                            <form action="checkPayment.php?id=<?= $select['id'] ?>"
                                class="mb-4 btn-update btn ms-2 me-2"
                                style="color: #eee; background-color: green !important;" method="POST">

                                <input type="submit" value="Payer votre réservation" data-key="<?= $stripePublicKey ?>"
                                    style="background-color: transparent; border: none; outline: none; color: white;"
                                    data-amount="<?= $price * 100 ?>" data-locale="auto" data-currency="eur"
                                    data-name="<?= $name['name'] ?>" data-description="Paiement de la réservation"
                                    data-image="../images/logo.png" />

                                <script src="https://checkout.stripe.com/checkout.js"></script>
                                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.js"></script>
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
                                <input type="hidden" name="price" value="<?= $price * 100 ?>">
                            </form>
                            <a href="../includes/estimate.php?id=<?= $select['id'] ?>"
                                class=" btn-update btn ms-2 me-2">Télécharger un
                                devis</a>
                            <br>
                            <a href="editReserv.php?id=<?= $select['id'] ?>"
                                class="btn-submit btn ms-2 me-2">Modifier</a>
                            <br>
                            <a href="cancel.php?id=<?= $select['id'] ?>" class="btn-ban btn ms-2 me-2">Annuler</a>
                            <?php } else {echo '<svg width="46" height="46" fill="none" stroke="#0c9234" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
  <path d="M20 6 9 17l-5-5"></path>
</svg>';} ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
        <?php }
        ?>
    </main>
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>