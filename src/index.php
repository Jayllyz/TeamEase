<?php
try {
    $bdd = new PDO('mysql:host=mysql:3306;dbname=teamease','root','Respons11');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
?>