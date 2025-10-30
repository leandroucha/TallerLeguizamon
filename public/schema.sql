-- Estructura base Taller (MySQL 8 / MariaDB)

CREATE TABLE IF NOT EXISTS customers (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  full_name  VARCHAR(120) NOT NULL,
  email      VARCHAR(120) NULL,
  phone      VARCHAR(60)  NULL,
  doc        VARCHAR(32)  NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_customers_doc (doc),
  INDEX idx_customers_name (full_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS vehicles (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  customer_id  INT NOT NULL,
  brand        VARCHAR(80)  NOT NULL,
  model        VARCHAR(80)  NOT NULL,
  year         INT NULL,
  plate        VARCHAR(16)  NOT NULL,
  vin          VARCHAR(64)  NULL,
  created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_vehicles_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
  INDEX idx_vehicles_plate (plate),
  INDEX idx_vehicles_customer (customer_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Estados nuevos: revision, presupuestado, reparacion, entregado
CREATE TABLE IF NOT EXISTS work_orders (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  vehicle_id  INT NOT NULL,
  status      ENUM('revision','presupuestado','reparacion','entregado') NOT NULL DEFAULT 'revision',
  opened_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  started_at  TIMESTAMP NULL,
  closed_at   TIMESTAMP NULL,
  notes       TEXT NULL,
  CONSTRAINT fk_work_orders_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE,
  INDEX idx_work_orders_vehicle (vehicle_id),
  INDEX idx_work_orders_status (status),
  INDEX idx_work_orders_opened (opened_at),
  INDEX idx_work_orders_started (started_at),
  INDEX idx_work_orders_closed (closed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS work_order_items (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  work_order_id  INT NOT NULL,
  description    VARCHAR(255) NOT NULL,
  qty            DECIMAL(10,2) NOT NULL DEFAULT 1.00,
  unit_price     DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_items_order FOREIGN KEY (work_order_id) REFERENCES work_orders(id) ON DELETE CASCADE,
  INDEX idx_items_order (work_order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
