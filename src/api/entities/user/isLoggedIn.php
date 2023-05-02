<?php

function isLoggedIn($token)
{
  require_once '/home/php/includes/db.php';

  $getUser = $db->prepare('SELECT * FROM ATTENDEE WHERE token = :token');

  $getUser->execute([
    ':token' => $token,
  ]);

  $user = $getUser->fetch(PDO::FETCH_ASSOC);

  if (!$user) {
    return false;
  }

  return true;
}

?>