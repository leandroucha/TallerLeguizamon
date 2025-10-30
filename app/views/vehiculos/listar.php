<h1 class="h4 mb-3">Vehículos</h1>

<div class="row g-2 align-items-center mb-3">
  <div class="col-md-6">
    <input class="form-control table-filter" data-table="#tblVehiculos" placeholder="Buscar por cliente, patente, marca, modelo...">
  </div>
  <div class="col-md-6 text-end">
    <a class="btn btn-primary" href="/admin/?r=vehiculos/crear">Nuevo vehículo</a>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <?php if (empty($rows)): ?>
      <div class="alert alert-light mb-0">No hay vehículos.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table id="tblVehiculos" class="table table-striped align-middle" data-sortable>
          <thead>
            <tr>
              <th data-sort data-type="num" style="width:90px;">ID</th>
              <th data-sort>Cliente</th>
              <th data-sort>Patente</th>
              <th data-sort>Marca</th>
              <th data-sort>Modelo</th>
              <th data-sort data-type="num">Año</th>
              <th style="width:140px;"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td><?= (int)$r['id'] ?></td>
                <td><?= htmlspecialchars($r['customer_name']) ?></td>
                <td><?= htmlspecialchars($r['plate']) ?></td>
                <td><?= htmlspecialchars($r['brand']) ?></td>
                <td><?= htmlspecialchars($r['model']) ?></td>
                <td><?= htmlspecialchars($r['year']) ?></td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-secondary" href="/admin/?r=vehiculos/ver&id=<?= (int)$r['id'] ?>">Ver</a>
                  <a class="btn btn-sm btn-outline-primary" href="/admin/?r=vehiculos/editar&id=<?= (int)$r['id'] ?>">Editar</a>
                </td>

              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
