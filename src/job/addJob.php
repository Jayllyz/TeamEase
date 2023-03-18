<?php
include '../includes/db.php';
$name = $_POST['name'];
$salary = $_POST['salary'];

if (isset($name) && !strlen($name) > 0) {
  header('location: ../job.php?message=Le nom du métier est invalide !&valid=invalid&input=name&check=provider');
  exit();
}

if (isset($salary) && !strlen($salary) > 0 && !is_numeric($salary)) {
  header('location: ../job.php?message=Le salaire est invalide !&valid=invalid&input=firstname&check=provider');
  exit();
}

$req = $db->prepare('SELECT id FROM OCCUPATION WHERE name = :name');
$req->execute([
  'name' => $name,
]);

$reponse = $req->fetch();

if ($reponse) {
  header('location: ../job.php?message=Le métier existe déja !&valid=invalid&input=job&check=provider');
  exit();
} else {
  $req = $db->prepare('INSERT INTO OCCUPATION (name, salary) VALUES (:name, :salary)');
  $req->execute([
    'name' => $name,
    'salary' => $salary,
  ]);
  $reponse = $req->fetch();
  if ($reponse) {
    echo 'success';
  }
  exit();
}
