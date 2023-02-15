<?php
include '../includes/db.php';

$getId = $db->prepare('SELECT id FROM ACTIVITY ORDER BY id DESC LIMIT 1');
$getId->execute();

$id = $getId->fetch();
if ($id == null) {
  $id = 0;
}
//   if (!isset($_POST['category'])) {
//     $message = "Aucune catégorie n'a été selectionnée";
//     header('location:addActivityPage.php?message=' . $message);
//     exit();
//   }
if ($_POST['duration'] <= 0) {
  $message = "La durée de l'activité doit être supérieure à 0";
  header('location:addActivityPage.php?message=' . $message);
  exit();
}
if ($_POST['price'] < 0) {
  $message = "Le prix de l'activité ne peut être négatif";
  header('location:addActivityPage.php?message=' . $message);
  exit();
}
if ($_POST['maxAttendee'] <= 0) {
  $message = 'Le nombre de participants maximum doit être supérieur à 0';
  header('location:addActivityPage.php?message=' . $message);
  exit();
}

$uploadsPath = '../images/activities';
if (!file_exists($uploadsPath)) {
  mkdir($uploadsPath, 0777, true);
}

var_dump($_FILES);

$mainImage = $_FILES['mainImage']['name'];
$array = explode('.', $mainImage);
$ext = end($array);
$mainImage = $id . $_POST['name'] . '0' . '.' . $ext;
$destination = $uploadsPath . '/' . $mainImage;
move_uploaded_file($_FILES['mainImage']['tmp_name'], $destination);

$secondImage = $_FILES['secondImage']['name'];
$array = explode('.', $secondImage);
$ext = end($array);
$secondImage = $id . $_POST['name'] . '1' . '.' . $ext;
$destination = $uploadsPath . '/' . $secondImage;
move_uploaded_file($_FILES['secondImage']['tmp_name'], $destination);

$thirdImage = $_FILES['thirdImage']['name'];
$array = explode('.', $thirdImage);
$ext = end($array);
$thirdImage = $id . $_POST['name'] . '2' . '.' . $ext;
$destination = $uploadsPath . '/' . $thirdImage;
move_uploaded_file($_FILES['thirdImage']['tmp_name'], $destination);

$fourthImage = $_FILES['fourthImage']['name'];
$array = explode('.', $fourthImage);
$ext = end($array);
$fourthImage = $id . $_POST['name'] . '3' . '.' . $ext;
$destination = $uploadsPath . '/' . $fourthImage;
move_uploaded_file($_FILES['fourthImage']['tmp_name'], $destination);

include '../includes/resolution.php';
