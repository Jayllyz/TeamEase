<div class="container-fluid headerBar">
    <div class="row">
        <div class="col-4 col-md-1"></div>
        <div class="col-4 col-md-1 d-flex justify-content-center">
            <a href="index.php"><img src="<?= $linkLogo ?>" alt="TeamEase" class="img-fluid"></a>
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
                <a class="btn btn-secondary col mx-3 mt-3" <?php if (
                  isset($_SESSION['siret']) ||
                  isset($_SESSION['id'])
                ) {
                  echo 'href="#">Mes reservations</a>';
                } else {
                  echo 'href="login.php">Se connecter</a>';
                } ?> <a class="btn btn-secondary col mx-3 mt-3" <?php if (
   isset($_SESSION['siret']) ||
   isset($_SESSION['id'])
 ) {
   echo 'href="logout.php">Se deconnecter</a>';
   echo '<a class="btn btn-secondary col mx-3 mt-3" href="profile.php">Ton profil</a>';
 } else {
   echo 'href="signin.php">S\'inscrire</a>';
 } ?> </div>
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
                        <a class="nav-link" href="catalog.php">Catalogue d'activités</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Categorie d'activités
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php
                            $query = $db->query('SELECT name FROM CATEGORY');
                            while ($category = $query->fetch()) {
                              echo '<li><a class="dropdown-item" href="catalog.php?category=' .
                                $category['name'] .
                                '">' .
                                $category['name'] .
                                '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="partenaire.php">Entreprises partenaires</a>
                    </li>
                    <?php if (isset($_SESSION['rights'])) {
                      if ($_SESSION['rights'] == 2) {
                        echo '
                              <li class="nav-item">
                                  <a class="nav-link" href="material.php">Gestion du matériel</a>
                              </li>
                              ';
                      }
                    } ?>
                    <li class="nav-item">
                        <a class="nav-link" href="aPropos.php">A propos de nous</a>
                    </li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION["siret"]) && $_SESSION["rights"] == 2) { ?>

                            <a href="admin.php" class="logo_admin">
                                <svg viewBox="0 0 24 24" width="24" height="24" fill="black" id="logo_admin">
                                    <path d="M0 0 H24 V24 H0 V0 z" style="fill:none;"></path>
                                    <path d="M17,11c0.34,0,0.67,0.04,1,0.09V6.27L10.5,3L3,6.27v4.91c0,4.54,3.2,8.79,7.5,9.82c0.55-0.13,1.08-0.32,1.6-0.55 C11.41,19.47,11,18.28,11,17C11,13.69,13.69,11,17,11z"></path>
                                    <path d="M17,13c-2.21,0-4,1.79-4,4c0,2.21,1.79,4,4,4s4-1.79,4-4C21,14.79,19.21,13,17,13z M17,14.38c0.62,0,1.12,0.51,1.12,1.12 s-0.51,1.12-1.12,1.12s-1.12-0.51-1.12-1.12S16.38,14.38,17,14.38z M17,19.75c-0.93,0-1.74-0.46-2.24-1.17 c0.05-0.72,1.51-1.08,2.24-1.08s2.19,0.36,2.24,1.08C18.74,19.29,17.93,19.75,17,19.75z"></path>
                                </svg>
                            </a>

                        <?php } ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

</div>