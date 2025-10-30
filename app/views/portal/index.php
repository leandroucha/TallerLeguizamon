<h1 class="h5 mb-3">Consultar mis órdenes</h1>
<div class="card shadow-sm">
  <div class="card-body">
    <form class="row g-3" method="get" action="/admin/">
      <input type="hidden" name="r" value="portal/buscar">

      <div class="col-md-4">
        <label class="form-label">Patente</label>
        <input name="plate" class="form-control" placeholder="ABC123 o AC123BC" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">DNI del titular</label>
        <input name="doc" class="form-control" placeholder="Sin puntos (ej: 30123456)" required>
      </div>

      <div class="col-md-4 d-flex align-items-end">
        <button class="btn btn-primary w-100">Ver órdenes</button>
      </div>
    </form>

    <div class="small text-muted mt-2">
      Por seguridad, necesitás la <strong>patente</strong> y el <strong>DNI del titular</strong> del vehículo.
    </div>
  </div>
</div>
