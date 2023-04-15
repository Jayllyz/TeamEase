<?php

function getAuthorizationHeader()
{
  $headers = getallheaders();

  if (!isset($headers['Authorization'])) {
    return null;
  }

  return $headers['Authorization'];
}
