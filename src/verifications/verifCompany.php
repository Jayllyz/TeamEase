<?php
if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    header(
        "location: ../signin.php?message=Email invalide !&valid=invalid&input=email"
    );
    exit();
} else {
    setcookie("email", $_POST["email"], time() + 3600, "/");
}

if (strlen($_POST["siret"]) != 14) {
    header(
        "location: ../signin.php?message=Le siret doit contenir 14 chiffres !&valid=invalid&input=siret"
    );
    exit();
}

$req = $db->prepare("SELECT siret FROM COMPANY WHERE siret = :siret");
$req->execute([
    "siret" => $_POST["siret"],
]);

$reponse = $req->fetch();

if ($reponse) {
    header(
        "location: ../signin.php?message=Ce SIRET d'entreprise est déja utilisé !&valid=invalid&input=pseudo"
    );
    exit();
} else {
    setcookie("siret", $_POST["siret"], time() + 3600, "/");
}

if (strlen($_POST["name"]) == 0) {
    header(
        "location: ../signin.php?message=Nom d'entreprise invalide !&valid=invalid&input=name"
    );
    exit();
}


//TODO : check with API
$address = $_POST["address"];

if (strlen($address) < 5 || strlen($address) > 50) {
    header(
        "location: ../signin.php?message=Adresse invalide !&valid=invalid&input=address"
    );
    exit();
}


if (strlen($_POST["password"]) < 6 || strlen($_POST["password"]) > 15) {
    header(
        "location: ../signin.php?message=Mot de passe invalide. Il doit etre compris entre 6 et 15 caractères !&valid=invalid&input=mdp"
    );
    exit();
}

$req = $db->prepare("SELECT siret FROM COMPANY WHERE email = :email");
$req->execute([
    "email" => $_POST["email"],
]);

$reponse = $req->fetch();

if ($reponse) {
    header(
        "location: ../signin.php?message=Cet email est déja utilisé !&valid=invalid&input=email"
    );
    exit();
}

$req = $db->prepare("SELECT id FROM PROVIDER WHERE email = :email");
$req->execute([
    "email" => $_POST["email"],
]);

$reponse = $req->fetch();

if ($reponse) {
    header(
        "location: ../signin.php?message=Cet email est déja utilisé !&valid=invalid&input=email"
    );
    exit();
}


if (
    !empty($_POST["name"]) &&
    !empty($_POST["email"]) &&
    !empty($_POST["password"]) &&
    !empty($_POST["conf_password"]) &&
    !empty($_POST["address"]) &&
    !empty($_POST["siret"])
) {
    if ($_POST["password"] == $_POST["conf_password"]) {
        $req = $db->prepare(
            "INSERT INTO COMPANY (siret, companyName, email, address, password, rights) VALUES (:siret, :companyName, :email, :address, :password, :rights)"
        );
        $companyName = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $address = $_POST["address"];
        $siret = $_POST["siret"];
        $rights = 0;

        $req->execute([
            "siret" => $siret,
            "companyName" => $companyName,
            "email" => $email,
            "address" => $address,
            "password" => hash("sha512", $password),
            "rights" => $rights,
        ]);

        header(
            "location: ../login.php?message=Votre compte a bien été créé !&type=success&valid=valid"
        );
    } else {
        header(
            "location: ../signin.php?message=Les mots de passes ne sont pas identiques !&type=danger&valid=invalid&input=conf_mdp"
        );
        exit();
    }
}
