<?php
include '../includes/db.php';

if (isset($_GET['delete'])) {
  $request = $db->prepare('DELETE FROM BELONG WHERE id_activity = :id_activity');
  $result = $request->execute([
    ':id_activity' => $_GET['delete'],
  ]);
  $request = $db->prepare('DELETE FROM ACTIVITY WHERE id = :id');
  $result2 = $request->execute([
    ':id' => $_GET['delete'],
  ]);
  $request = $db->prepare('DELETE FROM ANIMATE WHERE id_activity = :id_activity');
  $result3 = $request->execute([
    ':id_activity' => $_GET['delete'],
  ]);

  if ($result && $result2 && $result3) {
    $message = 'L\'activité a bien été supprimée';
    header('location:../catalog.php?message=' . $message . '&type=success');
    exit();
  } else {
    $message = 'Une erreur est survenue';
    header('location:../catalog.php?message=' . $message . '&type=danger');
    exit();
  }
}

$getId = $db->prepare('SELECT id FROM ACTIVITY ORDER BY id DESC LIMIT 1');
$getId->execute();

if (!isset($_POST['category'])) {
  $message = "Aucune catégorie n'a été selectionnée";
  header('location:../addActivityPage.php?message=' . $message . '&type=danger');
  exit();
}

if ($_POST['duration'] <= 0) {
  $message = "La durée de l'activité doit être supérieure à 0";
  header('location:../addActivityPage.php?message=' . $message . '&type=danger');
  exit();
}
if ($_POST['price'] < 0) {
  $message = "Le prix de l'activité ne peut être négatif";
  header('location:../addActivityPage.php?message=' . $message . '&type=danger');
  exit();
}
if ($_POST['maxAttendee'] <= 0) {
  $message = 'Le nombre de participants maximum doit être supérieur à 0';
  header('location:../addActivityPage.php?message=' . $message . '&type=danger');
  exit();
}

$request = $db->prepare(
  'INSERT INTO ACTIVITY (name, description, duration, priceAttendee, maxAttendee) 
  VALUES (:name, :description, :duration, :priceAttendee, :maxAttendee)'
);

$result = $request->execute([
  'name' => $_POST['name'],
  'description' => $_POST['description'],
  'duration' => $_POST['duration'],
  'priceAttendee' => $_POST['price'],
  'maxAttendee' => $_POST['maxAttendee'],
]);

$getId = $db->prepare('SELECT id FROM ACTIVITY ORDER BY id DESC LIMIT 1');
$getId->execute();
$id = $getId->fetch();

$uploadsPath = '../images/activities';
if (!file_exists($uploadsPath)) {
  mkdir($uploadsPath, 0777, true);
}

$mainImage = $_FILES['mainImage']['name'];
$array = explode('.', $mainImage);
$ext = end($array);
$mainImage = $id[0] . $_POST['name'] . '0' . '.' . $ext;
$destination = $uploadsPath . '/' . $mainImage;
move_uploaded_file($_FILES['mainImage']['tmp_name'], $destination);

$secondImage = $_FILES['secondImage']['name'];
$array = explode('.', $secondImage);
$ext = end($array);
$secondImage = $id[0] . $_POST['name'] . '1' . '.' . $ext;
$destination = $uploadsPath . '/' . $secondImage;
move_uploaded_file($_FILES['secondImage']['tmp_name'], $destination);

$thirdImage = $_FILES['thirdImage']['name'];
$array = explode('.', $thirdImage);
$ext = end($array);
$thirdImage = $id[0] . $_POST['name'] . '2' . '.' . $ext;
$destination = $uploadsPath . '/' . $thirdImage;
move_uploaded_file($_FILES['thirdImage']['tmp_name'], $destination);

$fourthImage = $_FILES['fourthImage']['name'];
$array = explode('.', $fourthImage);
$ext = end($array);
$fourthImage = $id[0] . $_POST['name'] . '3' . '.' . $ext;
$destination = $uploadsPath . '/' . $fourthImage;
move_uploaded_file($_FILES['fourthImage']['tmp_name'], $destination);

include '../includes/resolution.php';

$belong = $_POST['category'];
$i = 0;
do {
  $getCategory = $db->prepare('SELECT id FROM CATEGORY WHERE name=:name');
  $getCategory->execute([
    'name' => $belong[$i],
  ]);

  $categoryId = $getCategory->fetch();

  $insert = $db->prepare('INSERT INTO BELONG (id_activity, id_category) VALUES (:id_activity, :id_category)');
  $result2 = $insert->execute([
    'id_activity' => $id[0],
    'id_category' => $categoryId[0],
  ]);
  $i++;
} while ($i < count($belong));

$providers = [];
$providersCount = 0;

foreach ($_POST as $key => $value) {
  if (preg_match('/^provider(\d+)$/', $key, $matches)) {
    $provider_id = $matches[1];
    $providers[] = $provider_id;
    $providersCount++;
  }
}

$i = 0;
do {
  $insert = $db->prepare('INSERT INTO ANIMATE (id_activity, id_provider) VALUES (:id_activity, :id_provider)');
  $result3 = $insert->execute([
    'id_activity' => $id[0],
    'id_provider' => $providers[$i],
  ]);
  $i++;
} while ($i < $providersCount);

if ($result && $result2 && $result3) {
  $message = 'L\'activité a bien été ajoutée';
  header('location:../addActivityPage.php?message=' . $message . '&type=success');
} else {
  $db->prepare('DELETE FROM BELONG WHERE id_activity = :id_activity');
  $result = $request->execute([
    ':id_activity' => $id[0],
  ]);
  $request = $db->prepare('DELETE FROM ACTIVITY WHERE id = :id');
  $request->execute([
    ':id' => $id[0],
  ]);
  $request = $db->prepare('DELETE FROM ANIMATE WHERE id_activity = :id_activity');
  $request->execute([
    ':id_activity' => $id[0],
  ]);
  $message = 'Une erreur est survenue';
  header('location:../addActivityPage.php?message=' . $message . '&type=danger');
}

?>
