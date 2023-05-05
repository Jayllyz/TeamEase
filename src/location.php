<?php session_start();
if (!isset($_SESSION['rights']) || $_SESSION['rights'] != 2) {
  header('location: index.php');
  exit();
}
?>
<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html>
<?php
$linkCss = 'css-js/style.css';
$linkLogo = 'images/logo.png';
$title = 'Gestion des salles et sites d\'activités';
include 'includes/head.php';
?>

<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <div class="container">
            <div class="accordion" id="locationContainer">

            </div>
        </div>
        <div class="d-md-flex justify-content-center">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLocation">
                Ajouter un site
            </button>

            <!-- Modal -->
            <div class="modal fade" id="addLocation" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ajout d'un site</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <label>Nom du site</label>
                            <input type="text" class="form-control name">
                            <label>Adresse du site</label>
                            <input type="text" class="form-control address">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-success" data-bs-dismiss="modal"
                                onclick="addLocation(this)">Confirmer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="css-js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>