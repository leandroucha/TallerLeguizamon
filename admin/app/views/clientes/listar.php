<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="h5 mb-0">Clientes</h2>
  <a class="btn btn-primary" href="/?r=clientes/crear">Nuevo cliente</a>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Documento</th>
            <th>Creado</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach (($rows ?? []) as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['id']) ?></td>
            <td><?= htmlspecialchars($r['full_name']) ?></td>
            <td><?= htmlspecialchars($r['phone']) ?></td>
            <td><?= htmlspecialchars($r['email']) ?></td>
            <td><?= htmlspecialchars($r['doc']) ?></td>
            <td><?= htmlspecialchars($r['created_at']) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($rows)): ?>
          <tr><td colspan="6" class="text-center text-muted">No hay clientes</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
