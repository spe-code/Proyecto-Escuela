-- Crear la base de datos
CREATE DATABASE escuela;

-- Usar la base de datos
USE escuela;

-- Tabla estudiantes
CREATE TABLE estudiantes (
    id_estudiante INT AUTO_INCREMENT PRIMARY KEY,
    nombre_estudiante VARCHAR(100) NOT NULL,
    apellido_estudiante VARCHAR(100) NOT NULL,
    sexo_estudiante ENUM('M', 'F') NOT NULL,
    nacionalidad_estudiante ENUM('Venezolana', 'Extranjera') NOT NULL,
    cedula_estudiante VARCHAR(20) UNIQUE NOT NULL,
    fecha_nacimiento_estudiante DATE NOT NULL,
    lugar_nacimiento_estudiante VARCHAR(100) NOT NULL,
    direccion_estudiante VARCHAR(255) NOT NULL,
    telefono_local_estudiante VARCHAR(20),
    telefono_celular_estudiante VARCHAR(20) NOT NULL,
    correo_estudiante VARCHAR(100),
    año_cursar_estudiante INT NOT NULL,
    seccion_estudiante VARCHAR(10) NOT NULL,
    tipo_estudiante ENUM('regular', 'repitiente') NOT NULL,
    padece_enfermedad_estudiante TEXT,
    es_alergico_estudiante TEXT,
    periodo_escolar VARCHAR(9) NOT NULL
);

-- Tabla madre
CREATE TABLE madre (
    id_madre INT AUTO_INCREMENT PRIMARY KEY,
    nombre_madre VARCHAR(100) NOT NULL,
    apellido_madre VARCHAR(100) NOT NULL,
    cedula_madre VARCHAR(20) UNIQUE NOT NULL,
    nacionalidad_madre ENUM('Venezolana', 'Extranjera') NOT NULL,
    telefono_local_madre VARCHAR(20),
    telefono_celular_madre VARCHAR(20) NOT NULL,
    direccion_madre VARCHAR(255) NOT NULL,
    oficio_madre VARCHAR(100),
    id_estudiante INT,
    FOREIGN KEY (id_estudiante) REFERENCES estudiantes(id_estudiante) ON DELETE CASCADE
);

-- Tabla padre
CREATE TABLE padre (
    id_padre INT AUTO_INCREMENT PRIMARY KEY,
    nombre_padre VARCHAR(100) NOT NULL,
    apellido_padre VARCHAR(100) NOT NULL,
    cedula_padre VARCHAR(20) UNIQUE NOT NULL,
    nacionalidad_padre ENUM('Venezolana', 'Extranjera') NOT NULL,
    telefono_local_padre VARCHAR(20),
    telefono_celular_padre VARCHAR(20) NOT NULL,
    direccion_padre VARCHAR(255) NOT NULL,
    oficio_padre VARCHAR(100),
    id_estudiante INT,
    FOREIGN KEY (id_estudiante) REFERENCES estudiantes(id_estudiante) ON DELETE CASCADE
);

-- Tabla representante_legal
CREATE TABLE representante_legal (
    id_representante INT AUTO_INCREMENT PRIMARY KEY,
    cedula_representante VARCHAR(20) UNIQUE NOT NULL,
    nombre_representante VARCHAR(100) NOT NULL,
    apellido_representante VARCHAR(100) NOT NULL,
    nacionalidad_representante ENUM('Venezolana', 'Extranjera') NOT NULL,
    telefono_local_representante VARCHAR(20),
    telefono_celular_representante VARCHAR(20) NOT NULL,
    parentesco_representante ENUM('Madre', 'Padre', 'Tio', 'Tia', 'Abuelo', 'Abuela', 'Hermano', 'Hermana', 'Otro') NOT NULL,
    direccion_representante VARCHAR(50),
    codigo_representante VARCHAR(50),
    serial_representante VARCHAR(50),
    id_estudiante INT,
    FOREIGN KEY (id_estudiante) REFERENCES estudiantes(id_estudiante) ON DELETE CASCADE
);

CREATE TABLE egresados (
    id_estudiante INT PRIMARY KEY COMMENT 'ID original del estudiante',
    nombre_estudiante VARCHAR(100) NOT NULL,
    apellido_estudiante VARCHAR(100) NOT NULL,
    fecha_nacimiento_estudiante DATE NOT NULL,
    sexo_estudiante ENUM('M', 'F') NOT NULL,
    direccion_estudiante VARCHAR(255) NOT NULL,
    telefono_estudiante VARCHAR(20),
    correo_estudiante VARCHAR(100),
    periodo_escolar VARCHAR(9) COMMENT 'Año cuando ingresó al sistema',
    año_egreso INT NOT NULL COMMENT 'Año en que egresó',
    INDEX idx_periodo_escolar (periodo_escolar),
    INDEX idx_nombre (nombre_estudiante, apellido_estudiante)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Agregar esta tabla a tu base de datos escuela
CREATE TABLE administradores (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso DATETIME,
    activo TINYINT(1) DEFAULT 1
);


INSERT INTO `administradores` (`id_admin`, `username`, `password_hash`, `nombre_completo`, `email`, `fecha_creacion`, `ultimo_acceso`, `activo`) VALUES
(2, 'admin', '$2y$10$8ps4WJHyGpFTufzrVzzZD.Isuzin0IsoAA9UpLD2Dn8D/BHigxsXG', 'Administrador Principal', 'admin@escuela.edu', '2025-04-04 21:33:27', '2025-04-05 09:56:11', 1);

//*admin y clave es Admin1234