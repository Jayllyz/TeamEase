<!DOCTYPE html>
<html lang="fr">
<?php
$linkLogo = 'images/logo.png';
$linkCss = 'css-js/style.css';
$title = 'Inscription';
include 'includes/head.php';
?>

<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <h1 style="margin-top: 1rem; margin-bottom: 1rem;">Inscription</h1>

        <div class="d-flex justify-content-center container ">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" onchange="changeSignForm()" checked>
                <label class="form-check-label" for="flexRadioDefault1">
                    Entreprise
                </label>
            </div>

            <div class="form-check mx-3">
                <input class=" form-check-input" type="radio" name="flexRadioDefault" onchange="changeSignForm()" id="provider-check">
                <label class="form-check-label" for="flexRadioDefault2">
                    Prestataire
                </label>
            </div>
        </div>

        <div class="formulaire" id="forms-company">
            <form name="signin" onsubmit="return validateForm(this.name)" id="form" class="container col-md-4" action="verifications/verifSignin.php" method="post" enctype="multipart/form-data">
                <?php include 'includes/msg.php'; ?>
                <div class="mb-3">


                    <label class="form-label"><strong>Nom d'entreprise</strong></label>
                    <input type="text" name="nameCompany" class="form-control is-<?= isset($_GET['valid']) &&
                    $_GET['input'] == 'nameCompany'
                      ? $_GET['valid']
                      : '' ?>" value="<?= isset($_COOKIE['nameCompany']) ? $_COOKIE['nameCompany'] : '' ?>" required>
                    <?php if (isset($_GET['valid'])) { ?>
                        <div class="<?= $_GET['valid'] ?>-feedback">
                            <?= $_GET['message'] ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>n° de SIRET</strong></label>
                    <input type="number" name="siret" class="form-control is-<?= isset($_GET['valid']) &&
                    $_GET['input'] == 'siret'
                      ? $_GET['valid']
                      : '' ?>" value="<?= isset($_COOKIE['siret']) ? $_COOKIE['siret'] : '' ?>" required>
                    <?php if (isset($_GET['valid'])) { ?>
                        <div class="<?= $_GET['valid'] ?>-feedback">
                            <?= $_GET['message'] ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>Adresse de l'entreprise</strong></label>
                    <input type="text" name="address" class="form-control is-<?= isset($_GET['valid']) &&
                    $_GET['input'] == 'address'
                      ? $_GET['valid']
                      : '' ?>" value="<?= isset($_COOKIE['address']) ? $_COOKIE['address'] : '' ?>" required>

                    <?php if (isset($_GET['valid'])) { ?>
                        <div class="<?= $_GET['valid'] ?>-feedback">
                            <?= $_GET['message'] ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>Adresse mail</strong></label>
                    <input type="email" name="emailCompany" class="form-control is-<?= isset($_GET['valid']) &&
                    $_GET['input'] == 'emailCompany'
                      ? $_GET['valid']
                      : '' ?>" value="<?= isset($_COOKIE['emailCompany']) ? $_COOKIE['emailCompany'] : '' ?>" required>
                    <?php if (isset($_GET['valid'])) { ?>
                        <div class="<?= $_GET['valid'] ?>-feedback">
                            <?= $_GET['message'] ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Mot de passe</strong></label>
                    <input type="password" name="passwordCompany" class="form-control is-<?= isset($_GET['valid']) &&
                    $_GET['input'] == 'passwordCompany'
                      ? $_GET['valid']
                      : '' ?>" id="password" oninput="strengthChecker()" required>
                    <div id="strength-bar"></div>
                    <p id="msg"></p>
                    <label class="form-label">Voir mon mot de passe</label>
                    <input type="checkbox" class="form-check-input" onClick="viewPasswordInscription()">
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Confirmation du mot de passe</strong></label>
                    <input type="password" name="conf_password" class="form-control" id="conf_Password_inscription" required>
                    <label class="form-label">Voir mon mot de passe</label>
                    <input type="checkbox" class="form-check-input" onClick="viewConfPasswordInscription()">
                </div>
                <div class="text-center">
                    <button type="submit" name="submit" class="btn btn-lg btn-submit">Envoyer</button>
                </div>
            </form>
        </div>



        <div class="formulaire" id="forms-provider" style="display: none;">
            <form name="signin" onsubmit="return validateForm(this.name)" id="form" class="container col-md-4" action="verifications/verifSignin.php" method="post" enctype="multipart/form-data">
                <?php include 'includes/msg.php'; ?>
                <div class="mb-3">


                    <label class="form-label"><strong>Nom </strong></label>
                    <input type="text" name="name" class="form-control is-<?= isset($_GET['valid']) &&
                    $_GET['input'] == 'name'
                      ? $_GET['valid']
                      : '' ?>" value="<?= isset($_COOKIE['name']) ? $_COOKIE['name'] : '' ?>" required>
                    <?php if (isset($_GET['valid'])) { ?>
                        <div class="<?= $_GET['valid'] ?>-feedback">
                            <?= $_GET['message'] ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="mb-3">


                    <label class="form-label"><strong>Prénom </strong></label>
                    <input type="text" name="firstname" class="form-control is-<?= isset($_GET['valid']) &&
                    $_GET['input'] == 'firstname'
                      ? $_GET['valid']
                      : '' ?>" value="<?= isset($_COOKIE['firstname']) ? $_COOKIE['firstname'] : '' ?>" required>
                    <?php if (isset($_GET['valid'])) { ?>
                        <div class="<?= $_GET['valid'] ?>-feedback">
                            <?= $_GET['message'] ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>Adresse mail</strong></label>
                    <input type="email" name="email" class="form-control is-<?= isset($_GET['valid']) &&
                    $_GET['input'] == 'email'
                      ? $_GET['valid']
                      : '' ?>" value="<?= isset($_COOKIE['email']) ? $_COOKIE['email'] : '' ?>" required>
                    <?php if (isset($_GET['valid'])) { ?>
                        <div class="<?= $_GET['valid'] ?>-feedback">
                            <?= $_GET['message'] ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>Métier </strong></label>
                    <input type="text" name="job" class="form-control is-<?= isset($_GET['valid']) &&
                    $_GET['input'] == 'job'
                      ? $_GET['valid']
                      : '' ?>" value="<?= isset($_COOKIE['job']) ? $_COOKIE['job'] : '' ?>" required>
                    <?php if (isset($_GET['valid'])) { ?>
                        <div class="<?= $_GET['valid'] ?>-feedback">
                            <?= $_GET['message'] ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>Salaire par heure </strong></label>
                    <input type="number" name="salary" class="form-control is-<?= isset($_GET['valid']) &&
                    $_GET['input'] == 'salary'
                      ? $_GET['valid']
                      : '' ?>" value="<?= isset($_COOKIE['salary']) ? $_COOKIE['salary'] : '' ?>" required>
                    <?php if (isset($_GET['valid'])) { ?>
                        <div class="<?= $_GET['valid'] ?>-feedback">
                            <?= $_GET['message'] ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Mot de passe</strong></label>
                    <input type="password" name="password" class="form-control is-<?= isset($_GET['valid']) &&
                    $_GET['input'] == 'mdp'
                      ? $_GET['valid']
                      : '' ?>" id="password" oninput="strengthChecker()" required>
                    <div id="strength-bar"></div>
                    <p id="msg"></p>
                    <label class="form-label">Voir mon mot de passe</label>
                    <input type="checkbox" class="form-check-input" onClick="viewPasswordInscription()">
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Confirmation du mot de passe</strong></label>
                    <input type="password" name="conf_password" class="form-control" id="conf_Password_inscription" required>
                    <label class="form-label">Voir mon mot de passe</label>
                    <input type="checkbox" class="form-check-input" onClick="viewConfPasswordInscription()">
                </div>
                <div class="text-center">
                    <button type="submit" name="submit" class="btn btn-lg btn-submit">Envoyer</button>
                </div>
            </form>
        </div>
    </main>
    <script src="css-js/scripts.js"></script>
    <?php include 'includes/footer.php'; ?>
</body>

</html>