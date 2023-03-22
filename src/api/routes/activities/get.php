<?php

require_once __DIR__ . '/../../libraries/response.php';
require_once __DIR__ . '/../../entities/activites/get.php';

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
