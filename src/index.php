<?php
try {
  $bdd = new PDO('mysql:host=mysql:3306;dbname=teamease', 'root', 'Respons11');
  $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo 'Connexion réussie !';
  echo '<br>';
  echo 'Liste des tables :';
  echo '<br>';

  $sql = 'SHOW TABLES';
  $result = $bdd->query($sql);
  while ($row = $result->fetch(PDO::FETCH_NUM)) {
    echo $row[0] . '<br>';
  }
} catch (PDOException $e) {
  echo 'Erreur : ' . $e->getMessage();
}
?>
