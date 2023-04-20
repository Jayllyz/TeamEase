<?php
ob_start();

require_once '/home/php/src/api/libraries/response.php';
require_once '/home/php/src/api/libraries/body.php';
require_once '/home/php/src/api/libraries/function.php';
require_once '/home/php/src/api/libraries/header.php';
require_once '/home/php/src/api/entities/company/isLoggedInAdmin.php';

try {
  $token = getAuthorizationHeader();

  $body = getBody();

  $isAdmin = isLoggedInAdmin($token);

  if (!$isAdmin) {
    echo jsonResponse(
      404,
      [],
      [
        'success' => false,
        'message' => 'Not logged in as admin',
      ],
    );
    return;
  } else {
    $activities = getAll('topActivity');

    if (!$activities) {
      echo jsonResponse(
        404,
        [],
        [
          'success' => false,
          'message' => 'No activities found',
        ],
      );
      return;
    }

    echo jsonResponse(
      200,
      [],
      [
        'success' => true,
        'message' => 'found activities',
        'data' => $activities,
      ],
    );
  }
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
