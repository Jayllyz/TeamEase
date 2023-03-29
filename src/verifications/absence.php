<?php
include '../includes/db.php';
$id = htmlspecialchars($_GET['id']);


if (!isset($id)) {
    header('location: ../index.php');
    exit();
}else{
    $date = htmlspecialchars($_GET['date']);
    $raison = htmlspecialchars($_POST['raison']);
    

    // changer le format de la date et mettre année mois jour
    $date = explode('/', $date);
    $date = $date[2] . '-' . $date[1] . '-' . $date[0];



    $sql = "SELECT * FROM RESERVATION WHERE id_activity = :id AND date = :date";
    $query = $db->prepare($sql);
    $query->execute([
        'id' => $id,
        'date' => $date,
    ]);
    $reservation = $query->fetchAll(PDO::FETCH_ASSOC);

    for($i = 0; $i < count($reservation); $i++){
        $sql = "DELETE FROM ESTIMATE WHERE id_reservation = :id";
    $query = $db->prepare($sql);
    $query->execute([
        'id' => $reservation[$i]['id'],
    ]);
}



    for($i = 0; $i < count($reservation); $i++){
        
        


    $sql = "DELETE FROM RESERVATION WHERE id_activity = :id AND date = :date";
        $query = $db->prepare($sql);
        $query->execute([
            'id' => $id,
            'date' => $date,
        ]);

        $sql = 'SELECT email FROM COMPANY WHERE siret = :siret';
        $query = $db->prepare($sql);
        $query->execute([
            'siret' => $reservation[$i]['siret'],
        ]);
        $email = $query->fetch(PDO::FETCH_ASSOC);
        $email = $email['email'];

        
        $subject = 'Annulation d\'une activité';
        $msgHTML =
      '<img src="localhost/images/logo.png" class="logo float-left m-2 h-75 me-4" width="95" alt="Logo">
                <p class="display-2">Bonjour nous vous envoyons ce message pour vous informer que votre activité du ' . $date .'
                 ne sera pas disponible pour raison '. $raison . ':<br></p>
      <a href="localhost/includes/confemail.php?
      email=' . $email .
      '&type=' .
      'company">Pour plus de detaille !</a>';
    $destination = '../login.php';
    include '../includes/mailer.php';
   
    }

    header('location: ../profile.php');
    exit;

    
}
