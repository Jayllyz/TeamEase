<?php
session_start();
include "../includes/db.php";
$siret = htmlspecialchars($_GET["siret"]);
if ($_SESSION["rights"] == 2 && isset($_SESSION["siret"])) { ?>
    <!DOCTYPE html>
    <html lang="fr">
    <?php
    $linkLogo = "../images/logo.png";
    $linkCss = "../css-js/style.css";
    $title = "Consultation";
    include "../includes/head.php";
    ?>

    <body>
        <?php include "../includes/header.php"; ?>

        <?php
        $req = $db->prepare(
            "SELECT companyName, email, address, rights FROM COMPANY WHERE siret = :siret"
        );
        $req->execute([
            "siret" => $siret,
        ]);
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $select) { ?>

            <h1>Informations de <?= $select["companyName"] ?></h1>
            <div class="container info_user">
                <table class="table text-center table-bordered">
                    <tr>
                        <th>SIRET</th>
                        <th>Nom de l'entreprise</th>
                        <th>Email</th>
                        <th>Adresse</th>
                        <th>Droits</th>
                    </tr>
                    <tr>
                        <td><?= $siret ?></td>
                        <td><?= $select["companyName"] ?></td>
                        <td><?= $select["email"] ?></td>
                        <td><?= $select["address"] ?></td>
                        <td><?= $select["rights"] ?></td>
                    </tr>
                </table>
            </div>
            <h1>Historiques des activitées</h1>
            <h2 class="mt-3 mb-3 text-center">Commentaires Activitées</h2>
            <div class="container">
                <table class="table text-center table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Activitées</th>
                            <th>Avis/Notes</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tr>
                        <td><?= "todo" ?></td>
                        <td><?= "todo" ?></td>
                        <td><?= "todo" ?></td>
                    </tr>
                </table>
                <div class="text-center mt-auto">
                    <a href="../admin.php" class="btn col-1 btn-dark">Retour</a>
                </div>
            </div>

        <?php }
        ?>


        <?php include "../includes/footer.php"; ?>
        <script src="css-js/scripts.js"></script>
    </body>

    </html>
<?php } else {
    header("location: localhost");
    exit();
} ?>