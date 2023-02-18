<?php
session_start();
date_default_timezone_set("Europe/Paris");
$date = date("d/m/Y H:i:s");

include "../includes/db.php";

if (isset($_POST["submit"])) {

    if (isset($_POST["login"]) && !empty($_POST["login"])) {
        setCookie("login", $_POST["login"], time() + 24 * 3600);
    }

    if (
        empty($_POST["login"]) ||
        !filter_var($_POST["login"], FILTER_VALIDATE_EMAIL)
    ) {
        header("location: ../login.php?message=Email invalide !&type=danger");
        exit();
    }

    if (!isset($_POST["password"]) || empty($_POST["password"])) {
        header(
            "location: ../login.php?message=Mot de passe manquant !&type=danger"
        );
        exit();
    }

    $req = $db->prepare(
        "SELECT siret, rights,email FROM COMPANY WHERE email = :email AND password = :password"
    );
    $req->execute([
        "email" => $_POST["login"],
        "password" => hash("sha512", $_POST["password"]),
    ]);

    $reponse = $req->fetchAll(PDO::FETCH_ASSOC);

    if ($reponse) {
        foreach ($reponse as $select) {

            if ($select["rights"] != -1) {
                $_SESSION["siret"] = $select["siret"];
                $_SESSION["rights"] = $select["rights"];
                $_SESSION["email"] = $select["email"];
                setcookie("email", $_POST["login"], time() + 3600);
                $login = $_POST["login"];
                header(
                    "location: ../index.php?message=Vous êtes connecté&type=success"
                );
                exit();
            }
        }
    } else {
        header(
            "location: ../login.php?message=Erreur dans le mots de passe ou le mail !&type=danger"
        );
        exit();
    }
}
