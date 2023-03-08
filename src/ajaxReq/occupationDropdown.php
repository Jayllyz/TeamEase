<?php include '../includes/db.php'; ?>
<?php
$query = $db->query('SELECT name FROM OCCUPATION');
$fetch = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="btn-group">
    <button type="button" class="btn btn-secondary dropdown-toggle" style="padding-left:50px; padding-right:50px" data-bs-toggle="dropdown" aria-expanded="false">
        Métier
    </button>
    <ul class="dropdown-menu">
        <?php foreach ($fetch as $occupation) {
          echo '<li><a class="dropdown-item" onclick="selectOccupation(this)" style="padding-left:50px; padding-right:50px">' .
            $occupation['name'] .
            '</a></li>';
        } ?>
    </ul>
</div>
<button type="button" class="btn btn-danger" onclick="unassignProvider(this)">Supprimer</button>