<?php session_start(); ?>
<?php include 'includes/db.php'; ?>

<!DOCTYPE html>
<html>

<?php
$linkCss = 'css-js/style.css';
$linkLogo = 'images/logo.png';
$title = 'Catalogue des activités';
include 'includes/head.php';
?>
<?php
if (isset($_GET['page'])) {
  $page = htmlspecialchars($_GET['page']);
} else {
  $page = 1;
}
if (isset($_GET['category'])) {
  $category = htmlspecialchars($_GET['category']);
  $populate = $page . ',' . $category;
} else {
  $populate = $page;
}
?>

<body value=<?= $populate ?>>
    <?php include 'includes/header.php'; ?>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center">Catalogue des activités</h1>
                </div>
            </div>
            <br>
            <div class="row">
                <?php if (isset($_SESSION['rights']) && $_SESSION['rights'] == 2) {
                  echo '
          <div class="text-center">
          <a href="addActivityPage.php" class="btn btn-secondary">Ajouter une activité</a>
          </div>';
                } ?>
            </div>
            <hr size="5">
            <div class="row">
                <?php
                $query = $db->prepare('SELECT name, id FROM CATEGORY');
                $query->execute();
                $categories = $query->fetchAll(PDO::FETCH_ASSOC);
                echo '<div class="row d-flex justify-content-center">';
                foreach ($categories as $category) { ?>
                <button class="btn btn-outline-success col-2 m-2" onclick="filterCategory(<?php echo $page; ?>, this)"
                    id=<?= $category['id'] ?>><?php echo $category['name']; ?></button>
                <?php }
                echo '</div>';
                ?>
            </div>
            <hr size="5">
            <div class="row">
                <button class="btn btn-primary col mx-2" onclick="filterName(<?php echo $page; ?>, this)"
                    id="name">Nom</button>
                <button class="btn btn-primary col mx-2" onclick="filterDuration(<?php echo $page; ?>, this)"
                    id="duration">Durée</button>
                <button class="btn btn-primary col mx-2" onclick="filterPrice(<?php echo $page; ?>, this)"
                    id="price">Prix</button>
                <button class="btn btn-primary col mx-2" onclick="filterMaxAttendee(<?php echo $page; ?>, this)"
                    id="maxAttendee">Nombre de participants</button>
                <?php if (isset($_SESSION['rights']) && $_SESSION['rights'] == 2) {
                  echo '<button class="btn btn-primary col mx-2" onclick="filterStatus(';
                  echo $page;
                  echo ', this)" id="status">Status</button>';
                } ?>
            </div>
            <hr size="5">

            <?php include 'includes/msg.php'; ?>

            <div id="activities">

            </div>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
    <script src="css-js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>