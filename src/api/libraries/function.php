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
}
?>
