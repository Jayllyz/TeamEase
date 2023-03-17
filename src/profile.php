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

  <main>
  <div class="container section-about-us border border-2 border-secondary rounded">
    <div>
      <div class="row">
        <h1>Informations de l'entreprise</h1>
          
          <br>

          <?php
            
            $sql = "SELECT * FROM COMPANY WHERE siret = :siret";
            $stmt = $db->prepare($sql);
            $stmt->execute([
              "siret" => $_SESSION["siret"],

            ]);
            $company = $stmt->fetch();

            ?>
            <br>
            <br>
            <br>

            <div class="text-center">
            <?php
            echo "Siret :   n°";
            echo $company['siret']; //Ne pas changer
            echo '<br>';
            echo '<br>';
            echo '<br>';
            echo "Nom de l'entreprise :   ";
            echo $company['companyName']; // Ne pas Changer
            echo '<br>';
            echo '<br>';
            echo '<br>';
            echo "Email :    ";
            echo $company['email']; // Changer
            echo '<br>';
            echo '<br>';
            echo '<br>';
            echo "Adresse :   ";
            echo $company['address']; // Ne pas changer
            echo '<br>';
            

            ?>
            
            <br>
              <button class="btn-read">
              <a href="modify.php?siret=<?= $company["siret"] ?>&name=<?= $company["companyName"] ?>&email=<?= $company["email"] ?>&rights=<?= $company["rights"] ?>" class="btn ms-2 me-2">Modifier</a>
              </button>
            </div>
      </div>
    </div>
  </div>

  <div class="container section-about-us border border-2 border-secondary rounded">
    <div>
      <div class="row">
          <h1>Dernières Réservation</h1>
          <?php
            
            $sql = "SELECT * FROM RESERVATION WHERE siret = :siret";
        

            $stmt = $db->prepare($sql);
            $stmt->execute([
              "siret" => $_SESSION["siret"],

            ]);
            $reservations = $stmt->fetchAll();
            foreach ($reservations as $reservation) {
              echo $reservation['id']; //Ne pas changer
              echo '<br>';
              echo $reservation['attendee']; // Ne pas Changer
              echo '<br>';
              echo $reservation['id_activity']; // Changer
              echo '<br>';
              echo $reservation['siret']; // Ne pas changer
              echo '<br>';
              echo $reservation['id_location']; // Changer
              echo '<br>';
              
            }
          

          
          ?>
          <br>
      </div>

    </div>
  </div>

  <div class="container section-about-us border border-2 border-secondary rounded">
    <div>
      <div class="row">
          <h1>Dernière Activités</h1>
          <br>
      </div>

    </div>
  </div>

  </main>
  <?php include 'includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>