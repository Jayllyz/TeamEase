<?php

ob_start();

require_once '/home/php/src/api/libraries/header.php';
require_once '/home/php/src/api/libraries/response.php';
require_once '/home/php/src/api/entities/company/logoutCompany.php';

try {
  $token = getAuthorizationHeader();

  echo $token;

  if (!logoutCompany($token) || !$token) {
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
      'message' => 'User logged out',
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
