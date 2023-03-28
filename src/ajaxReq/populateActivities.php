<?php
session_start();
include '../includes/db.php';
?>
<?php
if (isset($_GET['page'])) {
  $currentPage = htmlspecialchars($_GET['page']);
} else {
  $currentPage = 1;
}
if ($_POST['searchBarInput'] == 'none') {
  $searchBarInput = '';
} else {
  $searchBarInput = 'AND name LIKE \'%' . $_POST['searchBarInput'] . '%\' ';
}
if (isset($_POST['category']) && $_POST['category'] != '' && $_POST['category'] != 'null') {
  $categories = $_POST['category'];
} else {
  $categories = 'none';
}
$categoryList = explode(',', $categories);
$categoriesFilter = '';
foreach ($categoryList as $value) {
  if ($value < 0) {
    $categoriesFilter =
      $categoriesFilter . ' AND NOT id IN (SELECT id_activity FROM BELONG WHERE id_category=' . abs($value) . ') ';
  } elseif ($value > 0 && $value != 'none') {
    $categoriesFilter =
      $categoriesFilter . ' AND id IN (SELECT id_activity FROM BELONG WHERE id_category=' . abs($value) . ') ';
  } else {
    $categoriesFilter = $categoriesFilter . '';
  }
}
if ($_POST['search'] == 'none') {
  $search = '';
} elseif ($_POST['search'] == 'maxAttendeeDesc') {
  $search = 'ORDER BY maxAttendee DESC';
} elseif ($_POST['search'] == 'maxAttendeeAsc') {
  $search = 'ORDER BY maxAttendee ASC';
} elseif ($_POST['search'] == 'priceDesc') {
  $search = 'ORDER BY priceAttendee DESC';
} elseif ($_POST['search'] == 'priceAsc') {
  $search = 'ORDER BY priceAttendee ASC';
} elseif ($_POST['search'] == 'durationDesc') {
  $search = 'ORDER BY duration DESC';
} elseif ($_POST['search'] == 'durationAsc') {
  $search = 'ORDER BY duration ASC';
} elseif ($_POST['search'] == 'nameDesc') {
  $search = 'ORDER BY name DESC';
} elseif ($_POST['search'] == 'nameAsc') {
  $search = 'ORDER BY name ASC';
} elseif ($_POST['search'] == 'statusDesc') {
  $search = 'ORDER BY status DESC';
} elseif ($_POST['search'] == 'statusAsc') {
  $search = 'ORDER BY status ASC';
}
if (isset($_SESSION['rights']) && $_SESSION['rights'] == 2) {
  $statusQuery = 'status = 1 OR status = 0 ';
} else {
  $statusQuery = 'status = 1';
}
$query = $db->query('SELECT id FROM ACTIVITY WHERE ' . $statusQuery . $categoriesFilter . $searchBarInput . $search);
$id = $query->fetchAll(PDO::FETCH_COLUMN);
$countId = count($id);
if ($countId == 0) {
  echo '<div class="my-5">
            <h3 class="py-5"><p class="text-secondary text-center">Aucune activité trouvée</p></h3>
          </div>';
} elseif ($countId - ($currentPage - 1) * 10 < 10) {
  for ($i = ($currentPage - 1) * 10; $i < $countId; $i++) {

    $query = $db->prepare(
      'SELECT name, SUBSTRING(description, 1, 450), duration, priceAttendee, maxAttendee, status FROM ACTIVITY WHERE id =:id',
    );
    $query->execute([
      ':id' => $id[$i],
    ]);
    $activity = $query->fetch(PDO::FETCH_ASSOC);
    $query = $db->prepare('SELECT id_category FROM BELONG WHERE id_activity = :id');
    $query->execute([
      ':id' => $id[$i],
    ]);
    $countCategory = count($query->fetchAll(PDO::FETCH_COLUMN));
    $category = $query->fetchAll(PDO::FETCH_COLUMN);
    ?>
<div class="card text-light bg-secondary mb-3">
    <?php
    $path = 'images/activities/';
    $idImage = $id[$i];
    include '../includes/image.php';
    ?>
    <div class="row">
        <div class="col-4">
            <a href="activity.php?id=<?= $id[$i] ?>"><img src="<?= $image0 ?>" class="card-img-top"
                    alt="<?= $activity['name'] ?>"></a>
        </div>
        <div class="card-body col-8 p-0 row">
            <div class="col-11 card-title">
                <h4 class="mt-2">
                    <a href="activity.php?id=<?= $id[$i] ?>" class="text-light"><?php
echo $activity['name'];
if ($activity['status'] == 0) {
  echo '<span class="badge bg-danger ms-2">Indisponible</span>';
} else {
  $query = $db->prepare(
    'SELECT ROUND(AVG(notation), 1) AS notation FROM COMMENT WHERE id_reservation IN (SELECT id FROM RESERVATION WHERE id_activity = :id)',
  );
  $query->execute([':id' => $id[$i]]);
  $notation = $query->fetch(PDO::FETCH_ASSOC);
  if ($notation['notation'] == 0) {
    echo '<span class="badge bg-warning ms-2">Aucune note</span>';
  } else {
    echo '<span class="badge bg-warning ms-2">' . $notation['notation'] . '/5.0</span>';
  }
}
?></a>
                </h4>
            </div>
            <?php
            $altId = str_replace(' ', '-', $id[$i]); //On remplace les espaces par des . pcq sinon ca passe pas en id pour les modals/popup
            if (isset($_SESSION['rights']) && $_SESSION['rights'] == 2) { ?>
            <div class="col-1 d-flex justify-content-end pe-3">
                <button type="button" class="btn-close btn-danger btn-sm me-2" aria-label="Close" data-bs-toggle="modal"
                    data-bs-target="#suppression<?= $altId ?>"></button>
            </div>
            <?php }
            ?>
            <div class="modal fade popup" id="suppression<?= $altId ?>" tabindex="-1" aria-labelledby="suppressionLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-dark" id="suppressionLabel">Suppression</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-dark">
                            Etes-vous sûr de supprimer cette activité de la base de donnée? Cet action est irréversible
                            !
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <a type="button" class="btn btn-danger"
                                href="verifications/verifActivity.php?delete=<?= $altId ?>">Supprimer</a>
                        </div>
                    </div>
                </div>
            </div>
            <p> <?= $activity['SUBSTRING(description, 1, 450)'] ?></p>
            <div class="mb-0">
                <?php for ($j = 0; $j < $countCategory; $j++) {

                  $query = $db->prepare(
                    'SELECT name, id FROM CATEGORY WHERE id IN (SELECT id_category FROM BELONG WHERE id_activity = :id_activity)',
                  );
                  $query->execute([':id_activity' => $id[$i]]);
                  $categoryName = $query->fetchAll(PDO::FETCH_ASSOC);
                  ?>
                <a type="button" class="btn btn-info mx-2" href="catalog.php?category=<?= $categoryName[$j][
                  'id'
                ] ?>"><?= $categoryName[$j]['name'] ?></a>
                <?php
                } ?>
            </div>
            <div class="row">
                <p class="fs-6 mb-0 col">Durée de l'activité : <i class="bi bi-clock" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Durée de l'activité"></i> <?= $activity[
                          'duration'
                        ] ?> min | Prix par participants :
                    <?= $activity[
                      'priceAttendee'
                    ] ?><i class="bi bi-currency-euro"></i> / <i class="bi bi-person-circle" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Prix par participant"></i>
                    | Nombre maximum de participants : <i class="bi bi-people" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Nombre maximum de participants"></i><?= $activity[
                          'maxAttendee'
                        ] ?></p>
            </div>
            <div class="row p-0 m-0">
                <div class="d-flex p-0 m-0 ps-3"> <?php
                $query = $db->prepare('SELECT day FROM SCHEDULE WHERE id_activity = :id');
                $query->execute([':id' => $id[$i]]);
                $day = $query->fetchAll(PDO::FETCH_ASSOC);
                $j = 0;
                $dayOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                $frenchDayInitial = ['L', 'M', 'M', 'J', 'V', 'S', 'D'];
                for ($k = 0; $k < 7; $k++) {
                  if (in_array($dayOfWeek[$k], array_column($day, 'day'))) { ?>
                    <div class="card bg-primary text-white mx-1 p-0 fs-6 text-center iconWeek">
                        <div class="card-body"> <?= $frenchDayInitial[$k] ?> </div>
                    </div>

                    <?php $j++;} else { ?>
                    <div class="card text-white mx-1 p-0 fs-6 text-center iconWeek" style="background-color:#7A828A">
                        <div class="card-body"> <?= $frenchDayInitial[$k] ?> </div>
                    </div>

                    <?php }
                }
                ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
  }
} else {
  for ($i = ($currentPage - 1) * 10; $i < 10 * $currentPage; $i++) {

    $query = $db->prepare(
      'SELECT name, SUBSTRING(description, 1, 450), duration, priceAttendee, maxAttendee FROM ACTIVITY WHERE id =:id',
    );
    $query->execute([
      ':id' => $id[$i],
    ]);
    $activity = $query->fetch(PDO::FETCH_ASSOC);
    $query = $db->prepare('SELECT id_category FROM BELONG WHERE id_activity = :id');
    $query->execute([
      ':id' => $id[$i],
    ]);
    $countCategory = count($query->fetchAll(PDO::FETCH_COLUMN));
    $category = $query->fetchAll(PDO::FETCH_COLUMN);
    ?>
<div class="card text-light bg-secondary mb-3">

    <?php
    $path = 'images/activities/';
    $idImage = $id[$i];
    include '../includes/image.php';
    ?>

    <div class="row">
        <div class="col-4">
            <a href="activity.php?id=<?= $id[$i] ?>"><img src="<?= $image0 ?>" class="card-img-top"
                    alt="<?= $activity['name'] ?>"></a>
        </div>
        <div class="card-body col-8 p-0 row">
            <div class="col-11 card-title">
                <h4 class="mt-2">
                    <a href="activity.php?id=<?= $id[$i] ?>" class="text-light"><?php
echo $activity['name'];
if ($activity['status'] == 0) {
  echo '<span class="badge bg-danger ms-2">Indisponible</span>';
} else {
  $query = $db->prepare(
    'SELECT ROUND(AVG(notation), 1) AS notation FROM COMMENT WHERE id_reservation IN (SELECT id FROM RESERVATION WHERE id_activity = :id)',
  );
  $query->execute([':id' => $id[$i]]);
  $notation = $query->fetch(PDO::FETCH_ASSOC);
  if ($notation['notation'] == 0) {
    echo '<span class="badge bg-warning ms-2">Aucune note</span>';
  } else {
    echo '<span class="badge bg-warning ms-2">' . $notation['notation'] . '/5.0</span>';
  }
}
?></a>
                </h4>
            </div>
            <?php
            $altId = str_replace(' ', '-', $id[$i]); //On remplace les espaces par des . pcq sinon ca passe pas en id pour les modals/popup
            if (isset($_SESSION['rights']) && $_SESSION['rights'] == 2) { ?>
            <div class="col-1 d-flex justify-content-end pe-3">
                <button type="button" class="btn-close btn-danger btn-sm" aria-label="Close" data-bs-toggle="modal"
                    data-bs-target="#suppression<?= $altId ?>"></button>
            </div>
            <?php }
            ?>
            <div class="modal fade popup" id="suppression<?= $altId ?>" tabindex="-1" aria-labelledby="suppressionLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-dark" id="suppressionLabel">Suppression</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-dark">
                            Etes-vous sûr de supprimer cette activité de la base de donnée? Cet action est
                            irréversible !
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <a type="button" class="btn btn-danger"
                                href="verifications/verifActivity.php?delete=<?= $altId ?>">Supprimer</a>
                        </div>
                    </div>
                </div>
            </div>
            <p><?= $activity['SUBSTRING(description, 1, 450)'] ?></p>
            <div class="mb-0">
                <?php for ($j = 0; $j < $countCategory; $j++) {

                  $query = $db->prepare(
                    'SELECT name, id FROM CATEGORY WHERE id IN (SELECT id_category FROM BELONG WHERE id_activity = :id_activity)',
                  );
                  $query->execute([':id_activity' => $id[$i]]);
                  $categoryName = $query->fetchAll(PDO::FETCH_ASSOC);
                  ?>
                <a type="button" class="btn btn-info mx-2" href="catalog.php?category=<?= $categoryName[$j][
                  'id'
                ] ?>"><?= $categoryName[$j]['name'] ?></a>
                <?php
                } ?>
            </div>
            <div class="row">
                <p class="fs-6 mb-0 col">Durée de l'activité : <i class="bi bi-clock" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Durée de l\'activité"></i><?= $activity[
                          'duration'
                        ] ?> min | Prix par participants :
                    <?= $activity[
                      'priceAttendee'
                    ] ?><i class="bi bi-currency-euro"></i> / <i class="bi bi-person-circle" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Prix par participant"></i>
                    | Nombre maximum de participants : <i class="bi bi-people" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Nombre maximum de participants"></i><?= $activity[
                          'maxAttendee'
                        ] ?></p>
            </div>
            <div class="row p-0 m-0">
                <div class="d-flex p-0 m-0 ps-3">
                    <?php
                    $query = $db->prepare('SELECT day FROM SCHEDULE WHERE id_activity = :id');
                    $query->execute([':id' => $id[$i]]);
                    $day = $query->fetchAll(PDO::FETCH_ASSOC);
                    $j = 0;
                    $dayOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                    $frenchDayInitial = ['L', 'M', 'M', 'J', 'V', 'S', 'D'];
                    for ($k = 0; $k < 7; $k++) {
                      if (in_array($dayOfWeek[$k], array_column($day, 'day'))) { ?>
                    <div class="card bg-primary text-white mx-1 p-0 fs-6 text-center iconWeek">
                        <div class="card-body"> <?= $frenchDayInitial[$k] ?> </div>
                    </div>

                    <?php $j++;} else { ?>
                    <div class="card text-white mx-1 p-0 fs-6 text-center iconWeek" style="background-color:#7A828A">
                        <div class="card-body"> <?= $frenchDayInitial[$k] ?> </div>
                    </div>

                    <?php }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
  }
}
?>
<?php
$totalPage = $countId / 10;
$totalPage = ceil($totalPage);
?>
<div class="d-flex justify-content-center">
    <nav>
        <ul class="pagination">
            <?php if ($countId > 10) {
              echo '<li class="page-item">
            <a class="page-link" href="catalog.php?page=1" aria-label="Start" style="background-color:green; color: white">
              <span aria-hidden="true">&laquo;</span>
            </a>
          </li>';
              if ($currentPage == $totalPage && $totalPage > 3) {
                echo '<li class="page-item"><a class="page-link" href="catalog.php?page=';
                echo $currentPage - 2;
                if ($_POST['searchBarInput'] != 'none') {
                  echo '&search=' . $_POST['searchBarInput'];
                }
                echo '" style="background-color:green; color: white">';
                echo $currentPage - 2;
                echo '</a></li>';
              }
              if ($currentPage > 1) {
                echo '
            <li class="page-item"><a class="page-link" href="catalog.php?page=';
                echo $currentPage - 1;
                if ($_POST['searchBarInput'] != 'none') {
                  echo '&search=' . $_POST['searchBarInput'];
                }
                echo '" style="background-color:green; color: white">';
                echo $currentPage - 1;
                echo '</a></li>';
              }
              echo '<li class="page-item"><a class="page-link" href="catalog.php?page=';
              echo $currentPage;
              if ($_POST['searchBarInput'] != 'none') {
                echo '&search=' . $_POST['searchBarInput'];
              }
              echo '" style="background-color:darkgreen; color: white">';
              echo $currentPage;
              echo '</a></li>';
              if ($currentPage != $totalPage) {
                echo '<li class="page-item"><a class="page-link" href="catalog.php?page=';
                echo $currentPage + 1;
                if ($_POST['searchBarInput'] != 'none') {
                  echo '&search=' . $_POST['searchBarInput'];
                }
                echo '" style="background-color:green; color: white">';
                echo $currentPage + 1;
                echo '</a></li>';
              }
              if ($currentPage == 1 && $totalPage > 2) {
                echo '<li class="page-item"><a class="page-link" href="catalog.php?page=';
                echo $currentPage + 2;
                if ($_POST['searchBarInput'] != 'none') {
                  echo '&search=' . $_POST['searchBarInput'];
                }
                echo '" style="background-color:green; color: white">';
                echo $currentPage + 2;
                echo '</a></li>';
              }
              echo '
            <li class="page-item">
              <a class="page-link" href="catalog.php?page=';
              echo $totalPage;
              echo '" aria-label="End" style="background-color:green; color: white">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>';
            } ?>
        </ul>
    </nav>