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
  <div class="container col-md-6">
    <?php include 'includes/msg.php'; ?>
  </div>

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
        </div>
      </div>

    <br>


