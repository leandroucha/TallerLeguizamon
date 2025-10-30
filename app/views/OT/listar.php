<h1 class="h4 mb-3">Órdenes de trabajo</h1>

<div class="row g-2 align-items-center mb-3">
  <div class="col-md-6">
    <input class="form-control table-filter" data-table="#tblOT" placeholder="Buscar por patente, marca, modelo, estado...">
  </div>
  <div class="col-md-6 text-end">
    <a class="btn btn-primary" href="/admin/?r=ot/crear">Nueva OT</a>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <?php if (empty($rows)): ?>
      <div class="alert alert-light mb-0">No hay órdenes.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table id="tblOT" class="table table-striped align-middle" data-sortable>
          <thead>
            <tr>
              <th data-sort data-type="num" style="width:90px;">#</th>
              <th data-sort>Vehículo</th>
              <th data-sort>Estado</th>
              <th data-sort data-type="date">Abierta</th>
              <th data-sort data-type="date">Cierre</th>
              <th class="text-end" data-sort data-type="num">Total</th>
              <th style="width:180px;"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $o): ?>
              <tr>
                <td><?= (int)$o['id'] ?></td>
                <td><?= htmlspecialchars($o['brand'].' '.$o['model'].' — '.$o['plate']) ?></td>
                <td><span class="badge bg-secondary"><?= htmlspecialchars($o['status']) ?></span></td>
                <td><?= htmlspecialchars($o['opened_at']) ?></td>
                <td><?= htmlspecialchars($o['closed_at'] ?? '-') ?></td>
                <td class="text-end">
                  <?php
                    // si querés total acá, agregá al SELECT como hicimos en portal; si no, dejalo en blanco o '-'
                    echo isset($o['total']) ? '$ '.number_format((float)$o['total'],2,',','.') : '-';
                  ?>
                </td>
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
