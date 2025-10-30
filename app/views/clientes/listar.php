<h1 class="h4 mb-3">Clientes</h1>

<div class="row g-2 align-items-center mb-3">
  <div class="col-md-6">
    <input class="form-control table-filter" data-table="#tblClientes" placeholder="Buscar cliente, email o teléfono...">
  </div>
  <div class="col-md-6 text-end">
    <a class="btn btn-primary" href="/admin/?r=clientes/crear">Nuevo cliente</a>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <?php if (empty($rows)): ?>
      <div class="alert alert-light mb-0">No hay clientes.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table id="tblClientes" class="table table-striped align-middle" data-sortable>
          <thead>
            <tr>
              <th data-sort data-type="num" style="width:90px;">ID</th>
              <th data-sort>Nombre</th>
              <th data-sort>Email</th>
              <th data-sort>Teléfono</th>
              <th data-sort>Documento</th>
              <th style="width:180px;" class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
              <tr>
                <td><?= (int)$r['id'] ?></td>
                <td><?= htmlspecialchars($r['full_name']) ?></td>
                <td><?= htmlspecialchars($r['email']) ?></td>
                <td><?= htmlspecialchars($r['phone']) ?></td>
                <td><?= htmlspecialchars($r['doc']) ?></td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-secondary" href="/admin/?r=clientes/ver&id=<?= (int)$r['id'] ?>">Ver</a>
                  <a class="btn btn-sm btn-outline-primary" href="/admin/?r=clientes/editar&id=<?= (int)$r['id'] ?>">Editar</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
