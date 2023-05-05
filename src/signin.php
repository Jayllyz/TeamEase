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

<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <h1 style="margin-top: 1rem; margin-bottom: 1rem;" class="lang-title-signin">Inscription</h1>

        <div class="d-flex justify-content-center container">
            <p id="jsCheckRadio" style="display: none;"><?= $check ?></p>
            <div class="radio-inputs">
                <label class="radio">
                    <input type="radio" name="radio" id="radioCompany" onchange="changeSignForm()">
                    <span class="name lang-signin-company">Entreprise</span>
                </label>
                <label class="radio">
                    <input type="radio" name="radio" id="provider-check" onchange="changeSignForm()">
                    <span class="name lang-signin-provider">Prestataire</span>
                </label>
            </div>
        </div>

        <div class="formulaire" id="forms-company">
            <form name="signin" onsubmit="return validateForm(this.name)" id="form"
                class="container col-md-4 border border-2 border-secondary rounded"
                action="verifications/verifSignin.php" method="post" enctype="multipart/form-data">
                <?php include 'includes/msg.php'; ?>
                <div class="mb-3">
                    <label class="form-label"><strong class="lang-number-siret">n° de SIRET</strong></label>
                    <input type="number" name="siret" placeholder="Saisir le SIRET de votre entreprise" size="14" class="form-control lang-placeholder-siret is-<?= isset(
                      $_GET['valid'],
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
                    <label class="form-label"><strong class="lang-signin-email-company">Adresse mail</strong></label>
                    <input type="email" name="emailCompany" placeholder="name@mail.com" class="form-control is-<?= isset(
                      $_GET['valid'],
                    ) && $_GET['input'] == 'emailCompany'
                      ? $_GET['valid']
                      : '' ?>" value="<?= isset($_COOKIE['emailCompany']) ? $_COOKIE['emailCompany'] : '' ?>" required>
                    <?php if (isset($_GET['valid'])) { ?>
                    <div class="<?= $_GET['valid'] ?>-feedback">
                        <?= $_GET['message'] ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong class="lang-signin-password-company">Mot de passe</strong></label>
                    <input type="password" name="passwordCompany" placeholder="Saisir votre mot de passe" class="form-control lang-placeholder-password-company is-<?= isset(
                      $_GET['valid'],
                    ) && $_GET['input'] == 'passwordCompany'
                      ? $_GET['valid']
                      : '' ?>" id="password" oninput="strengthChecker(this)" required>
                    <div id="strength-bar"></div>
                    <p id="msg"></p>
                    <label class="form-label lang-signin-see-password1-company">Voir mon mot de passe</label>
                    <input type="checkbox" class="form-check-input" onClick="viewPasswordInscription(this)">
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong class="lang-signin-confirm-password-company">Confirmation du mot
                            de
                            passe</strong></label>
                    <input type="password" name="conf_password" class="form-control" id="conf_Password_inscription"
                        required>
                    <label class="form-label lang-signin-see-password2-company">Voir mon mot de passe</label>
                    <input type="checkbox" class="form-check-input" onClick="viewConfPasswordInscription(this)">
                </div>

                <label class="form-label lang-signin-CGU-company mb-3">
                    J'accepte les <a href="#" data-bs-toggle="modal" data-bs-target="#CGU-company">conditions
                        générales
                        d'utilisation</a>
                </label>
                <input type="checkbox" class="form-check-input" required>

                <div class="modal fade popup" id="CGU-company" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark">Conditions générales d'utilisation
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-dark">
                                Les présentes Conditions Générales d'Utilisation (« CGU ») régissent votre utilisation
                                en
                                tant que Client de la plateforme de teambuilding en ligne TeamEase (« Plateforme »),
                                développée et exploitée par Together&Stronger, située à 242 Rue du Faubourg
                                Saint-Antoine,
                                75012 Paris («
                                TeamEase »).
                                <br><br><br>
                                1.Acceptation des CGU<br><br>
                                En utilisant la Plateforme, vous acceptez les présentes CGU et vous vous engagez à les
                                respecter. Si vous n'êtes pas d'accord avec les CGU, vous ne pouvez pas utiliser la
                                Plateforme.
                                <br><br><br>
                                2.Description de la Plateforme<br><br>
                                TeamEase fournit une plateforme en ligne pour les entreprises et les organisations à la
                                recherche d'activités de teambuilding pour leurs employés ou leurs membres (« Clients
                                »). La
                                Plateforme permet aux Clients de rechercher des prestataires de teambuilding, de
                                consulter
                                leurs profils et leurs services, et de réserver des prestations.
                                <br><br><br>
                                3.Inscription et création de compte<br><br>
                                Pour pouvoir utiliser la Plateforme en tant que Client, vous devez créer un compte en
                                fournissant des informations exactes et à jour, notamment votre nom, votre adresse
                                e-mail,
                                votre numéro de téléphone, et les informations relatives à votre entreprise si vous
                                agissez
                                au nom d'une société.
                                <br><br><br>
                                4.Recherche et réservation de prestations<br><br>
                                La Plateforme permet aux Clients de rechercher des prestataires de teambuilding en
                                fonction
                                de leurs besoins et de leurs préférences, et de réserver des prestations directement en
                                ligne. Les tarifs proposés par les prestataires sont indiqués sur la Plateforme.
                                <br><br><br>
                                5.Paiement<br><br>
                                Les paiements des prestations réservées sur la Plateforme sont effectués en ligne via
                                des
                                moyens de paiement sécurisés. Les tarifs indiqués sur la Plateforme comprennent les
                                taxes en
                                vigueur. TeamEase se réserve le droit de modifier les tarifs à tout moment, mais les
                                tarifs
                                applicables seront ceux en vigueur au moment de la réservation.
                                <br><br><br>
                                6.Responsabilités<br><br>
                                TeamEase ne garantit pas la qualité des prestations fournies par les prestataires sur la
                                Plateforme. Vous êtes responsable de vérifier la qualité et la conformité des
                                prestations
                                proposées avant de les réserver. En cas de litige avec un prestataire, vous acceptez de
                                régler le différend de manière amiable.
                                <br><br><br>
                                7.Propriété intellectuelle<br><br>
                                Vous reconnaissez que TeamEase est propriétaire de tous les droits de propriété
                                intellectuelle sur la Plateforme et son contenu, notamment les marques, logos, textes,
                                images, graphismes, photographies, vidéos et sons. Vous vous engagez à ne pas utiliser,
                                reproduire ou distribuer ces éléments sans l'autorisation écrite de TeamEase.
                                <br><br><br>
                                8.Modification des CGU<br><br>
                                TeamEase se réserve le droit de modifier les présentes CGU à tout moment et sans
                                préavis.
                                Les modifications seront effectives dès leur publication sur la Plateforme. Il vous
                                appartient de vérifier régulièrement les CGU pour vous assurer que vous les respectez.
                                <br><br><br>
                                9.Résiliation<br><br>
                                TeamEase se réserve le droit de résilier votre compte et votre accès à la Plateforme à
                                tout
                                moment et sans préavis si vous violez les présentes CGU.
                                <br><br><br>
                                10.Loi applicable et juridiction compétente<br><br>
                                Les présentes CGU sont régies et interprétées conformément au droit français. Tout
                                litige
                                relatif à l'utilisation de la Plateforme
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" data-bs-dismiss="modal">J'ai pris
                                    connaissance</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="g-recaptcha mb-4" data-sitekey="<?= $siteKey ?>" data-callback="validCompany"></div>

                <p class="lang-signin-already-registered1">Déjà inscrit ? <a href="login.php">Connectez-vous</a></p>

                <div class="text-center">
                    <button type="submit" style="display: none" id="submitCompany" name="submit"
                        class="btn btn-lg btn-submit lang-signin-submit">Envoyer</button>
                </div>
            </form>
        </div>



        <div class="formulaire" id="forms-provider" style="display: none;">
            <form name="signin" onsubmit="return validateForm(this.name)" id="form"
                class="container col-md-4 border border-2 border-secondary rounded"
                action="verifications/verifSignin.php" method="post" enctype="multipart/form-data">
                <?php include 'includes/msg.php'; ?>
                <div class="mb-3">

                    <label class="form-label"><strong class="lang-signin-lastName">Nom</strong></label>
                    <input type="text" name="name" placeholder="Saisir votre nom" class="form-control lang-placeholder-name is-<?= isset(
                      $_GET['valid'],
                    ) && $_GET['input'] == 'name'
                      ? $_GET['valid']
                      : '' ?>" value="<?= isset($_COOKIE['name']) ? $_COOKIE['name'] : '' ?>" required>
                    <?php if (isset($_GET['valid'])) { ?>
                    <div class="<?= $_GET['valid'] ?>-feedback">
                        <?= $_GET['message'] ?>
                    </div>
                    <?php } ?>
                </div>

                <div class="mb-3">


                    <label class="form-label"><strong class="lang-signin-firstName">Prénom</strong></label>
                    <input type="text" name="firstname" placeholder="Saisir votre prénom" class="form-control lang-placeholder-firstName is-<?= isset(
                      $_GET['valid'],
                    ) && $_GET['input'] == 'firstname'
                      ? $_GET['valid']
                      : '' ?>" value="<?= isset($_COOKIE['firstname']) ? $_COOKIE['firstname'] : '' ?>" required>
                    <?php if (isset($_GET['valid'])) { ?>
                    <div class="<?= $_GET['valid'] ?>-feedback">
                        <?= $_GET['message'] ?>
                    </div>
                    <?php } ?>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong class="lang-signin-email-provider">Adresse mail</strong></label>
                    <input type="email" name="email" placeholder="name@mail.com" class="form-control is-<?= isset(
                      $_GET['valid'],
                    ) && $_GET['input'] == 'email'
                      ? $_GET['valid']
                      : '' ?>" value="<?= isset($_COOKIE['email']) ? $_COOKIE['email'] : '' ?>" required>
                    <?php if (isset($_GET['valid'])) { ?>
                    <div class="<?= $_GET['valid'] ?>-feedback">
                        <?= $_GET['message'] ?>
                    </div>
                    <?php } ?>
                </div>

                <div class="mb-3">
                    <label class="custom-select" for="selectjob"><strong
                            class="lang-signin-occupation">Métier</strong></label>
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
                    <label class="form-label"><strong class="lang-signin-password-provider">Mot de
                            passe</strong></label>
                    <input type="password" name="password" placeholder="Saisir votre mot de passe" class="form-control lang-placeholder-password-provider is-<?= isset(
                      $_GET['valid'],
                    ) && $_GET['input'] == 'mdp'
                      ? $_GET['valid']
                      : '' ?>" id="passwordProvider" oninput="strengthChecker(this)" required>
                    <div id="strength-bar-provider"></div>
                    <p id="msg-provider"></p>
                    <label class="form-label lang-signin-see-password1-provider">Voir mon mot de passe</label>
                    <input type="checkbox" class="form-check-input" onClick="viewPasswordInscription(this)">
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong class="lang-signin-confirm-password-provider">Confirmation du mot
                            de passe</strong></label>
                    <input type="password" name="conf_password" class="form-control" id="conf_Password_inscription"
                        required>
                    <label class="form-label lang-signin-see-password2-provider">Voir mon mot de passe</label>
                    <input type="checkbox" class="form-check-input" onClick="viewConfPasswordInscription(this)">
                </div>
                <div class="mb-3">
                    <label for="form-label"><strong class="lang-signin-availability">Vos disponibilités</strong></label>
                    <div class="row mt-2">
                        <input type="checkbox" class="btn btn-check" name="day[]" value="monday" id="monday"
                            autocomplete="off">
                        <label class="btn btn-outline-success col me-2 mb-3 lang-signin-monday"
                            for="monday">Lundi</label>
                        <input type="checkbox" class="btn btn-check" name="day[]" value="tuesday" id="tuesday"
                            autocomplete="off">
                        <label class="btn btn-outline-success col me-2 mb-3 lang-signin-tuesday"
                            for="tuesday">Mardi</label>
                        <input type="checkbox" class="btn btn-check" name="day[]" value="wednesday" id="wednesday"
                            autocomplete="off">
                        <label class="btn btn-outline-success col me-2 mb-3 lang-signin-wednesday"
                            for="wednesday">Mercredi</label>
                        <input type="checkbox" class="btn btn-check" name="day[]" value="thursday" id="thursday"
                            autocomplete="off">
                        <label class="btn btn-outline-success col me-2 mb-3 lang-signin-thursday"
                            for="thursday">Jeudi</label>
                        <input type="checkbox" class="btn btn-check" name="day[]" value="friday" id="friday"
                            autocomplete="off">
                        <label class="btn btn-outline-success col me-2 mb-3 lang-signin-friday"
                            for="friday">Vendredi</label>
                        <input type="checkbox" class="btn btn-check" name="day[]" value="saturday" id="saturday"
                            autocomplete="off">
                        <label class="btn btn-outline-success col me-2 mb-3 lang-signin-saturday"
                            for="saturday">Samedi</label>
                        <input type="checkbox" class="btn btn-check" name="day[]" value="sunday" id="sunday"
                            autocomplete="off">
                        <label class="btn btn-outline-success col me-2 mb-3 lang-signin-sunday"
                            for="sunday">Dimanche</label>
                    </div>
                </div>

                <label class="form-label lang-signin-CGU-provider mb-3">
                    J'accepte les <a href="#" data-bs-toggle="modal" data-bs-target="#CGU-provider">conditions
                        générales
                        d'utilisation</a>
                </label>
                <input type="checkbox" class="form-check-input" required>

                <div class="modal fade popup" id="CGU-provider" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-dark">Conditions générales d'utilisation
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-dark">
                                Les présentes Conditions Générales d'Utilisation (« CGU ») régissent votre utilisation
                                en
                                tant que prestataire de la plateforme de teambuilding en ligne TeamEase (« Plateforme
                                »),
                                développée et exploitée par Together&Stronger, située au 242 Rue du Faubourg
                                Saint-Antoine
                                (« TeamEase »).,
                                75012 Paris.
                                <br><br><br>
                                1.Acceptation des CGU<br><br>
                                En utilisant la Plateforme, vous acceptez les présentes CGU et vous vous engagez à les
                                respecter. Si vous n'êtes pas d'accord avec les CGU, vous ne pouvez pas utiliser la
                                Plateforme.
                                <br><br><br>
                                2.Description de la Plateforme<br><br>
                                TeamEase fournit une plateforme en ligne pour les entreprises et les organisations à la
                                recherche d'activités de teambuilding pour leurs employés ou leurs membres (« Clients
                                »). En
                                tant que prestataire, vous pouvez proposer vos services de teambuilding sur la
                                Plateforme
                                pour être mis en relation avec les Clients.
                                <br><br><br>
                                3.Inscription et création de compte<br><br>
                                Pour pouvoir utiliser la Plateforme en tant que prestataire, vous devez créer un compte
                                en
                                fournissant des informations exactes et à jour, notamment votre nom, votre adresse
                                e-mail,
                                votre numéro de téléphone, vos qualifications, vos compétences et votre expérience
                                professionnelle. Vous devez également fournir les informations relatives à votre
                                entreprise
                                si vous agissez au nom d'une société.
                                <br><br><br>
                                4.Mise en relation avec les Clients<br><br>
                                En proposant vos services sur la Plateforme, vous acceptez d'être mis en relation avec
                                les
                                Clients qui recherchent des prestataires pour des activités de teambuilding. Vous
                                acceptez
                                également que TeamEase puisse utiliser votre profil et vos informations pour promouvoir
                                vos
                                services auprès des Clients.
                                <br><br><br>
                                5.Rémunération<br><br>
                                Vous acceptez de payer à TeamEase une commission sur chaque prestation de teambuilding
                                que
                                vous réalisez avec un Client trouvé sur la Plateforme. La commission est calculée sur la
                                base du montant total facturé au Client pour la prestation, conformément aux tarifs en
                                vigueur sur la Plateforme au moment de la prestation.
                                <br><br><br>
                                6.Responsabilités<br><br>
                                Vous êtes responsable de fournir des services de teambuilding de qualité et conformes
                                aux
                                attentes des Clients. Vous êtes également responsable de respecter les délais et les
                                engagements que vous prenez envers les Clients. En cas de litige avec un Client, vous
                                acceptez de régler le différend de manière amiable.
                                <br><br><br>
                                7.Propriété intellectuelle<br><br>
                                Vous reconnaissez que TeamEase est propriétaire de tous les droits de propriété
                                intellectuelle sur la Plateforme et son contenu, notamment les marques, logos, textes,
                                images, graphismes, photographies, vidéos et sons. Vous vous engagez à ne pas utiliser,
                                reproduire ou distribuer ces éléments sans l'autorisation écrite de TeamEase.
                                <br><br><br>
                                8.Modification des CGU<br><br>
                                TeamEase se réserve le droit de modifier les présentes CGU à tout moment et sans
                                préavis.
                                Les modifications seront effectives dès leur publication sur la Plateforme. Il vous
                                appartient de vérifier régulièrement les CGU pour vous assurer que vous les respectez.
                                <br><br><br>
                                9.Résiliation<br><br>
                                TeamEase se réserve le droit de résilier votre compte et votre accès à la Plateforme à
                                tout
                                moment et sans préavis si vous violez les présentes CGU.
                                <br><br><br>
                                10.Loi applicable et juridiction compétente<br><br>
                                Les présentes CGU sont régies et interprétées conformément au droit français. Tout
                                litige
                                relatif à l'utilisation de la Plate
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" data-bs-dismiss="modal">J'ai pris
                                    connaissance</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="g-recaptcha mb-4" id="provider" data-sitekey="<?= $siteKey ?>"
                    data-callback="validProvider"></div>

                <p class="lang-signin-already-registered2">Déjà inscrit ? <a href="login.php">connectez-vous</a>
                </p>

                <div class="text-center">
                    <button type="submit" id="submitProvider" style="display: none" name="submit"
                        class="btn btn-lg btn-submit lang-signin-submit2">Envoyer</button>
                </div>
            </form>
        </div>
    </main>
    <script src="css-js/scripts.js"></script>
    <script>
    var validCompany = function(response) {
        const state = (grecaptcha.getResponse()) ? true : false;

        if (state === true) {
            document.getElementById('submitCompany').style.display = 'block';
        }
    };

    var validProvider = function(response) {
        const state = (grecaptcha.getResponse(1)) ? true : false;

        if (state === true) {
            document.getElementById('submitProvider').style.display = 'block';
        }
    };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <?php include 'includes/footer.php'; ?>
</body>

</html>