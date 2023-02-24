<?php
try {
  $path = dirname(__DIR__, 2) . '/.env';
  $_ENV = parse_ini_file($path);
  $db = new PDO('mysql:host=mysql:3306;dbname=' . $_ENV['MYSQL_DATABASE'], 'root', $_ENV['MYSQL_ROOT_PASSWORD']);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo 'Erreur : ' . $e->getMessage();
}
