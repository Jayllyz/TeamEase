<?php
session_start();
include '../includes/db.php';

if (!isset($_POST['activity'])) {
  header('location: ../index.php');
  exit();
}
if (!isset($_POST['comment']) || !isset($_POST['notation'])) {
  header('location: ../activity.php?id=' . $_POST['activity']);
  exit();
}
if (empty($_POST['comment'])) {
  header(
    'location: ../activity.php?id=' .
      $_POST['activity'] .
      '&message=Le commentaire ne peut pas être vide !&type=danger&valid=invalid',
  );
  exit();
}
if (empty($_POST['notation'])) {
  header(
    'location: ../activity.php?id=' .
      $_POST['activity'] .
      '&message=Veuillez donner une note !&type=danger&valid=invalid',
  );
  exit();
}
if (!isset($_SESSION['siret'])) {
  header('location: ../activity.php?id=' . $_POST['activity']);
  exit();
}

$query = $db->prepare(
  'SELECT id FROM RESERVATION WHERE CAST(CONCAT(date," ", time) AS datetime) < NOW() AND siret = :siret AND status = 1 AND id_activity = :id_activity',
);
$query->execute([
  ':siret' => htmlspecialchars($_SESSION['siret']),
  ':id_activity' => htmlspecialchars($_POST['activity']),
]);
$payed = $query->fetch(PDO::FETCH_ASSOC);

if (!$payed) {
  header('location: ../activity.php?id=' . $_POST['activity']);
  exit();
}

if (isset($_POST['delete']) && $_POST['delete'] == 1) {
  $query = $db->prepare('DELETE FROM COMMENT WHERE id_reservation = :id_reservation');
  $result = $query->execute([
    ':id_reservation' => htmlspecialchars($payed['id']),
  ]);

  if ($result) {
    header('location: ../activity.php?id=' . $_POST['activity'] . '&message=Commentaire bien supprimé!&type=success');
    exit();
  } else {
    header(
      'location: ../activity.php?id=' .
        $_POST['activity'] .
        '&message=Erreur lors de la suppression du commentaire!&type=danger',
    );
    exit();
  }
}

if (isset($_POST['update']) && $_POST['update'] == 1) {
  $query = $db->prepare(
    'UPDATE COMMENT SET content = :content, notation = :notation, date = NOW() WHERE id_reservation = :id_reservation',
  );
  $result = $query->execute([
    ':content' => htmlspecialchars($_POST['comment']),
    ':notation' => htmlspecialchars($_POST['notation']),
    ':id_reservation' => htmlspecialchars($payed['id']),
  ]);

  if ($result) {
    header('location: ../activity.php?id=' . $_POST['activity'] . '&message=Commentaire bien modifié!&type=success');
    exit();
  } else {
    header(
      'location: ../activity.php?id=' .
        $_POST['activity'] .
        '&message=Erreur lors de la modification du commentaire!&type=danger',
    );
    exit();
  }
}

$query = $db->prepare(
  'INSERT INTO COMMENT (content, notation, date, id_reservation) VALUES (:content, :notation, NOW(), :id_reservation)',
);
$result = $query->execute([
  ':content' => htmlspecialchars($_POST['comment']),
  ':notation' => htmlspecialchars($_POST['notation']),
  ':id_reservation' => htmlspecialchars($payed['id']),
]);

if ($result) {
  header('location: ../activity.php?id=' . $_POST['activity'] . '&message=Commentaire bien enregistré!&type=success');
  exit();
} else {
  header(
    'location: ../activity.php?id=' .
      $_POST['activity'] .
      '&message=Erreur lors de l\'enregistrement du commentaire!&type=danger',
  );
  exit();
}
?>
