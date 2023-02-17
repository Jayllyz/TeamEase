<?php session_start(); ?>
<?php include 'includes/db.php'; ?>

<!DOCTYPE html>
<html>

<?php
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
            <div class="text-center">
            <a href="addActivityPage.php" class="btn btn-secondary">Ajouter une activité</a>
            </div>
        </div>
        <hr size="5">

        <?php include 'includes/errorMessage.php'; ?>

        <?php
        $query = $db->query('SELECT id FROM ACTIVITY');
        $id = $query->fetchAll(PDO::FETCH_COLUMN);
        $countId = count($id);
        for ($i = 0; $i < $countId; $i++) {
          $query = $db->prepare('SELECT name FROM ACTIVITY WHERE id =:id');
          $query->execute([
            ':id' => $id[$i],
          ]);
          $activity = $query->fetchAll(PDO::FETCH_COLUMN);
          $query = $db->prepare('SELECT name FROM CATEGORY WHERE id = :id');
          $query->execute([
            ':id' => $id[$i],
          ]);
          $category = $query->fetchAll(PDO::FETCH_COLUMN);
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
          $query = $db->prepare('SELECT companyName FROM COMPANY, SERVICE WHERE SERVICE.id_activity = :id');
          $query->execute([
            ':id' => $id[$i],
          ]);
          $company = $query->fetchAll(PDO::FETCH_COLUMN);
          echo '
          <div class="card text-light bg-secondary mb-3">

          <div class="row">
          <div class="col-4">
          <a href="activity.php?jeu=' .
            $id[$i] .
            '"><img src="images/activities/0' .
            $activity[0] .
            '0.jpg" class="card-img-top" alt="' .
            $activity[0] .
            '"></a>
          </div>
          <div class="card-body col-8 row">
          <div class="col-11 card-title">
          <h4><a href="activity.php?jeu=' .
            $id[$i] .
            '" class="text-light">' .
            $activity[0] .
            '</a></h4>
          </div>';
        }
        $altId = str_replace(' ', '-', $activity[0]); //On remplace les espaces par des . pcq sinon ca passe pas en id pour les modals/popup
        echo '
            <div class="modal fade popup" id="suppression' .
          $altId .
          '" tabindex="-1" aria-labelledby="suppressionLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="suppressionLabel">Suppression</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            Etes-vous sûr de supprimer cette activité de la base de donnée? Cet action est irréversible !
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <a type="button" class="btn btn-danger" href="verification_jeu.php?delete=' .
          $altId .
          '">Supprimer</a>
            </div>
            </div>
            </div>
            </div>

            <p>Developpé par ' .
          $developpeur[$i] .
          '   |   Publié le ' .
          $jour .
          ' ' .
          $mois .
          ' ' .
          $annee .
          '   |   <img src="images/views-light.png" style="width: 20px;"> ' .
          $vues .
          ' vues</p>
            <p>' .
          $extrait[0] .
          '...</p>

            </div>
            </div>
            </div>';
        if ($countId == 0) {
          echo '<div class="row">
            <div class="col-12">
                <h3 class="text-center text-secondary fs-2">Aucune activité</h3>
            </div>';
        }
        ?>
    </div>
  </main>
  <?php include 'includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>