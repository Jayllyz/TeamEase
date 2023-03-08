<?php
session_start();
include "includes/db.php";
if ($_SESSION["rights"] == 2 && isset($_SESSION["siret"])) { ?>
    <!DOCTYPE html>
    <html lang="fr">
    <?php
    $linkLogo = "images/logo.png";
    $linkCss = "css-js/style.css";
    $title = "Gestion clients";
    include "includes/head.php";
    ?>

    <body>
        <?php include "includes/header.php"; ?>

        <main>
            <div class="container col-md-6">
                <?php include "includes/errorMessage.php"; ?>
            </div>

            <h1>Liste des Clients</h1>
            <div class="container">
                <div id="logs">
                    <a href="#" class="btn mb-4 logs">Consulter les logs</a>
                    <a href="#" class="btn ms-4 mb-4 exportData">Exporter les données</a>
                </div>
                <table class="table text-center table-bordered table-hover" id="active">
                    <thead>
                        <tr>
                            <th>SIRET</th>
                            <th>Nom de l'entreprise</th>
                            <th>Email</th>
                            <th>Adresse</th>
                            <th>Droits</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <?php
                    $query = $db->query(
                        "SELECT siret, companyName, email, rights, address FROM COMPANY WHERE rights != 2"
                    );
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $select) { ?>
                        <tbody id="<?= $select["companyName"] ?>">
                            <tr>
                                <td><?= $select["siret"] ?></td>
                                <td><?= $select["companyName"] ?></td>
                                <td><?= $select["email"] ?></td>
                                <td><?= $select["address"] ?></td>
                                <td><?php
                                    echo $select["rights"];
                                    echo "<br>";
                                    if ($select["rights"] == 0) {
                                        echo "Client/Entreprise";
                                    } elseif ($select["rights"] == 1) {
                                        echo "Prestataire";
                                    } elseif ($select["rights"] == -1) {
                                        echo "Banni";
                                    } elseif ($select["rights"] == 2) {
                                        echo "Admin/Together&Stronger";
                                    }
                                    ?></td>
                                <td>
                                    <div class="button_profil">
                                        <a href="clients/read.php?siret=<?= $select["siret"] ?>" class="btn-read btn ms-2 me-2">Consulter</a>
                                        <br>
                                        <a href="clients/update.php?siret=<?= $select["siret"] ?>&name=<?= $select["companyName"] ?>&email=<?= $select["email"] ?>&rights=<?= $select["rights"] ?>" class=" btn-update btn ms-2 me-2">Modifier</a>
                                        <br>

                                        <button type="button" class="btn-ban btn ms-2 me-2" data-bs-toggle="modal" data-bs-target="#pop-up-del-<?= $select["siret"] ?>"><?= $select["rights"] != -1
                                                                                                                                                                            ? "Bannir"
                                                                                                                                                                            : "Débannir" ?></button>
                                        <div class="modal fade" id="pop-up-del-<?= $select["siret"] ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirmation du <?= $select["rights"] != -1
                                                                                                    ? "bannissement"
                                                                                                    : "débannissement" ?> de <span class="text-uppercase"><?= $select["companyName"] ?></span></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Saisir le nom de l'entreprise pour confirmation
                                                        <form action="clients/ban.php?siret=<?= $select["siret"] ?>&name=<?= $select["companyName"] ?>&rights=<?= $select["rights"] ?>" method="post">
                                                            <div class="container col-md-8">
                                                                <input type="text" class="form-control" name="name" placeholder="<?= $select["companyName"] ?>" required>
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="submit" name="submit" value="Valider" class="btn btn-success">
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        </tbody>
                    <?php }
                    ?>
                </table>
            </div>

        </main>

        <?php include "includes/footer.php"; ?>

        <script src="css-js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    </body>

    </html>
<?php } else {
    header("location: index.php");
    exit();
} ?>