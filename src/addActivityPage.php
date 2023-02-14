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
        <form action="verificationActivity.php" method="post" enctype="multipart/form-data">
            <?php
            $query = $bdd->query('SELECT nom FROM CATEGORIE');
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
                  '" name="genre[]" value="' .
                  $fetch[$i] .
                  '" autocomplete="off">
                                <label class="btn btn-outline-primary col me-2 mb-3" for="' .
                  $fetch[$i] .
                  '">' .
                  $fetch[$i] .
                  '</label>
                                ';
              } else {
                echo '<input type="checkbox" class="btn btn-check" id="' .
                  $fetch[$i] .
                  '" name="genre[]" value="' .
                  $fetch[$i] .
                  '" autocomplete="off">
                                <label class="btn btn-outline-primary col me-2 mb-3" for="' .
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
                <label for="nom" class="form-label"><h4>Nom de l'activité</h4></label>
                <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom de l'activité" required>
            </div>
            <div class="form-group mb-4">
                <label for="description"><h4>Description de l'activité</h4></label>
                <textarea class="form-control" id="description" name="description" rows="10" required></textarea>
            </div>
            <div class="mb-4">
                <label for="date"><h4>Date de l'activité</h4></label>
                <input type="date" name="date" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="duree"><h4>Durée de l'activité (en heure)</h4></label>
                <input type="number" name="duree" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="prix"><h4>Prix par personne (en €)</h4></label>
                <input type="number" name="prix" class="form-control" required>
            </div>
            <div>
                <label for="maxParticipant"><h4>Nombre maximum de participants</h4></label>
                <input type="number" name="maxParticipant" class="form-control" required>
            </div>
            <div class="row">
                <label for="carousel" class="form-label"><h4>Images de présentation</h4></label>
                <div class="col-4">
                    <input class="form-control" name="carousel1" type="file" accept="image/jpeg" required>
                </div>
                <div class="col-4">
                    <input class="form-control" name="carousel2" type="file" accept="image/jpeg">
                </div>
                <div class="col-4 mb-4">
                    <input class="form-control" name="carousel3" type="file" accept="image/jpeg">
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