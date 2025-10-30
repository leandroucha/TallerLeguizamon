<?php
require __DIR__ . '/db.php';
require __DIR__ . '/auth.php';
auth_start();
require __DIR__ . '/routes.php';

// /admin → login por defecto
if (!isset($_GET['r']) && str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/admin')) {
  header('Location: /admin/?r=login');
  exit;
}

$route = $_GET['r'] ?? 'home';

if (route_is_protected($route)) {
  require_login_or_redirect();
}

$handled = dispatch($route);
if (!$handled) {
  http_response_code(404);
  echo "404 - Recurso no encontrado";
}
