<?php session_start();
if (!isset($_SESSION['rights']) || $_SESSION['rights'] != 2) {
  header('location: ../index.php');
  exit();
}
include '../includes/db.php';
$siret = htmlspecialchars($_GET['siret']);
$name = htmlspecialchars($_GET['name']);
$email = htmlspecialchars($_GET['email']);
$rights = htmlspecialchars($_GET['rights']);

$req = $db->prepare('SELECT address FROM COMPANY WHERE siret = :siret');
$req->execute([
  'siret' => $siret,
]);
$address = $req->fetch(PDO::FETCH_ASSOC);

if ($_SESSION['rights'] == 2 && isset($_SESSION['siret'])) { ?>

<!DOCTYPE html>
<html lang="fr">
<?php
$linkLogo = '../images/logo.png';
$linkCss = '../css-js/style.css';
$title = "Modification de $name";
include '../includes/head.php';
?>

<body>

    <?php include '../includes/header.php'; ?>
    <main>
        <form action="verifUpdateCompany.php?siret=<?= $siret ?>" method="post">
            <div class="container col-md-6">
                <?php include '../includes/msg.php'; ?>
            </div>
            <div class="container col-md-4" id="form">
                <div class="mb-3">
                    <label class="form-label"><strong>Email</strong></label>
                    <input type="email" class="form-control" name="email" value="<?= $email ?>">
                    <label class="form-label mt-3"><strong>Nom de l'entreprise</strong></label>
                    <input type="text" class="form-control" name="companyName" value="<?= $name ?>">
                    <label class="form-label mt-3"><strong>Adresse</strong></label>
                    <input type="text" class="form-control" name="address" value="<?= $address['address'] ?>">
                    <label class="form-label mt-3"><strong>Droits</strong></label>
                    <input type="number" class="form-control" name="rights" value="<?= $rights ?>"
                        aria-describedby="helpRights">
                    <div id="helpRights" class="form-text">
                        <p>-1 = banni, 0 = entreprise, 1 = prestataire, 2 = administrateur</p>
                    </div>
                    <button type="submit" name="submit" class="btn mt-3 btn-submit">Confirmer</button>
                </div>
            </div>
        </form>
    </main>
    <?php include '../includes/footer.php'; ?>

    <script src="css-js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

</body>

</html>
<?php } else {header('location: ../index.php');
  exit();} ?>
