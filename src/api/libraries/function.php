<?php

function banWordFilter($string)
{
  $banWords = ['connard', 'pute', 'salope'];
  for ($i = 0; $i < count($banWords); $i++) {
    if (strpos($string, $banWords[$i])) {
      echo 'Sananes va te ban';
    }
  }
} ?>
