<?php
include '../includes/db.php';
$id = htmlspecialchars($_GET['id']);

if (!isset($id) || empty($id)) {
  header('location: ../index.php');
  exit();
} else {
  $date = htmlspecialchars($_GET['date']);
  $raison = htmlspecialchars($_POST['raison']);

  // changer le format de la date et mettre année mois jour
  $date = explode('/', $date);
  $date = $date[2] . '-' . $date[1] . '-' . $date[0];

  $sql = 'SELECT * FROM RESERVATION WHERE id_activity = :id AND date = :date';
  $query = $db->prepare($sql);
  $query->execute([
    'id' => $id,
    'date' => $date,
  ]);
  $reservation = $query->fetchAll(PDO::FETCH_ASSOC);

  for ($i = 0; $i < count($reservation); $i++) {
    $sql = 'DELETE FROM ESTIMATE WHERE id_reservation = :id';
    $query = $db->prepare($sql);
    $query->execute([
      'id' => $reservation[$i]['id'],
    ]);
  }

  for ($i = 0; $i < count($reservation); $i++) {
    $sql = 'DELETE FROM RESERVATION WHERE id_activity = :id AND date = :date';
    $query = $db->prepare($sql);
    $query->execute([
      'id' => $id,
      'date' => $date,
    ]);

    $sql = 'SELECT email FROM COMPANY WHERE siret = :siret';
    $query = $db->prepare($sql);
    $query->execute([
      'siret' => $reservation[$i]['siret'],
    ]);
    $email = $query->fetch(PDO::FETCH_ASSOC);
    $email = $email['email'];

    $subject = 'Annulation d\'une activité';
    $msgHTML =
      '<p class="display-2">Bonjour nous vous envoyons ce mail pour vous informer que votre activité du ' .
      $date .
      '
                 ne sera pas disponible pour la raison suivante : ' .
      $raison .
      ':<br></p>
      <a href="togetherandstronger.site/includes/confemail.php?
      email=' .
      $email .
      '&type=' .
      'company">Pour plus de details !</a>';
    $destination = '../login.php';
    include '../includes/mailer.php';

    header('location: ../profile.php?message=Votre absence a bien été enregistrée!&type=success');
    exit();
  }

  header('location: ../profile.php');
  exit();
}
