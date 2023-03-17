<?php
include '../includes/db.php';

if (isset($_POST['populate']) == 'true') {
  $query = $db->prepare('SELECT * FROM LOCATION');
  $query->execute();
  $locations = $query->fetchAll(PDO::FETCH_ASSOC);
  foreach ($locations as $location) {
    echo '<div class="accordion-item">
        <h2 class="accordion-header" id="location' .
      $location['id'] .
      '">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLocation' .
      $location['id'] .
      '" aria-expanded="true" aria-controls="collapseLocation' .
      $location['id'] .
      '">
            ' .
      $location['name'] .
      '(' .
      $location['address'] .
      ')
        </button>
        </h2>
        <div id="collapseLocation' .
      $location['id'] .
      '" class="acordion-collapse collapse show" aria-label="location' .
      $location['id'] .
      '" aria-controls="collapseLocation' .
      $location['id'] .
      '">
            <div class="accordion-body">
                
            </div>
            <div class="d-flex justify-content-center pb-2">
                <button class="btn btn-danger" onclick="deleteLocation(this, ' .
      $location['id'] .
      ')">
                    Supprimer le site
                </button>
            </div>
        </div>
    </div>';
  }
  exit();
}

if (isset($_POST['name']) && isset($_POST['address'])) {
  $query = $db->prepare('SELECT id FROM LOCATION WHERE name = :name AND address = :address');
  $query->execute([
    'name' => $_POST['name'],
    'address' => $_POST['address'],
  ]);
  $id = $query->fetch(PDO::FETCH_COLUMN);
}
?>

<div class="accordion-item">
    <h2 class="accordion-header" id="location<?php echo $id; ?>">
    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLocation<?php echo $id; ?>" aria-expanded="true" aria-controls="collapseLocation<?php echo $id; ?>">
        <?php echo $_POST['name'] . '(' . $_POST['address'] . ')'; ?>
    </button>
    </h2>
    <div id="collapseLocation<?php echo $id; ?>" class="acordion-collapse collapse show" aria-label="location<?php echo $id; ?>" aria-controls="collapseLocation<?php echo $id; ?>">
        <div class="accordion-body">
            
        </div>
        <div class="d-flex justify-content-center pb-2">
            <button class="btn btn-danger" onclick="deleteLocation(this, <?php echo $id; ?>)">
                Supprimer le site
            </button>
        </div>
    </div>
</div>