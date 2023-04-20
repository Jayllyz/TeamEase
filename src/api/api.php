<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/libraries/path.php';
require_once __DIR__ . '/libraries/method.php';
require_once __DIR__ . '/libraries/response.php';

if (isPath('auth/login')) {
  if (isPostMethod()) {
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

if (isPath('company/paid')) {
  if (isGetMethod()) {
    require_once __DIR__ . '/routes/company/getCompanyPaid.php';
    die();
  }
}

if (isPath('company/topPaid')) {
  if (isGetMethod()) {
    require_once __DIR__ . '/routes/company/getTopCompanyPaid.php';
    die();
  }
}

if (isPath('company')) {
  if (isGetMethod()) {
    require_once __DIR__ . '/routes/company/get.php';
    die();
  }
}

if (isPath('activities/allActivities')) {
  if (isGetMethod()) {
    require_once __DIR__ . '/routes/activities/getAllActivities.php';
    die();
  }
}

if (isPath('activities/countActivitiesByMonth')) {
  if (isGetMethod()) {
    require_once __DIR__ . '/routes/activities/getActivitiesCountByMonth.php';
    die();
  }
}

if (isPath('activities/topActivities')) {
  if (isGetMethod()) {
    require_once __DIR__ . '/routes/activities/getTopActivities.php';
    die();
  }
}

if (isPath('activities')) {
  if (isGetMethod()) {
    require_once __DIR__ . '/routes/activities/get.php';
    die();
  }
}

if (isPath('provider/animate')) {
  if (isGetMethod()) {
    require_once __DIR__ . '/routes/provider/getAnimate.php';
    die();
  }
}

if (isPath('provider/topAnimate')) {
  if (isGetMethod()) {
    require_once __DIR__ . '/routes/provider/getTopAnimate.php';
    die();
  }
}

if (isPath('provider')) {
  if (isGetMethod()) {
    require_once __DIR__ . '/routes/provider/get.php';
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
