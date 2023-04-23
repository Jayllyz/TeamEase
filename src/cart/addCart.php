<?php
session_start();
include_once '../includes/db.php';

if (isset($_SESSION['siret'])) {
  $idActivity = $_GET['id'];

  $select = $db->prepare('SELECT * FROM CART WHERE id_activity = :id_activity AND siret = :siret');
  $select->execute([
    'id_activity' => $idActivity,
    'siret' => $_SESSION['siret'],
  ]);

  $result = $select->fetch(PDO::FETCH_ASSOC);

  if ($result) {
    header('Location: ../activity.php?id=' . $idActivity . '&message=Activité déjà dans le panier !&type=danger');
    exit();
  }

  $addCart = $db->prepare('INSERT INTO CART (id_activity, siret) VALUES (:id_activity, :siret)');
  $addCart->execute([
    'id_activity' => $idActivity,
    'siret' => $_SESSION['siret'],
  ]);

  header('Location: ../activity.php?id=' . $idActivity . '&message=Activité ajouté au panier !&type=success');
  exit();
} else {
  header('Location: ../index.php');
  exit();
}
