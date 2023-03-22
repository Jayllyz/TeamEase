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

function getAllActivitiesPriceLowerThan(int $price): array
{
  include '/home/php/src/includes/db.php';

  $getActivities = $db->prepare('SELECT * FROM ACTIVITY WHERE PRICE <= :price;');
  $getActivities->bindParam(':price', $price);
  $getActivities->execute();
  return $getActivities->fetchAll(PDO::FETCH_ASSOC);
}

function getAllActivitiesPriceHigherThan(int $price): array
{
  include '/home/php/src/includes/db.php';

  $getActivities = $db->prepare('SELECT * FROM ACTIVITY WHERE PRICE => :price;');
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

  $getActivities = $db->prepare('select a.* from ACTIVITY a inner join BELONG b  on a.id = b.id_activity  inner join CATEGORY c on b.id_category = c.id  where c.name = :category;');
  $getActivities->bindParam(':category', $category);
  $getActivities->execute();
  return $getActivities->fetchAll(PDO::FETCH_ASSOC);
}