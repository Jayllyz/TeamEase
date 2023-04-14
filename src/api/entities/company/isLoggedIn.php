<?php

function isLoggedIn($token) {

    require_once __DIR__ . '/home/php/src/includes/db.php';

    $getCompany = $db->prepare('SELECT * FROM COMPANY WHERE authToken = :token');

    $getCompany->execute(array(
        ':token' => $token
    ));

    $company = $getCompany->fetch(PDO::FETCH_ASSOC);

    if(!$company) {
        return false;
    }

    return true;
}