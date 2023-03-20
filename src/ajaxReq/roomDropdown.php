<?php
include '../includes/db.php';

if (!isset($_POST['id'])) {
  exit();
}
$idLocation = $_POST['id'];

$query = $db->query('SELECT id, name FROM ROOM WHERE id_location = ' . $idLocation);
$rooms = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<button type="button" class="btn btn-secondary dropdown-toggle mx-2" id="room" data-bs-toggle="dropdown" aria-expanded="false">
    Salles
</button>
<ul class="dropdown-menu">
    <?php foreach ($rooms as $room) {
      echo '<li><a class="dropdown-item" onclick="selectRoom(this)" id="' .
        $room['id'] .
        '">' .
        $room['name'] .
        '</a></li>';
    } ?>
</ul>