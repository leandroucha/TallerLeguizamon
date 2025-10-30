<?php
function view(string $name, array $vars = []): void {
  extract($vars);
  ob_start();
  include __DIR__ . "/views/{$name}.php";
  $content = ob_get_clean();
  include __DIR__ . "/views/layout.php";
}
function redirect(string $url): void { header("Location: {$url}"); exit; }

function dispatch(string $route): bool {
  switch ($route) {
    /* ------------------ PÚBLICO ------------------ */
    case 'home': redirect('/index.html');

    /* ------------------ AUTH ------------------ */
    case 'login': {
      $error = null;
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ok = auth_login(trim($_POST['user'] ?? ''), trim($_POST['pass'] ?? ''));
        if ($ok) redirect('/admin/?r=clientes/listar');
        $error = 'Usuario o contraseña incorrectos';
      }
      view('auth/login', ['error' => $error]);
      return true;
    }
    case 'logout': auth_logout(); redirect('/admin/?r=login');

    /* ------------------ PORTAL ------------------ */
    case 'portal': view('portal/index'); return true;

    case 'portal/buscar': {
      // 1) Leer y normalizar entrada
      $plate_raw = trim($_GET['plate'] ?? '');
      $doc_raw   = trim($_GET['doc'] ?? '');
    
      // Patente en mayúsculas sin espacios/guiones, DNI solo dígitos
      $plate = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $plate_raw));
      $dni   = preg_replace('/\D+/', '', $doc_raw);
    
      if ($plate === '' || $dni === '') {
        if (function_exists('flash')) {
          flash('danger', 'Ingresá patente y DNI.');
        }
        redirect('/admin/?r=portal');
      }
    
      $pdo = db();
    
      // 2) Buscar vehículo cuyo titular (customers.doc) coincida con el DNI dado
      $stmt = $pdo->prepare("
        SELECT v.id AS vehicle_id, v.brand, v.model, v.year, v.plate,
               c.id AS customer_id, c.full_name, c.doc
        FROM vehicles v
        JOIN customers c ON c.id = v.customer_id
        WHERE UPPER(REPLACE(v.plate, ' ', '')) = ?
          AND REPLACE(c.doc, '.', '') = ?
        LIMIT 1
      ");
      $stmt->execute([$plate, $dni]);
      $match = $stmt->fetch();
    
      // 3) Si no hay match exacto (patente + DNI del titular), no mostrar nada
      if (!$match) {
        view('portal/result', [
          'secure' => true,
          'found'  => false,
          'plate'  => $plate_raw,
          'doc'    => $doc_raw,
        ]);
        return true;
      }
    
      // 4) Traer órdenes del vehículo, incluyendo started_at y total
      $ordersSt = $pdo->prepare("
        SELECT wo.id, wo.status, wo.opened_at, wo.started_at, wo.closed_at,
               COALESCE((
                 SELECT SUM(qty * unit_price)
                 FROM work_order_items i
                 WHERE i.work_order_id = wo.id
               ), 0) AS total
        FROM work_orders wo
        WHERE wo.vehicle_id = ?
        ORDER BY wo.id DESC
      ");
      $ordersSt->execute([(int)$match['vehicle_id']]);
      $orders = $ordersSt->fetchAll();
    
      // 5) Traer ítems agrupados por OT (para mostrar en el modal)
      $itemsByOrder = [];
      if (!empty($orders)) {
        $ids = array_column($orders, 'id');
        $ph  = implode(',', array_fill(0, count($ids), '?'));
        $it  = $pdo->prepare("
          SELECT id, work_order_id, description, qty, unit_price
          FROM work_order_items
          WHERE work_order_id IN ($ph)
          ORDER BY id ASC
        ");
        $it->execute($ids);
        foreach ($it->fetchAll() as $row) {
          $itemsByOrder[(int)$row['work_order_id']][] = $row;
        }
      }
    
      // 6) Render de resultados seguros (solo ese vehículo y sus OTs)
      view('portal/result', [
        'secure'       => true,
        'found'        => true,
        'vehicle'      => $match,
        'orders'       => $orders,
        'itemsByOrder' => $itemsByOrder,
        'plate'        => $plate_raw,
        'doc'          => $doc_raw,
      ]);
      return true;
    }
  
    
    

    /* ------------------ CLIENTES ------------------ */
    case 'clientes/listar': {
      require_login_or_redirect();
      $q=trim($_GET['q']??'');
      if ($q!==''){
        $st=db()->prepare("SELECT * FROM customers WHERE full_name LIKE ? OR email LIKE ? OR phone LIKE ? ORDER BY id DESC");
        $st->execute(["%$q%","%$q%","%$q%"]); $rows=$st->fetchAll();
      } else { $rows=db()->query("SELECT * FROM customers ORDER BY id DESC")->fetchAll(); }
      view('clientes/listar',['rows'=>$rows,'q'=>$q]); return true;
    }
    case 'clientes/crear': {
      require_login_or_redirect();
      if($_SERVER['REQUEST_METHOD']==='POST'){
        db()->prepare("INSERT INTO customers(full_name,phone,email,doc) VALUES (?,?,?,?)")
            ->execute([trim($_POST['full_name']??''),trim($_POST['phone']??''),trim($_POST['email']??''),trim($_POST['doc']??'')]);
        redirect('/admin/?r=clientes/listar');
      }
      view('clientes/crear'); return true;
    }
    case 'clientes/editar': {
      require_login_or_redirect();
      $id=(int)($_GET['id']??0);
      if($_SERVER['REQUEST_METHOD']==='POST'){
        db()->prepare("UPDATE customers SET full_name=?, phone=?, email=?, doc=? WHERE id=?")
            ->execute([trim($_POST['full_name']??''),trim($_POST['phone']??''),trim($_POST['email']??''),trim($_POST['doc']??''),$id]);
        redirect('/admin/?r=clientes/listar');
      }
      $st=db()->prepare("SELECT * FROM customers WHERE id=?"); $st->execute([$id]); $row=$st->fetch();
      view('clientes/editar',['row'=>$row]); return true;
    }
    /* ------------------ CLIENTE: Ver ------------------ */
case 'clientes/ver': {
  require_login_or_redirect();
  $id = (int)($_GET['id'] ?? 0);

  // Cliente
  $st = db()->prepare("SELECT * FROM customers WHERE id=?");
  $st->execute([$id]);
  $customer = $st->fetch();
  if (!$customer) { if(function_exists('flash')) flash('danger','Cliente no encontrado'); redirect('/admin/?r=clientes/listar'); }

  // Vehículos del cliente
  $vst = db()->prepare("SELECT * FROM vehicles WHERE customer_id=? ORDER BY id DESC");
  $vst->execute([$id]);
  $vehicles = $vst->fetchAll();

  // Órdenes por vehículo
  $ordersByVehicle = [];
  if ($vehicles) {
    $vids = array_column($vehicles, 'id');
    $ph = implode(',', array_fill(0, count($vids), '?'));
    $ost = db()->prepare("
      SELECT wo.*, v.plate, v.brand, v.model,
             COALESCE((SELECT SUM(qty*unit_price) FROM work_order_items i WHERE i.work_order_id=wo.id),0) AS total
      FROM work_orders wo
      JOIN vehicles v ON v.id=wo.vehicle_id
      WHERE wo.vehicle_id IN ($ph)
      ORDER BY wo.id DESC
    ");
    $ost->execute($vids);
    foreach ($ost->fetchAll() as $o) {
      $ordersByVehicle[(int)$o['vehicle_id']][] = $o;
    }
  }

  view('clientes/ver', ['customer'=>$customer, 'vehicles'=>$vehicles, 'ordersByVehicle'=>$ordersByVehicle]);
  return true;
}

/* ------------------ VEHÍCULO: Ver ------------------ */
/* ------------------ VEHÍCULO: Ver ------------------ */
case 'vehiculos/ver': {
  require_login_or_redirect();
  $id = (int)($_GET['id'] ?? 0);

  // Vehículo + cliente
  $st = db()->prepare("
    SELECT v.*, c.full_name AS customer_name, c.id AS customer_id
    FROM vehicles v
    JOIN customers c ON c.id = v.customer_id
    WHERE v.id=?
  ");
  $st->execute([$id]);
  $vehicle = $st->fetch();
  if (!$vehicle) { if(function_exists('flash')) flash('danger','Vehículo no encontrado'); redirect('/admin/?r=vehiculos/listar'); }

  // Órdenes del vehículo
  $ost = db()->prepare("
    SELECT wo.*,
           COALESCE((SELECT SUM(qty*unit_price) FROM work_order_items i WHERE i.work_order_id=wo.id),0) AS total
    FROM work_orders wo
    WHERE wo.vehicle_id=?
    ORDER BY wo.id DESC
  ");
  $ost->execute([$id]);
  $orders = $ost->fetchAll();

  view('vehiculos/ver', ['vehicle'=>$vehicle, 'orders'=>$orders]);
  return true;
}



    /* ------------------ VEHÍCULOS ------------------ */
    case 'vehiculos/listar': {
      require_login_or_redirect();
      $rows=db()->query("SELECT v.*,c.full_name customer_name
                         FROM vehicles v JOIN customers c ON c.id=v.customer_id
                         ORDER BY v.id DESC")->fetchAll();
      view('vehiculos/listar',['rows'=>$rows]); return true;
    }
    case 'vehiculos/crear': {
      require_login_or_redirect();
      if($_SERVER['REQUEST_METHOD']==='POST'){
        db()->prepare("INSERT INTO vehicles(customer_id,brand,model,year,plate,vin) VALUES (?,?,?,?,?,?)")
            ->execute([(int)($_POST['customer_id']??0),trim($_POST['brand']??''),trim($_POST['model']??''),(int)($_POST['year']??0),
                       trim($_POST['plate']??''),trim($_POST['vin']??'')]);
        redirect('/admin/?r=vehiculos/listar');
      }
      $customers=db()->query("SELECT id,full_name FROM customers ORDER BY full_name ASC")->fetchAll();
      view('vehiculos/crear',['customers'=>$customers]); return true;
    }
    case 'vehiculos/editar': {
      require_login_or_redirect();
      $id=(int)($_GET['id']??0);
      if($_SERVER['REQUEST_METHOD']==='POST'){
        db()->prepare("UPDATE vehicles SET customer_id=?, brand=?, model=?, year=?, plate=?, vin=? WHERE id=?")
            ->execute([(int)($_POST['customer_id']??0),trim($_POST['brand']??''),trim($_POST['model']??''),(int)($_POST['year']??0),
                       trim($_POST['plate']??''),trim($_POST['vin']??''),$id]);
        redirect('/admin/?r=vehiculos/listar');
      }
      $st=db()->prepare("SELECT * FROM vehicles WHERE id=?"); $st->execute([$id]); $row=$st->fetch();
      $customers=db()->query("SELECT id,full_name FROM customers ORDER BY full_name ASC")->fetchAll();
      view('vehiculos/editar',['row'=>$row,'customers'=>$customers]); return true;
    }

    /* ------------------ ÓRDENES DE TRABAJO ------------------ */
    case 'ot/listar': {
      require_login_or_redirect();
      $rows=db()->query("SELECT wo.*, v.plate, v.brand, v.model
                         FROM work_orders wo
                         JOIN vehicles v ON v.id=wo.vehicle_id
                         ORDER BY wo.id DESC")->fetchAll();
      view('OT/listar',['rows'=>$rows]); return true;
    }

    /* Crear OT */
    case 'ot/crear': {
      require_login_or_redirect();
    
      if($_SERVER['REQUEST_METHOD']==='POST'){
        $vehicle_id = (int)($_POST['vehicle_id'] ?? 0);
        $status_in  = trim($_POST['status'] ?? 'revision');
        $allowed    = ['revision','presupuestado','reparacion','entregado'];
        $status     = in_array($status_in, $allowed, true) ? $status_in : 'revision';
    
        db()->prepare("INSERT INTO work_orders(vehicle_id,status,opened_at) VALUES (?,?,NOW())")
           ->execute([$vehicle_id,$status]);
        $otId=(int)db()->lastInsertId();
    
        $desc=trim($_POST['item_desc']??''); $qty=(float)($_POST['item_qty']??0); $price=(float)($_POST['item_price']??0);
        if($desc!=='' && $qty>0){
          db()->prepare("INSERT INTO work_order_items(work_order_id,description,qty,unit_price) VALUES (?,?,?,?)")
             ->execute([$otId,$desc,$qty,$price]);
        }
        redirect("/admin/?r=ot/editar&id={$otId}");
      }
    
      $vehicles=db()->query("SELECT v.id, CONCAT(c.full_name,' — ',v.brand,' ',v.model,' ',IFNULL(v.plate,'')) as label
                             FROM vehicles v JOIN customers c ON c.id=v.customer_id
                             ORDER BY c.full_name ASC, v.id DESC")->fetchAll();
      view('OT/crear',['vehicles'=>$vehicles]); return true;
    }
    

    /* Editar OT + manejar items */
/* Editar OT + manejar items */
case 'ot/editar': {
  require_login_or_redirect();
  $id = (int)($_GET['id'] ?? 0);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_header') {
      $status = trim($_POST['status'] ?? 'revision');
    
      // Obtener tiempos actuales
      $ts = db()->prepare("SELECT opened_at, started_at, closed_at FROM work_orders WHERE id=?");
      $ts->execute([$id]);
      $cur = $ts->fetch() ?: ['opened_at'=>null, 'started_at'=>null, 'closed_at'=>null];
    
      $started = $cur['started_at'];
      $closed  = $cur['closed_at'];
    
      switch ($status) {
        case 'revision':
          $started = null;
          $closed  = null;
          break;
    
        case 'presupuestado':
          if (empty($started)) $started = date('Y-m-d H:i:s');
          $closed = null;
          break;
    
        case 'reparacion':
          if (empty($started)) $started = date('Y-m-d H:i:s');
          $closed = null;
          break;
    
        case 'entregado':
          if (empty($started)) $started = date('Y-m-d H:i:s');
          if (empty($closed))  $closed  = date('Y-m-d H:i:s');
          break;
      }
    
      $up = db()->prepare("UPDATE work_orders SET status=?, started_at=?, closed_at=? WHERE id=?");
      $up->execute([$status, $started, $closed, $id]);
    
      if (function_exists('flash')) flash('success', 'Estado actualizado correctamente.');
    }
    
    elseif ($action === 'add_item') {
      $desc  = trim($_POST['description'] ?? '');
      $qty   = (float)($_POST['qty'] ?? 0);
      $price = (float)($_POST['unit_price'] ?? 0);
      if ($desc === '' || $qty <= 0) {
        if (function_exists('flash')) flash('danger','Completá descripción y cantidad > 0.');
      } else {
        $ii = db()->prepare("INSERT INTO work_order_items (work_order_id, description, qty, unit_price) VALUES (?,?,?,?)");
        $ii->execute([$id, $desc, $qty, $price]);
        if (function_exists('flash')) flash('success','Ítem agregado.');
      }
    }
    elseif ($action === 'del_item') {
      $itemId = (int)($_POST['item_id'] ?? 0);
      $dd = db()->prepare("DELETE FROM work_order_items WHERE id=? AND work_order_id=?");
      $dd->execute([$itemId, $id]);
      if (function_exists('flash')) flash('success','Ítem eliminado.');
    }

    // Importante: cerrar POST y redirigir para evitar re-envíos
    redirect("/admin/?r=ot/editar&id={$id}");
  } // <-- acá cerramos el if (POST)

  // GET: cargar datos
  $st = db()->prepare("SELECT wo.*, v.plate, v.brand, v.model
                       FROM work_orders wo JOIN vehicles v ON v.id=wo.vehicle_id
                       WHERE wo.id=?");
  $st->execute([$id]);
  $order = $st->fetch();

  if (!$order) {
    if (function_exists('flash')) flash('danger','La OT no existe.');
    redirect('/admin/?r=ot/listar');
  }

  $it = db()->prepare("SELECT * FROM work_order_items WHERE work_order_id=? ORDER BY id ASC");
  $it->execute([$id]);
  $items = $it->fetchAll();

  view('OT/editar', ['order'=>$order, 'items'=>$items]);
  return true;
} // <-- y acá cerramos el case 'ot/editar'


    /* Vista solo lectura (sigue igual) */
    case 'ot/ver': {
      require_login_or_redirect();
      $id=(int)($_GET['id']??0);
      $st=db()->prepare("SELECT wo.*, v.plate, v.brand, v.model
                         FROM work_orders wo JOIN vehicles v ON v.id=wo.vehicle_id
                         WHERE wo.id=?");
      $st->execute([$id]); $order=$st->fetch();
      $it=db()->prepare("SELECT * FROM work_order_items WHERE work_order_id=? ORDER BY id ASC");
      $it->execute([$id]); $items=$it->fetchAll();
      view('OT/ver',['order'=>$order,'items'=>$items]); return true;
    }

    default: return false;
  }
}
