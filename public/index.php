<?php
// (opcional, útil en deploy inicial)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ruta al código de aplicación
$APP_DIR = dirname(__DIR__) . '/app';

// Carga bootstrap/index de tu app
if (file_exists($APP_DIR . '/index.php')) {
  require $APP_DIR . '/index.php';
  exit;
}

// Fallback por si tu app arma el dispatch en bootstrap propio:
if (file_exists($APP_DIR . '/bootstrap.php')) {
  require $APP_DIR . '/bootstrap.php';
} else {
  http_response_code(500);
  echo "No se encontró app/index.php ni app/bootstrap.php";
  exit;
}

// Si tu bootstrap define un router tipo dispatch($_GET['r'] ?? ...), llamalo.
// La mayoría de los casos no necesitan nada extra acá si app/index.php ya lo hace.
