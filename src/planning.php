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
    <?php include 'includes/msg.php'; ?>
  </div>
  <main>
    <div class="container rounded main-text">


    <div class="container section-about-us border border-2 border-secondary rounded">
    <div class="container rounded align-text-bottom">
        <div class="row align-items-center">
          <div class="col-8">
            <h5>Planning</h5>
          </div>
        </div>
      </div>
        <table style="border: 1px solid black;" class="table text-center table-bordered table-hover" id="active">
                    <?php
                    

                    ?>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Lundi</th>
                            <th>Mardi</th>
                            <th>Mercredi</th>
                            <th>Jeudi</th>
                            <th>Vendredi</th>
                            <th>Samedi</th>
                            <th>Dimanche</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php for ($i = 0; $i < 10; $i++) { ?>
                        <tr>
                            <td><?= $i ?>h</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tbody>
                    <?php } ?>
                </table>

    </div>
    </div>
    </main>
  <?php include 'includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>
