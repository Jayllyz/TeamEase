<!DOCTYPE html>
<html lang="fr">
<?php
$linkLogo = 'images/logo.png';
$linkCss = 'css-js/style.css';
$title = 'Inscription';
include 'includes/head.php';
include 'includes/db.php';
?>

<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <h1 style="margin-top: 2rem; margin-bottom: 1rem;">Mot de passe oublié</h1>
        <form action="verifications/updatePassword.php" method="post">
            <div class="container col-md-6">
                <?php include 'includes/msg.php'; ?>
            </div>
            <div class="container col-md-4" id="form">
                <div class="mb-3">
                    <label for="login" class="form-label"><strong>Email</strong></label>
                    <input type="email" class="form-control" name="email" value="<?= isset($_COOKIE['email'])
                      ? $_COOKIE['email']
                      : '' ?>" required><br>
                    <button type="submit" name="submit" class="btn btn-lg btn-submit">Envoyer</button>
                </div>
            </div>
        </form>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>