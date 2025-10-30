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
<h1 class="h4 mb-3">Cliente: <?= esc($customer['full_name']) ?></h1>

<div class="mb-3">
  <a class="btn btn-outline-secondary" href="/admin/?r=clientes/listar">Volver</a>
  <a class="btn btn-primary" href="/admin/?r=clientes/editar&id=<?= (int)$customer['id'] ?>">Editar cliente</a>
</div>

<div class="row g-3">
  <div class="col-md-5">
    <div class="card">
      <div class="card-body">
        <h2 class="h6 mb-3">Datos del cliente</h2>
        <div><strong>Nombre:</strong> <?= esc($customer['full_name']) ?></div>
        <div><strong>Email:</strong> <?= esc($customer['email']) ?: '-' ?></div>
        <div><strong>Teléfono:</strong> <?= esc($customer['phone']) ?: '-' ?></div>
        <div><strong>DNI/CUIT:</strong> <?= esc($customer['doc']) ?: '-' ?></div>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h2 class="h6 mb-0">Vehículos</h2>
          <a class="btn btn-sm btn-outline-primary" href="/admin/?r=vehiculos/crear">Agregar vehículo</a>
        </div>

        <?php if (empty($vehicles)): ?>
          <div class="alert alert-light mb-0">Sin vehículos asignados.</div>
        <?php else: ?>
          <div class="accordion" id="vehAccordion">
            <?php foreach ($vehicles as $v): $vid=(int)$v['id']; $accId='veh'.$vid; ?>
              <div class="accordion-item">
                <h2 class="accordion-header" id="h<?= $accId ?>">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c<?= $accId ?>">
                    <?= esc($v['brand'].' '.$v['model']) ?> — <strong class="ms-1"><?= esc($v['plate']) ?></strong>
                  </button>
                </h2>
                <div id="c<?= $accId ?>" class="accordion-collapse collapse" data-bs-parent="#vehAccordion">
                  <div class="accordion-body">
                    <div class="mb-2 small text-muted">
                      Año: <?= esc($v['year'] ?: '-') ?> · VIN: <?= esc($v['vin'] ?: '-') ?>
                    </div>

                    <div class="mb-2">
                      <a class="btn btn-sm btn-outline-secondary" href="/admin/?r=vehiculos/ver&id=<?= $vid ?>">Ver vehículo</a>
                      <a class="btn btn-sm btn-outline-primary" href="/admin/?r=vehiculos/editar&id=<?= $vid ?>">Editar vehículo</a>
                      <a class="btn btn-sm btn-success" href="/admin/?r=ot/crear">Nueva OT</a>
                    </div>

                    <?php $orders = $ordersByVehicle[$vid] ?? []; ?>
                    <?php if (empty($orders)): ?>
                      <div class="alert alert-light mb-0">Este vehículo no tiene órdenes.</div>
                    <?php else: ?>
                      <div class="table-responsive">
                        <table class="table table-sm align-middle">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Estado</th>
                              <th>Abierta</th>
                              <th>Inicio</th>
                              <th>Cierre</th>
                              <th class="text-end">Total</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach($orders as $o): ?>
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
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
