<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php
if (!isset($_SESSION['rights']) || $_SESSION['rights'] != 2) {
  header('location: index.php');
  exit();
}
$linkLogo = 'images/logo.png';
$linkCss = 'css-js/style.css';
$title = 'Gestion des métiers';
include 'includes/head.php';
?>

<body onload="getJob()">
    <?php include 'includes/header.php'; ?>
    <main>
        <h1 style="margin-top: 1rem; margin-bottom: 1rem;">Gestion des métiers</h1>

        <div class="container col-md-6">
            <?php include 'includes/msg.php'; ?>
        </div>
        <div class="container">

        <div class="text-center mb-4">
          <button data-bs-toggle="modal" data-bs-target="#addJob" class="btn btn-secondary">Ajouter un métier</button>
        </div>

            <div id="joblist"></div>

    </div>

    <div class="modal fade" id="addJob" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">Ajouter un métier
                    </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        <label for="name">Nom du métier</label>
                        <input type="text" name="name" id="job-name" class="form-control" placeholder="Nom du métier" required><br>
                        <label for="name">Salaire</label>
                        <input type="number" name="salary" id="job-salary" class="form-control" placeholder="Salaire" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" onclick="addJob()">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
    <div id="edit-job-id" style="display: none;"></div>

    <div class="modal fade" id="editJob" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">Modification
                    </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                        <label for="name">Nom du métier</label>
                        <input type="text" name="name" id="edit-job-name" class="form-control" placeholder="Nom du métier" required><br>
                        <label for="name">Salaire</label>
                        <input type="number" name="salary" id="edit-job-salary" class="form-control" placeholder="Salaire" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" onclick="editJob()">Valider</button>
                </div>
            </div>
        </div>
    </div>
    

    </main>
    <script src="css-js/scripts.js"></script>
    <script>
    function addEditJobId(id){
        console.log(id);
        document.getElementById('edit-job-id').innerHTML = id;   
    }</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <?php include 'includes/footer.php'; ?>
</body>

</html>