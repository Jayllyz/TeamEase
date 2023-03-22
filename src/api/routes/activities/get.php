<?php

ob_start();

require_once '/home/php/src/api/libraries/response.php';
require_once '/home/php/src/api/libraries/body.php';
include '/home/php/src/api/entities/activities/get.php';
include '/home/php/src/api/libraries/function.php';

try {
  $body = getBody();
  bodyCheck($body);
  banWordFilter($body['question']);

  $body['question'] = explode(' ', $body['question']);

  for ($i = 0; $i < count($body['question']); $i++) {
    if (preg_match('/[cC][oO0][mM][bB6][iI1][eE3][nN]/', $body['question'][$i])) {
      searchQuestionParameters($body['question'], $i);
    }
  }
  exit();
} catch (Exception $exception) {
  echo jsonResponse(
    500,
    [],
    [
      'success' => false,
      'error' => $exception->getMessage(),
    ],
  );
}
