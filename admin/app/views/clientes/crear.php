<div class="card shadow-sm">
  <div class="card-body">
    <h2 class="h5">Nuevo cliente</h2>
    <form method="post">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nombre completo *</label>
          <input name="full_name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Documento</label>
          <input name="doc" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Teléfono</label>
          <input name="phone" class="form-control">
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control">
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-primary">Guardar</button>
        <a href="/?r=clientes/listar" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>
