<?php session_start();
if (!isset($_SESSION['rights']) || $_SESSION['rights'] != 2) {
  header('location: index.php');
  exit();
}
?>
<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html>
  <?php
  $linkCss = 'css-js/style.css';
  $linkLogo = 'images/logo.png';
  $title = 'Gestion du matériel';
  include 'includes/head.php';
  if (isset($_GET['location']) && $_GET['location'] != 'stock') {
    $query = $db->prepare('SELECT name FROM LOCATION WHERE id = :id');
    $query->execute(['id' => htmlspecialchars($_GET['location'])]);
    $nameLocation = $query->fetch(PDO::FETCH_COLUMN);
    $id = htmlspecialchars($_GET['location']);
  } elseif (isset($_GET['location']) && $_GET['location'] == 'stock') {
    $nameLocation = 'général';
    $id = 'stock';
  } else {
    header('Location: index.php');
  }
  ?>
  <body>
    <?php include 'includes/header.php'; ?>
    <main>
      <div class="container">
        <div class="row">
          <div class="col-12">
            <h1 class="text-center">Gestion du matériel de <?php echo $nameLocation; ?></h1>
          </div>
        </div>
        
        <?php if ($_GET['location'] != 'stock') { ?>
        <div>
          <div class="my-5 location">
            <h3>Matériel du site</h3>
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Nom</th>
                  <th scope="col">Quantité</th>
                  <th scope="col">Modification</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query = $db->prepare(
                  'SELECT id, type, MATERIAL_LOCATION.quantity FROM MATERIAL INNER JOIN MATERIAL_LOCATION ON MATERIAL.id = MATERIAL_LOCATION.id_material WHERE MATERIAL_LOCATION.id_location = :id_location',
                );
                $query->execute([':id_location' => $id]);
                $materials = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach ($materials as $material) { ?>
                  <tr>
                    <th scope="row">
                      <?php
                      $query = $db->prepare('SELECT type, id FROM MATERIAL');
                      $query->execute();
                      $materials = $query->fetchAll(PDO::FETCH_ASSOC);
                      ?>
                      <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="<?php echo $material[
                        'id'
                      ]; ?>">
                        <?php echo $material['type']; ?>
                      </button>
                      <ul class="dropdown-menu">
                        <?php foreach ($materials as $material2) {
                          echo '<li><a class="dropdown-item" onclick="selectMaterialForAllocation(this, this.id)" id="' .
                            $material2['id'] .
                            '">' .
                            $material2['type'] .
                            '</a></li>';
                        } ?>
                      </ul>
                    </th>
                    <td>
                      <input type="number" class="form-control" id="quantity" placeholder="Quantité" value="<?php echo $material[
                        'quantity'
                      ]; ?>">
                    </td>
                    <td>
                      <button type="button" class="btn btn-primary" onclick="updateAllocatedMaterial(this)">Modifer</button>
                      <button type="button" class="btn btn-danger" onclick="unallocateMaterial(this)">Supprimer</button>
                    </td>
                  </tr>
                <?php }
                ?>
              </tbody>
            </table>
            <div class="d-flex justify-content-center">
              <button class="btn btn-primary" onclick="allocateMaterial(this)">Assigner du matériel</button>
            </div>
          </div>
          <?php
          $query = $db->prepare('SELECT name, id FROM ROOM WHERE id_location = :id');
          $query->execute([':id' => $id]);
          $rooms = $query->fetchAll(PDO::FETCH_ASSOC);
          foreach ($rooms as $room) { ?>
            <hr>
            <div class="my-5 room" id="<?php echo $room['id']; ?>">
              <h5>Matériel de <?php echo $room['name']; ?></h5>
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Quantité</th>
                    <th scope="col">Modification</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = $db->prepare(
                    'SELECT id, type, MATERIAL_ROOM.quantity FROM MATERIAL INNER JOIN MATERIAL_ROOM ON MATERIAL.id = MATERIAL_ROOM.id_material WHERE MATERIAL_ROOM.id_room = :id_room',
                  );
                  $query->execute([':id_room' => $room['id']]);
                  $materials = $query->fetchAll(PDO::FETCH_ASSOC);
                  foreach ($materials as $material) { ?>
                    <tr>
                      <th scope="row">
                        <?php
                        $query = $db->prepare('SELECT type, id FROM MATERIAL');
                        $query->execute();
                        $materials = $query->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="<?php echo $material[
                          'id'
                        ]; ?>">
                          <?php echo $material['type']; ?>
                        </button>
                        <ul class="dropdown-menu">
                          <?php foreach ($materials as $material2) {
                            echo '<li><a class="dropdown-item" onclick="selectMaterialForAllocation(this, this.id)" id="' .
                              $material2['id'] .
                              '">' .
                              $material2['type'] .
                              '</a></li>';
                          } ?>
                        </ul>
                      </th>
                      <td>
                        <input type="number" class="form-control" id="quantity" placeholder="Quantité" value="<?php echo $material[
                          'quantity'
                        ]; ?>">
                      </td>
                      <td>
                        <button type="button" class="btn btn-primary" onclick="updateAllocatedMaterial(this)">Modifer</button>
                        <button type="button" class="btn btn-danger" onclick="unallocateMaterial(this)">Supprimer</button>
                      </td>
                    </tr>
                  <?php }
                  ?>
                </tbody>
              </table>
              <div class="d-flex justify-content-center">
                <button class="btn btn-primary" onclick="allocateMaterial(this)">Assigner du matériel</button>
              </div>
            </div>
          <?php }
          ?>
        </div>
        <?php } ?>


        <?php if ($_GET['location'] == 'stock') { ?>
        <div>
          <table class="table">
            <thead>
              <tr>
                <th scope="col">Nom</th>
                <th scope="col">Quantité</th>
                <th scope="col">Assigné</th>
                <th scope="col">Disponible</th>
                <th scope="col">Modification</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $query = $db->prepare('SELECT * FROM MATERIAL');
              $query->execute();
              $materials = $query->fetchAll(PDO::FETCH_ASSOC);
              foreach ($materials as $material) {
                $query = $db->prepare('SELECT SUM(quantity) FROM MATERIAL_ROOM WHERE id_material = :id');
                $query->execute([
                  ':id' => $material['id'],
                ]);
                $roomQuantity = $query->fetch(PDO::FETCH_COLUMN);
                if ($roomQuantity == null) {
                  $roomQuantity = 0;
                }
                $query = $db->prepare('SELECT SUM(quantity) FROM MATERIAL_LOCATION WHERE id_material = :id');
                $query->execute([
                  ':id' => $material['id'],
                ]);
                $locationQuantity = $query->fetch(PDO::FETCH_COLUMN);
                if ($locationQuantity == null) {
                  $locationQuantity = 0;
                }
                $query = $db->prepare('SELECT quantity FROM MATERIAL WHERE id = :id');
                $query->execute([
                  ':id' => $material['id'],
                ]);
                $quantity = $query->fetch(PDO::FETCH_COLUMN);
                $used = $roomQuantity + $locationQuantity;
                $available = $quantity - $roomQuantity - $locationQuantity;
                echo '<tr>';
                echo '<th scope="row">';
                echo '<input type="text" class="form-control" id="name" value="' . $material['type'] . '">';
                echo '</th>';
                echo '<td>';
                echo '<input type="number" class="form-control" id="quantity" value="' . $material['quantity'] . '">';
                echo '</td>';
                echo '<td>';
                echo '<input type="number" class="form-control" id="used" value="' . $used . '" disabled>';
                echo '</td>';
                echo '<td>';
                echo '<input type="number" class="form-control" id="available" value="' . $available . '" disabled>';
                echo '</td>';
                echo '<td>';
                echo '<button type="button" class="btn btn-primary" onclick="updateMaterial(this,' .
                  $material['id'] .
                  ')">Modifer</button>';
                echo '<button type="button" class="btn btn-danger" onclick="deleteMaterial(this,' .
                  $material['id'] .
                  ')">Supprimer</button>';
                echo '</td>';
                echo '</tr>';
              }
              ?>
            </tbody>
          </table>

          <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-primary" onclick="addMaterial(this)">Ajouter du matériel</button>
          </div>
        </div>
        <?php } ?>

      </div>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="css-js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>