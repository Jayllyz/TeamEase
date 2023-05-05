<?php session_start();
if (!isset($_SESSION['siret'])) {
  header('Location: ../index.php');
  exit();
}
include '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<?php
$linkLogo = '../images/logo.png';
$linkCss = '../css-js/style.css';
$title = 'Saisie des partcipants';
include '../includes/head.php';
$req = $db->prepare('SELECT * FROM RESERVATION WHERE id = :id');
$req->execute([
  'id' => htmlspecialchars($_GET['id']),
]);
$reserv = $req->fetch(PDO::FETCH_ASSOC);
$attendee = $reserv['attendee'];
$idReserv = $reserv['id'];
?>

<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <h1 style="margin-top: 1rem; margin-bottom: 1rem;"><?= $title ?></h1>

        <form method="POST" onsubmit="fillParticipants(<?= $idReserv ?>)">
            <div class="container col-md-6 border border-2 border-secondary rounded" id="form">
                <label for="participants" class="form-label"><strong>Merci de suivre la syntaxe suivante :</strong>
                    NOM; PRENOM; MAIL;</label>
                <textarea class="form-control mb-6" id="participants" name="participants" rows="5" required></textarea>

                <label class="mb-2">Pour procéder à une modification, veuillez ressaisir toute la liste.</label>

                <input type="hidden" name="attendees" id="attendees" value="<?= $attendee ?>">

                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary" id="submit">Valider</button>
                </div>
            </div>
        </form>
    </main>
    <script src="../css-js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <?php include '../includes/footer.php'; ?>
</body>

</html>