<?php session_start(); ?>
<?php include '../includes/db.php'; ?>
<!DOCTYPE html>
<html>
  <?php
  $linkCss = 'css-js/style.css';
  $linkLogo = 'images/logo.png';
  $title = 'AjaxReq';
  include '../includes/head.php';
  ?>
   <body>
    <main>
    <?php
    $query = $db->query('SELECT name FROM OCCUPATION');
    $fetch = $query->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="btn-group">
        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            Métier
        </button>
        <ul class="dropdown-menu">
            <?php foreach ($fetch as $occupation) {
              echo '<li><a class="dropdown-item" href="#" onclick="selectOccupation(this)">' .
                $occupation['name'] .
                '</a></li>';
            } ?>
        </ul>
    </div>
    <button type="button" class="btn btn-danger" onclick="deleteProvider(this)">Supprimer</button>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>