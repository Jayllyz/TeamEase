<?php session_start(); ?>
<?php include 'includes/db.php'; ?>

<!DOCTYPE html>
<html>
<?php
$title = 'Ajouter une activité';
include 'includes/head.php';
?>

<body>
  <?php include 'includes/header.php'; ?>
  <main>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">Ajouter une activité</h1>
            </div>
        </div>
        <form action="verifications/verificationActivity.php" method="post" enctype="multipart/form-data">
            <?php
            $query = $db->query('SELECT name FROM CATEGORY');
            $fetch = $query->fetchAll(PDO::FETCH_COLUMN);
            $count = count($fetch);
            echo '<label for="genre" class="form-label"><h4>Catégorie de l\'activité</h4></label>
                            <div class="row mb-3">';
            for ($i = 0; $i < $count; $i++) {
              if ($i % 6 == 0 and $i != 0) {
                echo '
                                </div>
                                <div class="row mb-3">
                                <input type="checkbox" class="btn btn-check" id="' .
                  $fetch[$i] .
                  '" name="category[]" value="' .
                  $fetch[$i] .
                  '" autocomplete="off">
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
                  '" autocomplete="off">
                                <label class="btn btn-outline-success col me-2 mb-3" for="' .
                  $fetch[$i] .
                  '">' .
                  $fetch[$i] .
                  '</label>';
              }
            }
            if (($count + 1) % 6 != 0) {
              echo '</div>';
            }
            ?>

            <div class="my-3">
                <label for="name" class="form-label"><h4>Nom de l'activité</h4></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nom de l'activité" required>
            </div>
            <div class="form-group mb-4">
                <label for="description"><h4>Description de l'activité</h4></label>
                <textarea class="form-control" id="description" name="description" rows="10" required></textarea>
            </div>
            <div class="row">
                <div class="col">
                    <label for="duration"><h4>Durée de l'activité (en heure)</h4></label>
                    <input type="number" name="duration" class="form-control" required>
                </div>
                <div class="col">
                    <label for="price"><h4>Prix par personne (en €)</h4></label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="col">
                    <label for="maxAttendee"><h4>Nombre maximum de participants</h4></label>
                    <input type="number" name="maxAttendee" class="form-control" required>
                </div>
            </div>
            <br>
            <div class="row">
                <label for="mainImage" class="form-label"><h4>Images de présentation</h4></label>
                <div class="col-3 mb-3">
                    <input class="form-control" name="mainImage" type="file" accept="image/jpeg, image/png" required>
                </div>
                <div class="col-3 mb-3">
                    <input class="form-control" name="secondImage" type="file" accept="image/jpeg, image/png">
                </div>
                <div class="col-3 mb-3">
                    <input class="form-control" name="thirdImage" type="file" accept="image/jpeg, image/png">
                </div>
                <div class="col-3 mb-3">
                    <input class="form-control" name="fourthImage" type="file" accept="image/jpeg, image/png">
                </div>
                <button type="submit" class="btn btn-success btn-lg">Valider</button>
            </div>
        </form>
        <?php include 'includes/errorMessage.php'; ?>
    </div>
  </main>
  <?php include 'includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>