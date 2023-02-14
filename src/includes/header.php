<div class="container-fluid headerBar">
    <div class="row">
        <div class="col-4 col-md-1"></div>
        <div class="col-4 col-md-1 d-flex justify-content-center">
            <a href="index.php"><img src="images/logo.png" alt="TeamEase" class="img-fluid"></a>
        </div>

        <div class="col-12 col-md-9">
            <form class="d-flex" action="GET" action="#">
                <div class="col-10">
                    <input id="search" class="form-control form-control-lg me-3" name="search" type="search" placeholder="Rechercher une activité" aria-label="Search">
                </div>
                <div class="col-2">
                    <button class="btn btn-outline-secondary btn-lg" type="submit">Rechercher</button>
                </div>
            </form>

            <div class="col-md-1"></div>
            <div class="row">
                <a href="button" class="btn btn-secondary col mx-3 mt-3" <?php if (isset($_SESSION['compte'])) {
                  echo 'href="#">Mes reservations</a>';
                } else {
                  echo 'href="connexion.php">Se connecter</a>';
                } ?>
                <a href="test.php" class="btn btn-secondary col mx-3 mt-3" <?php if (isset($_SESSION['compte'])) {
                  echo 'href="deconnexion.php">Se deconnecter</a>';
                } else {
                  echo 'href="inscription.php">S\'inscrire</a>';
                } ?>
            </div>
        </div>
    </div>
</div>

<nav id="navbar" class="navbar sticky-top navbar-expand-lg navbar-light" style="background-color: #f0ead3; box-shadow: 0px 5px 5px -5px rgba(0, 0, 0, 0.75);">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="activites.php">Catalogue d'activités</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Categorie d'activités
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <!-- Prendre dans la db les categorie et les afficher ici -->
                        <li><a class="dropdown-item" href="genre.php?genre=Test">Test</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="partenaire.php">Entreprises partenaires</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="aPropos.php">A propos de nous</a>
                </li>
            </ul>
        </div>
    </div>
</nav>