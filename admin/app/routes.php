<?php
function view($view, $vars = []) {
  $vars['view'] = $view;   // nombre del archivo a renderizar
  extract($vars);          // hace disponibles las variables (ej. $rows)
  include __DIR__ . '/views/layout.php';
  return true;
}

function dispatch($route) {
  switch ($route) {
    // Página principal
    case 'home':
      return view('home');

    // Listar clientes
    case 'clientes/listar':
      $pdo = db();
      $rows = $pdo->query("SELECT * FROM customers ORDER BY id DESC")->fetchAll();
      return view('clientes/listar', ['rows' => $rows]);

    // Crear cliente
    case 'clientes/crear':
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pdo = db();
        $stmt = $pdo->prepare("INSERT INTO customers(full_name, phone, email, doc) VALUES (?,?,?,?)");
        $stmt->execute([
          $_POST['full_name'],
          $_POST['phone'] ?? null,
          $_POST['email'] ?? null,
          $_POST['doc'] ?? null
        ]);
        header("Location: /?r=clientes/listar");
        exit;
      }
      return view('clientes/crear');

    default:
      return false;
  }
}
