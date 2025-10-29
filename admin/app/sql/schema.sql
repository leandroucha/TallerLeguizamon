<?php
function db() : PDO {
  static $pdo = null;
  if ($pdo === null) {
    $host = 'db';       // nombre del servicio en docker-compose
    $db   = 'taller';
    $user = 'taller';
    $pass = 'taller';
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);
  }
  return $pdo;
}