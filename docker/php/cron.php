<?php

include_once '/home/php/includes/db.php';

$today = date('Y-m-d');

$date = date('Y-m-d', strtotime($today . ' + 2 day'));

$getSiret = $db->prepare('SELECT siret FROM RESERVATION WHERE date = :date');

$getSiret->execute([
  'date' => $date,
]);
$siret = $getSiret->fetchAll(PDO::FETCH_ASSOC);

foreach ($siret as $key => $value) {
  $getMail = $db->prepare('SELECT email FROM COMPANY WHERE siret = :siret');

  $getMail->execute([
    'siret' => $value['siret'],
  ]);

  $mail = $getMail->fetch(PDO::FETCH_ASSOC);

  $email = $mail['email'];
  $subject = 'Rappel de réservation';
  $msgHTML = 'Bonjour, vous avez une réservation dans 2 jours.';

  include_once '/home/php/includes/mailer.php';
}
