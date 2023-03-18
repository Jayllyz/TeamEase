<?php
include '../includes/db.php';

if ($_POST['type'] == 'stock') {

  $query = $db->prepare('SELECT id FROM MATERIAL ORDER BY id ASC LIMIT 1');
  $query->execute();
  $id = $query->fetch(PDO::FETCH_COLUMN) + 1;
  ?>

    <tr>
        <th scope="row">
            <input type="text" class="form-control" id="name" placeholder="Nom">
        </th>
        <td>
            <input type="number" class="form-control" id="quantity" placeholder="Quantité">
        </td>
        <td>
            <input type="number" class="form-control" id="used" value="0" disabled>
        </td>
        <td>
            <input type="number" class="form-control" id="available" value="0" disabled>
        </td>
        <td>
            <button type="button" class="btn btn-primary" onclick="updateMaterial(this,<?php echo $id; ?>)">Modifer</button>
            <button type="button" class="btn btn-danger" onclick="deleteMaterial(this,<?php echo $id; ?>)">Supprimer</button>
        </td>
    </tr>

<?php
} elseif ($_POST['type'] == 'location') { ?>

<tr>
    <th scope="row">
        <?php
        $query = $db->prepare('SELECT type, id FROM MATERIAL');
        $query->execute();
        $materials = $query->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            Matériel
        </button>
        <ul class="dropdown-menu">
            <?php foreach ($materials as $material) {
              echo '<li><a class="dropdown-item" onclick="selectMaterialForAllocation(this, this.id)" id="' .
                $material['id'] .
                '">' .
                $material['type'] .
                '</a></li>';
            } ?>
        </ul>
    </th>
    <td>
        <input type="number" class="form-control" id="quantity" placeholder="Quantité">
    </td>
    <td>
        <button type="button" class="btn btn-primary" onclick="updateAllocatedMaterial(this)">Modifer</button>
        <button type="button" class="btn btn-danger" onclick="unallocateMaterial(this)">Supprimer</button>
    </td>
</tr>
<?php } ?>
