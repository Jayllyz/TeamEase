<?php
session_start();
include '../includes/db.php';

$lastName = $_POST['lastName'];
$firstName = $_POST['firstName'];
$email = $_POST['email'];
$job = $_POST['job'];
$rights = $_POST['rights'];
$salary = $_POST['salary'];
$id = htmlspecialchars($_GET['id']);

if (isset($email) && !empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header('location: ../update.php?message=Email invalide !&type=danger');
  exit();
}

if (
  (isset($lastName) && !empty($lastName)) ||
  (isset($firstName) && !empty($firstName)) ||
  (isset($rights) && !empty($rights)) ||
  (isset($companyName) && !empty($companyName)) ||
  (isset($email) && !empty($email)) ||
  (isset($job) && !empty($job)) ||
  (isset($salary) && !empty($salary))
) {
  setcookie('rights', $rights, time() + 3600, '/');

  $update = $db->prepare(
    'UPDATE PROVIDER SET email = :email, lastName = :lastName, firstName = :firstName, rights = :rights, id_occupation = :job, salary = :salary WHERE id = :id',
  );
  $update->execute([
    'email' => $email,
    'lastName' => $lastName,
    'firstName' => $firstName,
    'rights' => $rights,
    'job' => $job,
    'salary' => $salary,
    'id' => $id,
  ]);
  header('location: ../admin.php?message=Modification effectué !&type=success');
  exit();
}
