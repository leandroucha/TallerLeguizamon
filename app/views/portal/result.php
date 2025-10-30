<?php
function esc($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function moneyAR($n){ return '$ '.number_format((float)$n, 2, ',', '.'); }
$secure = !empty($secure);

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
<h1 class="h5 mb-3">Resultado de la consulta</h1>

<?php if (!$secure): ?>
  <div class="alert alert-danger">Acceso inválido.</div>
  <a class="btn btn-outline-secondary" href="/admin/?r=portal">Volver</a>
  <?php return; ?>
<?php endif; ?>

<?php if (empty($found)): ?>
  <div class="alert alert-warning">
    No encontramos un vehículo que coincida con la patente <strong><?= esc($plate) ?></strong>
    y el DNI <strong><?= esc($doc) ?></strong>.
  </div>
  <a class="btn btn-outline-secondary" href="/admin/?r=portal">Intentar de nuevo</a>
  <?php return; ?>
<?php endif; ?>

<!-- Vehicle card -->
<div class="card shadow-sm mb-3">
  <div class="card-body">
    <h2 class="h6 mb-2">Vehículo</h2>
    <div><strong>Patente:</strong> <?= esc($vehicle['plate']) ?></div>
    <div><strong>Modelo:</strong> <?= esc($vehicle['brand'].' '.$vehicle['model']) ?><?= $vehicle['year'] ? ' ('.esc($vehicle['year']).')' : '' ?></div>
    <div class="text-muted small mt-1">
      Titular: <?= esc($vehicle['full_name']) ?> — DNI <?= esc($vehicle['doc']) ?>
    </div>
  </div>
</div>

<!-- Orders table -->
<div class="card">
  <div class="card-body">
    <h2 class="h6 mb-3">Órdenes de trabajo</h2>
    <?php if (empty($orders)): ?>
      <div class="alert alert-light mb-0">Este vehículo no tiene órdenes registradas.</div>
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
            <?php foreach ($orders as $o): $oid=(int)$o['id']; ?>
              <tr>
                <td class="fw-bold"><?= $oid ?></td>
                <td><?= statusBadgeHtml($o['status']) ?></td>
                <td><?= esc($o['opened_at']) ?></td>
                <td><?= esc($o['started_at'] ?: '-') ?></td>
                <td><?= esc($o['closed_at'] ?: '-') ?></td>
                <td class="text-end fw-semibold"><?= moneyAR($o['total']) ?></td>
                <td class="text-end">
                  <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#otModal<?= $oid ?>">
                    Ver detalle
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
    <a class="btn btn-outline-secondary mt-3" href="/admin/?r=portal">Nueva consulta</a>
  </div>
</div>

<!-- ===== Estilos timeline mejorados ===== -->
<style>
  .tl {
    position: relative;
    margin: 0;
    padding-left: 1.25rem;
    list-style: none;
  }
  .tl::before {
    content: "";
    position: absolute;
    left: 0.5rem;
    top: 0.25rem;
    bottom: 0.25rem;
    width: 3px;
    background: #dee2e6;
    border-radius: 3px;
  }
  .tl-item {
    position: relative;
    padding: .5rem 0 .5rem 1rem;
    color: #6c757d;
    font-weight: 500;
  }
  .tl-item::before {
    content: "";
    position: absolute;
    left: 0.33rem;
    top: .7rem;
    width: .75rem;
    height: .75rem;
    border-radius: 50%;
    background: #ced4da;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
  }
  .tl-item.active { color: #0d6efd; font-weight: 700; }
  .tl-item.active::before { background: #0d6efd; box-shadow: 0 0 0 2px rgba(13,110,253,.35); }
  .tl-date { font-size: .85rem; color: #6c757d; }
</style>

<?php
function timelineStateClasses($status, $step) {
  $map = [
    'revision'      => 1,
    'presupuestado' => 2,
    'reparacion'    => 3,
    'entregado'     => 4,
  ];
  $cur = $map[$status] ?? 1;
  return ($cur === $step) ? 'tl-item active' : 'tl-item';
}

function fmtDate($s){ return $s ? esc($s) : '-'; }
?>

<?php foreach (($orders ?? []) as $o):
  $oid   = (int)$o['id'];
  $items = $itemsByOrder[$oid] ?? [];
?>
<div class="modal fade" id="otModal<?= $oid ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalle OT #<?= $oid ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <!-- Timeline del estado con fechas -->
        <div class="mb-3">
          <div class="d-flex align-items-center justify-content-between">
            <strong>Estado</strong>
            <span class="badge bg-light text-dark border fw-semibold">
              Abierta: <?= fmtDate($o['opened_at']) ?><?php
                if ($o['started_at']) echo ' · Inicio: '.fmtDate($o['started_at']);
                if ($o['closed_at'])  echo ' · Cierre: '.fmtDate($o['closed_at']);
              ?>
            </span>
          </div>
          <ul class="tl mt-2">
            <li class="<?= timelineStateClasses($o['status'], 1) ?>">
              Revisión
              <div class="tl-date">Inicio: <?= fmtDate($o['opened_at']) ?></div>
            </li>
            <li class="<?= timelineStateClasses($o['status'], 2) ?>">
              Presupuestado
              <div class="tl-date"><?= fmtDate($o['started_at']) ?></div>
            </li>
            <li class="<?= timelineStateClasses($o['status'], 3) ?>">
              Reparación
              <div class="tl-date"><?= fmtDate($o['started_at']) ?></div>
            </li>
            <li class="<?= timelineStateClasses($o['status'], 4) ?>">
              Entregado
              <div class="tl-date">Cierre: <?= fmtDate($o['closed_at']) ?></div>
            </li>
          </ul>

        </div>

        <!-- Ítems -->
        <h6 class="mt-3">Trabajos / Repuestos</h6>
        <?php if (empty($items)): ?>
          <div class="alert alert-light">Sin ítems registrados.</div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-sm align-middle">
              <thead>
                <tr>
                  <th>Descripción</th>
                  <th class="text-end">Cant.</th>
                  <th class="text-end">P. Unit.</th>
                  <th class="text-end">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php $sum=0; foreach ($items as $it):
                  $sub = (float)$it['qty']*(float)$it['unit_price']; $sum+=$sub; ?>
                  <tr>
                    <td><?= esc($it['description']) ?></td>
                    <td class="text-end"><?= esc($it['qty']) ?></td>
                    <td class="text-end"><?= moneyAR($it['unit_price']) ?></td>
                    <td class="text-end"><?= moneyAR($sub) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3" class="text-end">Total</th>
                  <th class="text-end"><?= moneyAR($sum) ?></th>
                </tr>
              </tfoot>
            </table>
          </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>
