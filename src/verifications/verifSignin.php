<?php
session_start();
date_default_timezone_set("Europe/Paris");

include "../includes/db.php";

if (isset($_POST["submit"])) {
    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        header(
            "location: ../signin.php?message=Email invalide !&valid=invalid&input=email"
        );
        exit();
    } else {
        setcookie("email", $_POST["email"], time() + 3600, "/");
    }

    if (strlen($_POST["siret"])  > 14) {
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
            "location: ../signin.php?message=Ce nom d'entreprise est déja utilisé !&valid=invalid&input=pseudo"
        );
        exit();
    } else {
        setcookie("name", $_POST["name"], time() + 3600, "/");
    }


    $address = $_POST["address"];

    if (strlen($address) < 5 || strlen($address) > 50) {
        header(
            "location: ../signin.php?message=Adresse invalide !&valid=invalid&input=address"
        );
        exit();
    } else {
        setcookie("address", $_POST["address"], time() + 3600, "/");
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
                "INSERT INTO COMPANY (name,email,password,siret,address) VALUES (:pseudo,:email,:password,:siret,:address)"
            );
            $name = $_POST["name"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $conf_password = $_POST["conf_password"];
            $address = $_POST["address"];
            $siret = $_POST["siret"];

            $req->execute([
                "name" => $name,
                "email" => $email,
                "password" => hash("sha512", $password),
                "address" => $address,
                "siret" => $siret,
            ]);

            echo "T INSCRIT CONNARD";
        } else {
            header(
                "location: ../signin.php?message=Les mots de passes ne sont pas identiques !&type=danger"
            );
            exit();
        }
    }
} else {
    header(
        "location: ../signin.php?message=Les champs ne sont pas tous remplis !&type=danger"
    );
    exit();
}
