<?php
session_start();
include '../includes/db.php';
$oldpassword = $_POST['oldpassword'];
$newpassword = $_POST['newpassword'];
$newpasswordverif = $_POST['newpasswordverif'];
$rights = htmlspecialchars($_GET['rights']);
$id = htmlspecialchars($_GET['id']);



if (
  (isset($rights) && !empty($rights)) ||
  (isset($oldpassword) && !empty($oldpassword)) ||
  (isset($newpassword) && !empty($newpassword)) ||
  (isset($newpasswordverif) && !empty($newpasswordverif))
) {
  setcookie('rights', $rights, time() + 3600, '/');

  $compare = $db->prepare('SELECT password FROM PROVIDER WHERE id = :id');
  $compare->execute(
    [
      'id' => $id,
    ]
  );
  $compare = $compare->fetch();

  $oldpassword = hash('sha512', $oldpassword);
    $newpassword = hash('sha512', $newpassword);
    $newpasswordverif = hash('sha512', $newpasswordverif);

    if ($compare['password'] != $oldpassword) {
        header("location: ../modifyProviderPassword.php?id=$id&rights=$rights&message=Mot de passe incorrect !&type=danger");
        exit();
    }
    if ($newpassword != $newpasswordverif) {
        header("location: ../modifyProviderPassword.php?id=$id&rights=$rights&message=Les mots de passe ne correspondent pas entre eux !&type=danger");
        exit();
    }
    if ($oldpassword == $newpassword) {
        header("location: ../modifyProviderPassword.php?id=$id&rights=$rights&message=Le mot de passe choisi est le même que l'ancien !&type=danger");
        exit();
    }

    



  $update = $db->prepare(
    'UPDATE PROVIDER SET password = :password WHERE id = :id'
  );
  $update->execute([
    'password' => $newpassword,
    'id' => $id,
  ]);
  header('location: ../profile.php?message=Modification effectué !&type=success');
  exit();
}

