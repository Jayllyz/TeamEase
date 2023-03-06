<?php
include '../includes/db.php';
$query = $db->query('SELECT id FROM MATERIAL ORDER BY id DESC LIMIT 1');
$fetch = $query->fetch(PDO::FETCH_COLUMN);
?>

<div class="row mb-4">
    <div class="col-4">
        <input type="text" class="form-control material-input" id="<?php echo $fetch +
          1; ?>" name="name" placeholder="Matériel">
    </div>
    <div class="col-2">
        <input type="number" class="form-control" id="quantity" name="quantity">
    </div>
    <div class="col-2">
        <input type="number" class="form-control" id="used" value="0" disabled readonly>
    </div>
    <div class="col-2">
        <input type="number" class="form-control" id="available" value="0" disabled readonly>
    </div>
    <div class="col-2">
        <button type="button" class="btn btn-success" onclick="updateMaterial(this)">Modifier</button>
        <button type="button" class="btn btn-danger" onclick="deleteMaterial(this)">Supprimer</button>
    </div>
</div>