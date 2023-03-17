<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php
$linkLogo = 'images/logo.png';
$siteKey = $_ENV['CAPTCHA_SITE'];
if (isset($_GET['check'])) {
  $check = htmlspecialchars($_GET['check']);
} else {
  $check = 'company';
}
$linkCss = 'css-js/style.css';
$title = 'Inscription';
include 'includes/head.php';
include 'includes/db.php';
?>

<body onload="checkRadio('jsCheckRadio', 'forms')">
    <?php include 'includes/header.php'; ?>
    <main>
        <h1 style="margin-top: 1rem; margin-bottom: 1rem;">Inscription</h1>

        <div class="d-flex justify-content-center container ">
                <p id="jsCheckRadio" style="display: none;"><?= $check ?></p>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="radio" id="radioCompany" onchange="changeSignForm()" >
                    <label class="form-check-label" for="radioCompany">
                        Entreprise
                    </label>
                </div>

                <div class="form-check mx-3">
                    <input class="form-check-input" type="radio" name="radio" onchange="changeSignForm()" id="provider-check">
                    <label class="form-check-label" for="radioProvider">
                        Prestataire
                    </label>
                </div>
            </div>

        <div class="formulaire" id="forms-company">
            <form name="signin" onsubmit="return validateForm(this.name)" id="form" class="container col-md-4" action="verifications/verifSignin.php" method="post" enctype="multipart/form-data">
                <?php include 'includes/msg.php'; ?>
                <div class="mb-3">
                    <label class="form-label"><strong>n° de SIRET</strong></label>
                    <input type="number" name="siret" placeholder="31130000800017" size="14" class="form-control is-<?= isset(
                      $_GET['valid']
                    ) && $_GET['input'] == 'siret'
                      ? $_GET['valid']
                      : '' ?>" value="<?= isset($_COOKIE['siret']) ? $_COOKIE['siret'] : '' ?>" required>
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
                      : '' ?>" id="password" oninput="strengthChecker(this)" required>
                    <div id="strength-bar"></div>
                    <p id="msg"></p>
                    <label class="form-label">Voir mon mot de passe</label>
                    <input type="checkbox" class="form-check-input" onClick="viewPasswordInscription(this)">
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Confirmation du mot de passe</strong></label>
                    <input type="password" name="conf_password" class="form-control" id="conf_Password_inscription" required>
                    <label class="form-label">Voir mon mot de passe</label>
                    <input type="checkbox" class="form-check-input" onClick="viewConfPasswordInscription(this)">
                </div>

                <div class="g-recaptcha mb-4" data-sitekey="<?= $siteKey ?>" data-callback="captchaValidation"></div>
                
                <div class="text-center">
                    <button type="submit" style="display: none" id="submit" name="submit" class="btn btn-lg btn-submit">Envoyer</button>
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
                    <label class="custom-select" for="selectjob"><strong>Métier</strong></label>
                    <select class="form-select is-<?= isset($_GET['valid']) && $_GET['input'] == 'job'
                      ? $_GET['valid']
                      : '' ?>" value="<?= isset($_COOKIE['job'])
  ? $_COOKIE['job']
  : '' ?>" id="selectjob" name="job" required>

                        <?php
                        $req = $db->query('SELECT id, name FROM OCCUPATION');
                        $req = $req->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($req as $value) {
                          echo '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
                        }
                        ?>
                    </select>

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
                      : '' ?>" id="passwordProvider" oninput="strengthChecker(this)" required>
                    <div id="strength-bar-provider"></div>
                    <p id="msg-provider"></p>
                    <label class="form-label">Voir mon mot de passe</label>
                    <input type="checkbox" class="form-check-input" onClick="viewPasswordInscription(this)">
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Confirmation du mot de passe</strong></label>
                    <input type="password" name="conf_password" class="form-control" id="conf_Password_inscription" required>
                    <label class="form-label">Voir mon mot de passe</label>
                    <input type="checkbox" class="form-check-input" onClick="viewConfPasswordInscription(this)">
                </div>
                <div class="g-recaptcha mb-4" data-sitekey="<?= $siteKey ?>" data-callback="captchaValidation" ></div>
                <div class="text-center">
                    <button type="submit" id="submit" style="display: none" name="submit" class="btn btn-lg btn-submit">Envoyer</button>
                </div>
            </form>
        </div>
    </main>
    <script src="css-js/scripts.js"></script>
    <script>
        var captchaValidation = function(response) {
            const state = (grecaptcha.getResponse()) ? true : false;

            if (state) { 
                document.getElementById("submit").style.display = "block";
            }
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <?php include 'includes/footer.php'; ?>
</body>

</html>