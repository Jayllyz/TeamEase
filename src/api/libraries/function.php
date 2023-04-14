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



function getReservationWithToken($token){

  include '/home/php/src/includes/db.php';

  $getSiret = $db->prepare('SELECT siret FROM COMPANY WHERE authToken = :token');
  $getSiret->execute(['token' => $token]);
  $siret = $getSiret->fetch();

  $getAllReservation = $db->prepare('SELECT * FROM RESERVATION WHERE siret = :siret');
  $getAllReservation->execute(['siret' => $siret['siret']]);
  $reservations = $getAllReservation->fetchAll(PDO::FETCH_ASSOC);

  return $reservations;
}

?>