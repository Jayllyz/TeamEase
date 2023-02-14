<?php
try {
    $_ENV = parse_ini_file('./../.env');
    $bdd = new PDO('mysql:host=mysql:3306;dbname=' . $_ENV['MYSQL_DATABASE'], 'root', $_ENV['MYSQL_ROOT_PASSWORD']);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>