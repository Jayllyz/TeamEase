<?php

function jsonResponse($statusCode, $headers, $body)
{
  http_response_code($statusCode);

  foreach ($headers as $headerName => $headerValue) {
    header("$headerName: $headerValue");
  }

  header('Content-Type: application/json');

  return json_encode($body);
}
