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
?>

<div class="container col-md-6 border border-2 border-secondary rounded" id="table">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
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
          echo '<td>' . $participant['lastName'] . '</td>';
          echo '<td>' . $participant['firstName'] . '</td>';
        }
        echo '</tr>';
        echo '</tbody>';
        ?>
    </table>
</div>