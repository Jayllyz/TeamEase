<?php
session_start();
date_default_timezone_set("Europe/Paris");

include "../includes/db.php";

if (isset($_POST["submit"])) {
    if (!isset($_POST["job"])) {
        include "verifCompany.php";
    } else {
        include "verifProvider.php";
    }
} else {
    header(
        "location: ../signin.php?message=Les champs ne sont pas tous remplis !&type=danger&valid=invalid"
    );
    exit();
}
