<?php

include '../includes/db.php';

if (!isset($_POST['id']) || !isset($_POST['day'])) {
  exit();
}

$idActivity = htmlspecialchars($_POST['id']);
$day = htmlspecialchars($_POST['day']);

$query = $db->prepare(
  'SELECT PROVIDER.firstName, PROVIDER.lastName, OCCUPATION.name FROM PROVIDER INNER JOIN OCCUPATION ON PROVIDER.id_occupation = OCCUPATION.id INNER JOIN AVAILABILITY ON PROVIDER.id = AVAILABILITY.id_provider WHERE PROVIDER.id IN (SELECT id_provider FROM ANIMATE WHERE id_activity = :id) AND AVAILABILITY.day = :day',
);
$query->execute([
  'id' => $idActivity,
  'day' => $day,
]);
$providers = $query->fetchAll(PDO::FETCH_ASSOC);

echo '<h4>Animateurs disponibles</h4>';

foreach ($providers as $provider) {
  echo "$provider[firstName] $provider[lastName] ($provider[name])";
  if ($provider != end($providers)) {
    echo '<br>';
  }
}
?>