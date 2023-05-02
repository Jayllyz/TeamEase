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
        'message' => 'Not logged as Admin',
      ],
    );
    return;
  }

  $companies = getAll('countAllCompany');

  if (!$companies) {
    echo jsonResponse(
      404,
      [],
      [
        'success' => false,
        'message' => 'No companies found',
      ],
    );
    return;
  }

  echo jsonResponse(
    200,
    [],
    [
      'success' => true,
      'message' => 'found companies',
      'data' => $companies,
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
