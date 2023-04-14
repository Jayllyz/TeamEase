<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/libraries/path.php';
require_once __DIR__ . '/libraries/method.php';
require_once __DIR__ . '/libraries/response.php';


if(isPath('auth/login')) {
  if(isPostMethod()) {
    require_once __DIR__ . '/routes/auth/login.php';
    die();
  }
}

if (isPath('auth/logout')) {
  if (isPostMethod()) {
    require_once __DIR__ . '/routes/auth/logout.php';
    die();
  }
}

if (isPath('company')) {
  if (isGetMethod()) {
    require_once __DIR__ . '/routes/company/get.php';
    die();
  }
}

if (isPath('activities')) {
  if (isGetMethod()) {
    require_once __DIR__ . '/routes/activities/get.php';
    die();
  }
}


echo jsonResponse(
  404,
  [],
  [
    'success' => false,
    'message' => 'Route not found',
  ],
);