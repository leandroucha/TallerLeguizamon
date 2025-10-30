<div class="small text-muted mb-2">
  <?= (int)($total ?? 0) ?> resultado<?= ($total ?? 0)==1?'':'s' ?> · página <?= (int)($page ?? 1) ?> de <?= (int)($pages ?? 1) ?>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Documento</th>
            <th>Creado</th>
            <th style="width:1%"></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach (($rows ?? []) as $r): ?>
          <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= htmlspecialchars($r['full_name']) ?></td>
            <td><?= htmlspecialchars($r['phone']) ?></td>
            <td><?= htmlspecialchars($r['email']) ?></td>
            <td><?= htmlspecialchars($r['doc']) ?></td>
            <td><?= htmlspecialchars($r['created_at']) ?></td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-primary js-ver-cliente" data-id="<?= (int)$r['id'] ?>" data-nombre="<?= htmlspecialchars($r['full_name']) ?>">Ver</button>
              <a class="btn btn-sm btn-outline-secondary" href="/?r=clientes/editar&id=<?= (int)$r['id'] ?>">Editar</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?>
          <tr><td colspan="7" class="text-center text-muted">No hay clientes</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php if (($pages ?? 1) > 1): ?>
<nav class="mt-3">
  <ul class="pagination">
    <?php
      $p=(int)($page ?? 1); $pp=max(1,$p-1); $np=min((int)($pages ?? 1),$p+1); $base=$base ?? '/?r=clientes/listar';
      $link=function($pg,$label,$disabled=false,$active=false) use($base){ $attr = $disabled?' class="page-item disabled"':' class="page-item'.($active?' active':'').'"'; $a=$disabled?'<span class="page-link">'.$label.'</span>':'<a class="page-link" href="#" data-page="'.$pg.'">'.$label.'</a>'; echo '<li'.$attr.'>'.$a.'</li>'; };
      $link(1, '«', $p==1); $link($pp,'Anterior',$p==1);
      $start=max(1,$p-2); $end=min(($pages ?? 1),$p+2);
      for($i=$start;$i<=$end;$i++){ $link($i,$i,false,$i==$p); }
      $link($np,'Siguiente',$p==($pages ?? 1)); $link(($pages ?? 1),'»',$p==($pages ?? 1));
    ?>
  </ul>
</nav>
<?php endif; ?>
