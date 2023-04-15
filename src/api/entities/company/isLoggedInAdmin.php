<?php

function isLoggedInAdmin($token)
{
  require_once '/home/php/src/includes/db.php';

  $query = $db->query('SELECT authToken FROM COMPANY WHERE siret=12345678901234');

  $isAdmin = $query->fetch(PDO::FETCH_COLUMN);

  if ($isAdmin !== $token) {
    return false;
  }
  return true;
}

?>
