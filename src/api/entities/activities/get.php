<?php

function getAllActivities(): array
{
  include '/home/php/src/includes/db.php';

  $getActivities = $db->query('SELECT * FROM ACTIVITY;');
  return $getActivities->fetchAll(PDO::FETCH_ASSOC);
}
