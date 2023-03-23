<?php
if (file_exists($path . $idImage . $activity['name'] . '0.jpg')) {
  $image0 = $path . $idImage . $activity['name'] . '0.jpg';
} elseif (file_exists($path . $idImage . $activity['name'] . '0.png')) {
  $image0 = $path . $idImage . $activity['name'] . '0.png';
} else {
  $image0 = $path . 'placeholder.jpg';
}

if (file_exists($path . $idImage . $activity['name'] . '1.jpg')) {
  $image1 = $path . $idImage . $activity['name'] . '1.jpg';
} elseif (file_exists($path . $idImage . $activity['name'] . '1.png')) {
  $image1 = $path . $idImage . $activity['name'] . '1.png';
} else {
  $image1 = $path . 'placeholder.jpg';
}

if (file_exists($path . $idImage . $activity['name'] . '2.jpg')) {
  $image2 = $path . $idImage . $activity['name'] . '2.jpg';
} elseif (file_exists($path . $idImage . $activity['name'] . '2.png')) {
  $image2 = $path . $idImage . $activity['name'] . '2.png';
} else {
  $image2 = $path . 'placeholder.jpg';
}

if (file_exists($path . $idImage . $activity['name'] . '3.jpg')) {
  $image3 = $path . $idImage . $activity['name'] . '3.jpg';
} elseif (file_exists($path . $idImage . $activity['name'] . '3.png')) {
  $image3 = $path . $idImage . $activity['name'] . '3.png';
} else {
  $image3 = $path . 'placeholder.jpg';
}
?>
