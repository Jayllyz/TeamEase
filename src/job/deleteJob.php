<?php
include '../includes/db.php';

$id = $_POST['id'];

if (isset($id) && !strlen($id) > 0 && !is_numeric($id)) {
  header(
    'location: ../job.php?message=L\'identifiant du métier est invalide !&valid=invalid&input=name&check=provider',
  );
  exit();
}

$req = $db->prepare('DELETE FROM OCCUPATION WHERE id = :id');
$req->execute(['id' => $id]);
$reponse = $req->fetch();
if ($reponse) {
  echo 'success';
}
exit();
