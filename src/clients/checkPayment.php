<?php
require_once '../includes/db.php';
require_once '/home/php/src/vendor/autoload.php';

$secret = $_ENV['STRIPE_SECRET'];

\Stripe\Stripe::setApiKey($secret);

$token = $_POST['stripeToken'];
$email = $_POST['stripeEmail'];
$price = $_POST['price'];

$customer = \Stripe\Customer::create([
  'email' => $email,
  'source' => $token,
]);

$charge = \Stripe\Charge::create([
  'customer' => $customer->id,
  'amount' => $price,
  'currency' => 'eur',
  'description' => 'Together & Stronger',
  'receipt_email' => $email,
]);

if ($charge->status !== 'succeeded') {
  header('Location: ../clients/reservations.php?message=Une erreur est survenue lors du paiement.&type=error');
  exit();
}

$id_reservation = htmlspecialchars($_GET['id']);

$req = $db->prepare('UPDATE RESERVATION SET status = 1 WHERE id = :id');
$req->execute([
  'id' => $id_reservation,
]);

if ($req === false) {
  header('Location: ../clients/reservations.php?message=Une erreur est survenue lors du paiement.&type=error');
  exit();
}

$isinvoice = true;
$msgHTML = '<img src="localhost/images/logo.png" class="logo float-left m-2 h-75 me-4" width="95" alt="Logo">
            <p class="display-2">Merci d\'avoir choisi Together&Stronger pour votre séminaire, toute l\'équipe espère que vous avez apprécié votre expérience parmi nous.<br></p>
            <p class="display-2">Vous trouverez ci-dessous la facture de votre réservation.<br></p>';
$destination = '../clients/reservations.php';

require_once '../includes/estimate.php';
require_once '../includes/mailer.php';


$select = $db->prepare('SELECT id_activity FROM RESERVATION WHERE id = :id');
$select->execute([
  'id' => $id_reservation,
]);
$idActivity = $select->fetch(PDO::FETCH_ASSOC);

$select = $db->prepare('SELECT name FROM ACTIVITY WHERE id = :id');
$select->execute([
  'id' => $idActivity['id_activity'],
]);
$nameActivity = $select->fetch(PDO::FETCH_ASSOC);

$req = $db->prepare(
  'INSERT INTO INVOICE (amount, id_reservation, paymentDay, details) VALUES (:amount, :id_reservation, :paymentDay, :details)',
);
$req->execute([
  'amount' => $price,
  'id_reservation' => $id_reservation,
  'paymentDay' => date('Y-m-d'),
  'details' => 'Réservation de l\'activité ' . $nameActivity['name'],
]);

if($req === false) {
  header('Location: ../clients/reservations.php?message=Une erreur est survenue lors de la création de la facture.&type=error');
  exit();
}

header(
  'Location: ../clients/reservations.php?message=Votre paiement a bien été effectué, vous allez recevoir votre facture par mail.&type=success',
);
exit();

?>