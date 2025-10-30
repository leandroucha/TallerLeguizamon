-- Clientes ficticios
INSERT INTO customers (full_name,email,phone,doc) VALUES
('Juan Pérez','juan@example.com','351-555-1000','30123456'),
('María Gómez','maria@example.com','351-555-2000','28999888'),
('Carlos Díaz','carlos@example.com','351-555-3000','33222444');

-- Vehículos
INSERT INTO vehicles (customer_id,brand,model,year,plate,vin) VALUES
(1,'Volkswagen','Gol Trend',2016,'AB123CD','9BWZZZ377VT004251'),
(1,'Peugeot','208',2020,'AE456FG','VF3CCHNZTKT123456'),
(2,'Toyota','Corolla',2018,'AC789HJ','JTDBR32E920123456'),
(3,'Renault','Kangoo',2015,'AA321BB','VF1FW15B123456789');

-- Órdenes (con estados nuevos)
INSERT INTO work_orders (vehicle_id,status,opened_at,started_at,closed_at,notes) VALUES
(1,'revision',      NOW() - INTERVAL 7 DAY, NULL, NULL, 'Revisión de frenos y ruidos'),
(1,'presupuestado', NOW() - INTERVAL 6 DAY, NOW() - INTERVAL 6 DAY, NULL, 'Presupuesto enviado'),
(2,'reparacion',    NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 3 DAY, NULL, 'Cambio kit distribución'),
(3,'entregado',     NOW() - INTERVAL 20 DAY, NOW() - INTERVAL 19 DAY, NOW() - INTERVAL 15 DAY, 'Service 10.000 km'),
(4,'reparacion',    NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 2 DAY, NULL, 'Embrague patina');

-- Ítems
INSERT INTO work_order_items (work_order_id,description,qty,unit_price) VALUES
(1,'Diagnóstico de frenos',1,15000.00),
(2,'Pastillas delanteras',1,38000.00),
(2,'Mano de obra frenos',1,25000.00),
(3,'Kit distribución',1,120000.00),
(3,'Mano de obra distribución',1,70000.00),
(4,'Service 10.000 km',1,45000.00),
(5,'Kit embrague',1,160000.00),
(5,'Mano de obra embrague',1,90000.00);
