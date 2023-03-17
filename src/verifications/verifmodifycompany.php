<?php
session_start();
include "../includes/db.php";
$email = $_POST["email"];
$companyName = $_POST["companyName"];
$address = $_POST["address"];
$rights = htmlspecialchars($_GET["rights"]);
$siret =   htmlspecialchars($_GET["siret"]);

if (isset($email) && !empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header(
        "location: ../modify.php?message=Email invalide !&type=danger"
    );
    exit();
}

if (isset($rights) && !empty($rights) || isset($companyName) && !empty($companyName) || isset($email) && !empty($email) || isset($address) && !empty($address)) {
    setcookie("rights", $rights, time() + 3600, "/");

    $compare = $db->prepare("SELECT email, companyName, siret FROM COMPANY");
    $compare->execute();
    $compare = $compare->fetchAll();
    

    for($i = 0; $i < count($compare); $i++){
        if ($compare[$i]["email"] == $email && $compare[$i]["siret"] != $siret) {
            header("location: ../profile.php?message=Email déjà utilisé !&type=danger");
            
        }
        if ($compare[$i]["companyName"] == $companyName && $compare[$i]["siret"] != $siret) {
            header("location: ../profile.php?message=Nom d'entreprise déjà utilisé !&type=danger");
        
        
        }
    }

    exit;
    $update = $db->prepare("UPDATE COMPANY SET email = :email, companyName = :companyName, rights = :rights, address = :address WHERE siret = :siret");
    $update->execute([
        "email" => $email,
        "companyName" => $companyName,
        "rights" => $rights,
        "address" => $address,
        "siret" => $siret,
    ]);
    header("location: ../profile.php?message=Modification effectué !&type=success");
    exit();
}