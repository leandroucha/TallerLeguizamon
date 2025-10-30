<div class="small text-muted mb-2">
  <?= (int)$total ?> resultado<?= $total==1?'':'s' ?> · página <?= (int)$page ?> de <?= (int)$pages ?>
</div>
<div class="card shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th><th>Cliente</th><th>Marca</th><th>Modelo</th><th>Año</th><th>Patente</th><th>VIN</th><th>Creado</th><th style="width:1%"></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach (($rows ?? []) as $r): ?>
          <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= htmlspecialchars($r['customer_name']) ?></td>
            <td><?= htmlspecialchars($r['brand']) ?></td>
            <td><?= htmlspecialchars($r['model']) ?></td>
            <td><?= htmlspecialchars($r['year']) ?></td>
            <td><?= htmlspecialchars($r['plate']) ?></td>
            <td><?= htmlspecialchars($r['vin']) ?></td>
            <td><?= htmlspecialchars($r['created_at']) ?></td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-secondary" href="/?r=vehiculos/editar&id=<?= (int)$r['id'] ?>">Editar</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?>
          <tr><td colspan="9" class="text-center text-muted">No hay vehículos</td></tr>
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
