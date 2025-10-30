<?php
function moneyAR($n){ return '$ '.number_format((float)$n, 2, ',', '.'); }
$total = 0;
foreach (($items ?? []) as $i) $total += (float)$i['qty'] * (float)$i['unit_price'];
?>
<h1 class="h4 mb-3">OT #<?= (int)$order['id'] ?></h1>

<div class="card mb-3">
  <div class="card-body">
    <div><strong>Vehículo:</strong> <?= htmlspecialchars($order['brand'].' '.$order['model'].' — '.$order['plate']) ?></div>
    <div><strong>Estado:</strong> <?= htmlspecialchars($order['status']) ?></div>
    <div><strong>Apertura:</strong> <?= htmlspecialchars($order['opened_at']) ?></div>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <?php if (empty($items)): ?>
      <div class="alert alert-light mb-0">Sin ítems</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-sm">
          <thead><tr><th>Descripción</th><th class="text-end">Cant</th><th class="text-end">P. Unit</th><th class="text-end">Subtotal</th></tr></thead>
          <tbody>
            <?php foreach ($items as $it): $sub = (float)$it['qty']*(float)$it['unit_price']; ?>
              <tr>
                <td><?= htmlspecialchars($it['description']) ?></td>
                <td class="text-end"><?= htmlspecialchars($it['qty']) ?></td>
                <td class="text-end"><?= moneyAR($it['unit_price']) ?></td>
                <td class="text-end"><?= moneyAR($sub) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr><th colspan="3" class="text-end">Total</th><th class="text-end"><?= moneyAR($total) ?></th></tr>
          </tfoot>
        </table>
      </div>
    <?php endif; ?>

    <a class="btn btn-outline-secondary mt-3" href="/admin/?r=ot/listar">Volver</a>
  </div>
</div>
