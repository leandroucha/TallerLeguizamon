<?php
function moneyAR($n){ return '$ '.number_format((float)$n,2,',','.'); }
$total=0; foreach(($items??[]) as $i){ $total += (float)$i['qty']*(float)$i['unit_price']; }
?>
<h1 class="h4 mb-3">Editar OT #<?= (int)$order['id'] ?></h1>

<div class="card mb-3">
  <div class="card-body">
    <form method="post" action="/admin/?r=ot/editar&id=<?= (int)$order['id'] ?>" class="row g-3">
      <input type="hidden" name="action" value="update_header">
      <div class="col-md-4">
        <label class="form-label">Vehículo</label>
        <input class="form-control" value="<?= htmlspecialchars($order['brand'].' '.$order['model'].' — '.$order['plate']) ?>" disabled>
      </div>
      <div class="col-md-3">
        <label class="form-label">Estado</label>
        <select class="form-select" name="status">
          <option value="revision"      <?= $order['status']==='revision'?'selected':'' ?>>Revisión</option>
          <option value="presupuestado" <?= $order['status']==='presupuestado'?'selected':'' ?>>Presupuestado</option>
          <option value="reparacion"    <?= $order['status']==='reparacion'?'selected':'' ?>>Reparación</option>
          <option value="entregado"     <?= $order['status']==='entregado'?'selected':'' ?>>Entregado</option>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label">Apertura</label>
        <input class="form-control" value="<?= htmlspecialchars($order['opened_at']) ?>" disabled>
      </div>
      <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary">Guardar encabezado</button>
        <a class="btn btn-outline-secondary" href="/admin/?r=ot/listar">Volver</a>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h2 class="h6 mb-0">Ítems</h2>
      <div><strong>Total:</strong> <?= moneyAR($total) ?></div>
    </div>

    <?php if (empty($items)): ?>
      <div class="alert alert-light">Sin ítems</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-sm align-middle">
          <thead><tr><th>Descripción</th><th class="text-end">Cant</th><th class="text-end">P. Unit</th><th class="text-end">Subtotal</th><th></th></tr></thead>
          <tbody>
            <?php foreach($items as $it): $sub=(float)$it['qty']*(float)$it['unit_price']; ?>
            <tr>
              <td><?= htmlspecialchars($it['description']) ?></td>
              <td class="text-end"><?= htmlspecialchars($it['qty']) ?></td>
              <td class="text-end"><?= moneyAR($it['unit_price']) ?></td>
              <td class="text-end"><?= moneyAR($sub) ?></td>
              <td class="text-end">
                <form method="post" action="/admin/?r=ot/editar&id=<?= (int)$order['id'] ?>">
                  <input type="hidden" name="action" value="del_item">
                  <input type="hidden" name="item_id" value="<?= (int)$it['id'] ?>">
                  <button class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar ítem?')">Eliminar</button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr><th colspan="3" class="text-end">Total</th><th class="text-end"><?= moneyAR($total) ?></th><th></th></tr>
          </tfoot>
        </table>
      </div>
    <?php endif; ?>

    <hr>

    <h3 class="h6">Agregar ítem</h3>
    <form class="row g-2" method="post" action="/admin/?r=ot/editar&id=<?= (int)$order['id'] ?>">
      <input type="hidden" name="action" value="add_item">
      <div class="col-md-6">
        <input class="form-control" name="description" placeholder="Descripción" required>
      </div>
      <div class="col-md-2">
        <input class="form-control" name="qty" type="number" step="0.01" min="0.01" value="1" required>
      </div>
      <div class="col-md-3">
        <input class="form-control" name="unit_price" type="number" step="0.01" min="0" value="0" required>
      </div>
      <div class="col-md-1 d-grid">
        <button class="btn btn-success">Agregar</button>
      </div>
    </form>
  </div>
</div>
