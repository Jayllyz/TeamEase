<?php
if (file_exists('images/activities/' . $id . $activity['name'] . '0.jpg')) {
  $image0 = 'images/activities/' . $id . $activity['name'] . '0.jpg';
} elseif (file_exists('images/activities/' . $id . $activity['name'] . '0.png')) {
  $image0 = 'images/activities/' . $id . $activity['name'] . '0.png';
} else {
  $image0 = 'images/activities/placeholder.jpg';
}

if (file_exists('images/activities/' . $id . $activity['name'] . '1.jpg')) {
  $image1 = 'images/activities/' . $id . $activity['name'] . '1.jpg';
} elseif (file_exists('images/activities/' . $id . $activity['name'] . '1.png')) {
  $image1 = 'images/activities/' . $id . $activity['name'] . '1.png';
} else {
  $image1 = 'images/activities/placeholder.jpg';
}

if (file_exists('images/activities/' . $id . $activity['name'] . '2.jpg')) {
  $image2 = 'images/activities/' . $id . $activity['name'] . '2.jpg';
} elseif (file_exists('images/activities/' . $id . $activity['name'] . '2.png')) {
  $image2 = 'images/activities/' . $id . $activity['name'] . '2.png';
} else {
  $image2 = 'images/activities/placeholder.jpg';
}

if (file_exists('images/activities/' . $id . $activity['name'] . '3.jpg')) {
  $image3 = 'images/activities/' . $id . $activity['name'] . '3.jpg';
} elseif (file_exists('images/activities/' . $id . $activity['name'] . '3.png')) {
  $image3 = 'images/activities/' . $id . $activity['name'] . '3.png';
} else {
  $image3 = 'images/activities/placeholder.jpg';
}
?>
