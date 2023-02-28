<?php
include '../includes/db.php';

if (!isset($_GET['occupation'])) {
  exit();
}
$occupation = $_GET['occupation'];
$query = $db->query('SELECT id FROM OCCUPATION WHERE name = "' . $occupation . '"');
$idList = $query->fetch(PDO::FETCH_ASSOC);
foreach ($idList as $id) {
  $query = $db->query('SELECT id, firstName, UPPER(lastName) FROM PROVIDER WHERE id_occupation = ' . $id);
  $fetch = $query->fetchAll(PDO::FETCH_ASSOC);
}
?>
<button type="button" class="btn btn-secondary dropdown-toggle mx-2" id="" style="padding-left:300px; padding-right:300px;" data-bs-toggle="dropdown" aria-expanded="false">
    Prestataires
</button>
<ul class="dropdown-menu">
    <?php foreach ($fetch as $provider) {
      echo '<li><a class="dropdown-item" onclick="selectProvider(this)" id="' .
        $provider['id'] .
        '" style="padding-left:300px; padding-right:300px;">' .
        $provider['firstName'] .
        ' ' .
        $provider['UPPER(lastName)'] .
        '</a></li>';
    } ?>
</ul>