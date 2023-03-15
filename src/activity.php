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
        <?php include 'includes/msg.php'; ?>
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

        <hr class="mb-3">
        <h2>Catégories
        <?php if ($_SESSION['rights'] == 2) {
          echo '<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#edition-category">Modifier</button>';
        } ?>
        </h2>
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
        <h1 class="mb-5">Description de l'activité
            <?php if ($_SESSION['rights'] == 2) {
              echo '<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#edition-description">Modifier</button>';
            } ?>
        </h1>
        <p class="fs-5" style="margin-left: 30px; margin-right: 30px"><?php echo $activity['description']; ?></p>

        <hr class="my-5" size="5">

        <h2 class="text-center">Details de l'activité
        <?php if ($_SESSION['rights'] == 2) {
          echo '<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#edition-details">Modifier</button>';
        } ?>
        </h2>
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
                    <p class="fs-5"><?php
                    echo $activity['maxAttendee'];
                    if ($activity['maxAttendee'] == 1) {
                      echo ' personne';
                    } else {
                      echo ' personnes';
                    }
                    ?></p>
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
                      'SELECT id, type FROM MATERIAL WHERE id IN (SELECT id_material FROM MATERIAL_ACTIVITY WHERE id_activity = :id)'
                    );
                    $query->execute([
                      ':id' => $id,
                    ]);
                    $materials = $query->fetchAll(PDO::FETCH_ASSOC);
                    if (empty($materials)) {
                      echo '<p class="text-center fs-3">Aucun matériel fourni</p>';
                    } else {
                      foreach ($materials as $material) {
                        $query = $db->prepare(
                          'SELECT quantity FROM MATERIAL_ACTIVITY WHERE id_material = :id_material AND id_activity = :id_activity'
                        );
                        $query->execute([':id_material' => $material['id'], ':id_activity' => $id]);
                        $material['quantity'] = $query->fetch(PDO::FETCH_ASSOC)['quantity'];
                        echo '<li class="fs-3">' . $material['quantity'] . ' ' . $material['type'] . '</li>';
                      }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Modal edition description -->
    <div class="modal fade popup" id="edition-description" tabindex="-1" aria-labelledby="editionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editionLabel">Edition de la description</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="verifications/verifActivity.php?update=description&id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control" rows="10"><?php echo $activity[
                              'description'
                            ]; ?></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Sauvegarder</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal edition details -->
    <div class="modal fade popup" id="edition-details" tabindex="-1" aria-labelledby="editionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editionLabel">Edition des détails</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="verifications/verifActivity.php?update=details&id=<?php echo $id; ?>" method="post" id="activity-form" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="duration">Durée de l'activité</label>
                            <input type="number" name="duration" class="form-control" value=<?php echo $activity[
                              'duration'
                            ]; ?>>
                            <label for="priceAttendee">Prix par participants</label>
                            <input type="number" name="priceAttendee" class="form-control" value=<?php echo $activity[
                              'priceAttendee'
                            ]; ?>>
                            <label for="maxAttendee">Nombre maximum de participants</label>
                            <input type="number" name="maxAttendee" class="form-control" value=<?php echo $activity[
                              'maxAttendee'
                            ]; ?>>
                            <div class="mb-4">
                                <label for="provider" class="form-label"><h4>Prestataires</h4></label>
                                <div id="provider-container">
                                    <?php
                                    $query = $db->query('SELECT name FROM OCCUPATION');
                                    $occupations = $query->fetchAll(PDO::FETCH_ASSOC);
                                    $query = $db->prepare(
                                      'SELECT p.id, p.firstName, UPPER(p.lastName), o.name FROM PROVIDER p JOIN OCCUPATION o ON p.id_occupation = o.id WHERE p.id IN (SELECT id_provider FROM ANIMATE WHERE id_activity = :id)'
                                    );
                                    $query->execute([
                                      ':id' => $id,
                                    ]);
                                    $providers = $query->fetchAll(PDO::FETCH_ASSOC);
                                    $query = $db->prepare(
                                      'SELECT id_material, quantity FROM MATERIAL_ACTIVITY WHERE id_activity = :id'
                                    );
                                    $query->execute([
                                      ':id' => $id,
                                    ]);
                                    $materials = $query->fetchAll(PDO::FETCH_ASSOC);
                                    $i = 0;
                                    foreach ($providers as $provider) {
                                      $query = $db->query(
                                        'SELECT id, firstName, UPPER(lastName) FROM PROVIDER WHERE id_occupation IN (SELECT id FROM OCCUPATION WHERE name = "' .
                                          $providers[$i]['name'] .
                                          '")'
                                      );
                                      $dropdownProvider = $query->fetchAll(PDO::FETCH_ASSOC);
                                      echo '
                                      <div class="mb-4">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle" style="padding-left:50px; padding-right:50px" data-bs-toggle="dropdown" aria-expanded="false">
                                                ';
                                      echo $providers[$i]['name'];
                                      echo '
                                            </button>
                                            <button type="button" class="btn btn-secondary dropdown-toggle mx-2 selected" id="';
                                      echo $providers[$i]['id'];
                                      echo '" data-bs-toggle="dropdown" aria-expanded="false">
                                    ';
                                      echo $providers[$i]['firstName'] . ' ' . $providers[$i]['UPPER(p.lastName)'];
                                      echo '
                                        </button>
                                            <ul class="dropdown-menu">';
                                      foreach ($occupations as $occupation) {
                                        echo '<li><a class="dropdown-item" onclick="selectOccupation(this)" style="padding-left:50px; padding-right:50px">' .
                                          $occupation['name'] .
                                          '</a></li>';
                                      }
                                      echo '
                                            </ul>
                                        </div>
                                    
                                    <button type="button" class="btn btn-danger" onclick="unassignProvider(this)">Supprimer</button>
                                    <ul class="dropdown-menu">';
                                      foreach ($dropdownProvider as $provider2) {
                                        echo '<li><a class="dropdown-item" onclick="selectProvider(this)" id="' .
                                          $provider2['id'] .
                                          '">' .
                                          $provider2['firstName'] .
                                          ' ' .
                                          $provider2['UPPER(lastName)'] .
                                          '</a></li>';
                                      }
                                      echo '</ul>
                                    </div>';
                                      $i++;
                                    }
                                    ?>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary" onclick="assignProvider()">Ajouter un prestataire</button>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="material" class="form-label"><h4>Matériels</h4></label>
                                <div id="material-container" class="mb-4">
                                    <?php
                                    $query = $db->query(
                                      'SELECT id, type FROM MATERIAL WHERE id IN (SELECT id_material FROM MATERIAL_ACTIVITY WHERE id_activity = ' .
                                        $id .
                                        ')'
                                    );
                                    $materials = $query->fetchAll(PDO::FETCH_ASSOC);
                                    $query = $db->query('SELECT id,type FROM MATERIAL');
                                    $dropdownMaterial = $query->fetchAll(PDO::FETCH_ASSOC);
                                    $i = 0;
                                    foreach ($materials as $material) {
                                      $query = $db->query(
                                        'SELECT quantity FROM MATERIAL_ACTIVITY WHERE id_material = ' .
                                          $material['id'] .
                                          ' AND id_activity = ' .
                                          $id .
                                          ''
                                      );
                                      $quantity = $query->fetch(PDO::FETCH_ASSOC);
                                      echo '
                                      <div class="mb-4">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle selected" style="padding-left:50px; padding-right:50px" data-bs-toggle="dropdown" aria-expanded="false" id="';
                                      echo $material['id'];
                                      echo '">
                                                ';
                                      echo $material['type'];
                                      echo '
                                            </button>
                                            <ul class="dropdown-menu">';
                                      foreach ($dropdownMaterial as $material2) {
                                        echo '<li><a class="dropdown-item" onclick="selectMaterial(this);" id="' .
                                          $material2['id'] .
                                          '" style="padding-left:50px; padding-right:50px">' .
                                          $material2['type'] .
                                          '</a></li>';
                                      }
                                      $i++;
                                      echo '
                                            </ul>
                                            <div class="inputNumber mx-2">
                                                <input type="number" id="';
                                      echo $material['id'];
                                      echo '" onchange="quantityChange(this.value, this.id)" class="form-control" value="';
                                      echo $quantity['quantity'];
                                      echo '">
                                            </div>
                                            <button type="button" class="btn btn-danger" onclick="unassignMaterial(this)">Supprimer</button>
                                        </div>
                                    </div>
                                    ';
                                    }
                                    ?>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary" onclick="assignMaterial()">Ajouter du matériel</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Sauvegarder</button>
                        </div>
                        <?php
                        $query = $db->prepare('SELECT id_provider FROM ANIMATE WHERE id_activity = :id');
                        $query->execute([
                          ':id' => $id,
                        ]);
                        $providers = $query->fetchAll(PDO::FETCH_ASSOC);
                        if (!empty($providers)) {
                          foreach ($providers as $provider) {
                            echo '<input type="hidden" name="provider' .
                              $provider['id_provider'] .
                              '" id="provider' .
                              $provider['id_provider'] .
                              '" value="' .
                              $provider['id_provider'] .
                              '">';
                          }
                        }
                        $query = $db->prepare(
                          'SELECT id_material, quantity FROM MATERIAL_ACTIVITY WHERE id_activity = :id'
                        );
                        $query->execute([
                          ':id' => $id,
                        ]);
                        $materials = $query->fetchAll(PDO::FETCH_ASSOC);
                        if (!empty($materials)) {
                          foreach ($materials as $material) {
                            echo '<input type="hidden" name="material' .
                              $material['id_material'] .
                              '" id="material' .
                              $material['id_material'] .
                              '" value="' .
                              $material['id_material'] .
                              '">';
                            echo '<input type="hidden" name="quantity' .
                              $material['id_material'] .
                              '" id="quantity' .
                              $material['id_material'] .
                              '" value="' .
                              $material['quantity'] .
                              '">';
                          }
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal edition categorie -->
    <div class="modal fade popup" id="edition-category" tabindex="-1" aria-labelledby="editionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editionLabel">Edition des catégories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="verifications/verifActivity.php?update=category&id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <?php
                            $query = $db->query('SELECT name FROM CATEGORY');
                            $fetch = $query->fetchAll(PDO::FETCH_COLUMN);
                            $count = count($fetch);
                            $query = $db->prepare(
                              'SELECT name FROM CATEGORY WHERE id IN (SELECT id_category FROM BELONG WHERE id_activity = :id)'
                            );
                            $query->execute([
                              ':id' => $id,
                            ]);
                            $categories = $query->fetchAll(PDO::FETCH_ASSOC);
                            echo '<label for="genre" class="form-label"><h4>Catégories de l\'activité</h4></label>
                                 <div class="row mb-3">';
                            for ($i = 0; $i < $count; $i++) {
                              if ($i % 3 == 0 && $i != 0) {
                                echo '
                                        </div>
                                        <div class="row mb-3">
                                        <input type="checkbox" class="btn btn-check" id="' .
                                  $fetch[$i] .
                                  '" name="category[]" value="' .
                                  $fetch[$i] .
                                  '" autocomplete="off"';
                                foreach ($categories as $category) {
                                  if ($category['name'] == $fetch[$i]) {
                                    echo 'checked';
                                  }
                                }
                                echo '>
                                        <label class="btn btn-outline-success col me-2 mb-3" for="' .
                                  $fetch[$i] .
                                  '">' .
                                  $fetch[$i] .
                                  '</label>
                                        ';
                              } else {
                                echo '<input type="checkbox" class="btn btn-check" id="' .
                                  $fetch[$i] .
                                  '" name="category[]" value="' .
                                  $fetch[$i] .
                                  '" autocomplete="off"';
                                foreach ($categories as $category) {
                                  if ($category['name'] == $fetch[$i]) {
                                    echo 'checked';
                                  }
                                }
                                echo '>
                                        <label class="btn btn-outline-success col me-2 mb-3" for="' .
                                  $fetch[$i] .
                                  '">' .
                                  $fetch[$i] .
                                  '</label>';
                              }
                            }
                            if (($count + 1) % 3 != 0) {
                              echo '</div>';
                            }
                            ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Sauvegarder</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
  </main>
  <?php include 'includes/footer.php'; ?>
  <script src="css-js/scripts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>