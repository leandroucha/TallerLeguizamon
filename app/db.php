<?php
// =====================================================
// db.php — conexión PDO con configuración local opcional
// =====================================================

// Si existe un archivo de configuración local (solo en el servidor),
// lo cargamos para obtener las credenciales reales (fuera de GitHub)
if (file_exists(__DIR__ . '/config.local.php')) {
    require __DIR__ . '/config.local.php';
}

/**
 * Devuelve una conexión PDO lista para usar.
 */
function db(): PDO {
    static $pdo = null;

    if ($pdo !== null) return $pdo;

    $host = getenv('DB_HOST') ?: 'localhost';
    $name = getenv('DB_NAME') ?: 'taller';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';
    $dsn  = "mysql:host={$host};dbname={$name};charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        die("❌ Error al conectar con la base de datos: " . $e->getMessage());
    }

    return $pdo;
}
