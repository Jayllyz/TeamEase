<?php

function callInsee($siret)
{
  $url = 'https://api.insee.fr/entreprises/sirene/V3/siret/' . $siret;
  $access_token = $_ENV['TOKEN_INSEE'];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $access_token]);
  $output = curl_exec($ch);
  curl_close($ch);

  return $output;
}
