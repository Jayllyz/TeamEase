<?php

function loginCompany($email, $password) {

    require_once '/home/php/src/includes/db.php';

    $getCompany = $db->prepare('SELECT * FROM COMPANY WHERE EMAIL = :email AND password = :password');

    $getCompany->execute(array(
        ':email' => $email,
        ':password' => hash('sha512', $password)
    ));

    $company = $getCompany->fetch(PDO::FETCH_ASSOC);

    if(!$company) {
        return false;
        
    } else {
        $updateToken = $db->prepare("UPDATE COMPANY SET authToken = :token WHERE email = :email");

        $token = bin2hex(random_bytes(30));
        
        $updateToken->execute(array(
            ':token' => $token,
            ':email' => $email
        ));

        return $token;

    } 

    return false;
}