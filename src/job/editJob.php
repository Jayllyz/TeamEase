<?php
include '../includes/db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$salary = $_POST['salary'];

if (isset($id) && !strlen($id) > 0 && !is_numeric($id)) {
  echo 'L\'identifiant du métier est invalide !';
  exit();
}

if (isset($name) && !strlen($name) > 0) {
  echo 'Le nom du métier est invalide !';
  exit();
}

if (isset($salary) && !strlen($salary) > 0 && !is_numeric($salary)) {
  echo 'Le salaire est invalide !';
  exit();
}

$req = $db->prepare('UPDATE OCCUPATION SET name = :name, salary = :salary WHERE id = :id');
$req->execute([
  'name' => $name,
  'salary' => $salary,
  'id' => $id,
]);
$reponse = $req->fetch();
if ($reponse) {
  echo 'success';
}
exit();
