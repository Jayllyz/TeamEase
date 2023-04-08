<?php
ob_start();
//Suppression d'une activité------------------------------------------------------------------------
session_start();

if (!isset($_SESSION['rights']) == 2) {
  header('Location: ../index.php');
  exit();
}
include '../includes/db.php';

if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $request = $db->prepare('SELECT name FROM ACTIVITY WHERE id = :id');
  $request->execute([
    ':id' => $id,
  ]);
  $activity = $request->fetch(PDO::FETCH_ASSOC);
  $path = '../images/activities/';
  $idImage = $id;
  include '../includes/image.php';
  if ($image0 != '../images/activities/placeholder.jpg') {
    unlink($image0);
  }
  if ($image1 != '../images/activities/placeholder.jpg') {
    unlink($image1);
  }
  if ($image2 != '../images/activities/placeholder.jpg') {
    unlink($image2);
  }
  if ($image3 != '../images/activities/placeholder.jpg') {
    unlink($image3);
  }

  $request = $db->prepare('DELETE FROM BELONG WHERE id_activity = :id_activity');
  $result = $request->execute([
    ':id_activity' => $id,
  ]);
  $request = $db->prepare('DELETE FROM MATERIAL_ACTIVITY WHERE id_activity = :id');
  $request->execute([
    'id' => $id,
  ]);
  $request = $db->prepare('DELETE FROM ANIMATE WHERE id_activity = :id_activity');
  $result3 = $request->execute([
    ':id_activity' => $id,
  ]);
  $request = $db->prepare('DELETE FROM ACTIVITY WHERE id = :id');
  $result2 = $request->execute([
    ':id' => $id,
  ]);

  if ($result && $result2 && $result3) {
    $message = 'L\'activité a bien été supprimée';
    header('location:../catalog.php?message=' . $message . '&type=success');
    exit();
  } else {
    $message = 'Une erreur est survenue';
    header('location:../catalog.php?message=' . $message . '&type=danger');
    exit();
  }
}

//Modification d'une activité-----------------------------------------------------------------

if (isset($_GET['update'])) {
  if ($_GET['update'] == 'description') {
    $request = $db->prepare('UPDATE ACTIVITY SET description = :description WHERE id = :id');
    $result = $request->execute([
      ':description' => $_POST['description'],
      ':id' => htmlspecialchars($_GET['id']),
    ]);
    if ($result) {
      $message = 'La description a bien été modifiée';
      header('location:../activity.php?id=' . $_GET['id'] . '&message=' . $message . '&type=success');
      exit();
    } else {
      $message = 'Une erreur est survenue';
      header('location:../activity.php?id=' . $_GET['id'] . '&message=' . $message . '&type=danger');
      exit();
    }
  } elseif ($_GET['update'] == 'category') {
    if (!isset($_POST['category'])) {
      $message = 'Veuillez selectionner au moins une catégorie';
      header('location:../activity.php?id=' . $_GET['id'] . '&message=' . $message . '&type=danger');
      exit();
    }

    foreach ($_POST['category'] as $category) {
      if ($category == 'En ligne') {
        $online = true;
      }
      if ($category == 'En personne') {
        $inPerson = true;
      }
    }

    if ($online && $inPerson) {
      $message = 'Une activité ne peut pas être à la fois en ligne et en personne';
      header('location:../activity.php?message=' . $message . '&type=danger');
      exit();
    }

    $delete = $db->prepare('DELETE FROM BELONG WHERE id_activity = :id_activity');
    $delete->execute([
      ':id_activity' => htmlspecialchars($_GET['id']),
    ]);

    $i = 0;
    do {
      $query = $db->prepare('SELECT id FROM CATEGORY WHERE name = :name');
      $query->execute([
        ':name' => $_POST['category'][$i],
      ]);
      $category = $query->fetch(PDO::FETCH_ASSOC);
      $insert = $db->prepare('INSERT INTO BELONG (id_activity, id_category) VALUES (:id_activity, :id_category)');
      $result = $insert->execute([
        'id_activity' => htmlspecialchars($_GET['id']),
        'id_category' => $category['id'],
      ]);
      if (!$result) {
        $message = 'Une erreur est survenue';
        header('location:../activity.php?id=' . $_GET['id'] . '&message=' . $message . '&type=danger');
        exit();
      }
      $i++;
    } while ($i < count($_POST['category']));

    $message = 'La catégorie a bien été modifiée';
    header('location:../activity.php?id=' . $_GET['id'] . '&message=' . $message . '&type=success');
    exit();
  } elseif ($_GET['update'] == 'details') {
    $result2 = true;
    $result3 = true;

    if ($_POST['duration'] <= 0) {
      $message = "La durée de l'activité doit être supérieure à 0";
      header('location:../activity.php?id=' . $_GET['id'] . '&message=' . $message . '&type=danger');
      exit();
    }
    if ($_POST['priceAttendee'] < 0) {
      $message = "Le prix de l'activité ne peut être négatif";
      header('location:../activity.php?id=' . $_GET['id'] . '&message=' . $message . '&type=danger');
      exit();
    }
    if ($_POST['maxAttendee'] <= 0) {
      $message = 'Le nombre de participants maximum doit être supérieur à 0';
      header('location:../activity.php?id=' . $_GET['id'] . '&message=' . $message . '&type=danger');
      exit();
    }
    if (!isset($_POST['room'])) {
      $message = 'Veuillez sélectionner une salle';
      header('location:../activity.php?id=' . $_GET['id'] . '&message=' . $message . '&type=danger');
      exit();
    }

    $query = $db->prepare('SELECT day FROM SCHEDULE WHERE id_activity = :id_activity');
    $query->execute([
      ':id_activity' => htmlspecialchars($_GET['id']),
    ]);
    $days = $query->fetchAll(PDO::FETCH_ASSOC);

    $providers = [];
    $providersCount = 0;

    foreach ($_POST as $key => $value) {
      if (preg_match('/^provider(\d+)$/', $key, $matches)) {
        $provider_id = $matches[1];
        $providers[] = $provider_id;
        $providersCount++;
      }
    }
    if ($providersCount != 0) {
      foreach ($providers as $provider) {
        $query = $db->prepare('SELECT day FROM AVAILABILITY WHERE id_provider = :id_provider');
        $query->execute([
          ':id_provider' => $provider,
        ]);
        $days = $query->fetchAll(PDO::FETCH_ASSOC);
        $available;
        foreach ($days as $day) {
          if (in_array($day['day'], $days)) {
            $available = true;
          }
        }
      }
    }

    if (!$available) {
      $message = 'Les jours de disponibilité ne correspondent pas à ceux des intervenants';
      header('location:../activity.php?id=' . $_GET['id'] . '&message=' . $message . '&type=danger');
      exit();
    }

    $query = $db->prepare(
      'SELECT firstName, lastName, email, name FROM PROVIDER INNER JOIN OCCUPATION ON PROVIDER.id_occupation = OCCUPATION.id WHERE OCCUPATION.id IN (SELECT id_provider FROM ANIMATE WHERE id_activity = :id)',
    );
    $query->execute([
      ':id' => htmlspecialchars($_GET['id']),
    ]);
    $oldProviders = $query->fetchAll(PDO::FETCH_ASSOC);

    $query = $db->prepare('SELECT name, address FROM LOCATION WHERE id IN (SELECT id_location FROM ROOM WHERE id=:id)');
    $query->execute([
      ':id' => htmlspecialchars($_POST['room']),
    ]);
    $oldLocation = $query->fetch(PDO::FETCH_ASSOC);

    $query = $db->prepare('SELECT name FROM ROOM WHERE id IN (SELECT id_room FROM ACTIVITY WHERE id=:id)');
    $query->execute([
      ':id' => htmlspecialchars($_GET['id']),
    ]);
    $oldRoom = $query->fetch(PDO::FETCH_ASSOC);

    $delete = $db->prepare('DELETE FROM ANIMATE WHERE id_activity = :id_activity');
    $delete->execute([
      ':id_activity' => htmlspecialchars($_GET['id']),
    ]);
    $delete = $db->prepare('DELETE FROM MATERIAL_ACTIVITY WHERE id_activity = :id_activity');
    $delete->execute([
      ':id_activity' => htmlspecialchars($_GET['id']),
    ]);

    $materials = [];
    $materialCount = 0;

    foreach ($_POST as $key => $value) {
      if (preg_match('/^material(\d+)$/', $key, $matches)) {
        $material_id = $matches[1];
        $materials[] = $material_id;
        $materialCount++;
      }
    }

    $quantity = [];

    foreach ($_POST as $key => $value) {
      if (preg_match('/^quantity(\d+)$/', $key, $matches)) {
        $quantity_id = $matches[1];
        $quantity[] = $quantity_id;
      }
    }

    if ($materialCount != 0) {
      $i = 0;
      do {
        if ($quantity[$i] == $materials[$i]) {
          $insert = $db->prepare(
            'INSERT INTO MATERIAL_ACTIVITY (id_activity, id_material, quantity) VALUES (:id_activity, :id_material, :quantity)',
          );
          $result3 = $insert->execute([
            'id_activity' => htmlspecialchars($_GET['id']),
            'id_material' => $materials[$i],
            'quantity' => $_POST['quantity' . $quantity[$i]],
          ]);
        }
        $i++;
      } while ($i < $materialCount);
    }

    $request = $db->prepare(
      'UPDATE ACTIVITY SET duration = :duration, priceAttendee = :priceAttendee, maxAttendee = :maxAttendee, id_room = :id_room WHERE id = :id',
    );
    $result = $request->execute([
      ':duration' => $_POST['duration'],
      ':priceAttendee' => $_POST['priceAttendee'],
      ':maxAttendee' => $_POST['maxAttendee'],
      ':id' => htmlspecialchars($_GET['id']),
      ':id_room' => $_POST['room'],
    ]);

    $query = $db->prepare(
      'SELECT firstName, lastName, email, name FROM PROVIDER INNER JOIN OCCUPATION ON PROVIDER.id_occupation = OCCUPATION.id WHERE OCCUPATION.id IN (SELECT id_provider FROM ANIMATE WHERE id_activity = :id)',
    );
    $query->execute([
      ':id' => htmlspecialchars($_GET['id']),
    ]);
    $newProviders = $query->fetchAll(PDO::FETCH_ASSOC);

    $query = $db->prepare('SELECT name, address FROM LOCATION WHERE id IN (SELECT id_location FROM ROOM WHERE id=:id)');
    $query->execute([
      ':id' => htmlspecialchars($_POST['room']),
    ]);
    $newLocation = $query->fetch(PDO::FETCH_ASSOC);

    $query = $db->prepare('SELECT name FROM ROOM WHERE id IN (SELECT id_room FROM ACTIVITY WHERE id=:id)');
    $query->execute([
      ':id' => htmlspecialchars($_GET['id']),
    ]);
    $newRoom = $query->fetch(PDO::FETCH_ASSOC);

    if ($oldLocation === $newLocation && $oldRoom === $newRoom) {
      $modifiedLocation = '';
    } else {
      $modifiedLocation =
        "L'adresse du lieu de l'activité a été modifiée.<br>Voici la nouvelle adresse: <br>" .
        $newLocation['name'] .
        ' ' .
        $newLocation['address'] .
        ' dans la salle ' .
        $newRoom['name'] .
        '<br>';
    }
    if ($oldProviders === $newProviders) {
      $modifiedProviders = '';
    } else {
      if ($newProviders == null) {
        $modifiedProviders = 'Les prestataires ont été désaffecté de cette activité.<br>';
      } else {
        $modifiedProviders = 'Voici la nouvelle liste des prestataires: <br>';
        foreach ($newProviders as $provider) {
          $modifiedProviders .=
            '- ' . $provider['firstName'] . ' ' . $provider['lastName'] . ', ' . $provider['name'] . '<br>';
        }
      }
    }
    if ($result && $result2 && $result3) {
      $query = $db->prepare(
        'SELECT email FROM COMPANY WHERE siret IN (SELECT siret FROM RESERVATION WHERE id_activity = :id)',
      );
      $query->execute([
        ':id' => htmlspecialchars($_GET['id']),
      ]);
      $companyEmails = $query->fetchAll(PDO::FETCH_ASSOC);

      foreach ($companyEmails as $companyEmail) {
        $email = $companyEmail['email'];
        $subject = 'Information modification des activités';
        $msgHTML =
          'Bonjour,<br><br>
        Des modifications ont été apportées aux activités auxquel vous êtes inscrit.<br>
        Voici les détails des modifications:<br>' .
          $modifiedLocation .
          '<br>' .
          $modifiedProviders .
          '<br>
          Cordialement,<br>
          L\'équipe de TeamEase';
        include '../includes/mailer.php';
      }
      $message = 'Les détails ont bien été modifiés';
      header('location:../activity.php?id=' . $_GET['id'] . '&message=' . $message . '&type=success');
      exit();
    } else {
      $message = 'Une erreur est survenue';
      header('location:../activity.php?id=' . $_GET['id'] . '&message=' . $message . '&type=danger');
      exit();
    }
  } elseif ($_GET['update'] == 'title') {
    if (isset($_POST['status'])) {
      $status = 1;
    } else {
      $status = 0;
    }
    $request = $db->prepare('UPDATE ACTIVITY SET name = :name, status = :status WHERE id = :id');
    $result = $request->execute([
      ':name' => $_POST['name'],
      ':status' => $status,
      ':id' => htmlspecialchars($_GET['id']),
    ]);
    if ($result) {
      $message = 'L\'activité a bien été modifié';
      header('location:../activity.php?id=' . $_GET['id'] . '&message=' . $message . '&type=success');
      exit();
    } else {
      $message = 'Une erreur est survenue';
      header('location:../activity.php?id=' . $_GET['id'] . '&message=' . $message . '&type=danger');
      exit();
    }
  }
}

//Ajout d'une activité------------------------------------------------------------------------

$getId = $db->prepare('SELECT id FROM ACTIVITY ORDER BY id DESC LIMIT 1');
$getId->execute();

// je set result3 et 4 sinon je peux pas faire la verif a la fin
$result3 = true;
$result4 = true;

if (!isset($_POST['name']) || empty($_POST['name'])) {
  $message = "Le nom de l'activité n'a pas été renseigné";
  header('location:../addActivityPage.php?message=' . $message . '&type=danger');
  exit();
} else {
  setcookie('nameActivity', $_POST['name'], time() + 3600, '/');
}

if (!isset($_POST['description']) || empty($_POST['description'])) {
  $message = "La description de l'activité n'a pas été renseignée";
  header('location:../addActivityPage.php?message=' . $message . '&type=danger');
  exit();
} else {
  setcookie('descriptionActivity', $_POST['description'], time() + 3600, '/');
}

if ($_POST['duration'] <= 0) {
  $message = "La durée de l'activité doit être supérieure à 0";
  header('location:../addActivityPage.php?message=' . $message . '&type=danger');
  exit();
} else {
  setcookie('durationActivity', $_POST['duration'], time() + 3600, '/');
}

if ($_POST['price'] < 0) {
  $message = "Le prix de l'activité ne peut être négatif";
  header('location:../addActivityPage.php?message=' . $message . '&type=danger');
  exit();
} else {
  setcookie('priceActivity', $_POST['price'], time() + 3600, '/');
}

if ($_POST['maxAttendee'] <= 0) {
  $message = 'Le nombre de participants maximum doit être supérieur à 0';
  header('location:../addActivityPage.php?message=' . $message . '&type=danger');
  exit();
} else {
  setcookie('maxAttendeeActivity', $_POST['maxAttendee'], time() + 3600, '/');
}

if (!isset($_POST['category'])) {
  $message = "Aucune catégorie n'a été selectionnée";
  header('location:../addActivityPage.php?message=' . $message . '&type=danger');
  exit();
}

foreach ($_POST['category'] as $category) {
  if ($category == 'En ligne') {
    $online = true;
  }
  if ($category == 'En personne') {
    $inPerson = true;
  }
}

if ($online && $inPerson) {
  $message = 'Une activité ne peut pas être à la fois en ligne et en personne';
  header('location:../addActivityPage.php?message=' . $message . '&type=danger');
  exit();
}

if (!isset($_POST['room'])) {
  $message = 'Aucune salle n\'a été selectionnée';
  header('location:../addActivityPage.php?message=' . $message . '&type=danger');
  exit();
}

if (!isset($_POST['day'])) {
  $message = 'Aucun jour de disponibilité n\'a été selectionné';
  header('location:../addActivityPage.php?message=' . $message . '&type=danger');
  exit();
}

$providers = [];
$providersCount = 0;

foreach ($_POST as $key => $value) {
  if (preg_match('/^provider(\d+)$/', $key, $matches)) {
    $provider_id = $matches[1];
    $providers[] = $provider_id;
    $providersCount++;
  }
}

if ($providersCount != 0) {
  foreach ($providers as $provider) {
    $query = $db->prepare('SELECT day FROM AVAILABILITY WHERE id_provider = :id_provider');
    $query->execute([
      ':id_provider' => $provider,
    ]);
    $days = $query->fetchAll(PDO::FETCH_ASSOC);
    $available;
    foreach ($days as $day) {
      if (in_array($day['day'], $_POST['day'])) {
        $available = true;
      }
    }
  }
}

if (!$available) {
  $message = 'Les jours de disponibilité ne correspondent pas à ceux des intervenants';
  header('location:../addActivityPage.php?message=' . $message . '&type=danger');
  exit();
}

$request = $db->prepare(
  'INSERT INTO ACTIVITY (name, description, duration, priceAttendee, maxAttendee, status, id_room) 
  VALUES (:name, :description, :duration, :priceAttendee, :maxAttendee, :status, :id_room)',
);

$result = $request->execute([
  'name' => $_POST['name'],
  'description' => $_POST['description'],
  'duration' => $_POST['duration'],
  'priceAttendee' => $_POST['price'],
  'maxAttendee' => $_POST['maxAttendee'],
  'status' => 1,
  'id_room' => $_POST['room'],
]);

$getId = $db->prepare('SELECT id FROM ACTIVITY ORDER BY id DESC LIMIT 1');
$getId->execute();
$id = $getId->fetch();

$uploadsPath = '../images/activities';
if (!file_exists($uploadsPath)) {
  mkdir($uploadsPath, 0777, true);
}

$mainImage = $_FILES['mainImage']['name'];
$array = explode('.', $mainImage);
$ext = end($array);
$mainImage = $id[0] . $_POST['name'] . '0' . '.' . $ext;
$destination = $uploadsPath . '/' . $mainImage;
move_uploaded_file($_FILES['mainImage']['tmp_name'], $destination);

$secondImage = $_FILES['secondImage']['name'];
$array = explode('.', $secondImage);
$ext = end($array);
$secondImage = $id[0] . $_POST['name'] . '1' . '.' . $ext;
$destination = $uploadsPath . '/' . $secondImage;
move_uploaded_file($_FILES['secondImage']['tmp_name'], $destination);

$thirdImage = $_FILES['thirdImage']['name'];
$array = explode('.', $thirdImage);
$ext = end($array);
$thirdImage = $id[0] . $_POST['name'] . '2' . '.' . $ext;
$destination = $uploadsPath . '/' . $thirdImage;
move_uploaded_file($_FILES['thirdImage']['tmp_name'], $destination);

$fourthImage = $_FILES['fourthImage']['name'];
$array = explode('.', $fourthImage);
$ext = end($array);
$fourthImage = $id[0] . $_POST['name'] . '3' . '.' . $ext;
$destination = $uploadsPath . '/' . $fourthImage;
move_uploaded_file($_FILES['fourthImage']['tmp_name'], $destination);

include '../includes/resolution.php';

$belong = $_POST['category'];
$i = 0;
do {
  $getCategory = $db->prepare('SELECT id FROM CATEGORY WHERE name=:name');
  $getCategory->execute([
    'name' => $belong[$i],
  ]);

  $categoryId = $getCategory->fetch();

  $insert = $db->prepare('INSERT INTO BELONG (id_activity, id_category) VALUES (:id_activity, :id_category)');
  $result2 = $insert->execute([
    'id_activity' => $id[0],
    'id_category' => $categoryId[0],
  ]);
  $i++;
} while ($i < count($belong));

if ($providersCount != 0) {
  $i = 0;
  do {
    $insert = $db->prepare('INSERT INTO ANIMATE (id_activity, id_provider) VALUES (:id_activity, :id_provider)');
    $result3 = $insert->execute([
      'id_activity' => $id[0],
      'id_provider' => $providers[$i],
    ]);
    $i++;
  } while ($i < $providersCount);
}

$materials = [];
$materialCount = 0;

foreach ($_POST as $key => $value) {
  if (preg_match('/^material(\d+)$/', $key, $matches)) {
    $material_id = $matches[1];
    $materials[] = $material_id;
    $materialCount++;
  }
}

$quantity = [];

foreach ($_POST as $key => $value) {
  if (preg_match('/^quantity(\d+)$/', $key, $matches)) {
    $quantity_id = $matches[1];
    $quantity[] = $quantity_id;
  }
}

if ($materialCount != 0) {
  $i = 0;
  do {
    if ($quantity[$i] == $materials[$i]) {
      $insert = $db->prepare(
        'INSERT INTO MATERIAL_ACTIVITY (id_activity, id_material, quantity) VALUES (:id_activity, :id_material, :quantity)',
      );
      $result4 = $insert->execute([
        'id_activity' => $id[0],
        'id_material' => $materials[$i],
        'quantity' => $_POST['quantity' . $quantity[$i]],
      ]);
    }
    $i++;
  } while ($i < $materialCount);
}

foreach ($_POST['day'] as $day) {
  $insert = $db->prepare(
    'INSERT INTO SCHEDULE (day, startHour, endHour, id_activity) VALUES (:day, :startHour, :endHour, :id_activity)',
  );
  $result5 = $insert->execute([
    'day' => strtolower($day),
    'startHour' => $_POST['start' . ucwords($day)],
    'endHour' => $_POST['end' . ucwords($day)],
    'id_activity' => $id[0],
  ]);
}

if ($result && $result2 && $result3 && $result4) {
  $message = 'L\'activité a bien été ajoutée';
  header('location:../addActivityPage.php?message=' . $message . '&type=success');
} else {
  $id = $id[0];
  $request = $db->prepare('SELECT name FROM ACTIVITY WHERE id = :id');
  $request->execute([
    ':id' => $id,
  ]);
  $activity = $request->fetch(PDO::FETCH_ASSOC);
  $path = '../images/activities/';
  $idImage = $id;
  include '../includes/image.php';
  if ($image0 != '../images/activities/placeholder.jpg') {
    unlink($image0);
  }
  if ($image1 != '../images/activities/placeholder.jpg') {
    unlink($image1);
  }
  if ($image2 != '../images/activities/placeholder.jpg') {
    unlink($image2);
  }
  if ($image3 != '../images/activities/placeholder.jpg') {
    unlink($image3);
  }

  $request = $db->prepare('DELETE FROM BELONG WHERE id_activity = :id');
  $request->execute([
    'id' => $id[0],
  ]);
  $request = $db->prepare('DELETE FROM ANIMATE WHERE id_activity = :id');
  $request->execute([
    'id' => $id[0],
  ]);
  $request = $db->prepare('DELETE FROM MATERIAL_ACTIVITY WHERE id_activity = :id');
  $request->execute([
    'id' => $id[0],
  ]);
  $request = $db->prepare('DELETE FROM ACTIVITY WHERE id = :id');
  $request->execute([
    'id' => $id[0],
  ]);
  $message = 'Une erreur est survenue';
  header('location:../addActivityPage.php?message=' . $message . '&type=danger');
}

?>
