<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Taller Mecánico</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg bg-dark navbar-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="/">Taller</a>
    <div class="navbar-nav">
      <a class="nav-link" href="/?r=clientes/listar">Clientes</a>
    </div>
  </div>
</nav>
<div class="container">
  <?php include __DIR__ . "/$view.php"; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
