<h1 class="h4 mb-3">Nuevo vehículo</h1>
<div class="card">
  <div class="card-body">
    <form method="post" action="/admin/?r=vehiculos/crear">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Cliente</label>
          <select class="form-select" name="customer_id" required>
            <option value="">Seleccionar...</option>
            <?php foreach ($customers as $c): ?>
              <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['full_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Marca</label>
          <input class="form-control" name="brand">
        </div>
        <div class="col-md-3">
          <label class="form-label">Modelo</label>
          <input class="form-control" name="model">
        </div>
        <div class="col-md-2">
          <label class="form-label">Año</label>
          <input class="form-control" name="year" type="number">
        </div>
        <div class="col-md-3">
          <label class="form-label">Patente</label>
          <input class="form-control" name="plate">
        </div>
        <div class="col-md-4">
          <label class="form-label">VIN</label>
          <input class="form-control" name="vin">
        </div>
      </div>
      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-primary">Guardar</button>
        <a class="btn btn-outline-secondary" href="/admin/?r=vehiculos/listar">Cancelar</a>
      </div>
    </form>
  </div>
</div>
