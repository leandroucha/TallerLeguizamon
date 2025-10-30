<div class="row justify-content-center mt-5">
  <div class="col-md-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title text-center mb-4">Ingreso al sistema</h5>
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="/admin/?r=login">
          <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" name="user" class="form-control" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="pass" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-dark w-100">Entrar</button>
        </form>
      </div>
    </div>
  </div>
</div>
