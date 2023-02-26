<?php include '../includes/db.php'; ?>
<?php
$query = $db->query('SELECT name FROM OCCUPATION');
$fetch = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="btn-group">
    <button type="button" class="btn btn-secondary dropdown-toggle" style="padding-left:100px; padding-right:100px" data-bs-toggle="dropdown" aria-expanded="false">
        Métier
    </button>
    <ul class="dropdown-menu">
        <?php foreach ($fetch as $occupation) {
          echo '<li><a class="dropdown-item" onclick="selectOccupation(this)" style="padding-left:100px; padding-right:100px">' .
            $occupation['name'] .
            '</a></li>';
        } ?>
    </ul>
</div>
<button type="button" class="btn btn-danger" onclick="deleteProvider(this)">Supprimer</button>