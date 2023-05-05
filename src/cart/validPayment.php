<?php
ob_start();
session_start();

require_once '../includes/db.php';
require_once '/home/php/vendor/autoload.php';

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
  header('Location: ../cart/cart.php?message=Une erreur est survenue lors du paiement.&type=error');
  exit();
}

$siret = htmlspecialchars($_SESSION['siret']);

$getIdActivity = $db->prepare('SELECT id_activity FROM CART WHERE siret = :siret');
$getIdActivity->execute([
  'siret' => $siret,
]);
$getIdActivity = $getIdActivity->fetchAll(PDO::FETCH_ASSOC);

foreach ($getIdActivity as $idActivity) {
  $getReservation = $db->prepare(
    'SELECT id FROM RESERVATION WHERE id_activity = :id_activity AND siret = :siret AND status = 0',
  );
  $getReservation->execute([
    'id_activity' => $idActivity['id_activity'],
    'siret' => $siret,
  ]);
  $getReservation = $getReservation->fetch(PDO::FETCH_ASSOC);

  $req = $db->prepare('UPDATE RESERVATION SET status = 1 WHERE id = :id');
  $req->execute([
    'id' => $getReservation['id'],
  ]);
}

$clearCart = $db->prepare('DELETE FROM CART WHERE siret = :siret');
$clearCart->execute(['siret' => $siret]);

header('Location: ../clients/reservations.php?message=Votre paiement a bien été prise en compte!&type=success');
exit();

?>
