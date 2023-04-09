<?php
include '../includes/db.php';

if (!isset($_GET['occupation'])) {
  exit();
}

$occupation = htmlspecialchars($_GET['occupation']);
$query = $db->prepare('SELECT id FROM OCCUPATION WHERE name = :name');
$query->execute([
  'name' => $occupation,
]);
$id = $query->fetch(PDO::FETCH_COLUMN);

$query = $db->prepare('SELECT id, firstName, UPPER(lastName) FROM PROVIDER WHERE id_occupation = :id');
$query->execute([
  'id' => $id,
]);
$providers = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<button type="button" class="btn btn-secondary dropdown-toggle mx-2 provider-dropdown" data-bs-toggle="dropdown"
    aria-expanded="false">
    Prestataires
</button>
<ul class="dropdown-menu">
    <?php foreach ($providers as $provider) { ?>
    <li><a class="dropdown-item" onclick="selectProvider(this)" id="<?= $provider['id'] ?>">
            <?= $provider['firstName'] ?> <?= $provider['UPPER(lastName)'] ?> (
            <?php
            $query = $db->prepare('SELECT day FROM AVAILABILITY WHERE id_provider = :id');
            $query->execute([
              'id' => $provider['id'],
            ]);
            $days = $query->fetchAll(PDO::FETCH_ASSOC);

            for ($i = 0; $i < count($days); $i++) {
              switch ($days[$i]['day']) {
                case 'monday':
                  echo 'Lundi';
                  break;
                case 'tuesday':
                  echo 'Mardi';
                  break;
                case 'wednesday':
                  echo 'Mercredi';
                  break;
                case 'thursday':
                  echo 'Jeudi';
                  break;
                case 'friday':
                  echo 'Vendredi';
                  break;
                case 'saturday':
                  echo 'Samedi';
                  break;
                case 'sunday':
                  echo 'Dimanche';
                  break;
              }
              if ($i < count($days) - 1) {
                echo ', ';
              }
            }
            ?> )
        </a></li>
    <?php } ?>
</ul>