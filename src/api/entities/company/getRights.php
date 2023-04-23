<?php
function getRights($email, $password, $db)
{
  $getCompany = $db->prepare('SELECT rights FROM COMPANY WHERE email = :email AND password = :password');

  $getCompany->execute([
    ':email' => $email,
    ':password' => hash('sha512', $password),
  ]);

  $company = $getCompany->fetch(PDO::FETCH_ASSOC);

  if (!$company) {
    return false;
  }

  return $company['rights'];
}
