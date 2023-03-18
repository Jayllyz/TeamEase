<?php session_start(); ?>
<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html>
  <?php
  $linkCss = 'css-js/style.css';
  $linkLogo = 'images/logo.png';
  $title = 'Gestion du matériel';
  include 'includes/head.php';
  if (isset($_GET['location'])) {
    $query = $db->prepare('SELECT name FROM LOCATION WHERE id = :id');
    $query->execute([':id' => htmlspecialchars($_GET['location'])]);
    $location = $query->fetch(PDO::FETCH_COLUMN);
    $id = htmlspecialchars($_GET['location']);
  } else {
    $query = $db->prepare('SELECT name FROM LOCATION ORDER BY id ASC LIMIT 1');
    $query->execute();
    $location = $query->fetch(PDO::FETCH_COLUMN);
    $query = $db->prepare('SELECT id FROM LOCATION ORDER BY id ASC LIMIT 1');
    $query->execute();
    $id = $query->fetch(PDO::FETCH_COLUMN);
  }
  ?>
  <body>
    <?php include 'includes/header.php'; ?>
    <main>
      <div class="container">
        <div class="row">
          <div class="col-12">
            <h1 class="text-center">Gestion du matériel de <?php echo $location; ?></h1>
          </div>
        </div>

        <br>

        <div>
          <h3 class="title location">Matériel du site</h3>
          <table class="table">
            <thead>
              <tr>
                <th scope="col">
                  Nom
                </th>
                <th scope="col">
                  Quantité
                </th>
                <th scope="col">
                  Utilisé
                </th>
                <th scope="col">
                  Disponible
                </th>
                <th scope="col">
                  Modifications
                </th>
              </tr>
            </thead>
            <tbody>
              
            </tbody>
          </table>
          <div class="row">
            <div class="text-center my-2">
              <button type="button" class="btn btn-primary" onclick="addMaterial(this, <?php echo $id; ?>)">Ajouter un matériel</button>
            </div>
          </div>
        </div>

        <?php
        $query = $db->prepare('SELECT id, name FROM ROOM WHERE id_location = :id');
        $query->execute([':id' => $id]);
        $rooms = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rooms as $room) {
          $id = $room['id']; ?>
          <div>
            <h5 class="title location">Matériel <?php echo $room['name']; ?></h5>
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">
                    Nom
                  </th>
                  <th scope="col">
                    Quantité
                  </th>
                  <th scope="col">
                    Utilisé
                  </th>
                  <th scope="col">
                    Disponible
                  </th>
                  <th scope="col">
                    Modifications
                  </th>
                </tr>
              </thead>
              <tbody>
                
              </tbody>
            </table>
            <div class="row">
              <div class="text-center my-2">
                <button type="button" class="btn btn-primary" onclick="addMaterial(this, <?php echo $id; ?>)">Ajouter un matériel</button>
              </div>
            </div>
          </div>
        <?php
        }
        ?>

      </div>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="css-js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>