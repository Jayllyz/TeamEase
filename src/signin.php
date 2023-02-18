<!DOCTYPE html>
<html lang="fr">
<?php
$title = "Inscription";
include "includes/head.php";
?>

<body>
    <?php include "includes/header.php"; ?>
    <main>
        <h1 style="margin-top: 1rem; margin-bottom: 1rem;">Inscription</h1>
        <div class="formulaire">
            <form name="signin" onsubmit="return validateForm(this.name)" id="form" class="container col-md-4" action="verifications/verifSignin.php" method="post" enctype="multipart/form-data">
                <?php include "includes/msg.php"; ?>
                <div class="mb-3">
                    <label class="form-label"><strong>Nom d'entreprise</strong></label>
                    <input type="text" name="name" class="form-control is-<?= isset(
                                                                                $_GET["valid"]
                                                                            ) && $_GET["input"] == "name"
                                                                                ? $_GET["valid"]
                                                                                : "" ?>" value="<?= isset($_COOKIE["name"])
                                                                                                    ? $_COOKIE["name"]
                                                                                                    : "" ?>" required>
                    <?php if (isset($_GET["valid"])) { ?>
                        <div class="<?= $_GET["valid"] ?>-feedback">
                            <?= $_GET["message"] ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>n° de SIRET</strong></label>
                    <input type="number" name="siret" class="form-control is-<?= isset(
                                                                                    $_GET["valid"]
                                                                                ) && $_GET["input"] == "siret"
                                                                                    ? $_GET["valid"]
                                                                                    : "" ?>" value="<?= isset($_COOKIE["siret"])
                                                                                                        ? $_COOKIE["siret"]
                                                                                                        : "" ?>" required>
                    <?php if (isset($_GET["valid"])) { ?>
                        <div class="<?= $_GET["valid"] ?>-feedback">
                            <?= $_GET["message"] ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>Adresse de l'entreprise</strong></label>
                    <input type="text" name="address" class="form-control is-<?= isset(
                                                                                    $_GET["valid"]
                                                                                ) && $_GET["input"] == "address"
                                                                                    ? $_GET["valid"]
                                                                                    : "" ?>" value="<?= isset($_COOKIE["address"])
                                                                                                        ? $_COOKIE["address"]
                                                                                                        : "" ?>" required>

                    <?php if (isset($_GET["valid"])) { ?>
                        <div class="<?= $_GET["valid"] ?>-feedback">
                            <?= $_GET["message"] ?>
                        </div>
                    <?php } ?>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>Adresse mail</strong></label>
                    <input type="email" name="email" class="form-control is-<?= isset(
                                                                                $_GET["valid"]
                                                                            ) && $_GET["input"] == "email"
                                                                                ? $_GET["valid"]
                                                                                : "" ?>" value="<?= isset($_COOKIE["email"])
                                                                                                    ? $_COOKIE["email"]
                                                                                                    : "" ?>" required>
                    <?php if (isset($_GET["valid"])) { ?>
                        <div class="<?= $_GET["valid"] ?>-feedback">
                            <?= $_GET["message"] ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Mot de passe</strong></label>
                    <input type="password" name="password" class="form-control is-<?= isset(
                                                                                        $_GET["valid"]
                                                                                    ) && $_GET["input"] == "mdp"
                                                                                        ? $_GET["valid"]
                                                                                        : "" ?>" id="password" oninput="strengthChecker()" required>
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
    <?php include "includes/footer.php"; ?>
</body>

</html>