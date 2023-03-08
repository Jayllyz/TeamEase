<?php
session_start();
include "../includes/db.php";
$email = $_POST["email"];
$companyName = $_POST["companyName"];
$address = $_POST["address"];
$rights = $_POST["rights"];
$siret =   htmlspecialchars($_GET["siret"]);

if (isset($email) && !empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header(
        "location: ../update.php?message=Email invalide !&type=danger"
    );
    exit();
}

if (isset($rights) && !empty($rights) || isset($companyName) && !empty($companyName) || isset($email) && !empty($email) || isset($address) && !empty($address)) {
    setcookie("rights", $rights, time() + 3600, "/");


    $update = $db->prepare("UPDATE COMPANY SET email = :email, companyName = :companyName, rights = :rights, address = :address WHERE siret = :siret");
    $update->execute([
        "email" => $email,
        "companyName" => $companyName,
        "rights" => $rights,
        "address" => $address,
        "siret" => $siret,
    ]);
    header("location: ../admin.php?message=Modification effectué !&type=success");
    exit();
}
