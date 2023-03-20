<?php
session_start();
if (!isset($_SESSION['rights']) || $_SESSION['rights'] != 2) {
  header('location: index.php');
  exit();
}
include 'includes/db.php';
if ($_SESSION['rights'] == 2 && isset($_SESSION['siret'])) { ?>
<!DOCTYPE html>
<html lang="fr">
<?php
$linkLogo = 'images/logo.png';
$linkCss = 'css-js/style.css';
$title = 'Gestion clients';
include 'includes/head.php';
if (isset($_GET['check'])) {
  $check = htmlspecialchars($_GET['check']);
} else {
  $check = 'company';
}
?>

<body onload="checkRadio('jsCheckRadio', 'back')">
    <?php include 'includes/header.php'; ?>
    <script src="css-js/scripts.js"></script>
    <main>
        <div class="container col-md-6">
            <?php include 'includes/msg.php'; ?>
        </div>

        <h1>Liste des Clients</h1>
        <div class="container">
            <div id="logs">
                <a href="#" class="btn mb-4 logs">Consulter les logs</a>
                <a href="#" class="btn ms-4 mb-4 exportData">Exporter les données</a>
            </div>
            <div class="d-flex justify-content-center container ">
                <p id="jsCheckRadio" style="display: none;"><?= $check ?></p>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="radio" id="radioCompany" onchange="changeTable()" >
                    <label class="form-check-label" for="radioCompany">
                        Entreprise
                    </label>
                </div>

                <div class="form-check mx-3">
                    <input class="form-check-input" type="radio" name="radio" onchange="changeTable()" id="provider-check">
                    <label class="form-check-label" for="radioProvider">
                        Prestataire
                    </label>
                </div>
            </div>
            <div id="table-company">
                <table class="table text-center table-bordered table-hover" id="table-company">
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
                      'SELECT siret, companyName, email, rights, address FROM COMPANY WHERE rights != 2'
                    );
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $select) { ?>
                        <tbody id="<?= $select['companyName'] ?>">
                            <tr>
                                <td><?= $select['siret'] ?></td>
                                <td><?= $select['companyName'] ?></td>
                                <td><?= $select['email'] ?></td>
                                <td><?= $select['address'] ?></td>
                                <td><?php
                                echo $select['rights'];
                                echo '<br>';
                                if ($select['rights'] == 0) {
                                  echo 'Client/Entreprise';
                                } elseif ($select['rights'] == 1) {
                                  echo 'Prestataire';
                                } elseif ($select['rights'] == -1) {
                                  echo 'Banni';
                                } elseif ($select['rights'] == 2) {
                                  echo 'Admin/Together&Stronger';
                                }
                                ?></td>
                                <td>
                                    <div class="button_profil">
                                        <a href="clients/read.php?siret=<?= $select[
                                          'siret'
                                        ] ?>" class="btn-read btn ms-2 me-2">Consulter</a>
                                        <br>
                                        <a href="clients/update.php?siret=<?= $select['siret'] ?>&name=<?= $select[
  'companyName'
] ?>&email=<?= $select['email'] ?>&rights=<?= $select['rights'] ?>" class="btn-update btn ms-2 me-2">Modifier</a>
                                        <br>

                                        <button type="button" class="btn-ban btn ms-2 me-2" data-bs-toggle="modal" data-bs-target="#pop-up-del-<?= $select[
                                          'siret'
                                        ] ?>"><?= $select['rights'] != -1 ? 'Bannir' : 'Débannir' ?></button><br>

                                        
                                        <div class="modal fade" id="pop-up-del-<?= $select['siret'] ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirmation du <?= $select['rights'] !=
                                                        -1
                                                          ? 'bannissement'
                                                          : 'débannissement' ?> de <span class="text-uppercase"><?= $select[
   'companyName'
 ] ?></span></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Saisir le nom de l'entreprise pour confirmation
                                                        <form action="clients/ban.php?siret=<?= $select[
                                                          'siret'
                                                        ] ?>&name=<?= $select['companyName'] ?>&rights=<?= $select[
  'rights'
] ?>" method="post">
                                                            <div class="container col-md-8">
                                                                <input type="text" class="form-control" name="name" placeholder="<?= $select[
                                                                  'companyName'
                                                                ] ?>" required>
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="submit" name="submit" value="Valider" class="btn btn-success">
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                          <a href="clients/delete.php?siret=<?= $select[
                                            'siret'
                                          ] ?>"class="btn-ban btn ms-2 me-2" onclick="checkConfirm('Voulez vous vraiment supprimer ?')">Supprimer
                                          </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    <?php }
                    ?>
                </table>
            </div>

            <div id="table-provider" style="display: none;">
                <table class="table text-center table-bordered table-hover" id="active">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Métier</th>
                            <th>Salaire</th>
                            <th>Droits</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <?php
                    $query = $db->query('SELECT id, lastName, firstName, email, rights, id_occupation FROM PROVIDER');
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    if ($result != null) {
                      foreach ($result as $select) { ?>
                        <?php
                        $id_occupation = $select['id_occupation'];
                        $req = $db->prepare('SELECT name, salary FROM OCCUPATION WHERE id = :id_occupation');
                        $req->execute(['id_occupation' => $id_occupation]);
                        $occupation = $req->fetchAll(PDO::FETCH_ASSOC);
                        $select['id_occupation'] = $occupation[0]['name'];
                        $select['salary'] = $occupation[0]['salary'];
                        ?>

                            <tbody id="<?= $select['id'] ?>">
                                <tr>
                                    <td><?= $select['id'] ?></td>
                                    <td><?= $select['lastName'] ?></td>
                                    <td><?= $select['firstName'] ?></td>
                                    <td><?= $select['email'] ?></td>
                                    <td><?= $select['id_occupation'] ?></td>
                                    <td><?= $select['salary'] . '€/h' ?></td>
                                    <td><?php
                                    echo $select['rights'];
                                    echo '<br>';
                                    if ($select['rights'] == 0) {
                                      echo 'Client/Entreprise';
                                    } elseif ($select['rights'] == 1) {
                                      echo 'Prestataire';
                                    } elseif ($select['rights'] == -1) {
                                      echo 'Banni';
                                    } elseif ($select['rights'] == 2) {
                                      echo 'Admin/Together&Stronger';
                                    }
                                    ?></td>
                                    <td>
                                        <div class="button_profil">
                                            <a href="provider/read.php?id=<?= $select[
                                              'id'
                                            ] ?>" class="btn-read btn ms-2 me-2">Consulter</a>
                                            <br>
                                            <a href="provider/update.php?id=<?= $select[
                                              'id'
                                            ] ?>" class=" btn-update btn ms-2 me-2">Modifier</a>
                                            <br>

                                            <button type="button" class="btn-ban btn ms-2 me-2" data-bs-toggle="modal" data-bs-target="#pop-up-del-<?= $select[
                                              'id'
                                            ] ?>"><?= $select['rights'] != -1 ? 'Bannir' : 'Débannir' ?></button><br>
                                            <div class="modal fade" id="pop-up-del-<?= $select['id'] ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Confirmation du <?= $select[
                                                              'rights'
                                                            ] != -1
                                                              ? 'bannissement'
                                                              : 'débannissement' ?> de <span class="text-uppercase"><?= $select[
   'lastName'
 ] ?></span></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Saisir le nom du prestataire pour confirmation
                                                            <form action="provider/ban.php?id=<?= $select[
                                                              'id'
                                                            ] ?>&name=<?= $select['lastName'] ?>&rights=<?= $select[
  'rights'
] ?>" method="post">
                                                                <div class="container col-md-8">
                                                                    <input type="text" class="form-control" name="name" placeholder="<?= $select[
                                                                      'lastName'
                                                                    ] ?>" required>
                                                                </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="submit" name="submit" value="Valider" class="btn btn-success">
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="provider/delete.php?id=<?= $select[
                                              'id'
                                            ] ?>"class="btn-ban btn ms-2 me-2" onclick="checkConfirm('Voulez vous vraiment supprimer ?')">Supprimer
                                          </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        <?php } ?>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>

    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>
<?php } else {header('location: index.php');
  exit();} ?>
