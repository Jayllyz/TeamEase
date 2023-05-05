<?php
include '../includes/db.php';
$siret = htmlspecialchars($_GET['siret']);


if (!isset($siret) || empty($siret)) {
    header('location: ../index.php');
    exit();
} else {
    $sql = 'DELETE FROM COMPANY WHERE siret = :siret';
    $query = $db->prepare($sql);
    $query->execute([
      'siret' => $siret,
    ]);
    
    header('location: ../logout.php');
    exit();
}