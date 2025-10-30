<h1 class="h4 mb-3">Editar vehículo</h1>
<div class="card">
  <div class="card-body">
    <form method="post" action="/admin/?r=vehiculos/editar&id=<?= (int)$row['id'] ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Cliente</label>
          <select class="form-select" name="customer_id" required>
            <?php foreach ($customers as $c): ?>
              <option value="<?= (int)$c['id'] ?>" <?= ((int)$c['id']===(int)$row['customer_id'])?'selected':'' ?>>
                <?= htmlspecialchars($c['full_name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Marca</label>
          <input class="form-control" name="brand" value="<?= htmlspecialchars($row['brand']) ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Modelo</label>
          <input class="form-control" name="model" value="<?= htmlspecialchars($row['model']) ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label">Año</label>
          <input class="form-control" type="number" name="year" value="<?= htmlspecialchars($row['year']) ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Patente</label>
          <input class="form-control" name="plate" value="<?= htmlspecialchars($row['plate']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">VIN</label>
          <input class="form-control" name="vin" value="<?= htmlspecialchars($row['vin']) ?>">
        </div>
      </div>
      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-primary">Guardar</button>
        <a class="btn btn-outline-secondary" href="/admin/?r=vehiculos/listar">Volver</a>
      </div>
    </form>
  </div>
</div>
