<?php
include 'db.php';

$company = htmlspecialchars($_GET['company']);

if ($company) {
  $req = $db->prepare('SELECT token FROM COMPANY WHERE email = :email');
} else {
  $req = $db->prepare('SELECT token FROM PROVIDER WHERE email = :email');
}

$req->execute([
  'email' => htmlspecialchars($_GET['email']),
]);
$result = $req->fetch(PDO::FETCH_ASSOC);
foreach ($result as $existToken) {
  if ($existToken != '') { ?>
    <!DOCTYPE html>
    <html lang="fr">
    <?php
    $linkLogo = '../images/logo.png';
    $linkCss = '../css-js/style.css';
    $title = 'Modification du mot de passe';
    include '../includes/head.php';
    ?>

    <body>
      <?php
      include '../includes/header.php';
      $email = htmlspecialchars($_GET['email']);
      $token = htmlspecialchars($_GET['token']);
      ?>

      <form action="../verifications/passwordScript.php?email=<?= $email ?>&token=<?= $token ?>&company=<?= $company ?>" method="post">
        <div class="container col-md-6">
          <?php include 'msg.php'; ?>
        </div>
        <div class="container col-md-4" id="form">
          <div class="mb-3">
            <label for="login" class="form-label"><strong>Mot de passe</strong></label>
            <input type="password" class="form-control" name="password" required>
            <label for="login" class="form-label"><strong>Retaper votre mot de passe</strong></label>
            <input type="password" class="form-control" name="confPassword" required><br>
            <button type="submit" name="submit" class="btn">Envoyer</button>
          </div>
        </div>
      </form>


      <?php include '../includes/footer.php'; ?>
    </body>

    </html>
<?php } else {header('location: ../login.php?message=Le lien à expiré !&type=danger');
    exit();}
} ?>
