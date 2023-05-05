<?php
include '../includes/db.php';

$req = $db->prepare('SELECT id, name, salary FROM OCCUPATION');
$req->execute();
$result = $req->fetchAll(PDO::FETCH_ASSOC);

echo '<table class="table text-center table-bordered table-hover" style="border-color:black">';
echo '<thead>';
echo '<tr>';
echo '<th>Nom</th>';
echo '<th>Salaire</th>';
echo '<th>Actions</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

foreach ($result as $select) {
  echo '<tr>';
  echo '<td>' . $select['name'] . '</td>';
  echo '<td>' . $select['salary'] . '€/h' . '</td>';
  echo '<td>';
  echo '<a data-bs-toggle="modal" data-bs-target="#editJob" class="btn btn-primary" onclick=addEditJobId(' .
    $select['id'] .
    ')>Modifier</a>';
  echo '<a class="btn btn-danger mx-1" onclick=deleteJob(' . $select['id'] . ')>Supprimer</a>';
  echo '</td>';
  echo '</tr>';
}

echo '</tbody>';
echo '</table>';

?>
