<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Taller Leguizamón — Gestión</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Estilos de tu web (sirve desde /css porque DocumentRoot = /public) -->
  <link href="/css/stylesheet.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark" style="background:#121314;">
  <div class="container">
    <a class="navbar-brand" href="/index.html">Taller Leguizamón</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <div class="navbar-nav me-auto">
        <a class="nav-link" href="/index.html">Inicio</a>
        <a class="nav-link" href="/admin/?r=portal">Portal</a>
        <?php if (auth_check()): ?>
          <a class="nav-link" href="/admin/?r=clientes/listar">Clientes</a>
          <a class="nav-link" href="/admin/?r=vehiculos/listar">Vehículos</a>
          <a class="nav-link" href="/admin/?r=ot/listar">Órdenes</a>
        <?php endif; ?>
      </div>
      <div class="navbar-nav ms-auto">
        <?php if (auth_check()): ?>
          <a class="nav-link" href="/admin/?r=logout">Salir</a>
        <?php else: ?>
          <a class="nav-link" href="/admin/?r=login">Administración</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<div class="container py-4">
  <?= $content ?? '' ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- === Tabla: filtro en vivo + sort columns === -->
    <script>
      (function(){
        // Normaliza para búsqueda acento-insensible
        const norm = s => (s||'')
          .toString()
          .normalize('NFD')
          .replace(/\p{Diacritic}/gu,'')
          .toLowerCase();

        // Parseos básicos para ordenamiento
        const parseNum = s => {
          if (s == null) return NaN;
          // soporta $ 1.234.567,89  -> 1234567.89
          const t = s.replace(/[^\d,.-]/g,'').replace(/\.(?=\d{3}(\D|$))/g,'').replace(',','.');
          const n = parseFloat(t);
          return isNaN(n) ? NaN : n;
        };
        const parseDate = s => {
          // intenta Date.parse directo; si no, deja string
          const d = Date.parse(s);
          return isNaN(d) ? null : d;
        };

        // FILTRO en vivo: input.table-filter[data-table="#selector"]
        document.querySelectorAll('.table-filter').forEach(inp=>{
          const sel = inp.getAttribute('data-table');
          const tbl = sel ? document.querySelector(sel) : null;
          if(!tbl) return;
          const tbody = tbl.querySelector('tbody');
          const rows = Array.from(tbody?.rows || []);

          inp.addEventListener('input', ()=>{
            const q = norm(inp.value);
            rows.forEach(tr=>{
              const txt = norm(tr.innerText);
              tr.style.display = txt.includes(q) ? '' : 'none';
            });
          });
        });

        // SORT: th[data-sort] con opcional data-type="num|date|text"
        document.querySelectorAll('table[data-sortable] thead th[data-sort]').forEach(th=>{
          th.style.cursor = 'pointer';
          th.addEventListener('click', ()=>{
            const table = th.closest('table');
            const tbody = table.querySelector('tbody');
            const idx = Array.from(th.parentNode.children).indexOf(th);
            const type = th.dataset.type || 'text';
            const current = th.dataset.dir === 'asc' ? 'asc' : (th.dataset.dir === 'desc' ? 'desc' : null);
            const nextDir = current === 'asc' ? 'desc' : 'asc';

            // reset iconos/estado
            th.parentNode.querySelectorAll('th[data-sort]').forEach(x=>{
              if(x!==th){ x.removeAttribute('data-dir'); x.classList.remove('sorted-asc','sorted-desc'); }
            });

            // ordena
            const rows = Array.from(tbody.rows).filter(r=>r.style.display !== 'none');
            const getVal = tr => tr.cells[idx] ? tr.cells[idx].innerText.trim() : '';
            rows.sort((a,b)=>{
              let va = getVal(a), vb = getVal(b);
              if(type==='num'){
                va = parseNum(va); vb = parseNum(vb);
                if(isNaN(va)) va = -Infinity; if(isNaN(vb)) vb = -Infinity;
              } else if(type==='date'){
                va = parseDate(va) ?? -Infinity; vb = parseDate(vb) ?? -Infinity;
              } else {
                va = norm(va); vb = norm(vb);
              }
              if(va < vb) return nextDir==='asc' ? -1 : 1;
              if(va > vb) return nextDir==='asc' ? 1 : -1;
              return 0;
            });

            // reinyectar
            rows.forEach(r=>tbody.appendChild(r));
            th.dataset.dir = nextDir;
            th.classList.toggle('sorted-asc', nextDir==='asc');
            th.classList.toggle('sorted-desc', nextDir==='desc');
          });
        });

        // Estética mínima de flechitas
        const style = document.createElement('style');
        style.textContent = `
          th[data-sort] { position: relative; user-select:none; }
          th[data-sort]::after{
            content: '↕'; opacity:.4; font-size:.85em; margin-left:.35rem;
          }
          th[data-sort].sorted-asc::after{ content:'↑'; opacity:1; }
          th[data-sort].sorted-desc::after{ content:'↓'; opacity:1; }
        `;
        document.head.appendChild(style);
      })();
    </script>
  </body>
</html>
