<?php
session_start();
include "../includes/db.php";
$id = htmlspecialchars($_GET["id"]);
if ($_SESSION["rights"] == 2) { ?>
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
            "SELECT lastName, firstName, salary, email,rights, id_occupation FROM PROVIDER WHERE id = :id"
        );
        $req->execute([
            "id" => $id,
        ]);
        $result = $req->fetchAll(PDO::FETCH_ASSOC);

        var_dump($result);

        $id_occupation = $result["id_occupation"];


        $req = $db->prepare(
            "SELECT name FROM OCCUPATION WHERE id = :id"
        );
        $req->execute([
            "id" => $id_occupation,
        ]);
        $test = $req->fetchAll(PDO::FETCH_ASSOC);

        var_dump($test);

        foreach ($result as $select) { ?>

            <h1>Informations de <?= $select["lastName"] . " " . $select["firstName"] ?></h1>
            <div class="container info_user">
                <table class="table text-center table-bordered">
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Occupation</th>
                        <th>Salaire</th>
                        <th>Email</th>
                        <th>Droits</th>
                    </tr>
                    <tr>
                        <td><?= $select["lastName"] ?></td>
                        <td><?= $select["firstName"] ?></td>
                        <td><?= $select["id_occupation"] ?></td>
                        <td><?= $select["salary"] ?></td>
                        <td><?= $select["email"] ?></td>
                        <td><?= $select["rights"] ?></td>
                    </tr>
                </table>
            </div>
            <h1>Participations aux activitées</h1>
            <div class="container">
                <table class="table text-center table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Activitées</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tr>
                        <td><?= "todo" ?></td>
                        <td><?= "todo" ?></td>
                    </tr>
                </table>
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