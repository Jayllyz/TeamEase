<?php

function logoutCompany($token)
{
  require_once '/home/php/includes/db.php';

  $getCompany = $db->prepare('SELECT * FROM COMPANY WHERE authToken = :token');

  $getCompany->execute([
    ':token' => $token,
  ]);

  $company = $getCompany->fetch(PDO::FETCH_ASSOC);

  if (!$company) {
    return false;
  }

  $deleteToken = $db->prepare('UPDATE COMPANY SET authToken = NULL WHERE SIRET = :siret');
  return $deleteToken->execute([
    ':siret' => $company['siret'],
  ]);
}
