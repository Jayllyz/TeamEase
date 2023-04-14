<?php

ob_start();

require_once '/home/php/src/api/libraries/body.php';
require_once '/home/php/src/api/libraries/response.php';
require_once '/home/php/src/api/entities/company/loginCompany.php';

try {
    $body = getBody();
    $email = $body['email'];
    $password = $body['password'];

    $token = loginCompany($email, $password);

    if(!$token) {
        echo jsonResponse(404, [], [
            'success' => false,
            'message' => 'User not found'
        ]);

        return;
    }

    echo jsonResponse(200, [], [
        'success' => true,
        'token' => $token
    ]);
    
} catch (Exception $e) {
    echo jsonResponse(500, [], [
        'success' => false,
        'message' => $e->getMessage()
    ]);
}