<?php

ob_start();

require_once '/home/php/api/libraries/body.php';
require_once '/home/php/api/libraries/response.php';
require_once '/home/php/api/entities/company/loginCompany.php';
require_once '/home/php/api/entities/company/getRights.php';
require_once '/home/php/api/entities/company/loginAttendee.php';

try {
  $body = getBody();
  $email = $body['email'];
  $password = $body['password'];

  require_once '/home/php/includes/db.php';

  $token = loginCompany($email, $password, $db);
  if (!$token) {
    $token = loginAttendee($email, $password, $db);
  }
  $rights = getRights($email, $password, $db);

  if (!$token) {
    echo jsonResponse(
      404,
      [],
      [
        'success' => false,
        'message' => 'User not found',
      ],
    );

    return;
  }

  echo jsonResponse(
    200,
    [],
    [
      'success' => true,
      'token' => $token,
      'data' => [
        'rights' => $rights,
      ],
    ],
  );
} catch (Exception $e) {
  echo jsonResponse(
    500,
    [],
    [
      'success' => false,
      'message' => $e->getMessage(),
    ],
  );
}
