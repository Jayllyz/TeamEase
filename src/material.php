<?php session_start(); ?>
<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html>
  <?php
  $linkCss = 'css-js/style.css';
  $linkLogo = 'images/logo.png';
  $title = 'Gestion du matériel';
  include 'includes/head.php';
  ?>
  <body>
    <?php include 'includes/header.php'; ?>
    <main>
      <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">Gestion du matériel</h1>
            </div>
        </div>

        <br>

        <div id="material-container">
            <div class="row">
                <div class="col-4">
                    <label class="form-label"><h4>Matériel</h4></label>
                </div>
                <div class="col-2">
                    <label class="form-label"><h4>Total</h4></label>
                </div>
                <div class="col-2">
                    <label class="form-label"><h4>Utilisé</h4></label>
                </div>
                <div class="col-2">
                    <label class="form-label"><h4>Disponible</h4></label>
                </div>
                <div class="col-2">
                    <label class="form-label"><h4>Modification</h4></label>
                </div>
            </div>
            <?php
            $query = $db->prepare('SELECT id FROM MATERIAL');
            $query->execute();
            $result = $query->fetchAll();
            $count = count($result);
            for ($i = 0; $i < $count; $i++) {
              $query = $db->prepare('SELECT type FROM MATERIAL WHERE id = :id');
              $query->execute([
                ':id' => $result[$i]['id'],
              ]);
              $type = $query->fetch(PDO::FETCH_COLUMN);
              $query = $db->prepare('SELECT quantity FROM MATERIAL WHERE id = :id');
              $query->execute([
                ':id' => $result[$i]['id'],
              ]);
              $quantity = $query->fetch(PDO::FETCH_COLUMN);
              $query = $db->prepare('SELECT SUM(quantity) FROM MATERIAL_ACTIVITY WHERE id_material = :id');
              $query->execute([
                ':id' => $result[$i]['id'],
              ]);
              $used = $query->fetch(PDO::FETCH_COLUMN);
              if ($used == null) {
                $used = 0;
              }
              $available = $quantity - $used;
              echo '
                <div class="row mb-4">
                    <div class="col-4">
                        <input type="text" class="form-control material-input" id="';
              echo $result[$i]['id'];
              echo '" value="' .
                $type .
                '" name="name" placeholder="Matériel">
                    </div>
                    <div class="col-2">
                        <input type="number" class="form-control" id="quantity" value="' .
                $quantity .
                '" name="quantity">
                    </div>
                    <div class="col-2">
                        <input type="number" class="form-control" id="used" value="' .
                $used .
                '" disabled readonly>
                    </div>
                    <div class="col-2">
                        <input type="number" class="form-control" id="available" value="' .
                $available .
                '" disabled readonly>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-success" onclick="updateMaterial(this)">Modifier</button>
                        <button type="button" class="btn btn-danger" onclick="deleteMaterial(this)">Supprimer</button>
                    </div>
                </div>';
            }
            ?>
        </div>

        <div class="row">
            <div class="col-12 text-center my-5">
                <button type="button" class="btn btn-primary" onclick="addMaterial()">Ajouter un matériel</button>
            </div>
        </div>

      </div>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="css-js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>