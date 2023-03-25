<?php session_start();
include 'includes/db.php';
$siret = htmlspecialchars($_GET['siret']);
$name = htmlspecialchars($_GET['name']);
$email = htmlspecialchars($_GET['email']);
$rights = htmlspecialchars($_GET['rights']);

$req = $db->prepare('SELECT address FROM COMPANY WHERE siret = :siret');
$req->execute([
  'siret' => $siret,
]);
$address = $req->fetch(PDO::FETCH_ASSOC);

if (isset($_SESSION['siret'])) { ?>

<!DOCTYPE html>
<html lang="fr">
<?php
    $linkLogo = 'images/logo.png';
    $linkCss = 'css-js/style.css';
    $title = "Modification de $name";
    include 'includes/head.php';
    ?>

<body>

    <?php include 'includes/header.php'; ?>
    <main>
        <h1 class="text-center mt-5">Modifier vos Informations</h1>
        <form action="verifications/verifmodifycompany.php?siret=<?= $siret ?>&rights=<?= $rights ?>" method="post">
            <div class="container col-md-6">
                <?php include 'includes/msg.php'; ?>
            </div>
            <div class="container col-md-4" id="form">
                <div class="mb-3">
                    <label class="form-label"><strong>Email</strong></label>
                    <input type="email" class="form-control" name="email" value="<?= $email ?>">
                    <label class="form-label mt-3"><strong>Nom de l'entreprise</strong></label>
                    <input type="text" class="form-control" name="companyName" value="<?= $name ?>">
                    <label class="form-label mt-3"><strong>Adresse</strong></label>
                    <input type="text" class="form-control" name="address" value="<?= $address['address'] ?>">
                    <button type="submit" name="submit" class="btn mt-3 btn-submit">Confirmer</button>
                </div>
            </div>
        </form>
    </main>
    <?php include 'includes/footer.php'; ?>

    <script src="css-js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

</body>

</html>
<?php } else {header('location: profile.php');
  exit();} ?>