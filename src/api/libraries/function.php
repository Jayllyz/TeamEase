<?php

function banWordFilter($string)
{
  $banWords = ['placeholder', 'badword', 'verybadword'];
  for ($i = 0; $i < count($banWords); $i++) {
    if (strpos($string, $banWords[$i])) {
      echo jsonResponse(
        400,
        [],
        [
          'success' => false,
          'error' => 'Je ne peux pas repondre a cette question car elle contient un mot interdit' . PHP_EOL,
        ],
      );
      exit();
    }
  }
}

function bodyCheck($body)
{
  if ($body == null) {
    echo jsonResponse(
      400,
      [],
      [
        'success' => false,
        'error' => 'Desole je n\'ai pas compris votre question' . PHP_EOL,
      ],
    );
    exit();
  }

  if ($body['question'] == null) {
    echo jsonResponse(
      400,
      [],
      [
        'success' => false,
        'error' => 'Desole je n\'ai pas compris votre question' . PHP_EOL,
      ],
    );
    exit();
  }
}

function searchQuestionHowMuchParameters($body, $i)
{
  $parameters = [];
  for ($i; $i < count($body); $i++) {
    if (preg_match('/\b(disponible)\b/i', $body[$i])) {
      $parameters += ['disponible' => true];
    }
    if (preg_match('/\b(lundi|mardi|mercredi|jeudi|vendredi|samedi|dimanche)\b/i', $body[$i], $match)) {
      $dayEnglish = [
        'lundi' => 'monday',
        'mardi' => 'tuesday',
        'mercredi' => 'wednesday',
        'jeudi' => 'thursday',
        'vendredi' => 'friday',
        'samedi' => 'saturday',
        'dimanche' => 'sunday',
      ];
      $parameters += ['day' => $dayEnglish[$match[0]]];
    }

    if (preg_match('/\b(cat(e|é)gorie)\b/i', $body[$i])) {
      $string = implode(' ', $body);

      if (preg_match('/\{(.+?)\}/', $string, $matches)) {
        $text_between_braces = $matches[1];
        $parameters += ['category' => $text_between_braces];
      }
    }
    if (preg_match('/\b(site(|s)|lieu(|x))\b/i', $body[$i])) {
      $string = implode(' ', $body);

      if (preg_match('/\{(.+?)\}/', $string, $matches)) {
        $text_between_braces = $matches[1];
        $parameters += ['location' => $text_between_braces];
      }
    }
    if (preg_match('/\b(max(|imum))\b/i', $body[$i])) {
      $string = implode(' ', $body);

      if (preg_match('/\b(inf(e|é)rieur(|s))\b/i', $string)) {
        if (preg_match('/\{(.+?)\}/', $string, $matches)) {
          $text_between_braces = $matches[1];
          $parameters += ['attendee' => $text_between_braces];
          $parameters += ['attendeeLower' => true];
        }
      } elseif (preg_match('/\b(sup(e|é)rieur(|s))\b/i', $string)) {
        if (preg_match('/\{(.+?)\}/', $string, $matches)) {
          $text_between_braces = $matches[1];
          $parameters += ['attendee' => $text_between_braces];
          $parameters += ['attendeeLower' => false];
        }
      }
    }
    if (preg_match('/\b(prix)\b/i', $body[$i])) {
      $string = implode(' ', $body);

      if (preg_match('/\b(inf(e|é)rieur(|s))\b/i', $string)) {
        if (preg_match('/\{(.+?)\}/', $string, $matches)) {
          $text_between_braces = $matches[1];
          $parameters += ['price' => $text_between_braces];
          $parameters += ['priceLower' => true];
        }
      } elseif (preg_match('/\b(sup(e|é)rieur(|s))\b/i', $string)) {
        if (preg_match('/\{(.+?)\}/', $string, $matches)) {
          $text_between_braces = $matches[1];
          $parameters += ['price' => $text_between_braces];
          $parameters += ['priceLower' => false];
        }
      }
    }
  }
  checkAllQuestionHowMuchParameters($parameters);
}

function checkAllQuestionHowMuchParameters($parameters)
{
  if (array_key_exists('disponible', $parameters)) {
    if (array_key_exists('day', $parameters)) {
      $result = getAllActivitiesByDay($parameters['day']);
      $frenchDay = [
        'monday' => 'lundi',
        'tuesday' => 'mardi',
        'wednesday' => 'mercredi',
        'thursday' => 'jeudi',
        'friday' => 'vendredi',
        'saturday' => 'samedi',
        'sunday' => 'dimanche',
      ];
      $message =
        "J'ai trouve " . count($result) . ' activites disponibles le ' . $frenchDay[$parameters['day']] . ':' . PHP_EOL;
      if (count($result) == 0) {
        $message = "Il n'y a pas d'activites disponibles le " . $frenchDay[$parameters['day']] . PHP_EOL;
      } else {
        foreach ($result as $activity) {
          $message .= '- ' . $activity['name'] . PHP_EOL;
        }
      }
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'message' => $message,
        ],
      );
    }
    if (array_key_exists('category', $parameters)) {
      $result = getAllActivitiesByCategory($parameters['category']);
      if (count($result) == 0) {
        $message = "Il n'y a pas d'activites dans la categorie " . $parameters['category'] . PHP_EOL;
      } else {
        $message =
          "J'ai trouve " . count($result) . ' activites dans la categorie ' . $parameters['category'] . ':' . PHP_EOL;
        foreach ($result as $activity) {
          $message .= '- ' . $activity['name'] . PHP_EOL;
        }
      }
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'message' => $message,
        ],
      );
    }
    if (array_key_exists('location', $parameters)) {
      $result = getAllActivitiesByLocation($parameters['location']);
      if (count($result) == 0) {
        $message = "Il n'y a pas d'activites au " . $parameters['location'] . PHP_EOL;
      } else {
        $message = "J'ai trouve " . count($result) . ' activites au ' . $parameters['location'] . ':' . PHP_EOL;
        foreach ($result as $activity) {
          $message .= '- ' . $activity['name'] . PHP_EOL;
        }
      }
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'message' => $message,
        ],
      );
    }
    if (array_key_exists('attendee', $parameters)) {
      $result = getAllActivitiesByMaxAttendee($parameters['attendee'], $parameters['attendeeCheck']);
      if (count($result) == 0) {
        $message = "Il n'y a pas d'activites avec un maximum de " . $parameters['attendee'] . ' participants' . PHP_EOL;
      } else {
        $message =
          "J'ai trouve " .
          count($result) .
          ' activites avec un maximum de ' .
          $parameters['attendee'] .
          ' participants:' .
          PHP_EOL;
        foreach ($result as $activity) {
          $message .= '- ' . $activity['name'] . PHP_EOL;
        }
      }
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'message' => $message,
        ],
      );
    }
    if (array_key_exists('price', $parameters)) {
      $result = getAllActivitiesPrice($parameters['price'], $parameters['priceLower']);
      if (count($result) == 0) {
        $message =
          "Il n'y a pas d'activites avec un prix " .
          ($parameters['priceLower'] ? 'inférieur' : 'supérieur') .
          ' à ' .
          $parameters['price'] .
          '€' .
          PHP_EOL;
      } else {
        $message =
          "J'ai trouve " .
          count($result) .
          ' activites avec un prix ' .
          ($parameters['priceLower'] ? 'inférieur' : 'supérieur') .
          ' à ' .
          $parameters['price'] .
          '€:' .
          PHP_EOL;
        foreach ($result as $activity) {
          $message .= '- ' . $activity['name'] . PHP_EOL;
        }
      }
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'message' => $message,
        ],
      );
    }
  } else {
    $result = getAllActivities();
    if (count($result) == 0) {
      $message = "Il n'y a pas d'activites disponibles" . PHP_EOL;
    } else {
      $message = "J'ai trouve " . count($result) . ' activites:' . PHP_EOL;
      foreach ($result as $activity) {
        $message .= '- ' . $activity['name'] . PHP_EOL;
      }
    }
    echo jsonResponse(
      200,
      [],
      [
        'success' => true,
        'message' => $message,
      ],
    );
  }
}

function searchQuestionHowParameters($body, $i)
{
  $parameters = [];
  for ($i; $i < count($body); $i++) {
    if (preg_match('/\b(voi(s|r))\b/i', $body[$i])) {
      $parameters += ['see' => true];
    }
    if (preg_match('/\b(filte(|r))\b/i', $body[$i])) {
      $parameters += ['filter' => true];
    }
    if (preg_match('/\b(inscription|inscri(s|t|re))\b/i', $body[$i])) {
      $parameters += ['register' => true];
    }
    if (preg_match('/\b(connexion|connecte(|r))\b/i', $body[$i])) {
      $parameters += ['connect' => true];
    }
    if (preg_match('/\b(r(é|e)servation(|s)|r(é|e)serv(é|e)(|r))\b/i', $body[$i])) {
      $parameters += ['reservation' => true];
    }
    if (preg_match('/\b((re|)cherche(|r))\b/i', $body[$i])) {
      $parameters += ['search' => true];
    }
    if (preg_match('/\b(activit(e|é))\b/i', $body[$i])) {
      $parameters += ['activity' => true];
    }
    if (preg_match('/\b(information(|s))\b/i', $body[$i])) {
      $parameters += ['information' => true];
    }
  }
  checkAllQuestionHowParameters($parameters);
}

function checkAllQuestionHowParameters($parameters)
{
  if (array_key_exists('see', $parameters)) {
    if (array_key_exists('information', $parameters)) {
      if (array_key_exists('activity', $parameters)) {
        echo jsonResponse(
          200,
          [],
          [
            'success' => true,
            'message' =>
              "Pour voir les informations d'une activité, il faut cliquer sur l'activité que vous souhaitez voir. Vous pouvez voir ensuite informations de l'activité sur la page.",
          ],
        );
        exit();
      }
      if (array_key_exists('reservation', $parameters)) {
        echo jsonResponse(
          200,
          [],
          [
            'success' => true,
            'message' =>
              "Pour voir les informations sur vos reservations vous pouvez vous rendre sur la page \"Réservation\".",
          ],
        );
        exit();
      }
      echo jsonResponse(
        200,
        [],
        [
          'success' => false,
        ],
      );
      exit();
    }
    if (array_key_exists('activity', $parameters)) {
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'message' => "Pour voir les activités, vous pouvez vous rendre sur la page \"Catalogue\".",
        ],
      );
    }
    if (array_key_exists('reservation', $parameters)) {
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'message' => "Pour voir vos reservations vous pouvez vous rendre sur la page \"Réservation\".",
        ],
      );
      exit();
    }
    echo jsonResponse(
      200,
      [],
      [
        'success' => false,
      ],
    );
    exit();
  }
  if (array_key_exists('search', $parameters) || array_key_exists('filter', $parameters)) {
    if (array_key_exists('activity', $parameters) && array_key_exists('search', $parameters)) {
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'message' =>
            "Pour rechercher une activité, vous pouvez utiliser la barre de recherche sur le haut de la page, ensuite taper le nom de l'activité que vous souhaitez rechercher ou vous rendre sur la page \"Catalogue\" et utiliser les filtres pour retrouvez votre activité.",
        ],
      );
      exit();
    }
    if (array_key_exists('activity', $parameters) && array_key_exists('search', $parameters)) {
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'message' =>
            "Pour filtrer les activités, vous pouvez vous rendre sur la page \"Catalogue\" et utiliser les filtres pour restreindre vos recherches.",
        ],
      );
      exit();
    }
    echo jsonResponse(
      200,
      [],
      [
        'success' => false,
      ],
    );
    exit();
  }
  if (array_key_exists('reservation', $parameters)) {
    echo jsonResponse(
      200,
      [],
      [
        'success' => true,
        'message' =>
          "Pour réserver une activité, vous pouvez vous devez être sur la page de l'activité que souhaitez réserver, ensuite vous pouvez réserver l'activité en cliquant sur le bouton \"Réserver\", cela vous amènera vers un formulaire de réservation, vous devez remplir ce formulaire pour réserver l'activité. Vous pourrez retrouver votre réservation ou l'annuler sur la page \"Réservation\"",
      ],
    );
    exit();
  }
  if (array_key_exists('register', $parameters)) {
    echo jsonResponse(
      200,
      [],
      [
        'success' => true,
        'message' =>
          "Pour vous inscrire en cliquant sur le bouton \"S'inscrire\" en haut de la page, vous pouvez vous inscrire en tant qu'entreprise ou en tant que préstataire selon vos dispositions, vous pouvez choisir cela en changéant de formulaire d'inscription.",
      ],
    );
    exit();
  }
  if (array_key_exists('connect', $parameters)) {
    echo jsonResponse(
      200,
      [],
      [
        'success' => true,
        'message' =>
          "Pour vous connecter à votre compte, vous pouvez vous rendre sur la page \"Connexion\" en appuyant sur le bouton \"Se connecter\" en haut de la page. Si vous n'avez pas de compte vous pouvez vous inscrire en appuyant sur le bouton \"S'inscrire\" en haut de la page.",
      ],
    );
    exit();
  }
  echo jsonResponse(
    200,
    [],
    [
      'success' => false,
    ],
  );
  exit();
}

function searchQuestionWhyParameters($body, $i)
{
  $parameters = [];
  for ($i; $i < count($body); $i++) {
    if (preg_match('/\b(choisir)\b/i', $body[$i])) {
      $parameters += ['choice' => true];
    }
    if (preg_match('/\b(teambuilding)\b/i', $body[$i])) {
      $parameters += ['teambuilding' => true];
    }
    if (preg_match('/\b(votre|teamease)\b/i', $body[$i])) {
      $parameters += ['teamease' => true];
    }
  }
  checkAllQuestionWhyParameters($parameters);
}

function checkAllQuestionWhyParameters($parameters)
{
  if (array_key_exists('choice', $parameters)) {
    if (array_key_exists('teamease', $parameters)) {
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'message' =>
            "Nous avons choisi de créer TeamEase car nous avons constaté que les entreprises n'avaient pas de moyen simple et efficace de trouver des activités de teambuilding pour leurs employés. Nous avons donc décidé de créer une plateforme qui permet aux entreprises de trouver des activités de teambuilding et aux prestataires de proposer leurs activités.",
        ],
      );
      exit();
    }
    if (array_key_exists('teambuilding', $parameters)) {
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'message' =>
            "Un team building réussi c'est l'occasion de rapprocher les collaborateurs, mais aussi de les amener dans un cadre propice à l'innovation et à la créativité. De plus, des personnes qui ont l'habitude de travailler en équipe apportent davantage en évoluant ensemble que la somme de leurs individualités réunies.",
        ],
      );
      exit();
    }
  }
  echo jsonResponse(
    200,
    [],
    [
      'success' => false,
    ],
  );
  exit();
}

function searchQuestionWhatParameters($body, $i)
{
  $parameters = [];
  for ($i; $i < count($body); $i++) {
    if (preg_match('/\b(teamease)\b/i', $body[$i])) {
      $parameters += ['teamease' => true];
    }
    if (preg_match('/\b(but(|s)|objectif(|s))\b/i', $body[$i])) {
      $parameters += ['goal' => true];
    }
    if (preg_match('/\b(teambuilding)\b/i', $body[$i])) {
      $parameters += ['teambuilding' => true];
    }
  }
  checkAllQuestionWhatParameters($parameters);
}

function checkAllQuestionWhatParameters($parameters)
{
  if (array_key_exists('teamease', $parameters)) {
    if (array_key_exists('goal', $parameters)) {
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'message' =>
            'Notre but est de permettre aux entreprises de trouver des activités de teambuilding pour leurs employés et aux prestataires de proposer leurs activités.',
        ],
      );
      exit();
    }
    echo jsonResponse(
      200,
      [],
      [
        'success' => true,
        'message' =>
          'TeamEase est une plateforme qui permet aux entreprises de trouver des activités de teambuilding pour leurs employés et aux prestataires de proposer leurs activités.',
      ],
    );
    exit();
  }
  if (array_key_exists('teambuilding', $parameters)) {
    echo jsonResponse(
      200,
      [],
      [
        'success' => true,
        'message' =>
          'Le teambuilding est un ensemble d’activités qui permettent de renforcer les liens entre les collaborateurs et de développer leur esprit d’équipe.',
      ],
    );
    exit();
  }
  echo jsonResponse(
    200,
    [],
    [
      'success' => false,
    ],
  );
  exit();
}

function getReservationWithToken($token)
{
  include '/home/php/includes/db.php';

  $getSiret = $db->prepare('SELECT siret FROM COMPANY WHERE authToken = :token');
  $getSiret->execute(['token' => $token]);
  $siret = $getSiret->fetch();

  $getAllReservation = $db->prepare(
    'SELECT RESERVATION.*, maxAttendee, duration, priceAttendee, ACTIVITY.name as nameActivity, description, ROOM.name as nameRoom, address, LOCATION.name as city FROM RESERVATION INNER JOIN ACTIVITY ON RESERVATION.id_activity = ACTIVITY.id INNER JOIN ROOM ON ROOM.id = ACTIVITY.id_room INNER JOIN LOCATION ON ROOM.id_location = LOCATION.id WHERE RESERVATION.siret = :siret',
  );
  $getAllReservation->execute(['siret' => $siret['siret']]);
  $reservations = $getAllReservation->fetchAll(PDO::FETCH_ASSOC);

  return $reservations;
}

function getAll($table)
{
  include '/home/php/includes/db.php';

  if ($table === 'activity') {
    $query = $db->query('SELECT * FROM ACTIVITY');
    $activities = $query->fetchAll(PDO::FETCH_ASSOC);

    return $activities;
  }

  if ($table === 'activityPublic') {
    $query = $db->query('SELECT * FROM ACTIVITY WHERE status = 1');
    $activities = $query->fetchAll(PDO::FETCH_ASSOC);

    return $activities;
  }

  if ($table === 'provider') {
    $query = $db->query('SELECT * FROM PROVIDER');
    $providers = $query->fetchAll(PDO::FETCH_ASSOC);

    return $providers;
  }

  if ($table === 'countActivityByMonth') {
    $query = $db->query(
      'SELECT COUNT(id) as count, DATE_FORMAT(date, \'%Y-%m\') AS newDate FROM RESERVATION GROUP BY newDate ORDER BY newDate ASC',
    );
    $countActivityByDate = $query->fetchAll(PDO::FETCH_ASSOC);

    return $countActivityByDate;
  }

  if ($table === 'topActivity') {
    $query = $db->query(
      'SELECT ACTIVITY.name, COUNT(RESERVATION.id) AS count FROM RESERVATION INNER JOIN ACTIVITY ON RESERVATION.id_activity = ACTIVITY.id GROUP BY ACTIVITY.name ORDER BY count DESC LIMIT 10',
    );
    $topActivity = $query->fetchAll(PDO::FETCH_ASSOC);

    return $topActivity;
  }

  if ($table === 'companyPaid') {
    $query = $db->query(
      'SELECT SUM(amount) as amount, COMPANY.companyName FROM INVOICE INNER JOIN RESERVATION ON RESERVATION.id = INVOICE.id_reservation INNER JOIN COMPANY ON RESERVATION.siret = COMPANY.siret GROUP BY companyName',
    );
    $companyPaid = $query->fetchAll(PDO::FETCH_ASSOC);

    return $companyPaid;
  }

  if ($table === 'topCompanyPaid') {
    $query = $db->query(
      'SELECT SUM(amount) as amount, COMPANY.companyName FROM INVOICE INNER JOIN RESERVATION ON RESERVATION.id = INVOICE.id_reservation INNER JOIN COMPANY ON RESERVATION.siret = COMPANY.siret GROUP BY companyName ORDER BY amount DESC LIMIT 5',
    );
    $topCompanyPaid = $query->fetchAll(PDO::FETCH_ASSOC);

    return $topCompanyPaid;
  }

  if ($table === 'providerActivity') {
    $query = $db->query(
      'SELECT PROVIDER.firstName, PROVIDER.lastName, COUNT(id_provider) as count FROM PROVIDER INNER JOIN HISTORY ON PROVIDER.id = HISTORY.id_provider GROUP BY id_provider',
    );
    $providerActivity = $query->fetchAll(PDO::FETCH_ASSOC);

    return $providerActivity;
  }

  if ($table === 'topProviderActivity') {
    $query = $db->query(
      'SELECT PROVIDER.firstName, PROVIDER.lastName, COUNT(id_provider) as count FROM PROVIDER INNER JOIN HISTORY ON PROVIDER.id = HISTORY.id_provider GROUP BY id_provider ORDER BY count DESC LIMIT 5',
    );
    $topProviderActivity = $query->fetchAll(PDO::FETCH_ASSOC);

    return $topProviderActivity;
  }

  if ($table === 'countAllActivity') {
    $query = $db->query('SELECT COUNT(id) as count FROM ACTIVITY');
    $countAllActivity = $query->fetchAll(PDO::FETCH_ASSOC);

    return $countAllActivity;
  }

  if ($table === 'countAllCompany') {
    $query = $db->query('SELECT COUNT(siret) as count FROM COMPANY');
    $countAllCompany = $query->fetchAll(PDO::FETCH_ASSOC);

    return $countAllCompany;
  }

  if ($table === 'countAllReservations') {
    $query = $db->query('SELECT COUNT(id) as count FROM RESERVATION');
    $countAllReservations = $query->fetchAll(PDO::FETCH_ASSOC);

    return $countAllReservations;
  }
}

function getReservationUsers()
{
  include '/home/php/includes/db.php';
  require_once '/home/php/api/libraries/parameters.php';

  $id = getParametersForRoute('/api/api.php/user/getReservationUsers/:id');

  $query = $db->prepare(
    'SELECT firstName, lastName, email FROM ATTENDEE INNER JOIN RESERVED ON RESERVED.id_attendee = ATTENDEE.id WHERE RESERVED.id_reservation = :id',
  );
  $query->execute(['id' => $id['id']]);
  $users = $query->fetchAll(PDO::FETCH_ASSOC);

  return $users;
}

function getUserActivities($token)
{
  include '/home/php/includes/db.php';

  $getId = $db->prepare('SELECT id FROM ATTENDEE WHERE token = :token');
  $getId->execute(['token' => $token]);
  $id = $getId->fetch();

  $query = $db->prepare(
    'SELECT RESERVATION.*, maxAttendee, duration, priceAttendee, ACTIVITY.name as nameActivity, description, ROOM.name as nameRoom, address, LOCATION.name as city FROM RESERVATION INNER JOIN ACTIVITY ON RESERVATION.id_activity = ACTIVITY.id INNER JOIN ROOM ON ROOM.id = ACTIVITY.id_room INNER JOIN LOCATION ON ROOM.id_location = LOCATION.id INNER JOIN RESERVED ON RESERVED.id_reservation = RESERVATION.id WHERE RESERVED.id_attendee = :id',
  );
  $query->execute(['id' => $id['id']]);
  $reservations = $query->fetchAll(PDO::FETCH_ASSOC);

  return $reservations;
}

function getMessages()
{
  include '/home/php/includes/db.php';
  require_once '/home/php/api/libraries/parameters.php';

  $id = getParametersForRoute('/api/api.php/chat/getChat/:id');

  $query = $db->prepare(
    'SELECT IF(DATE(MESSAGE.date) = CURDATE(), DATE_FORMAT(MESSAGE.date, \'%H:%i\'), DATE_FORMAT(MESSAGE.date, \'%m-%d\')) AS date, MESSAGE.content, ATTENDEE.firstName, ATTENDEE.lastName FROM MESSAGE INNER JOIN ATTENDEE ON MESSAGE.id_attendee = ATTENDEE.id WHERE id_reservation = :id ORDER BY date ASC',
  );
  $query->execute(['id' => $id['id']]);
  $messages = $query->fetchAll(PDO::FETCH_ASSOC);

  return $messages;
}

function sendMessage($message, $token)
{
  include '/home/php/includes/db.php';
  require_once '/home/php/api/libraries/parameters.php';

  $id = getParametersForRoute('/api/api.php/chat/sendMessage/:id');

  $query = $db->prepare(
    'INSERT INTO MESSAGE (id_attendee, id_reservation, content, date) VALUES ((SELECT id FROM ATTENDEE WHERE token = :token), :id_reservation, :message, now())',
  );
  $query->execute([
    'token' => $token,
    'id_reservation' => $id['id'],
    'message' => $message,
  ]);

  $query = $db->prepare('SELECT firstName, lastName FROM ATTENDEE WHERE token = :token');
  $query->execute(['token' => $token]);
  $user = $query->fetch(PDO::FETCH_ASSOC);

  return $user;
}

function verifyAttendance($token)
{
  include '/home/php/includes/db.php';

  $query = $db->prepare(
    'SELECT RESERVATION.id, ATTENDEE.firstName, ATTENDEE.lastName, RESERVED.present, DATE_FORMAT(RESERVATION.time,\'%H:%i\') as startTime, ACTIVITY.duration as endTime, RESERVATION.date FROM ATTENDEE INNER JOIN RESERVED ON RESERVED.id_attendee = ATTENDEE.id INNER JOIN RESERVATION ON RESERVATION.id = RESERVED.id_reservation INNER JOIN ACTIVITY ON ACTIVITY.id = RESERVATION.id_activity WHERE ATTENDEE.token = :token AND RESERVED.present = 0 AND RESERVATION.date = CURDATE()',
  );
  $query->execute(['token' => $token]);
  $user = $query->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return false;
  }

  $hours = floor($user['endTime'] / 60);
  $minutes = $user['endTime'] % 60;

  $user['endTime'] = date('H:i', mktime($hours, $minutes));

  $startTime = strtotime($user['startTime']);
  $endTime = strtotime($user['endTime']);
  $endTime = $startTime + $endTime;

  $hours = floor($endTime / 3600);
  $minutes = floor(($endTime % 3600) / 60);

  $user['endTime'] = date('H:i', mktime($hours, $minutes));

  date_default_timezone_set('Europe/Paris');

  $currentTime = time();

  $startTime = strtotime($user['startTime']);
  $endTime = strtotime($user['endTime']);

  if ($currentTime >= $startTime && $currentTime <= $endTime) {
    return $user;
  } else {
    return false;
  }
}

function validateAttendance($token)
{
  include '/home/php/includes/db.php';
  require_once '/home/php/api/libraries/parameters.php';

  $id = getParametersForRoute('/api/api.php/user/validateAttendance/:id');

  $query = $db->prepare(
    'UPDATE RESERVED SET present = 1 WHERE id_attendee = (SELECT id FROM ATTENDEE WHERE token = :token) AND id_reservation = :id',
  );
  $query->execute(['token' => $token, 'id' => $id['id']]);

  return true;
}
