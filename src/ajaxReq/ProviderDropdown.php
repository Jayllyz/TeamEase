<?php
include '../includes/db.php';

if (!isset($_GET['occupation'])) {
  exit();
}
$occupation = $_GET['occupation'];
$query = $db->query('SELECT id FROM OCCUPATION WHERE name = "' . $occupation . '"');
$idList = $query->fetch(PDO::FETCH_ASSOC);
foreach ($idList as $id) {
  $query = $db->query('SELECT firstName, lastName FROM PROVIDER WHERE id_occupation = ' . $id);
  $fetch = $query->fetchAll(PDO::FETCH_ASSOC);
}
?>
<button type="button" class="btn btn-secondary dropdown-toggle mx-2" data-bs-toggle="dropdown" aria-expanded="false">
    Prestataires
</button>
<ul class="dropdown-menu">
    <?php foreach ($fetch as $provider) {
      echo '<li><a class="dropdown-item" href="#" onclick="selectOccupation(this)">' .
        $provider['firstName'] .
        $provider['lastName'] .
        '</a></li>';
    } ?>
</ul>