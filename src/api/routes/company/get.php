<?php

ob_start();

require_once '/home/php/src/api/libraries/response.php';
require_once '/home/php/src/api/libraries/body.php';
require_once '/home/php/src/api/libraries/function.php';
require_once '/home/php/src/api/libraries/header.php';
require_once '/home/php/src/api/entities/company/isLoggedIn.php';


try {

    $token = getAuthorizationHeader();

    if(!isLoggedIn($token)) {
        echo jsonResponse(404, [], [
            'success' => false,
            'message' => 'Not logged in'
        ]);

        return;
    }

    $body = getBody();

    $reservations = getReservationWithToken($token);  
    
    if(!$reservations) {
        echo jsonResponse(404, [], [
            'success' => false,
            'message' => 'No reservation found'
        ]);

        return;
    }

    echo jsonResponse(200, [], [
        'success' => true,
        'message' => 'Reservation found',
        'data' => $reservations
    ]);

} catch (Exception $e) {
    echo jsonResponse(500, [], [
        'success' => false,
        'message' => $e->getMessage()
    ]);
}