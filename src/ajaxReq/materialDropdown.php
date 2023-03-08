<?php include '../includes/db.php'; ?>
<?php
$query = $db->query('SELECT id, type FROM MATERIAL');
$fetch = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="btn-group">
    <button type="button" class="btn btn-secondary dropdown-toggle" style="padding-left:50px; padding-right:50px" data-bs-toggle="dropdown" aria-expanded="false">
        Matériel
    </button>
    <ul class="dropdown-menu">
        <?php foreach ($fetch as $material) {
          echo '<li><a class="dropdown-item" onclick="selectMaterial(this)" id="' .
            $material['id'] .
            '" style="padding-left:50px; padding-right:50px">' .
            $material['type'] .
            '</a></li>';
        } ?>
    </ul>
    <div class="inputNumber mx-2"></div>
    <button type="button" class="btn btn-danger" onclick="unassignMaterial(this)">Supprimer</button>
</div>