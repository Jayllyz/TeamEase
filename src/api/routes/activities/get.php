<?php

require_once __DIR__ . '/../../libraries/response.php';
include '/home/php/src/api/entities/activities/get.php';

try {
  $getActivities = getAllActivities();

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
