<?php
$firstName = $_POST['firstname'];
$lastName = $_POST['name'];
$email = $_POST['email'];
$job = $_POST['job'];
$salary = $_POST['salary'];
$password = $_POST['password'];
$conf_password = $_POST['conf_password'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header('location: ../signin.php?message=Email invalide !&valid=invalid&input=email');
  exit();
} else {
  setcookie('email', $email, time() + 3600, '/');
}

if (isset($lastName) && !strlen($lastName) > 0) {
  header('location: ../signin.php?message=Le nom est invalide !&valid=invalid&input=name');
  exit();
}

if (isset($firstName) && !strlen($firstName) > 0) {
  header('location: ../signin.php?message=Le prenom est invalide !&valid=invalid&input=firstname');
  exit();
}

$req = $db->prepare('SELECT name FROM OCCUPATION WHERE id = :id');
$req->execute([
  'id' => $_POST['job'],
]);

$reponse = $req->fetch();

if (!$reponse) {
  header('location: ../signin.php?message=Le métier est invalide !&valid=invalid&input=job');
  exit();
}


if (isset($salary) && !is_numeric($salary)) {
  header('location: ../signin.php?message=Le salaire est invalide !&valid=invalid&input=salary');
  exit();
}

if (strlen($password) < 6) {
  header(
    'location: ../signin.php?message=Mot de passe invalide. Il doit avoir 6 caracteres minimum !&valid=invalid&input=mdp'
  );
  exit();
}

$req = $db->prepare('SELECT id FROM PROVIDER WHERE email = :email');
$req->execute([
  'email' => $_POST['email'],
]);

$reponse = $req->fetch();

if ($reponse) {
  header('location: ../signin.php?message=Cet email est déja utilisé !&valid=invalid&input=email');
  exit();
}

$req = $db->prepare('SELECT siret FROM COMPANY WHERE email = :email');
$req->execute([
  'email' => $_POST['email'],
]);

$reponse = $req->fetch();

if ($reponse) {
  header('location: ../signin.php?message=Cet email est déja utilisé !&valid=invalid&input=email');
  exit();
}

if (
  !empty($lastName) &&
  !empty($email) &&
  !empty($password) &&
  !empty($conf_password) &&
  !empty($firstName) &&
  !empty($job) &&
  !empty($salary)
) {
  if ($password == $conf_password) {
    $req = $db->prepare(
      'INSERT INTO PROVIDER (lastName, firstName, id_occupation, email, salary, password, rights) VALUES (:firstName, :lastName, :occupation, :email, :salary, :password, :rights)'
    );
    $rights = 0;

    $req->execute([
      'firstName' => $firstName,
      'lastName' => $lastName,
      'occupation' => $job,
      'email' => $email,
      'salary' => $salary,
      'rights' => $rights,
      'password' => hash('sha512', $password),
    ]);

    header('location: ../login.php?message=Votre compte a bien été créé !&type=success&valid=valid');
  } else {
    header(
      'location: ../signin.php?message=Les mots de passes ne sont pas identiques !&type=danger&valid=invalid&input=conf_mdp'
    );
    exit();
  }
}
