<?php session_start(); ?>
<?php
include 'includes/db.php';
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $query = $db->prepare('SELECT * FROM ACTIVITY WHERE id = :id');
  $query->execute([':id' => $id]);
  $activity = $query->fetch(PDO::FETCH_ASSOC);
  if (count($activity) == 0) {
    header('location: index.php');
  }
} else {
  header('location: index.php');
}
?>

<!DOCTYPE html>
<html>
<?php
$linkCss = 'css-js/style.css';
$linkLogo = 'images/logo.png';
$title = $activity['name'];
include 'includes/head.php';
?>

<body>
  <?php include 'includes/header.php'; ?>
  <div class="container col-md-6">
    <?php
    $path = 'images/activities/';
    include 'includes/image.php';
    ?>
  </div>
  <main>
    <div class="container mb-5 text-center">
        <h1><b><?php echo $activity['name']; ?></b></h1>
        <div class="row mt-5 text-white">
            <div class="col-8" style="border-right:solid #59A859">
                <img class="images img-thumbnail" src="<?php echo $image0; ?>" alt="">
            </div>
            <div class="col-4" style="background: ForestGreen">
                <div class="row">
                    <div class="fs-1">
                        Notation
                    </div>
                    <hr size = "5">
                    <div>
                        <i class="bi-star-half" style="font-size: 4rem; color: yellow"></i>
                        <p class="fs-2">
                            <?php
                            $query = $db->prepare(
                              'SELECT AVG(notation) FROM COMMENT WHERE id_reservation IN (SELECT id FROM RESERVATION WHERE id_activity = 0)'
                            );
                            $query->execute([
                              ':id' => $id,
                            ]);
                            $notation = $query->fetch(PDO::FETCH_COLUMN);
                            if ($notation == 0) {
                              echo 'Pas de notation';
                            } else {
                              echo $notation;
                            }
                            ?>
                        </p>
                    </div>
                    <hr size="5" style="margin:0">
                </div>
                <div class="row">
                    <div class="col-6 pt-4">
                        <p class="fs-3">Nombre de réservation</p>
                        <p class="fs-4">
                            <?php
                            $query = $db->prepare('SELECT COUNT(*) FROM RESERVATION WHERE id_activity = :id');
                            $query->execute([
                              ':id' => $id,
                            ]);
                            $count = $query->fetch(PDO::FETCH_COLUMN);
                            if ($count == 0) {
                              echo 'Aucune réservation';
                            } elseif ($count == 1) {
                              echo $count . ' réservation';
                            } else {
                              echo $count . ' réservations';
                            }
                            ?>
                        </p>
                    </div>
                    <div class="col pt-4" style="border-left:solid #59A859">
                        <p class="fs-3" style="margin:0">Réserver</p>
                        <?php if (isset($_SESSION['email'])) {
                          echo '<a href="" class="btn btn-primary">Bouton pour aller sur la page de reservation</a>
                            ';
                        } else {
                          echo '<a href="login.php" class="btn btn-primary">Se connecter</a>
                            ';
                        } ?>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'includes/msg.php'; ?>

        <hr class="mb-3">
        <h2>Catégories</h2>
        <div class="d-flex justify-content-center">
            <?php
            $query = $db->prepare(
              'SELECT name FROM CATEGORY WHERE id IN (SELECT id_category FROM BELONG WHERE id_activity = :id)'
            );
            $query->execute([
              ':id' => $id,
            ]);
            $categories = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($categories as $category) {
              echo '<a href="" class="btn btn-primary col-2 mx-1">' . $category['name'] . '</a>';
            }
            ?>
        </div>

        <hr class="mt-3">

        <div class="row mt-5">
            <h2 class="col-12"><b>Images de l'activité</b></h2>
        </div>

        <div id="showcase" class="carousel slide" data-bs-ride="carousel" data-interval="5000">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#showcase" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#showcase" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#showcase" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="<?php echo $image1; ?>" class="d-block w-100">
          </div>
          <div class="carousel-item">
            <img src="<?php echo $image2; ?>" class="d-block w-100">
          </div>
          <div class="carousel-item">
            <img src="<?php echo $image3; ?>" class="d-block w-100">
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#showcase" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#showcase" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>

    <div class="container">
        <h1 class="mb-5">Description de l'activité</h1>
        <p class="fs-5" style="margin-left: 30px; margin-right: 30px"><?php echo $activity['description']; ?></p>

        <hr class="my-5" size="5">

        <h2 class="text-center">Details de l'activité</h2>
        <hr style="margin-bottom:0" size="5">
        <div class="row">
            <div class="col-4 text-center" style="border-right:solid #C0C1B0">
                <h4>Durée de l'activité</h4>
                <div class="text-center">
                    <p class="fs-5"><?php echo $activity['duration']; ?>  h</p>
                </div>
            </div>
            <div class="col-4 text-center" style="border-right:solid #C0C1B0">
                <h4>Prix par participants</h4>
                <div class="text-center">
                    <p class="fs-5"><?php echo $activity['priceAttendee']; ?> € / personne</p>
                </div>
            </div>
            <div class="col-4 text-center">
                <h4>Nombre maximum de participants</h4>
                <div class="text-center">
                    <p class="fs-5"><?php echo $activity['maxAttendee']; ?> personnes</p>
                </div>
            </div>
        </div>
        <hr style="margin:0" size="5">
        <div class="row">
            <div class="col-6" style="border-right:solid #C0C1B0">
            <h2 class="text-center mb-3">Prestataires</h2>
                <ul class="text-center no-dot" style="padding:0">
                    <?php
                    $query = $db->prepare(
                      'SELECT firstName, lastName FROM PROVIDER WHERE id IN (SELECT id_provider FROM ANIMATE WHERE id_activity = :id)'
                    );
                    $query->execute([
                      ':id' => $id,
                    ]);
                    $providers = $query->fetchAll(PDO::FETCH_ASSOC);
                    if (empty($providers)) {
                      echo '<p class="text-center fs-3">Aucun prestataire</p>';
                    } else {
                      foreach ($providers as $provider) {
                        echo '<li class="fs-3">' . $provider['firstName'] . ' ' . $provider['lastName'] . '</li>';
                      }
                    }
                    ?>
                </ul>
            </div>
            <div class="col-6">
            <h2 class="text-center mb-3">Matériel fourni</h2>
                <ul class="text-center no-dot" style="padding:0">
                    <?php
                    $query = $db->prepare(
                      'SELECT MATERIAL_ACTIVITY.quantity, type FROM MATERIAL, MATERIAL_ACTIVITY WHERE id IN (SELECT id_activity FROM MATERIAL_ACTIVITY WHERE id = :id)'
                    );
                    $query->execute([
                      ':id' => $id,
                    ]);
                    $materials = $query->fetchAll(PDO::FETCH_ASSOC);
                    if (empty($materials)) {
                      echo '<p class="text-center fs-3">Aucun matériel fourni</p>';
                    } else {
                      foreach ($materials as $material) {
                        echo '<li class="fs-3">' . $material['quantity'] . ' ' . $material['type'] . '</li>';
                      }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
  </main>
  <?php include 'includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>