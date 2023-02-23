<?php
if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    header(
        "location: ../signin.php?message=Email invalide !&valid=invalid&input=email"
    );
    exit();
} else {
    setcookie("email", $_POST["email"], time() + 3600, "/");
}

if (isset($_POST["name"]) && !strlen($_POST["name"]) > 0) {
    header(
        "location: ../signin.php?message=Le nom est invalide !&valid=invalid&input=name"
    );
    exit();
}

if (isset($_POST["firstname"]) && !strlen($_POST["firstname"]) > 0) {
    header(
        "location: ../signin.php?message=Le prenom est invalide !&valid=invalid&input=firstname"
    );
    exit();
}

if (isset($_POST["job"]) && !strlen($_POST["job"]) > 0) {
    header(
        "location: ../signin.php?message=Le métier est invalide !&valid=invalid&input=job"
    );
    exit();
}

if (isset($_POST["salary"]) && !is_numeric($_POST["salary"])) {
    header(
        "location: ../signin.php?message=Le salaire est invalide !&valid=invalid&input=salary"
    );
    exit();
}

if (strlen($_POST["password"]) < 6) {
    header(
        "location: ../signin.php?message=Mot de passe invalide. Il doit avoir 6 caracteres minimum !&valid=invalid&input=mdp"
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


if (
    !empty($_POST["name"]) &&
    !empty($_POST["email"]) &&
    !empty($_POST["password"]) &&
    !empty($_POST["conf_password"]) &&
    !empty($_POST["firstname"]) &&
    !empty($_POST["job"]) &&
    !empty($_POST["salary"])
) {
    if ($_POST["password"] == $_POST["conf_password"]) {
        $req = $db->prepare(
            "INSERT INTO PROVIDER (lastName, firstName, occupation, email, rights, password, salary) VALUES (:firstName, :lastName, :occupation, :email, :salary, :password, :rights)"
        );
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $firstName = $_POST["firstname"];
        $occupation = $_POST["job"];
        $rights = 0;
        $salary = $_POST["salary"];

        $req->execute([
            "firstName" => $firstName,
            "lastName" => $name,
            "occupation" => $occupation,
            "email" => $email,
            "salary" => $salary,
            "rights" => $rights,
            "password" => hash("sha512", $password),
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
