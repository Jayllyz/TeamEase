<?php

function getAllActivities(): array
{
  include '/home/php/src/includes/db.php';

  $getActivities = $db->query('SELECT * FROM ACTIVITY;');
  return $getActivities->fetchAll(PDO::FETCH_ASSOC);
}

function getActivityById(int $id): array
{
  include '/home/php/src/includes/db.php';

  $getActivity = $db->prepare('SELECT * FROM ACTIVITY WHERE ID = :id;');
  $getActivity->bindParam(':id', $id);
  $getActivity->execute();
  return $getActivity->fetch(PDO::FETCH_ASSOC);
}

function getActivityByName(string $name): array
{
  include '/home/php/src/includes/db.php';

  $getActivity = $db->prepare('SELECT * FROM ACTIVITY WHERE NAME = :name;');
  $getActivity->bindParam(':name', $name);
  $getActivity->execute();
  return $getActivity->fetch(PDO::FETCH_ASSOC);
}

function getAllActivitiesPrice(int $price, bool $inferior): array
{
  include '/home/php/src/includes/db.php';

  if ($inferior) {
    $getActivities = $db->prepare('SELECT * FROM ACTIVITY WHERE priceAttendee <= :price;');
  } else {
    $getActivities = $db->prepare('SELECT * FROM ACTIVITY WHERE priceAttendee >= :price;');
  }
  $getActivities->bindParam(':price', $price);
  $getActivities->execute();
  return $getActivities->fetchAll(PDO::FETCH_ASSOC);
}

function getAllActivitiesDurationLowerThan(int $duration): array
{
  include '/home/php/src/includes/db.php';

  $getActivities = $db->prepare('SELECT * FROM ACTIVITY WHERE DURATION <= :duration;');
  $getActivities->bindParam(':duration', $duration);
  $getActivities->execute();
  return $getActivities->fetchAll(PDO::FETCH_ASSOC);
}

function getAllActivitiesDurationHigherThan(int $duration): array
{
  include '/home/php/src/includes/db.php';

  $getActivities = $db->prepare('SELECT * FROM ACTIVITY WHERE DURATION => :duration;');
  $getActivities->bindParam(':duration', $duration);
  $getActivities->execute();
  return $getActivities->fetchAll(PDO::FETCH_ASSOC);
}

function getAllActivitiesByCategory(string $category): array
{
  include '/home/php/src/includes/db.php';

  $getActivities = $db->prepare(
    'select a.name from ACTIVITY a inner join BELONG b  on a.id = b.id_activity  inner join CATEGORY c on b.id_category = c.id  where c.name = "en ligne";',
  );
  $getActivities->bindParam(':category', $category);
  $getActivities->execute();
  return $getActivities->fetch(PDO::FETCH_ASSOC);
}

function getAllActivitiesByDay(string $day): array
{
  include '/home/php/src/includes/db.php';

  $getActivities = $db->prepare(
    'SELECT name FROM ACTIVITY WHERE id IN (SELECT id_activity FROM SCHEDULE WHERE day = :day)',
  );
  $getActivities->bindParam(':day', $day);
  $getActivities->execute();
  return $getActivities->fetchAll(PDO::FETCH_ASSOC);
}

function getAllActivitiesByLocation(string $location): array
{
  include '/home/php/src/includes/db.php';

  $getActivities = $db->prepare(
    'SELECT name, name FROM ACTIVITY WHERE id_room IN (SELECT id FROM ROOM WHERE id_location IN (SELECT id FROM LOCATION WHERE name = :location))',
  );
  $getActivities->bindParam(':location', $location);
  $getActivities->execute();
  return $getActivities->fetchAll(PDO::FETCH_ASSOC);
}

function getAllActivitiesByMaxAttendee(int $maxAttendee, bool $inferior): array
{
  include '/home/php/src/includes/db.php';

  if ($inferior) {
    $getActivities = $db->prepare('SELECT name FROM ACTIVITY WHERE maxAttendee <= :maxAttendee');
  } else {
    $getActivities = $db->prepare('SELECT name FROM ACTIVITY WHERE maxAttendee >= :maxAttendee');
  }
  $getActivities->bindParam(':maxAttendee', $maxAttendee);
  $getActivities->execute();
  return $getActivities->fetchAll(PDO::FETCH_ASSOC);
}
