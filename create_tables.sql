-- Crear tablas para el sistema de gestión de traslados

-- Tabla users
CREATE TABLE IF NOT EXISTS users (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    email_verified_at timestamp NULL DEFAULT NULL,
    password varchar(255) NOT NULL,
    remember_token varchar(100) DEFAULT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    role varchar(255) NOT NULL DEFAULT 'hotel',
    hotel_id bigint unsigned DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY users_email_unique (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla cache
CREATE TABLE IF NOT EXISTS cache (
    `key` varchar(255) NOT NULL,
    value mediumtext NOT NULL,
    expiration int NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla cache_locks
CREATE TABLE IF NOT EXISTS cache_locks (
    `key` varchar(255) NOT NULL,
    owner varchar(255) NOT NULL,
    expiration int NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla jobs
CREATE TABLE IF NOT EXISTS jobs (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    queue varchar(255) NOT NULL,
    payload longtext NOT NULL,
    attempts tinyint unsigned NOT NULL,
    reserved_at int unsigned DEFAULT NULL,
    available_at int unsigned NOT NULL,
    created_at int unsigned NOT NULL,
    PRIMARY KEY (id),
    KEY jobs_queue_index (queue)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla job_batches
CREATE TABLE IF NOT EXISTS job_batches (
    id varchar(255) NOT NULL,
    name varchar(255) NOT NULL,
    total_jobs int NOT NULL,
    pending_jobs int NOT NULL,
    failed_jobs int NOT NULL,
    failed_job_ids longtext NOT NULL,
    options mediumtext,
    cancelled_at int DEFAULT NULL,
    created_at int NOT NULL,
    finished_at int DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla failed_jobs
CREATE TABLE IF NOT EXISTS failed_jobs (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    uuid varchar(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload longtext NOT NULL,
    exception longtext NOT NULL,
    failed_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY failed_jobs_uuid_unique (uuid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla password_reset_tokens
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email varchar(255) NOT NULL,
    token varchar(255) NOT NULL,
    created_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla sessions
CREATE TABLE IF NOT EXISTS sessions (
    id varchar(255) NOT NULL,
    user_id bigint unsigned DEFAULT NULL,
    ip_address varchar(45) DEFAULT NULL,
    user_agent text,
    payload longtext NOT NULL,
    last_activity int NOT NULL,
    PRIMARY KEY (id),
    KEY sessions_user_id_index (user_id),
    KEY sessions_last_activity_index (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla hotels
CREATE TABLE IF NOT EXISTS hotels (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    nombre varchar(255) NOT NULL,
    direccion varchar(255) NOT NULL,
    telefono varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    categoria int NOT NULL,
    habitaciones int NOT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    zona enum('Norte','Sur','Este','Oeste','Centro') DEFAULT NULL,
    comision_porcentaje decimal(5,2) DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY hotels_email_unique (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla hotel_users
CREATE TABLE IF NOT EXISTS hotel_users (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    hotel_id bigint unsigned NOT NULL,
    nombre varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    telefono varchar(255) DEFAULT NULL,
    activo tinyint(1) NOT NULL DEFAULT '1',
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY hotel_users_email_unique (email),
    KEY hotel_users_hotel_id_foreign (hotel_id),
    CONSTRAINT hotel_users_hotel_id_foreign FOREIGN KEY (hotel_id) REFERENCES hotels (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla reservations
CREATE TABLE IF NOT EXISTS reservations (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    hotel_id bigint unsigned NOT NULL,
    tipo_traslado enum('Llegada','Salida','Ida y vuelta') NOT NULL,
    fecha_traslado date NOT NULL,
    hora_traslado time NOT NULL,
    num_viajeros int NOT NULL,
    cliente_nombre varchar(255) NOT NULL,
    cliente_email varchar(255) NOT NULL,
    cliente_telefono varchar(255) NOT NULL,
    origen varchar(255) NOT NULL,
    destino varchar(255) NOT NULL,
    vehiculo_tipo enum('Turismo','Bus') NOT NULL,
    precio decimal(10,2) NOT NULL,
    comision decimal(10,2) DEFAULT NULL,
    estado enum('Pendiente','Confirmada','Cancelada','Completada') NOT NULL DEFAULT 'Pendiente',
    notas text,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY reservations_hotel_id_foreign (hotel_id),
    CONSTRAINT reservations_hotel_id_foreign FOREIGN KEY (hotel_id) REFERENCES hotels (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla availabilities
CREATE TABLE IF NOT EXISTS availabilities (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    hotel_id bigint unsigned NOT NULL,
    vehiculo_tipo enum('Turismo','Bus') NOT NULL,
    num_vehiculos int NOT NULL,
    fecha date NOT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY availabilities_hotel_id_foreign (hotel_id),
    CONSTRAINT availabilities_hotel_id_foreign FOREIGN KEY (hotel_id) REFERENCES hotels (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla migrations
CREATE TABLE IF NOT EXISTS migrations (
    id int unsigned NOT NULL AUTO_INCREMENT,
    migration varchar(255) NOT NULL,
    batch int NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar registros de migraciones
INSERT IGNORE INTO migrations (migration, batch) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2025_12_11_231125_add_role_and_hotel_to_users_table', 1),
('2025_12_11_234142_create_hotels_table', 1),
('2025_12_12_000301_create_hotel_users_table', 1),
('2025_12_12_000652_create_reservations_table', 1),
('2025_12_12_003836_create_availabilities_table', 1),
('2025_12_12_004044_create_availabilities_table', 1),
('2025_12_16_000001_add_zona_and_comision_to_hotels_table', 1);
