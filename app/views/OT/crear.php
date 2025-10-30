<h1 class="h4 mb-3">Nueva orden de trabajo</h1>

<div class="card">
  <div class="card-body">
    <form method="post" action="/admin/?r=ot/crear">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Vehículo</label>
          <select class="form-select" name="vehicle_id" required>
            <option value="">Seleccionar...</option>
            <?php foreach ($vehicles as $v): ?>
              <option value="<?= (int)$v['id'] ?>"><?= htmlspecialchars($v['label']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Estado</label>
          <select class="form-select" name="status" required>
            <option value="revision" selected>Revisión</option>
            <option value="presupuestado">Presupuestado</option>
            <option value="reparacion">Reparación</option>
            <option value="entregado">Entregado</option>
          </select>
        </div>
      </div>

      <hr class="my-4">

      <h2 class="h6">Ítem inicial (opcional)</h2>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Descripción</label>
          <input class="form-control" name="item_desc" placeholder="Ej: Cambio de aceite">
        </div>
        <div class="col-md-2">
          <label class="form-label">Cant.</label>
          <input class="form-control" name="item_qty" type="number" step="0.01" value="1">
        </div>
        <div class="col-md-3">
          <label class="form-label">Precio unit.</label>
          <input class="form-control" name="item_price" type="number" step="0.01" value="0">
        </div>
      </div>

      <div class="mt-4 d-flex gap-2">
        <button class="btn btn-primary">Crear OT</button>
        <a class="btn btn-outline-secondary" href="/admin/?r=ot/listar">Cancelar</a>
      </div>
    </form>
  </div>
</div>
