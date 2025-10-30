<div class="small text-muted mb-2">
  <?= (int)$total ?> resultado<?= $total==1?'':'s' ?> · página <?= (int)$page ?> de <?= (int)$pages ?>
</div>
<div class="card shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th><th>Cliente</th><th>Vehículo</th><th>Patente</th><th>Estado</th><th>Abierta</th><th>Total</th><th style="width:1%"></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach (($rows ?? []) as $r): ?>
          <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= htmlspecialchars($r['customer_name']) ?></td>
            <td><?= htmlspecialchars($r['brand'].' '.$r['model']) ?></td>
            <td><?= htmlspecialchars($r['plate']) ?></td>
            <td><?php $badge=['abierta'=>'secondary','en_curso'=>'warning','finalizada'=>'success'][$r['status']]??'secondary'; ?>
              <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($r['status']) ?></span></td>
            <td><?= htmlspecialchars($r['opened_at']) ?></td>
            <td>$ <?= number_format((float)$r['total'],2,',','.') ?></td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="/?r=ot/ver&id=<?= (int)$r['id'] ?>">Ver</a>
              <a class="btn btn-sm btn-outline-secondary" href="/?r=ot/editar&id=<?= (int)$r['id'] ?>">Editar</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?>
          <tr><td colspan="8" class="text-center text-muted">No hay órdenes</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php if (($pages ?? 1) > 1): ?>
<nav class="mt-3"><ul class="pagination">
<?php
  $p=$page; $pp=max(1,$p-1); $np=min($pages,$p+1);
  $link=function($pg,$label,$disabled=false,$active=false){ $attr=$disabled?' class="page-item disabled"':' class="page-item'.($active?' active':'').'"'; $a=$disabled?'<span class="page-link">'+$label+'</span>':'<a class="page-link" href="#" data-page="'+$pg+'">'+$label+'</a>'; echo '<li'.$attr.'>'.$a.'</li>'; };
  $link(1,'«',$p==1); $link($pp,'Anterior',$p==1);
  for($i=max(1,$p-2);$i<=min($pages,$p+2);$i++) $link($i,$i,false,$i==$p);
  $link($np,'Siguiente',$p==$pages); $link($pages,'»',$p==$pages);
?>
</ul></nav>
<?php endif; ?>
