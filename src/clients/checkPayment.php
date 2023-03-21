<?php

include '../includes/db.php';

define('autoload', '/home/php/src/vendor/autoload.php');

require_once(autoload);

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

$req = $db->prepare('UPDATE RESERVATION SET status = 1 WHERE id = :id');
$req->execute([
  'id' => htmlspecialchars($_GET['id']),
]);

if ($req === false) {
  header('Location: ../clients/reservations.php?message=Une erreur est survenue lors du paiement.&type=error');
  exit();
}

header('Location: ../clients/reservations.php?message=Votre paiement a bien été effectué.&type=success');
exit();

?>
