<?php
session_start();
include '../includes/db.php';
?>
<?php
if (isset($_GET['page'])) {
  $currentPage = $_GET['page'];
} else {
  $currentPage = 1;
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
$query = $db->query('SELECT id FROM ACTIVITY WHERE status = 1 ' . $search);
$id = $query->fetchAll(PDO::FETCH_COLUMN);
$countId = count($id);
if ($countId == 0) {
  echo '<div class="my-5">
            <h3 class="py-5"><p class="text-secondary text-center">Aucune activité trouvée</p></h3>
          </div>';
} elseif ($countId - ($currentPage - 1) * 10 < 10) {
  for ($i = ($currentPage - 1) * 10; $i < $countId; $i++) {
    $query = $db->prepare('SELECT name FROM ACTIVITY WHERE id =:id');
    $query->execute([
      ':id' => $id[$i],
    ]);
    $activity = $query->fetchAll(PDO::FETCH_COLUMN);
    $query = $db->prepare('SELECT id_category FROM BELONG WHERE id_activity = :id');
    $query->execute([
      ':id' => $id[$i],
    ]);
    $countCategory = count($query->fetchAll(PDO::FETCH_COLUMN));
    $category = $query->fetchAll(PDO::FETCH_COLUMN);
    echo '
            <div class="card text-light bg-secondary mb-3">
  
            <div class="row">
            <div class="col-4">
            <a href="activity.php?id=' .
      $id[$i] .
      '"><img src="images/activities/' .
      $id[$i] .
      $activity['name'] .
      '0.jpg" class="card-img-top" alt="' .
      $activity['name'] .
      '"></a>
            </div>
            <div class="card-body col-8 row">
              <div class="col-11 card-title">
              <h4><a href="activity.php?id=' .
      $id[$i] .
      '" class="text-light">' .
      $activity['name'] .
      '</a></h4>
              </div>';
    $altId = str_replace(' ', '-', $id[$i]); //On remplace les espaces par des . pcq sinon ca passe pas en id pour les modals/popup
    if (isset($_SESSION['rights']) && $_SESSION['rights'] == 2) {
      echo '
                <div class="col-1 d-flex justify-content-end pe-3">
                <button type="button" class="btn-close btn-danger btn-sm" aria-label="Close" data-bs-toggle="modal" data-bs-target="#suppression' .
        $altId .
        '"></button>
                </div>';
    }
    echo '
                <div class="modal fade popup" id="suppression' .
      $altId .
      '" tabindex="-1" aria-labelledby="suppressionLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title text-dark" id="suppressionLabel">Suppression</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-dark">
                          Etes-vous sûr de supprimer cette activité de la base de donnée? Cet action est irréversible !
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                          <a type="button" class="btn btn-danger" href="verifications/verifActivity.php?delete=' .
      $altId .
      '">Supprimer</a>
                        </div>
                    </div>
                  </div>
                </div>
                <p>' .
      $activity['SUBSTRING(description, 1, 450)'] .
      '</p>
              <p class="mb-0">';
    for ($j = 0; $j < $countCategory; $j++) {
      $query = $db->prepare(
        'SELECT name FROM CATEGORY WHERE id IN (SELECT id_category FROM BELONG WHERE :id_activity = :id_activity)'
      );
      $query->execute([':id_activity' => $id[$i]]);
      $categoryName = $query->fetchAll(PDO::FETCH_COLUMN);
      echo '<a type="button" class="btn btn-info mx-2" href="">' . $categoryName[0] . '</a>';
    }
    echo '<div class="row"></p>
              <p class="fs-6 mb-0 col">Durée de l\'activité : <i class="bi bi-clock" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Durée de l\'activité"></i> ' .
      $activity['duration'] .
      'h | Prix par participants : ' .
      $activity['priceAttendee'] .
      '<i class="bi bi-currency-euro"></i> / <i class="bi bi-person-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Prix par participant"></i>
               | Nombre maximum de participants : <i class="bi bi-people" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nombre maximum de participants"></i> ' .
      $activity['maxAttendee'] .
      '</p></div>';
    echo '
                </div>
                </div>
              </div>';
  }
} else {
  for ($i = ($currentPage - 1) * 10; $i < 10 * $currentPage; $i++) {
    $query = $db->prepare(
      'SELECT name, SUBSTRING(description, 1, 450), duration, priceAttendee, maxAttendee FROM ACTIVITY WHERE id = :id'
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
    echo '
            <div class="card text-light bg-secondary mb-3">
  
            <div class="row">
            <div class="col-4">
            <a href="activity.php?id=' .
      $id[$i] .
      '"><img src="images/activities/' .
      $id[$i] .
      $activity['name'] .
      '0.jpg" class="card-img-top" alt="' .
      $activity['name'] .
      '"></a>
            </div>
            <div class="card-body col-8 row">
              <div class="col-11 card-title">
              <h4><a href="activity.php?id=' .
      $id[$i] .
      '" class="text-light">' .
      $activity['name'] .
      '</a></h4>
              </div>';
    $altId = str_replace(' ', '-', $id[$i]); //On remplace les espaces par des . pcq sinon ca passe pas en id pour les modals/popup
    if (isset($_SESSION['rights']) && $_SESSION['rights'] == 2) {
      echo '
                <div class="col-1 d-flex justify-content-end pe-3">
                <button type="button" class="btn-close btn-danger btn-sm" aria-label="Close" data-bs-toggle="modal" data-bs-target="#suppression' .
        $altId .
        '"></button>
                </div>';
    }
    echo '
                <div class="modal fade popup" id="suppression' .
      $altId .
      '" tabindex="-1" aria-labelledby="suppressionLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title text-dark" id="suppressionLabel">Suppression</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-dark">
                          Etes-vous sûr de supprimer cette activité de la base de donnée? Cet action est irréversible !
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                          <a type="button" class="btn btn-danger" href="verifications/verifActivity.php?delete=' .
      $altId .
      '">Supprimer</a>
                        </div>
                    </div>
                  </div>
                </div>
                <p>' .
      $activity['SUBSTRING(description, 1, 450)'] .
      '</p>
              <p class="mb-0">';
    for ($j = 0; $j < $countCategory; $j++) {
      $query = $db->prepare(
        'SELECT name FROM CATEGORY WHERE id IN (SELECT id_category FROM BELONG WHERE :id_activity = :id_activity)'
      );
      $query->execute([':id_activity' => $id[$i]]);
      $categoryName = $query->fetchAll(PDO::FETCH_COLUMN);
      echo '<a type="button" class="btn btn-info mx-2" href="">' . $categoryName[0] . '</a>';
    }
    echo '<div class="row"></p>
              <p class="fs-6 mb-0 col">Durée de l\'activité : <i class="bi bi-clock" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Durée de l\'activité"></i> ' .
      $activity['duration'] .
      'h | Prix par participants : ' .
      $activity['priceAttendee'] .
      '<i class="bi bi-currency-euro"></i> / <i class="bi bi-person-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Prix par participant"></i>
               | Nombre maximum de participants : <i class="bi bi-people" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nombre maximum de participants"></i> ' .
      $activity['maxAttendee'] .
      '</p></div>';
    echo '
                </div>
                </div>
              </div>';
  }
}
?>
    </div>
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
            echo '" style="background-color:green; color: white">';
            echo $currentPage - 2;
            echo '</a></li>';
          }
          if ($currentPage > 1) {
            echo '
            <li class="page-item"><a class="page-link" href="catalog.php?page=';
            echo $currentPage - 1;
            echo '" style="background-color:green; color: white">';
            echo $currentPage - 1;
            echo '</a></li>';
          }
          echo '<li class="page-item"><a class="page-link" href="catalog.php?page=';
          echo $currentPage;
          echo '" style="background-color:darkgreen; color: white">';
          echo $currentPage;
          echo '</a></li>';
          if ($currentPage != $totalPage) {
            echo '<li class="page-item"><a class="page-link" href="catalog.php?page=';
            echo $currentPage + 1;
            echo '" style="background-color:green; color: white">';
            echo $currentPage + 1;
            echo '</a></li>';
          }
          if ($currentPage == 1 && $totalPage > 2) {
            echo '<li class="page-item"><a class="page-link" href="catalog.php?page=';
            echo $currentPage + 2;
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