<?php session_start(); ?>
<?php include 'includes/db.php'; ?>

<!DOCTYPE html>
<html>

<?php
$linkCss = 'css-js/style.css';
$linkLogo = 'images/logo.png';
$title = 'Catalogue des activités';
include 'includes/head.php';
?>

<body>
  <?php include 'includes/header.php'; ?>
  <main>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">Catalogue des activités</h1>
            </div>
        </div>
        <br>
        <div class="row">
          <?php if (isset($_SESSION['rights']) && $_SESSION['rights'] == 2) {
            echo '
            <div class="text-center">
            <a href="addActivityPage.php" class="btn btn-secondary">Ajouter une activité</a>
            </div>';
          } ?>
        </div>
        <hr size="5">

        <?php include 'includes/msg.php'; ?>

        <?php
        $query = $db->query('SELECT id FROM ACTIVITY');
        $id = $query->fetchAll(PDO::FETCH_COLUMN);
        $countId = count($id);
        if ($countId == 0) {
          echo '<div class="my-5">
            <h3 class="py-5"><p class="text-secondary text-center">Aucune activité trouvée</p></h3>
          </div>';
        } else {
          for ($i = 0; $i < $countId; $i++) {
            $query = $db->prepare('SELECT name FROM ACTIVITY WHERE id =:id');
            $query->execute([
              ':id' => $id[$i],
            ]);
            $activity = $query->fetchAll(PDO::FETCH_COLUMN);
            $query = $db->prepare('SELECT SUBSTRING(description, 1, 450) FROM ACTIVITY WHERE id = :id');
            $query->execute([
              ':id' => $id[$i],
            ]);
            $description = $query->fetchAll(PDO::FETCH_COLUMN);
            $query = $db->prepare('SELECT duration FROM ACTIVITY WHERE id = :id');
            $query->execute([
              ':id' => $id[$i],
            ]);
            $duration = $query->fetchAll(PDO::FETCH_COLUMN);
            $query = $db->prepare('SELECT priceAttendee FROM ACTIVITY WHERE id = :id');
            $query->execute([
              ':id' => $id[$i],
            ]);
            $priceAttendee = $query->fetchAll(PDO::FETCH_COLUMN);
            $query = $db->prepare('SELECT maxAttendee FROM ACTIVITY WHERE id = :id');
            $query->execute([
              ':id' => $id[$i],
            ]);
            $maxAttendee = $query->fetchAll(PDO::FETCH_COLUMN);
            $query = $db->prepare('SELECT id_category FROM BELONG WHERE id_activity = :id');
            $query->execute([
              ':id' => $id[$i],
            ]);
            $countCategory = count($query->fetchAll(PDO::FETCH_COLUMN));
            $category = $query->fetchAll(PDO::FETCH_COLUMN);
            echo '
          <div class="card text-light bg-secondary mb-3">

          <div class="row">
          <div class="col-4">
          <a href="activity.php?id=' .
              $id[$i] .
              '"><img src="images/activities/' .
              $id[$i] .
              $activity[0] .
              '0.jpg" class="card-img-top" alt="' .
              $activity[0] .
              '"></a>
          </div>
          <div class="card-body col-8 row">
            <div class="col-11 card-title">
            <h4><a href="activity.php?id=' .
              $id[$i] .
              '" class="text-light">' .
              $activity[0] .
              '</a></h4>
            </div>';
            $altId = str_replace(' ', '-', $id[0]); //On remplace les espaces par des . pcq sinon ca passe pas en id pour les modals/popup
            if (isset($_SESSION['rights']) && $_SESSION['rights'] == 2) {
              echo '
              <div class="col-1 d-flex justify-content-end pe-3">
              <button type="button" class="btn-close btn-danger btn-sm" aria-label="Close" data-bs-toggle="modal" data-bs-target="#suppression' .
                $altId .
                '"></button>
              </div>';
            }
            echo '
              <div class="modal fade popup" id="suppression' .
              $altId .
              '" tabindex="-1" aria-labelledby="suppressionLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title text-dark" id="suppressionLabel">Suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body text-dark">
                        Etes-vous sûr de supprimer cette activité de la base de donnée? Cet action est irréversible !
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <a type="button" class="btn btn-danger" href="verifications/verifActivity.php?delete=' .
              $altId .
              '">Supprimer</a>
                      </div>
                  </div>
                </div>
              </div>
              <p>' .
              $description[0] .
              '</p>
            <p class="mb-0">';
            for ($j = 0; $j < $countCategory; $j++) {
              $query = $db->prepare(
                'SELECT name FROM CATEGORY WHERE id IN (SELECT id_category FROM BELONG WHERE :id_activity = :id_activity)'
              );
              $query->execute([':id_activity' => $id[$i]]);
              $categoryName = $query->fetchAll(PDO::FETCH_COLUMN);
              echo '<a type="button" class="btn btn-info mx-2" href="">' . $categoryName[0] . '</a>';
            }
            echo '<div class="row"></p>
            <p class="fs-6 mb-0 col">Durée de l\'activité : <i class="bi bi-clock" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Durée de l\'activité"></i> ' .
              $duration[0] .
              'h | Prix par participants : ' .
              $priceAttendee[0] .
              '<i class="bi bi-currency-euro"></i> / <i class="bi bi-person-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Prix par participant"></i>
             | Nombre maximum de participants : <i class="bi bi-people" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nombre maximum de participants"></i> ' .
              $maxAttendee[0] .
              '</p></div>';
            echo '
              </div>
              </div>
            </div>';
          }
        }
        ?>
    </div>
  </main>
  <?php include 'includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>