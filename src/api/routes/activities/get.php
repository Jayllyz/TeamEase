<?php

require_once __DIR__ . '/../../libraries/response.php';
require_once '/home/php/src/api/libraries/body.php';
include '/home/php/src/api/entities/activities/get.php';
include '/home/php/src/api/libraries/function.php';

try {
  $body = getBody();

  banWordFilter($body['question']);

  exit();
  echo jsonResponse(
    200,
    [],
    [
      'success' => true,
      'activities' => $getActivities,
    ],
  );
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
