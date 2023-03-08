<?php

$email = $_POST['emailCompany'];
$password = $_POST['passwordCompany'];
$conf_password = $_POST['conf_password'];
$name = $_POST['nameCompany'];
$siret = $_POST['siret'];
$address = $_POST['address'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header('location: ../signin.php?message=Email invalide !&valid=invalid&input=emailCompany');
  exit();
} else {
  setcookie('email', $email, time() + 3600, '/');
}

if (strlen($siret) != 14 && !is_numeric($siret)) {
  header('location: ../signin.php?message=Le SIRET doit contenir 14 chiffres !&valid=invalid&input=siret');
  exit();
}

$req = $db->prepare('SELECT siret FROM COMPANY WHERE siret = :siret');
$req->execute([
  'siret' => $siret,
]);

$reponse = $req->fetch();

if ($reponse) {
  header("location: ../signin.php?message=Ce SIRET d'entreprise est déja utilisé !&valid=invalid&input=siret");
  exit();
} else {
  setcookie('siret', $siret, time() + 3600, '/');
}

if (strlen($name) == 0) {
  header("location: ../signin.php?message=Nom d'entreprise invalide !&valid=invalid&input=nameCompany");
  exit();
}

if (strlen($address) < 5) {
  header('location: ../signin.php?message=Adresse invalide !&valid=invalid&input=address');
  exit();
}

if (strlen($password) < 6) {
  header(
    'location: ../signin.php?message=Mot de passe invalide. Il doit avoir 6 caracteres minimum !&valid=invalid&input=passwordCompany'
  );
  exit();
}

$req = $db->prepare('SELECT siret FROM COMPANY WHERE email = :email');
$req->execute([
  'email' => $email,
]);

$reponse = $req->fetch();

if ($reponse) {
  header('location: ../signin.php?message=Cet email est déja utilisé !&valid=invalid&input=emailCompany');
  exit();
}

$req = $db->prepare('SELECT id FROM PROVIDER WHERE email = :email');
$req->execute([
  'email' => $email,
]);

$reponse = $req->fetch();

if ($reponse) {
  header('location: ../signin.php?message=Cet email est déja utilisé !&valid=invalid&input=emailCompany');
  exit();
}

if (
  !empty($name) &&
  !empty($email) &&
  !empty($password) &&
  !empty($conf_password) &&
  !empty($address) &&
  !empty($siret)
) {
  if ($password == $conf_password) {
    $req = $db->prepare(
      'INSERT INTO COMPANY (siret, companyName, email, address, password, rights) VALUES (:siret, :companyName, :email, :address, :password, :rights)'
    );
    $rights = 0;

    $req->execute([
      'siret' => $siret,
      'companyName' => $name,
      'email' => $email,
      'address' => $address,
      'password' => hash('sha512', $password),
      'rights' => $rights,
    ]);

    header('location: ../login.php?message=Votre compte a bien été créé !&type=success&valid=valid');
  } else {
    header(
      'location: ../signin.php?message=Les mots de passes ne sont pas identiques !&type=danger&valid=invalid&input=conf_mdp'
    );
    exit();
  }
}
