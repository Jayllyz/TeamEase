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
<?php if (isset($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = 1;
} ?>
<body onload="populateActivity(<?php echo $page; ?>)">
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
      <div>
        <button class="btn btn-primary" onclick="filterMaxAttendee(<?php echo $page; ?>)">Nombre de participants</button>
      </div>
      <hr size="5">

      <?php include 'includes/msg.php'; ?>

      <div id="activities">
        
      </div>
    </div>
  </main>
  <?php include 'includes/footer.php'; ?>
  <script src="css-js/scripts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>