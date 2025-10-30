<?php
function esc($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function moneyAR($n){ return '$ '.number_format((float)$n, 2, ',', '.'); }
function statusBadgeHtml($status) {
  $map = [
    'revision'      => 'bg-secondary',
    'presupuestado' => 'bg-warning text-dark',
    'reparacion'    => 'bg-primary',
    'entregado'     => 'bg-success',
  ];
  $label = [
    'revision'      => 'Revisión',
    'presupuestado' => 'Presupuestado',
    'reparacion'    => 'Reparación',
    'entregado'     => 'Entregado',
  ][$status] ?? ucfirst($status);
  $cls = $map[$status] ?? 'bg-light text-dark';
  return '<span class="badge '.$cls.' fw-bold">'.$label.'</span>';
}
?>
<h1 class="h4 mb-3">
  Vehículo: <?= esc($vehicle['brand'].' '.$vehicle['model']) ?>
  — <strong><?= esc($vehicle['plate']) ?></strong>
</h1>

<div class="mb-3 d-flex gap-2">
  <a class="btn btn-outline-secondary" href="/admin/?r=vehiculos/listar">Volver</a>
  <a class="btn btn-primary" href="/admin/?r=vehiculos/editar&id=<?= (int)$vehicle['id'] ?>">Editar vehículo</a>
  <a class="btn btn-success" href="/admin/?r=ot/crear">Nueva OT</a>
</div>

<div class="row g-3">
  <div class="col-md-5">
    <div class="card">
      <div class="card-body">
        <h2 class="h6 mb-3">Datos del vehículo</h2>
        <div><strong>Cliente:</strong>
          <a href="/admin/?r=clientes/ver&id=<?= (int)$vehicle['customer_id'] ?>"><?= esc($vehicle['customer_name']) ?></a>
        </div>
        <div><strong>Marca/Modelo:</strong> <?= esc($vehicle['brand'].' '.$vehicle['model']) ?></div>
        <div><strong>Año:</strong> <?= esc($vehicle['year'] ?: '-') ?></div>
        <div><strong>Patente:</strong> <?= esc($vehicle['plate']) ?></div>
        <div><strong>VIN:</strong> <?= esc($vehicle['vin'] ?: '-') ?></div>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h2 class="h6 mb-0">Órdenes de trabajo</h2>
          <div class="w-50">
            <input class="form-control table-filter" data-table="#tblOTVeh" placeholder="Buscar por estado/fecha/total...">
          </div>
        </div>

        <?php if (empty($orders)): ?>
          <div class="alert alert-light mb-0">Este vehículo no tiene órdenes.</div>
        <?php else: ?>
          <div class="table-responsive">
            <table id="tblOTVeh" class="table table-sm align-middle" data-sortable>
              <thead>
                <tr>
                  <th data-sort data-type="num" style="width:90px;">#</th>
                  <th data-sort>Estado</th>
                  <th data-sort data-type="date">Abierta</th>
                  <th data-sort data-type="date">Inicio</th>
                  <th data-sort data-type="date">Cierre</th>
                  <th class="text-end" data-sort data-type="num">Total</th>
                  <th style="width:160px;"></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($orders as $o): ?>
                  <tr>
                    <td><?= (int)$o['id'] ?></td>
                    <td><?= statusBadgeHtml($o['status']) ?></td>
                    <td><?= esc($o['opened_at']) ?></td>
                    <td><?= esc($o['started_at'] ?: '-') ?></td>
                    <td><?= esc($o['closed_at'] ?: '-') ?></td>
                    <td class="text-end"><?= moneyAR($o['total']) ?></td>
                    <td class="text-end">
                      <a class="btn btn-sm btn-outline-secondary" href="/admin/?r=ot/ver&id=<?= (int)$o['id'] ?>">Ver</a>
                      <a class="btn btn-sm btn-outline-primary" href="/admin/?r=ot/editar&id=<?= (int)$o['id'] ?>">Editar</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
