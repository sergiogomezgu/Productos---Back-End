-- Insertar datos de prueba

-- Usuario administrador
INSERT IGNORE INTO users (id, name, email, password, role, created_at, updated_at) VALUES
(1, 'Sergio Admin', 'sergio@admin.com', '$2y$12$sN0z3YSJqF5L6KvZ8QqZ0.xMrBpGw9PqH9aJKvJqH5L6KvZ8QqZ0.', 'admin', NOW(), NOW());

-- Hoteles con zonas y comisiones
INSERT IGNORE INTO hotels (id, nombre, direccion, telefono, email, categoria, habitaciones, zona, comision_porcentaje, created_at, updated_at) VALUES
(1, 'Hotel Costa Norte', 'Avenida del Mar 123, Zona Norte', '+34 922 111 222', 'info@costanorte.com', 5, 150, 'Norte', 15.00, NOW(), NOW()),
(2, 'Hotel Vista Sur', 'Calle Principal 456, Zona Sur', '+34 922 333 444', 'reservas@vistasur.com', 4, 100, 'Sur', 12.50, NOW(), NOW()),
(3, 'Hotel Plaza Este', 'Plaza Mayor 789, Zona Este', '+34 922 555 666', 'contact@plazaeste.com', 4, 120, 'Este', 13.00, NOW(), NOW()),
(4, 'Hotel Puesta Oeste', 'Paseo del Oeste 321, Zona Oeste', '+34 922 777 888', 'info@puestaoeste.com', 3, 80, 'Oeste', 10.00, NOW(), NOW()),
(5, 'Hotel Centro Plaza', 'Gran Vía 654, Centro Ciudad', '+34 922 999 000', 'reservas@centroplaza.com', 5, 200, 'Centro', 14.00, NOW(), NOW());

-- Usuarios de hotel (password: password)
INSERT IGNORE INTO hotel_users (hotel_id, nombre, email, password, telefono, activo, created_at, updated_at) VALUES
(1, 'María García', 'norte@hotel.com', '$2y$12$sN0z3YSJqF5L6KvZ8QqZ0.xMrBpGw9PqH9aJKvJqH5L6KvZ8QqZ0.', '+34 666 111 111', 1, NOW(), NOW()),
(2, 'Juan Pérez', 'sur@hotel.com', '$2y$12$sN0z3YSJqF5L6KvZ8QqZ0.xMrBpGw9PqH9aJKvJqH5L6KvZ8QqZ0.', '+34 666 222 222', 1, NOW(), NOW()),
(3, 'Ana Martínez', 'este@hotel.com', '$2y$12$sN0z3YSJqF5L6KvZ8QqZ0.xMrBpGw9PqH9aJKvJqH5L6KvZ8QqZ0.', '+34 666 333 333', 1, NOW(), NOW()),
(4, 'Carlos López', 'oeste@hotel.com', '$2y$12$sN0z3YSJqF5L6KvZ8QqZ0.xMrBpGw9PqH9aJKvJqH5L6KvZ8QqZ0.', '+34 666 444 444', 1, NOW(), NOW()),
(5, 'Laura Sánchez', 'centro@hotel.com', '$2y$12$sN0z3YSJqF5L6KvZ8QqZ0.xMrBpGw9PqH9aJKvJqH5L6KvZ8QqZ0.', '+34 666 555 555', 1, NOW(), NOW());

-- Usuarios para login (vinculados a hoteles)
INSERT IGNORE INTO users (id, name, email, password, role, hotel_id, created_at, updated_at) VALUES
(2, 'María García', 'norte@hotel.com', '$2y$12$sN0z3YSJqF5L6KvZ8QqZ0.xMrBpGw9PqH9aJKvJqH5L6KvZ8QqZ0.', 'hotel', 1, NOW(), NOW()),
(3, 'Juan Pérez', 'sur@hotel.com', '$2y$12$sN0z3YSJqF5L6KvZ8QqZ0.xMrBpGw9PqH9aJKvJqH5L6KvZ8QqZ0.', 'hotel', 2, NOW(), NOW()),
(4, 'Ana Martínez', 'este@hotel.com', '$2y$12$sN0z3YSJqF5L6KvZ8QqZ0.xMrBpGw9PqH9aJKvJqH5L6KvZ8QqZ0.', 'hotel', 3, NOW(), NOW()),
(5, 'Carlos López', 'oeste@hotel.com', '$2y$12$sN0z3YSJqF5L6KvZ8QqZ0.xMrBpGw9PqH9aJKvJqH5L6KvZ8QqZ0.', 'hotel', 4, NOW(), NOW()),
(6, 'Laura Sánchez', 'centro@hotel.com', '$2y$12$sN0z3YSJqF5L6KvZ8QqZ0.xMrBpGw9PqH9aJKvJqH5L6KvZ8QqZ0.', 'hotel', 5, NOW(), NOW());

-- Reservas de ejemplo con comisiones
INSERT IGNORE INTO reservations (hotel_id, tipo_traslado, fecha_traslado, hora_traslado, num_viajeros, cliente_nombre, cliente_email, cliente_telefono, origen, destino, vehiculo_tipo, precio, comision, estado, created_at, updated_at) VALUES
(1, 'Llegada', '2025-12-20', '10:00:00', 2, 'Pedro Ramírez', 'pedro@email.com', '+34 600 111 111', 'Aeropuerto Norte', 'Hotel Costa Norte', 'Turismo', 45.00, 6.75, 'Confirmada', NOW(), NOW()),
(1, 'Salida', '2025-12-27', '14:00:00', 2, 'Pedro Ramírez', 'pedro@email.com', '+34 600 111 111', 'Hotel Costa Norte', 'Aeropuerto Norte', 'Turismo', 45.00, 6.75, 'Pendiente', NOW(), NOW()),
(2, 'Llegada', '2025-12-21', '15:30:00', 4, 'Carmen Díaz', 'carmen@email.com', '+34 600 222 222', 'Aeropuerto Sur', 'Hotel Vista Sur', 'Turismo', 50.00, 6.25, 'Confirmada', NOW(), NOW()),
(2, 'Ida y vuelta', '2025-12-25', '09:00:00', 4, 'Carmen Díaz', 'carmen@email.com', '+34 600 222 222', 'Hotel Vista Sur', 'Parque Temático', 'Turismo', 80.00, 10.00, 'Confirmada', NOW(), NOW()),
(3, 'Llegada', '2025-12-22', '11:15:00', 6, 'Roberto Fernández', 'roberto@email.com', '+34 600 333 333', 'Aeropuerto Este', 'Hotel Plaza Este', 'Bus', 90.00, 11.70, 'Confirmada', NOW(), NOW()),
(3, 'Salida', '2025-12-29', '12:00:00', 6, 'Roberto Fernández', 'roberto@email.com', '+34 600 333 333', 'Hotel Plaza Este', 'Aeropuerto Este', 'Bus', 90.00, 11.70, 'Pendiente', NOW(), NOW()),
(4, 'Llegada', '2025-12-23', '16:45:00', 3, 'Isabel Moreno', 'isabel@email.com', '+34 600 444 444', 'Aeropuerto Oeste', 'Hotel Puesta Oeste', 'Turismo', 40.00, 4.00, 'Confirmada', NOW(), NOW()),
(5, 'Llegada', '2025-12-24', '13:30:00', 5, 'Miguel Torres', 'miguel@email.com', '+34 600 555 555', 'Aeropuerto Centro', 'Hotel Centro Plaza', 'Bus', 85.00, 11.90, 'Confirmada', NOW(), NOW()),
(5, 'Salida', '2025-12-31', '10:30:00', 5, 'Miguel Torres', 'miguel@email.com', '+34 600 555 555', 'Hotel Centro Plaza', 'Aeropuerto Centro', 'Bus', 85.00, 11.90, 'Pendiente', NOW(), NOW());

-- Disponibilidad de vehículos
INSERT IGNORE INTO availabilities (hotel_id, vehiculo_tipo, num_vehiculos, fecha, created_at, updated_at) VALUES
(1, 'Turismo', 5, '2025-12-20', NOW(), NOW()),
(1, 'Bus', 2, '2025-12-20', NOW(), NOW()),
(2, 'Turismo', 4, '2025-12-21', NOW(), NOW()),
(2, 'Bus', 1, '2025-12-21', NOW(), NOW()),
(3, 'Turismo', 6, '2025-12-22', NOW(), NOW()),
(3, 'Bus', 3, '2025-12-22', NOW(), NOW()),
(4, 'Turismo', 3, '2025-12-23', NOW(), NOW()),
(4, 'Bus', 1, '2025-12-23', NOW(), NOW()),
(5, 'Turismo', 8, '2025-12-24', NOW(), NOW()),
(5, 'Bus', 4, '2025-12-24', NOW(), NOW());
