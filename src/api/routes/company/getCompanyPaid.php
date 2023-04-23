<?php
ob_start();

require_once '/home/php/api/libraries/response.php';
require_once '/home/php/api/libraries/body.php';
require_once '/home/php/api/libraries/function.php';
require_once '/home/php/api/libraries/header.php';
require_once '/home/php/api/entities/company/isLoggedInAdmin.php';

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
    $spendings = getAll('companyPaid');

    if (!$spendings) {
      echo jsonResponse(
        404,
        [],
        [
          'success' => false,
          'message' => 'No spendings found',
        ],
      );
      return;
    }

    echo jsonResponse(
      200,
      [],
      [
        'success' => true,
        'message' => 'found company spending',
        'data' => $spendings,
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
