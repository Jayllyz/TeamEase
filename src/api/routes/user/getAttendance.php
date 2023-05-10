<?php
ob_start();

require_once '/home/php/api/libraries/response.php';
require_once '/home/php/api/libraries/body.php';
require_once '/home/php/api/libraries/function.php';
require_once '/home/php/api/libraries/header.php';
require_once '/home/php/api/entities/user/isLoggedIn.php';

try {
  $token = getAuthorizationHeader();

  $body = getBody();

  $logged = isLoggedIn($token);

  if (!$logged) {
    echo jsonResponse(
      404,
      [],
      [
        'success' => false,
        'message' => 'Not logged in',
      ],
    );
    return;
  }

  $attendance = verifyAttendance($token);

  if (!$attendance) {
    echo jsonResponse(
      404,
      [],
      [
        'success' => false,
        'message' => 'No attendance found',
      ],
    );

    return;
  }

  echo jsonResponse(
    200,
    [],
    [
      'success' => true,
      'message' => 'Found attendance',
      'data' => $attendance,
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

?>
