<?php session_start(); ?>

<?php include 'includes/db.php'; ?>

<!DOCTYPE html>
<html>
<?php
$linkCss = 'css-js/style.css';
$linkLogo = 'images/logo.png';
$title = 'Accueil';
include 'includes/head.php';
?>

<body>
  <?php include 'includes/header.php'; ?>
  <div class="container col-md-6">
    <?php include "includes/msg.php"; ?>
  </div>
  <main>
    <div class="container rounded main-text">

      <br>

      <div class="container section-about-us border border-2 border-secondary rounded">
        <div>
          <div class="row">
            <div class="col">
              <h1 class="text-center">À propos de nous</h1>
              <br>
              <p class="text-center lh-sm fs-4">
                Chez TeamEase, nous sommes passionnés par la création d'expériences de teambuilding efficaces pour les entreprises de toutes tailles et de tous secteurs.
                Nous proposons une large gamme d'activités de teambuilding personnalisées pour améliorer la communication, la collaboration et l'efficacité de votre équipe.
                Contactez-nous dès maintenant pour planifier votre prochain événement de teambuilding avec TeamEase.
              </p>
            </div>
            <div class="col">
              <img class="rounded img-fluid border border-2 border-secondary rounded" src="images/placeholderTeam.jpg" alt="placeholder">
            </div>
          </div>
          <div class="row">
            <div class="col-6 text-center">
              <a href="" class="btn btn-success" type="submit" style="padding-right: 20px; padding-left:20px">En savoir plus</a>
            </div>
          </div>
        </div>
      </div>

      <br>

      <div class="container section-title rounded align-text-bottom">
        <div class="row align-items-center">
          <div class="col-8">
            <h5>Catégorie</h5>
          </div>
          <div class="col-4 d-grid gap-2 d-md-flex justify-content-md-end">
            <a class="btn btn-outline-light" type="submit" href="categorie.php">Voir plus</a>
          </div>
        </div>
      </div>

      <br>

      <div class="container">
        <div class="row">
          <?php for ($i = 0; $i < 4; $i++) {
            echo '
          <div class="col-md col-sm-6 mb-3">
            <div class="card">
              <a href="page_activite.php?activite=#"><img class="rounded img-fluid" src="images/activities/placeholder.jpg" alt="placeholder"></a>
            </div>
          </div>';
          } ?>
        </div>
      </div>

      <br>

      <div class="container section-title rounded align-text-bottom">
        <div class="row align-items-center">
          <div class="col-8">
            <h5>Activités</h5>
          </div>
          <div class="col-4 d-grid gap-2 d-md-flex justify-content-md-end">
            <a class="btn btn-outline-light" type="submit" href="catalog.php">Voir plus</a>
          </div>
        </div>
      </div>

      <div class="container">
        <div class="row">
          <?php for ($i = 0; $i < 4; $i++) {
            echo '
          <div class="col-md col-sm-6 mb-3">
            <div class="card">
              <a href="page_activite.php?activite=#"><img class="rounded img-fluid" src="images/activities/placeholder.jpg" alt="placeholder"></a>
            </div>
          </div>';
          } ?>
        </div>
      </div>

    </div>
  </main>
  <?php include 'includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>