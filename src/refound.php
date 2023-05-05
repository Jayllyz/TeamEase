<?php session_start();
include 'includes/db.php';
?>

<!DOCTYPE html>
<html>
<?php
$linkCss = 'css-js/style.css';
$linkLogo = 'images/logo.png';
$title = 'Remboursement';
include 'includes/head.php';
?>

<body>
    <?php include 'includes/header.php'; ?>
    <div class="container col-md-6">
        <?php include 'includes/msg.php'; ?>
    </div>
    <main>

        <h1 class="text-center mb-4">Demande de remboursement</h1>

        <form action="" onsubmit="validRIB()" method="POST">
            <div class="container col-md-4" id="form">
                <div class="mb-3">
                    <label for="RIB" class="form-label">Saisir votre RIB :</label>
                    <input type="number" class="form-control" name="RIB" id="RIB">
                    <input type="submit" name="submit" class="btn btn-lg btn-submit mt-4" value="Valider">

                </div>
            </div>
        </form>

    </main>
    <?php include 'includes/footer.php'; ?>
    <script>
    function validRIB() {
        var RIB = document.getElementById("RIB").value;
        var regex = /^[0-9]{5,10}$/;
        if (regex.test(RIB) == false) {
            alert("Veuillez entrer un RIB valide");
            return false;
        } else {
            alert("Votre demande de remboursement a bien été prise en compte");
            return true;
        }
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>