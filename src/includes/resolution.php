<?php
for ($i = 0; $i < 4; $i++) {
  if ($i == 0) {
    $image = $_FILES['mainImage']['name'];
    $array = explode('.', $image);
    $ext = end($array);
  } elseif ($i == 1) {
    $image = $_FILES['secondImage']['name'];
    $array = explode('.', $image);
    $ext = end($array);
  } elseif ($i == 2) {
    $image = $_FILES['thirdImage']['name'];
    $array = explode('.', $image);
    $ext = end($array);
  } elseif ($i == 3) {
    $image = $_FILES['fourthImage']['name'];
    $array = explode('.', $image);
    $ext = end($array);
  }

  //source et destination
  $source = '../images/activities/' . $id . $_POST['name'] . $i . '.' . $ext;
  $dest = '../images/activities/' . $id . $_POST['name'] . $i . '.' . $ext;

  //resolution de l'image
  $size = getimagesize($source);
  $width = $size[0];
  $height = $size[1];

  //resolution voulue
  $rwidth = 1920;
  $rheight = 1080;

  //ouverture de l'image
  if ($ext == 'jpg' || $ext == 'jpeg') {
    $original = imagecreatefromjpeg($source);
  }
  if ($ext == 'png') {
    $original = imagecreatefrompng($source);
  }

  //resize
  $resized = imagecreatetruecolor($rwidth, $rheight);
  imagecopyresampled($resized, $original, 0, 0, 0, 0, $rwidth, $rheight, $width, $height);

  //sauvegarder l'image redimensionner
  imagejpeg($resized, $dest);
}

?>
