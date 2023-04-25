<?php
session_start();
include_once '../includes/db.php';

$id = $_POST['id'];

$deleteCart = $db->prepare('DELETE FROM CART WHERE id_activity = :id_activity');
$deleteCart->execute([
  'id_activity' => $id,
]);

if ($deleteCart) {
  echo 'Activité supprimée du panier';
} else {
  echo 'Une erreur est survenue';
}
