<?php

include_once '/home/php/includes/db.php';

$today = date('Y-m-d');

$date = date('Y-m-d', strtotime($today . ' + 2 day'));

$getSiret = $db->prepare('SELECT id, siret, id_activity, time FROM RESERVATION WHERE date = :date');

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

  $heure = date('H:i', strtotime($value['time']));


  $getActivity = $db->prepare('SELECT name FROM ACTIVITY WHERE id = :id');
  $getActivity->execute([
    'id' => $value['id_activity'],
  ]);
  $activity = $getActivity->fetch(PDO::FETCH_ASSOC);


  $subject = 'Rappel de réservation';
  $msgHTML = 'Bonjour, vous avez une réservation pour l\'activité ' . $activity['name'] . ' dans 2 jours à ' . $heure  . '. <br> Si vous n\'etes pas disponible merci de l\'annuler <br> Cordialement, <br> L\'équipe de Together&Stronger.';

  include_once '/home/php/includes/mailer.php';
}

foreach($siret as $reservation) {
  
  $idAttendee = $db->prepare('SELECT id_attendee FROM RESERVED WHERE id_reservation = :id_reservation');
  $idAttendee->execute([
    'id_reservation' => $reservation['id'],
  ]);
  $idAttendee = $idAttendee->fetchAll(PDO::FETCH_ASSOC);

  foreach($idAttendee as $id) {
    $getMail = $db->prepare('SELECT email FROM ATTENDEE WHERE id = :id');
    $getMail->execute([
      'id' => $id['id_attendee'],
    ]);
    $mail = $getMail->fetch(PDO::FETCH_ASSOC);

    $email = $mail['email'];
    $heure = date('H:i', strtotime($reservation['time']));

    $getActivity = $db->prepare('SELECT name FROM ACTIVITY WHERE id = :id');
    $getActivity->execute([
      'id' => $value['id_activity'],
    ]);
    $activity = $getActivity->fetch(PDO::FETCH_ASSOC);
  

    $subject = 'Rappel de réservation';
    $msgHTML = 'Bonjour, vous avez une réservation pour l\'activité ' . $activity['name'] . ' dans 2 jours à ' . $heure  . '. <br> Cordialement, <br> L\'équipe de Together&Stronger.';

    include_once '/home/php/includes/mailer.php';
  }  
}