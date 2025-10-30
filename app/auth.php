<?php
function auth_config() {
  return [
    'USER' => getenv('ADMIN_USER') ?: 'admin',
    'PASS' => getenv('ADMIN_PASS') ?: 'leguizamon123',
  ];
}

function auth_start() {
  if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
  }
}

function auth_check(): bool {
  return session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['admin_logged']);
}

function auth_login(string $user, string $pass): bool {
  $cfg = auth_config();
  if ($user === $cfg['USER'] && $pass === $cfg['PASS']) {
    $_SESSION['admin_logged'] = true;
    $_SESSION['admin_user'] = $user;
    return true;
  }
  return false;
}

function auth_logout(): void {
  if (session_status() === PHP_SESSION_ACTIVE) {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
      $p = session_get_cookie_params();
      setcookie(session_name(), '', time()-42000, $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
    }
    session_destroy();
  }
}

function require_login_or_redirect() {
  if (!auth_check()) {
    header('Location: /admin/?r=login');
    exit;
  }
}

function route_is_protected(string $r): bool {
  return str_starts_with($r, 'clientes/')
      || str_starts_with($r, 'vehiculos/')
      || str_starts_with($r, 'ot/');
}
