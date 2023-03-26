<?php

function getParametersForRoute(string $route): array
{
  $path = $_SERVER['REQUEST_URI'];
  $pathSeparatorPattern = '#/#';

  $path = str_replace('/api/api.php', '', $path);

  $routeParts = preg_split($pathSeparatorPattern, trim($route, '/'));
  $pathParts = preg_split($pathSeparatorPattern, trim($path, '/'));

  if (count($routeParts) !== count($pathParts)) {
    return false;
  }

  $parameters = [];

  foreach ($routeParts as $routePartIndex => $routePart) {
    $pathPart = $pathParts[$routePartIndex];

    if (str_starts_with($routePart, ':')) {
      $parameterName = substr($routePart, 1);
      $parameters[$parameterName] = $pathPart;
      continue;
    }

    if ($routePart !== $pathPart) {
      return [];
    }
  }

  return $parameters;
}
