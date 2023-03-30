<?php
include '../includes/functions.php';
$email = $_POST['emailCompany'];
$password = $_POST['passwordCompany'];
$conf_password = $_POST['conf_password'];
$siret = $_POST['siret'];

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

$output = callInsee($siret);
$jsonDecode = json_decode($output, true);

if ($jsonDecode['header']['statut'] != 200) {
  header(
    "location: ../signin.php?message=Le SIRET n'existe pas ou une erreur est survenue !&valid=invalid&input=siret",
  );
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

$address =
  $jsonDecode['etablissement']['adresseEtablissement']['numeroVoieEtablissement'] .
  ' ' .
  $jsonDecode['etablissement']['adresseEtablissement']['typeVoieEtablissement'] .
  ' ' .
  $jsonDecode['etablissement']['adresseEtablissement']['libelleVoieEtablissement'] .
  ', ' .
  $jsonDecode['etablissement']['adresseEtablissement']['codePostalEtablissement'] .
  ', ' .
  $jsonDecode['etablissement']['adresseEtablissement']['libelleCommuneEtablissement'];

$name = $jsonDecode['etablissement']['uniteLegale']['denominationUniteLegale'];

if (strlen($password) < 6) {
  header(
    'location: ../signin.php?message=Mot de passe invalide. Il doit avoir 6 caracteres minimum !&valid=invalid&input=passwordCompany',
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
      'INSERT INTO COMPANY (siret, companyName, email, address, password, rights, token) VALUES (:siret, :companyName, :email, :address, :password, :rights, :token)',
    );
    $rights = 0;
    $token = uniqid();

    $req->execute([
      'siret' => $siret,
      'companyName' => $name,
      'email' => $email,
      'address' => $address,
      'password' => hash('sha512', $password),
      'rights' => $rights,
      'token' => $token,
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
      'company">Confirmation !</a>';
    include '../includes/mailer.php';
    header('location: ../login.php?message=Votre inscription à été confirmé !&type=success&valid=valid');
    exit();
  } else {
    header(
      'location: ../signin.php?message=Les mots de passes ne sont pas identiques !&type=danger&valid=invalid&input=conf_mdp',
    );
    exit();
  }
}
