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
          'error' => 'Bad word detected',
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
        'error' => 'Body is empty',
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
        'error' => 'Question is empty',
      ],
    );
    exit();
  }
}

function searchQuestionParameters($body, $i)
{
  $test = false;
  $parameters = [];
  while ($test != true) {
    $i++;
    if ($i > count($body) - 1) {
      checkAllQuestionParameters($parameters);
      break;
    }
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
}

function checkAllQuestionParameters($parameters)
{
  if (array_key_exists('disponible', $parameters)) {
    if (array_key_exists('day', $parameters)) {
      $result = getAllActivitiesByDay($parameters['day']);
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'data' => $result,
        ],
      );
    }
    if (array_key_exists('category', $parameters)) {
      $result = getAllActivitiesByCategory($parameters['category']);
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'activities' => $result,
          'count' => count($result),
        ],
      );
    }
    if (array_key_exists('location', $parameters)) {
      $result = getAllActivitiesByLocation($parameters['location']);
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'activities' => $result,
          'count' => count($result),
        ],
      );
    }
    if (array_key_exists('attendee', $parameters)) {
      $result = getAllActivitiesByMaxAttendee($parameters['attendee'], $parameters['attendeeCheck']);
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'activities' => $result,
          'count' => count($result),
        ],
      );
    }
    if (array_key_exists('price', $parameters)) {
      $result = getAllActivitiesPrice($parameters['price'], $parameters['priceLower']);
      echo jsonResponse(
        200,
        [],
        [
          'success' => true,
          'activities' => $result,
          'count' => count($result),
        ],
      );
    }
  } else {
    $result = getAllActivities();
    echo jsonResponse(
      200,
      [],
      [
        'success' => true,
        'data' => $result,
      ],
    );
  }
}

?>
