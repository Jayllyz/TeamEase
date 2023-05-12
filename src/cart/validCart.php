<?php
ob_start();
session_start();
include_once '../includes/db.php';

if (isset($_SESSION['siret'])) {
  $siret = $_SESSION['siret'];
  $ids = [];

  foreach ($_POST as $key => $value) {
    if (strpos($key, 'attendee') === 0) {
      $id = substr($key, strlen('attendee'));
      if (!in_array($id, $ids)) {
        $ids[] = $id;
      }
    }
  }

  foreach ($ids as $id) {
    $attendee = $_POST['attendee' . $id];
    $date = $_POST['date' . $id];
    $date = date('Y-m-d', strtotime($date));
    $select = $_POST['container-slot' . $id];
    $price = $_POST['price' . $id];
    $idActivity = $id;

    $req = $db->prepare(
      'SELECT * FROM RESERVATION WHERE id_activity = :id_activity AND date = :date AND time = :time AND siret = :siret',
    );
    $req->execute([
      'id_activity' => $idActivity,
      'siret' => $siret,
      'date' => $date,
      'time' => $select,
    ]);
    $response = $req->fetch(PDO::FETCH_ASSOC);

    if ($response) {
      header('Location: ..cart.php?message=Vous avez déjà réservé ce créneau!&type=danger');
      exit();
    }

    $req = $db->prepare(
      'INSERT INTO RESERVATION (id_activity, siret, date, time, attendee) VALUES (:id_activity, :siret, :date, :time, :attendee)',
    );
    $result = $req->execute([
      'id_activity' => $idActivity,
      'siret' => $siret,
      'date' => $date,
      'time' => $select,
      'attendee' => $attendee,
    ]);

    if ($result) {
      $getId = $db->prepare('SELECT MAX(id) as id FROM RESERVATION');
      $getId->execute();
      $id = $getId->fetch(PDO::FETCH_ASSOC);

      $req = $db->prepare(
        'INSERT INTO ESTIMATE (amount, creationDate, id_reservation) VALUES (:amount, :creationDate, :id_reservation)',
      );
      $result = $req->execute([
        'amount' => $price * $attendee,
        'creationDate' => date('Y-m-d'),
        'id_reservation' => $id['id'],
      ]);
    } else {
      header('Location: cart.php?message=Erreur lors de la réservation!&type=danger');
      exit();
    }
  }
} else {
  header('Location: ../index.php?&message=Vous devez être connecté pour réserver!&type=danger');
  exit();
}

$totalPrice = 0;

foreach ($_POST as $key => $value) {
  if (strpos($key, 'price') === 0) {
    $totalPrice += $value * $_POST['attendee' . substr($key, strlen('price'))];
  }
}

if (isset($_GET['pay'])) {
  if ($_GET['pay'] === 'true') {
    header('Location: cart.php?amount=' . $totalPrice);
    exit();
  }
}

$clearCart = $db->prepare('DELETE FROM CART WHERE siret = :siret');
$clearCart->execute(['siret' => $siret]);

header('Location: ../clients/reservations.php?message=Votre panier a bien été prise en compte!&type=success');
exit();
