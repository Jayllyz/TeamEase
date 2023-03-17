<?php
$firstName = $_POST['firstname'];
$lastName = $_POST['name'];
$email = $_POST['email'];
$job = $_POST['job'];
$salary = $_POST['salary'];
$password = $_POST['password'];
$conf_password = $_POST['conf_password'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header('location: ../signin.php?message=Email invalide !&valid=invalid&input=email&check=provider');
  exit();
} else {
  setcookie('email', $email, time() + 3600, '/');
}

if (isset($lastName) && !strlen($lastName) > 0) {
  header('location: ../signin.php?message=Le nom est invalide !&valid=invalid&input=name&check=provider');
  exit();
}

if (isset($firstName) && !strlen($firstName) > 0) {
  header('location: ../signin.php?message=Le prenom est invalide !&valid=invalid&input=firstname&check=provider');
  exit();
}

$req = $db->prepare('SELECT name FROM OCCUPATION WHERE id = :id');
$req->execute([
  'id' => $_POST['job'],
]);

$reponse = $req->fetch();

if (!$reponse) {
  header('location: ../signin.php?message=Le métier est invalide !&valid=invalid&input=job&check=provider');
  exit();
}

if (isset($salary) && !is_numeric($salary)) {
  header('location: ../signin.php?message=Le salaire est invalide !&valid=invalid&input=salary&check=provider');
  exit();
}

if (strlen($password) < 6) {
  header(
    'location: ../signin.php?message=Mot de passe invalide. Il doit avoir 6 caracteres minimum !&valid=invalid&input=mdp&check=provider'
  );
  exit();
}

$req = $db->prepare('SELECT id FROM PROVIDER WHERE email = :email');
$req->execute([
  'email' => $_POST['email'],
]);

$reponse = $req->fetch();

if ($reponse) {
  header('location: ../signin.php?message=Cet email est déja utilisé !&valid=invalid&input=email&check=provider');
  exit();
}

$req = $db->prepare('SELECT siret FROM COMPANY WHERE email = :email');
$req->execute([
  'email' => $_POST['email'],
]);

$reponse = $req->fetch();

if ($reponse) {
  header('location: ../signin.php?message=Cet email est déja utilisé !&valid=invalid&input=email&check=provider');
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
      'INSERT INTO PROVIDER (lastName, firstName, id_occupation, email, salary, password, rights, token) VALUES (:firstName, :lastName, :occupation, :email, :salary, :password, :rights, :token)'
    );
    $rights = 0;
    $token = uniqid();

    $req->execute([
      'firstName' => $lastName,
      'lastName' => $firstName,
      'occupation' => $job,
      'email' => $email,
      'salary' => $salary,
      'rights' => $rights,
      'token' => $token,
      'password' => hash('sha512', $password),
    ]);

    $subject = 'Confirmation de votre inscription';
    $msgHTML =
      '<img src="localhost/images/logo.png" class="logo float-left m-2 h-75 me-4" width="95" alt="Logo">
                <p class="display-2">Bienvenue chez Together&Stronger. Veuillez cliquer sur le lien ci-dessous pour confirmer votre inscription :<br></p>
      <a href="localhost/includes/confRegistration.php?' .
      'token=' .
      $token .
      '&email=' .
      $email .
      '&type=' .
      'provider">Confirmation !</a>';
    $destination = '../login.php';
    include '../includes/mailer.php';
  } else {
    header(
      'location: ../signin.php?message=Les mots de passes ne sont pas identiques !&type=danger&valid=invalid&input=conf_mdp&check=provider'
    );
    exit();
  }
}
