<?php
session_start();
include '../includes/db.php';

if (isset($_GET['message']) && str_contains($_GET['message'], 'Commentaire')) {
  include 'includes/msg.php';
}
$id = $_POST['id'];
if (isset($_POST['filter']) == 'undefined') {
  $filter = '';
} elseif ($_POST['filter'] == 'dateAsc') {
  $filter = 'ORDER BY time ASC';
} elseif ($_POST['filter'] == 'dateDesc') {
  $filter = 'ORDER BY time DESC';
} elseif ($_POST['filter'] == 'noteAsc') {
  $filter = 'ORDER BY notation ASC';
} elseif ($_POST['filter'] == 'noteDesc') {
  $filter = 'ORDER BY notation DESC';
}
?>
<div>
    <form action="verifications/verifComment.php" method="POST">
        <?php if (isset($_SESSION['siret'])) {
          $query = $db->prepare(
            'SELECT id FROM RESERVATION WHERE CAST(CONCAT(date," ", time) AS datetime) < NOW() AND siret = :siret AND status = 1 AND id_activity = :id_activity',
          );
          $query->execute([
            ':siret' => htmlspecialchars($_SESSION['siret']),
            ':id_activity' => $id,
          ]);
          $payed = $query->fetch(PDO::FETCH_ASSOC);
          $query = $db->prepare(
            'SELECT id, content, notation, date_format(COMMENT.date, "%d/%m/%Y") as day, date_format(date, "%H:%i") as time FROM COMMENT WHERE id_reservation IN (SELECT id FROM RESERVATION WHERE siret=:siret AND id_activity = :id_activity)',
          );
          $query->execute([
            ':siret' => htmlspecialchars($_SESSION['siret']),
            ':id_activity' => $id,
          ]);
          $commented = $query->fetch(PDO::FETCH_ASSOC);
          if ($payed && !$commented) { ?>
        <textarea class="form-control" id="comment" name="comment" cols="30" rows="5"></textarea>
        <div>
            <span>
                <i class="bi-star-fill note me-2" data-value="1" style="font-size: 2.5rem;"></i>
                <i class="bi-star-fill note me-2" data-value="2" style="font-size: 2.5rem;"></i>
                <i class="bi-star-fill note me-2" data-value="3" style="font-size: 2.5rem;"></i>
                <i class="bi-star-fill note me-2" data-value="4" style="font-size: 2.5rem;"></i>
                <i class="bi-star-fill note me-2" data-value="5" style="font-size: 2.5rem;"></i>
            </span>
        </div>
        <input type="hidden" name="notation" id="notationInput">
        <input type="hidden" name="activity" value="<?= $id ?>">
        <button type="submit" class="btn btn-primary mt-3">Publier</button>
        <?php }
          if ($commented) { ?>
        <textarea class="form-control" id="comment" name="comment" cols="30" rows="5"
            style="white-space: pre-line"><?= $commented['content'] ?></textarea>
        <div>
            <span>
                <?php for ($j = 0; $j < 5; $j++) {
                  if ($j < $commented['notation']) { ?>
                <i class="bi-star-fill note me-2" data-value="<?= $j + 1 ?>"
                    style="font-size: 2.5rem; color: lightgray;"></i>
                <?php } else { ?>
                <i class="bi-star-fill note me-2" data-value="<?= $j + 1 ?>" style="font-size: 2.5rem;"></i>
                <?php }
                } ?>
            </span>
        </div>
        <input type="hidden" name="notation" id="notationInput" value="<?= $commented['notation'] ?>">
        <input type="hidden" name="activity" value="<?= $id ?>">
        <button type="submit" name="update" class="btn btn-primary mt-3" value="1">Modifier</button>
        <button type="submit" name="delete" class="btn btn-danger mt-3" value="1">Supprimer</button>
        <?php }
        } ?>
    </form>
</div>
<?php
$query = $db->prepare(
  'SELECT COMMENT.content, COMMENT.notation, date_format(COMMENT.date, "%d/%m/%Y") as day, date_format(COMMENT.date, "%H:%i") as time, COMPANY.companyName FROM COMMENT INNER JOIN RESERVATION ON id_reservation = RESERVATION.id INNER JOIN COMPANY ON RESERVATION.siret = COMPANY.siret WHERE RESERVATION.id_activity = :id ' .
    $filter,
);
$query->execute([':id' => $id]);
$comments = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($comments as $comment) { ?>
<hr size="4">
<div class="d-flex justify-content-center">
    <button class="btn btn-primary col-4 mx-2 <?php if ($_POST['filter'] == 'dateAsc') {
      echo 'asc';
    } elseif ($_POST['filter'] == 'dateDesc') {
      echo 'desc';
    } ?>" id="date" onclick="filterCommentDate(<?= $id ?>, this)"><?php if ($_POST['filter'] == 'dateAsc') {
  echo 'Date <i class="bi bi-arrow-up-short"></i>';
} elseif ($_POST['filter'] == 'dateDesc') {
  echo 'Date <i class="bi bi-arrow-down-short"></i>';
} else {
  echo 'Date';
} ?></button>
    <button class="btn btn-primary col-4 mx-2 <?php if ($_POST['filter'] == 'noteAsc') {
      echo 'asc';
    } elseif ($_POST['filter'] == 'noteDesc') {
      echo 'desc';
    } ?>" id="notation" onclick="filterCommentNotation(<?= $id ?>, this)"><?php if ($_POST['filter'] == 'noteAsc') {
  echo 'Note <i class="bi bi-arrow-up-short"></i>';
} elseif ($_POST['filter'] == 'noteDesc') {
  echo 'Note <i class="bi bi-arrow-down-short"></i>';
} else {
  echo 'Note';
} ?></button>
</div>
<hr size="4">
<div class="card text-white p-0 mb-3 fs-6" style="background-color:#7A828A">
    <div class="card-body row">
        <div class="card-title text-start ps-3 col-10 d-flex">
            <h4>
                <?= $comment['companyName'] . ' - ' . $comment['day'] ?>
            </h4>
        </div>

        <h6 class="col-2">
            <?php for ($i = 0; $i < 5; $i++) {
              if ($i < $comment['notation']) {
                echo '<i class="bi bi-star-fill" style="color: yellow"></i>';
              } else {
                echo '<i class="bi bi-star" style="color: yellow"></i>';
              }
            } ?>
        </h6>
        <hr size="4" class="mb-0">
        <p class="card-text text-start" style="white-space: pre-line">
            <?= $comment['content'] ?>
        </p>
    </div>
</div>
<?php }
if (!$comments) { ?>
<hr size="4">
<p class="text-center fs-2">Aucun commentaire</p>
<?php }


?>
