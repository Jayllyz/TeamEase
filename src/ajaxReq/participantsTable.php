<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['siret'])) {
  header('Location: ../index.php');
  exit();
}

$idReserv = htmlspecialchars($_POST['idReserv']);

$req = $db->prepare('SELECT * FROM RESERVED WHERE id_reservation = :id_reservation');
$req->execute([
  'id_reservation' => $idReserv,
]);
$participants = $req->fetchAll(PDO::FETCH_ASSOC);

if (!$participants) {
  echo '';
  exit();
}
?>


<h2 class="mt-5 text-center">Liste des participants</h2>

<div class="container col-md-6 border border-2 border-secondary rounded" id="table">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
                <th scope="col">Email</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <?php
        echo '<tbody>';
        echo '<tr>';

        foreach ($participants as $participant) {
          $req = $db->prepare('SELECT * FROM ATTENDEE WHERE id = :id');
          $req->execute([
            'id' => $participant['id_attendee'],
          ]);
          $participant = $req->fetch(PDO::FETCH_ASSOC);
          echo '<td> <input type="text" id="' .
            $participant['id'] .
            '-lastname" value="' .
            $participant['lastName'] .
            '" class="form-control""></td>';
          echo '<td> <input type="text" id="' .
            $participant['id'] .
            '-firstname" value="' .
            $participant['firstName'] .
            '" class="form-control""></td>';
          echo '<td> <input type="text"  id="' .
            $participant['id'] .
            '-mail" value="' .
            $participant['email'] .
            '" class="form-control""></td>';
          echo '<td><button type="button" class="btn btn-danger" onclick="deleteParticipant(' .
            $participant['id'] .
            ')">Supprimer</button> <button type="button" class="btn btn-warning" onclick="updateParticipant(' .
            $participant['id'] .
            ')">Modifier</button></td>';
        }
        echo '</tr>';
        echo '</tbody>';
        ?>
    </table>
</div>