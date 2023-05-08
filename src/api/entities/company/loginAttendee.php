<?php

function loginAttendee($email, $password, $db)
{
  $getAttendee = $db->prepare('SELECT * FROM ATTENDEE WHERE EMAIL = :email AND password = :password');

  $getAttendee->execute([
    ':email' => $email,
    ':password' => hash('sha512', $password),
  ]);

  $attendee = $getAttendee->fetch(PDO::FETCH_ASSOC);

  if (!$attendee) {
    return false;
  } else {
    $updateToken = $db->prepare('UPDATE ATTENDEE SET token = :token WHERE email = :email');

    $token = bin2hex(random_bytes(30));

    $updateToken->execute([
      ':token' => $token,
      ':email' => $email,
    ]);

    return $token;
  }

  return false;
}
