<?php
require __DIR__ . '/db.php';
require __DIR__ . '/routes.php';

// Ruta pedida (r=controlador/accion). Por defecto: home
$route = $_GET['r'] ?? 'home';

$handled = dispatch($route);
if (!$handled) {
  http_response_code(404);
  echo "404 - Recurso no encontrado";
}
