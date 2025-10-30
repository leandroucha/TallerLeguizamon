<h1 class="h4 mb-3">Nuevo cliente</h1>
<div class="card">
  <div class="card-body">
    <form method="post" action="/admin/?r=clientes/crear">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nombre</label>
          <input class="form-control" name="full_name" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Teléfono</label>
          <input class="form-control" name="phone">
        </div>
        <div class="col-md-3">
          <label class="form-label">Documento</label>
          <input class="form-control" name="doc">
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input class="form-control" name="email" type="email">
        </div>
      </div>
      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-primary">Guardar</button>
        <a class="btn btn-outline-secondary" href="/admin/?r=clientes/listar">Cancelar</a>
      </div>
    </form>
  </div>
</div>
