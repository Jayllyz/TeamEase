<?php session_start();
include 'includes/db.php';
$siret = htmlspecialchars($_GET['siret']);
$rights = htmlspecialchars($_GET['rights']);

if (isset($siret)) { ?>

    <!DOCTYPE html>
    <html lang="fr">
    <?php
    $linkLogo = 'images/logo.png';
    $linkCss = 'css-js/style.css';
    $title = 'Modification de Mot de Passe';
    include 'includes/head.php';
    ?>

    <body>

        <?php include 'includes/header.php'; ?>
        <main>
            <form action="verifications/verifmodifycompanypassword.php?siret=<?= $siret ?>&rights=<?= $rights ?>" method="post">
                <div class="container col-md-6">
                    <?php include 'includes/msg.php'; ?>
                </div>
                <div class="container col-md-4" id="form">
                    <div class="mb-3">
                        <label class="form-label mt-3"><strong>Ancien Mot de Passe</strong></label>
                        <input type="password" class="form-control" name="oldpassword" required id="password">
                        <label class="form-label">Voir mon mot de passe</label>
                        <input type="checkbox" class="form-check-input" onClick="viewPassword()">
                        <br>
                        <label class="form-label mt-3"><strong>Nouveau Mot de Passe</strong></label>
                        <input type="password" class="form-control" name="newpassword" id="PasswordCompany" required>
                        <label class="form-label">Voir mon mot de passe</label>
                        <input type="checkbox" class="form-check-input" onClick="viewPasswordCompany()">
                        <br>
                        <label class="form-label mt-3"><strong>Confirmation Mot de Passe</strong></label>
                        <input type="password" class="form-control" name="newpassword" id="VerifPasswordCompany" required>
                        <label class="form-label">Voir mon mot de passe</label>
                        <input type="checkbox" class="form-check-input" onClick="viewVerifPasswordCompany()">
                        <br>
                        <button type="submit" name="submit" class="btn mt-3 btn-submit">Confirmer</button>
                    </div>
                </div>
            </form>
        </main>
        <?php include 'includes/footer.php'; ?>

        <script src="css-js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    </body>

    </html>
<?php } else {header('location: profil.php');
  exit();} ?>
