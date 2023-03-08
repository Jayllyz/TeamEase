<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php
$linkLogo = 'images/logo.png';
$linkCss = 'css-js/style.css';
$title = 'Connexion';
include 'includes/head.php';
?>

<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <h1 style="margin-top: 1rem; margin-bottom: 1rem;">Connexion</h1>
        <div class="container col-md-6">
            <?php include 'includes/msg.php'; ?>
        </div>
        <form name="connexion" onsubmit="return validateForm(this.name)" action="verifications/verifLogin.php" method="post">
            <div class="container col-md-4" id="form">
                <div class="mb-3">
                    <label for="login" class="form-label"><strong>Email</strong></label>
                    <input type="email" class="form-control" name="login" value="<?= isset($_COOKIE['email'])
                      ? $_COOKIE['email']
                      : '' ?>" required>
                </div>
                <label for="password" class="form-label"><strong>Mot de passe</strong></label>
                <input type="password" class="form-control" name="password" id="password" required>
                <label class="form-label">Voir mon mot de passe</label>
                <input type="checkbox" class="form-check-input" onClick="viewPassword()">
                <div class="mb-3">
                    <a href="lost_password.php">Mot de passe oublié ?</a>
                </div>
                <button type="submit" name="submit" class="btn btn-lg btn-submit">Envoyer</button>
            </div>
        </form>

    </main>
    <script src="css-js/scripts.js"></script>
    <?php include 'includes/footer.php'; ?>
</body>

</html>