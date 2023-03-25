<?php session_start();
include 'includes/db.php';
$id = htmlspecialchars($_GET['id']);
$firstName = htmlspecialchars($_GET['firstName']);
$lastName = htmlspecialchars($_GET['lastName']);
$email = htmlspecialchars($_GET['email']);
$rights = htmlspecialchars($_GET['rights']);

if (isset($_SESSION['id'])) { ?>

<!DOCTYPE html>
<html lang="fr">
<?php
    $linkLogo = 'images/logo.png';
    $linkCss = 'css-js/style.css';
    $title = "Modification de $firstName $lastName";
    include 'includes/head.php';
    ?>

<body>

    <?php include 'includes/header.php'; ?>
    <main>
        <form action="verifications/verifmodifyprovider.php?id=<?= $id ?>&rights=<?= $rights ?>" method="post">
            <div class="container col-md-6">
                <?php include 'includes/msg.php'; ?>
            </div>
            <div class="container col-md-4" id="form">
                <div class="mb-3">
                    <label class="form-label"><strong>Email</strong></label>
                    <input type="email" class="form-control" name="email" value="<?= $email ?>">
                    <label class="form-label mt-3"><strong>Prénom</strong></label>
                    <input type="text" class="form-control" name="firstName" value="<?= $firstName ?>">
                    <label class="form-label mt-3"><strong>Nom de famille</label>
                    <input type="text" class="form-control" name="lastName" value="<?= $lastName ?>">
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