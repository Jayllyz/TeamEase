<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/libraries/path.php';
require_once __DIR__ . '/libraries/method.php';
require_once __DIR__ . '/libraries/response.php';

if (isPath('activities')) {
  if (isGetMethod()) {
    require_once __DIR__ . '/routes/activities/get.php';
    die();
  }
}

// if (isPath('users/:user')) {
//   if (isDeleteMethod()) {
//     require_once __DIR__ . '/routes/users/delete.php';
//     die();
//   }

echo jsonResponse(
  404,
  [],
  [
    'success' => false,
    'message' => 'Route not found',
  ],
);
