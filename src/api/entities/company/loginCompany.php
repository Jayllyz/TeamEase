<?php

function loginCompany($email, $password, $db)
{
  $getCompany = $db->prepare('SELECT * FROM COMPANY WHERE EMAIL = :email AND password = :password');

  $getCompany->execute([
    ':email' => $email,
    ':password' => hash('sha512', $password),
  ]);

  $company = $getCompany->fetch(PDO::FETCH_ASSOC);

  if (!$company) {
    return false;
  } else {
    $updateToken = $db->prepare('UPDATE COMPANY SET authToken = :token WHERE email = :email');

    $token = bin2hex(random_bytes(30));

    $updateToken->execute([
      ':token' => $token,
      ':email' => $email,
    ]);

    return $token;
  }

  return false;
}
