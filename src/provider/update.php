<?php session_start();
if (!isset($_SESSION['rights']) || $_SESSION['rights'] != 2) {
  header('location: ../index.php');
  exit();
}
include '../includes/db.php';
$id = htmlspecialchars($_GET['id']);

$req = $db->prepare('SELECT lastName, firstName, email, id_occupation, rights FROM PROVIDER WHERE id = :id');
$req->execute([
  'id' => $id,
]);
$result = $req->fetch(PDO::FETCH_ASSOC);

$lastName = $result['lastName'];
$firstName = $result['firstName'];
$email = $result['email'];
$job = $result['id_occupation'];
$rights = $result['rights'];

if ($_SESSION['rights'] == 2) { ?>

<!DOCTYPE html>
<html lang="fr">
<?php
$linkLogo = '../images/logo.png';
$linkCss = '../css-js/style.css';
$title = "Modification de $lastName" . ' ' . "$firstName";
include '../includes/head.php';
?>

<body>

    <?php include '../includes/header.php'; ?>
    <main>
        <form action="verifUpdateProvider.php?id=<?= $id ?>" method="post">
            <div class="container col-md-6">
                <?php include '../includes/msg.php'; ?>
            </div>
            <div class="container col-md-4" id="form">
                <div class="mb-3">
                    <label class="form-label"><strong>Email</strong></label>
                    <input type="email" class="form-control" name="email" value="<?= $email ?>">
                    <label class="form-label mt-3"><strong>Nom</strong></label>
                    <input type="text" class="form-control" name="lastName" value="<?= $lastName ?>">
                    <label class="form-label mt-3"><strong>Prénom</strong></label>
                    <input type="text" class="form-control" name="firstName" value="<?= $firstName ?>">
                    <label class="form-label mt-3"><strong>Métier</strong></label>
                    <select class="form-select" name="job" required>

                        <?php
                        $req = $db->query('SELECT id, name FROM OCCUPATION');
                        $req = $req->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($req as $value) {
                          if ($value['id'] == $job) {
                            echo '<option value="' . $value['id'] . '" selected>' . $value['name'] . '</option>';
                          } else {
                            echo '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
                          }
                        }
                        ?>
                    </select>
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
