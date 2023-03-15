<?php session_start(); ?>
<?php include 'includes/db.php'; ?>
<?php if (!isset($_SESSION['rights']) || $_SESSION['rights'] != 2) {
  header('location: index.php');
  exit();
} ?>

<!DOCTYPE html>
<html>
<?php
$linkCss = 'css-js/style.css';
$linkLogo = 'images/logo.png';
$title = 'Ajouter une activité';
include 'includes/head.php';
?>

<body>
  <?php include 'includes/header.php'; ?>
  <main>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">Ajouter une activité</h1>
            </div>
        </div>
        <div class="text-center">
            <?php include 'includes/msg.php'; ?>
        </div>
        <form action="verifications/verifActivity.php" method="post" id="activity-form" enctype="multipart/form-data">
            <?php
            $query = $db->query('SELECT name FROM CATEGORY');
            $fetch = $query->fetchAll(PDO::FETCH_COLUMN);
            $count = count($fetch);
            echo '<label for="genre" class="form-label"><h4>Catégorie de l\'activité</h4></label>
     <div class="row mb-3">';
            for ($i = 0; $i < $count; $i++) {
              if ($i % 6 == 0 and $i != 0) {
                echo '
        </div>
        <div class="row mb-3">
        <input type="checkbox" class="btn btn-check" id="' .
                  $fetch[$i] .
                  '" name="category[]" value="' .
                  $fetch[$i] .
                  '" autocomplete="off">
        <label class="btn btn-outline-success col me-2 mb-3" for="' .
                  $fetch[$i] .
                  '">' .
                  $fetch[$i] .
                  '</label>
        ';
              } else {
                echo '<input type="checkbox" class="btn btn-check" id="' .
                  $fetch[$i] .
                  '" name="category[]" value="' .
                  $fetch[$i] .
                  '" autocomplete="off">
        <label class="btn btn-outline-success col me-2 mb-3" for="' .
                  $fetch[$i] .
                  '">' .
                  $fetch[$i] .
                  '</label>';
              }
            }
            if (($count + 1) % 6 != 0) {
              echo '</div>';
            }
            ?>

            <div class="my-3">
                <label for="name" class="form-label"><h4>Nom de l'activité</h4></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nom de l'activité" required>
            </div>
            <div class="form-group mb-4 my-3">
                <label for="description"><h4>Description de l'activité</h4></label>
                <textarea class="form-control" id="description" name="description" rows="10" required></textarea>
            </div>
            <div class="row">
                <div class="col">
                    <label for="duration"><h4>Durée de l'activité (en heure)</h4></label>
                    <input type="number" name="duration" class="form-control" required>
                </div>
                <div class="col">
                    <label for="price"><h4>Prix par personne (en €)</h4></label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="col">
                    <label for="maxAttendee"><h4>Nombre maximum de participants</h4></label>
                    <input type="number" name="maxAttendee" class="form-control" required>
                </div>
            </div>
            <br>
            <div class="row">
                <label for="mainImage" class="form-label"><h4>Images de présentation</h4></label>
                <div class="col-3 mb-3">
                    <input class="form-control" name="mainImage" type="file" accept="image/jpeg, image/png" required>
                </div>
                <div class="col-3 mb-3">
                    <input class="form-control" name="secondImage" type="file" accept="image/jpeg, image/png">
                </div>
                <div class="col-3 mb-3">
                    <input class="form-control" name="thirdImage" type="file" accept="image/jpeg, image/png">
                </div>
                <div class="col-3 mb-3">
                    <input class="form-control" name="fourthImage" type="file" accept="image/jpeg, image/png">
                </div>
            </div>
            <div class="mb-4 my-3">
                <label for="provider" class="form-label"><h4>Prestataires</h4></label>
                <div id="provider-container"></div>
                <div>
                    <button type="button" class="btn btn-primary" onclick="assignProvider()">Ajouter un prestataire</button>
                </div>
            </div>
            <div class="mb-4 my-3">
                <label for="material" class="form-label"><h4>Matériels</h4></label>
                <div id="material-container"></div>
                <div>
                    <button type="button" class="btn btn-primary" onclick="assignMaterial()">Ajouter du matériel</button>
                </div>
            </div>
            <div class="mb-4 my-3">
                <label for="schedule" class="form-label"><h4>Disponibilité</h4></label>
                <div class="row mb-4 my-3">
                    <input type="checkbox" name="day[]" class="btn-check" id="monday" value="monday" autocomplete="off">
                    <label class="btn btn-outline-success col mx-2" for="monday" onclick="selectedDay('monday')">Lundi</label>
                    <input type="checkbox" name="day[]" class="btn-check" id="tuesday" value="tuesday" autocomplete="off">
                    <label class="btn btn-outline-success col mx-2" for="tuesday" onclick="selectedDay('tuesday')">Mardi</label>
                    <input type="checkbox" name="day[]" class="btn-check" id="wednesday" value="wednesday" autocomplete="off">
                    <label class="btn btn-outline-success col mx-2" for="wednesday" onclick="selectedDay('wednesday')">Mercredi</label>
                    <input type="checkbox" name="day[]" class="btn-check" id="thursday" value="thursday" autocomplete="off">
                    <label class="btn btn-outline-success col mx-2" for="thursday" onclick="selectedDay('thursday')">Jeudi</label>
                    <input type="checkbox" name="day[]" class="btn-check" id="friday" value="friday" autocomplete="off">
                    <label class="btn btn-outline-success col mx-2" for="friday" onclick="selectedDay('friday')">Vendredi</label>
                    <input type="checkbox" name="day[]" class="btn-check" id="saturday" value="saturday" autocomplete="off">
                    <label class="btn btn-outline-success col mx-2" for="saturday" onclick="selectedDay('saturday')">Samedi</label>
                    <input type="checkbox" name="day[]" class="btn-check" id="sunday" value="sunday" autocomplete="off">
                    <label class="btn btn-outline-success col mx-2" for="sunday" onclick="selectedDay('sunday')">Dimanche</label>
                </div>
                <div class="row">
                    <div class="col-4 my-3" style="display:none" id="mondayHour">
                        <label class="my-2"><h5>Horaires de Lundi</h5></label>
                        <div class="row">
                            <div class="col-5">
                                <input type="time" class="form-control" name="startMonday">
                            </div>
                            <div class="col-5">
                                <input type="time" class="form-control" name="endMonday">
                            </div>
                        </div>
                    </div>
                    <div class="col-4 my-3" style="display:none" id="tuesdayHour">
                        <label class="my-2"><h5>Horaires de Mardi</h5></label>
                        <div class="row">
                            <div class="col-5">
                                <input type="time" class="form-control" name="startTuesday">
                            </div>
                            <div class="col-5">
                                <input type="time" class="form-control" name="endTuesday">
                            </div>
                        </div>
                    </div>
                    <div class="col-4 my-3" style="display:none" id="wednesdayHour">
                        <label class="my-2"><h5>Horaires de Mercredi</h5></label>
                        <div class="row">
                            <div class="col-5">
                                <input type="time" class="form-control" name="startWednesday">
                            </div>
                            <div class="col-5">
                                <input type="time" class="form-control" name="endWednesday">
                            </div>
                        </div>
                    </div>
                    <div class="col-4 my-3" style="display:none" id="thursdayHour">
                        <label class="my-2"><h5>Horaires de Jeudi</h5></label>
                        <div class="row">
                            <div class="col-5">
                                <input type="time" class="form-control" name="startThursday">
                            </div>
                            <div class="col-5">
                                <input type="time" class="form-control" name="endThursday">
                            </div>
                        </div>
                    </div>
                    <div class="col-4 my-3" style="display:none" id="fridayHour">
                        <label class="my-2"><h5>Horaires de Vendredi</h5></label>
                        <div class="row">
                            <div class="col-5">
                                <input type="time" class="form-control" name="startFriday">
                            </div>
                            <div class="col-5">
                                <input type="time" class="form-control" name="endFriday">
                            </div>
                        </div>
                    </div>
                    <div class="col-4 my-3" style="display:none" id="saturdayHour">
                        <label class="my-2"><h5>Horaires de Samedi</h5></label>
                        <div class="row">
                            <div class="col-5">
                                <input type="time" class="form-control" name="startSaturday">
                            </div>
                            <div class="col-5">
                                <input type="time" class="form-control" name="endSaturday">
                            </div>
                        </div>
                    </div>
                    <div class="col-4 my-3" style="display:none" id="sundayHour">
                        <label class="my-2"><h5>Horaires de Dimanche</h5></label>
                        <div class="row">
                            <div class="col-5">
                                <input type="time" class="form-control" name="startSunday">
                            </div>
                            <div class="col-5">
                                <input type="time" class="form-control" name="endSunday">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success btn-lg" id="submit">Valider</button>
        </form>
    </div>
  </main>
  <?php include 'includes/footer.php'; ?>
  <script src="css-js/scripts.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>