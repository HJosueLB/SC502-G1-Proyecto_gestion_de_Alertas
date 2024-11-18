-- Base de datos para el sistema de Gestor de Alertas
CREATE SCHEMA IF NOT EXISTS GestorAlertas;

-- Se realiza query para remplazar la base de datos en caso que ya se encuentre creada
DROP SCHEMA IF EXISTS GestorAlertas; 
DROP USER IF EXISTS 'clienteGA'@'%'; 
CREATE SCHEMA GestorAlertas;

-- Se crea usuario para utilizarse como medio de conexion con el sistema
CREATE USER 'clienteGA'@'%' IDENTIFIED BY 'usuario_GA'; 
GRANT ALL PRIVILEGES ON LightningTechnologies.* TO 'clienteGA'@'%'; 
FLUSH PRIVILEGES;

-- Tabla 'criticidad'
CREATE TABLE GestorAlertas.criticidad (
    idCriticidad INT NOT NULL AUTO_INCREMENT,
    nivel VARCHAR(20) NOT NULL,
    PRIMARY KEY (idCriticidad)
)  ENGINE=INNODB DEFAULT CHARACTER SET=UTF8MB4;

-- Insertar datos de tabla
INSERT INTO GestorAlertas.criticidad (nivel) 
VALUES 
('Crítica'),
('Media'),
('Baja');

-- Tabla 'departamentos'
CREATE TABLE GestorAlertas.departamentos (
    idDepartamento INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(30) NOT NULL,
    PRIMARY KEY (idDepartamento)
) ENGINE=INNODB DEFAULT CHARACTER SET=UTF8MB4;

-- Insertar datos de tabla
INSERT INTO GestorAlertas.departamentos (nombre) 
VALUES 
('CC360'),
('Proyectos'),
('Comercial');

-- Tabla 'puesto'
CREATE TABLE GestorAlertas.puesto (
    idPuesto INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(30) NOT NULL,
    PRIMARY KEY (idPuesto)
) ENGINE=INNODB DEFAULT CHARACTER SET=UTF8MB4;

-- Insertar datos de tabla
INSERT INTO GestorAlertas.puesto (nombre) 
VALUES 
('Analista'),
('Coordinador de CC360'),
('Gestor'),
('Vendedor');

-- Tabla 'empleado'
CREATE TABLE GestorAlertas.empleado (
    idEmpleado INT NOT NULL,
    nombre VARCHAR(30) NOT NULL,
    apellido VARCHAR(30) NOT NULL,
    correo VARCHAR(50) NOT NULL UNIQUE,
    idPuesto INT NOT NULL,
    idDepartamento INT NOT NULL,
    PRIMARY KEY (idEmpleado),
    FOREIGN KEY (idPuesto)
        REFERENCES GestorAlertas.puesto (idPuesto)
        ON DELETE CASCADE,
    FOREIGN KEY (idDepartamento)
        REFERENCES GestorAlertas.departamentos (idDepartamento)
        ON DELETE CASCADE
)  ENGINE=INNODB DEFAULT CHARACTER SET=UTF8MB4;

-- Insertar datos de tabla
INSERT INTO GestorAlertas.empleado (idEmpleado,nombre, apellido, correo, idPuesto, idDepartamento) 
VALUES 
(409850985,'Harlyn', 'Luna', 'hluna@corp.com', 1, 1),
(111345356,'Carmen', 'Fonseca Espinoza', 'cfonsecae@corp.com', 4, 3),
(111345764,'Esther', 'Murillo Calderón', 'emurilloc@corp.com', 3, 2),
(809867065,'Roberto', 'Zambrana', 'rzambrana@corp.com', 2, 1);

-- Tabla 'servicio'
CREATE TABLE GestorAlertas.servicio (
    idServicio INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    PRIMARY KEY (idServicio)
)  ENGINE=INNODB DEFAULT CHARACTER SET=UTF8MB4;

-- Insertar datos de tabla
INSERT INTO GestorAlertas.servicio (nombre) 
VALUES 
('SOC - Security Operations Center'),
('NOC - Network Operations Center');

-- Tabla 'Unidad de Negocio'
CREATE TABLE GestorAlertas.unidadNegocio (
    idUnidadNegocio INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(30) NOT NULL,
    PRIMARY KEY (idUnidadNegocio)
)  ENGINE=INNODB DEFAULT CHARACTER SET=UTF8MB4;

-- Insertar datos de tabla
INSERT INTO GestorAlertas.unidadNegocio (nombre)
VALUES 
('Componentes El Orbe S.A.'),
('Sistemas Convergentes S.A.');

-- Tabla 'cliente'
CREATE TABLE GestorAlertas.cliente (
    idCliente INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    PRIMARY KEY (idCliente)
)  ENGINE=INNODB DEFAULT CHARACTER SET=UTF8MB4;

-- Insertar datos de tabla
INSERT INTO GestorAlertas.cliente (nombre)
VALUES 
('Ministerio de Educación Pública');

-- Tabla 'proyecto'
CREATE TABLE GestorAlertas.proyecto (
    idProyecto INT NOT NULL AUTO_INCREMENT,
    idCliente INT NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    FechaInicio DATE NOT NULL,
    FechaFin DATE NOT NULL,
    tipoContrato VARCHAR(30) NOT NULL,
    idVendedor INT NOT NULL,
    idGestor INT NOT NULL,
    idUnidadNegocio INT NOT NULL,
    cobroMensual DECIMAL(10,2) DEFAULT 0.00,
    idServicio1 INT NOT NULL,
    idServicio2 INT NULL,
    PRIMARY KEY (idProyecto),
    FOREIGN KEY (idVendedor) REFERENCES GestorAlertas.empleado(idEmpleado) ON DELETE CASCADE,
    FOREIGN KEY (idGestor) REFERENCES GestorAlertas.empleado(idEmpleado) ON DELETE CASCADE,
    FOREIGN KEY (idUnidadNegocio) REFERENCES GestorAlertas.unidadNegocio(idUnidadNegocio) ON DELETE CASCADE,
    FOREIGN KEY (idServicio1) REFERENCES GestorAlertas.servicio(idServicio) ON DELETE CASCADE,
    FOREIGN KEY (idServicio2) REFERENCES GestorAlertas.servicio(idServicio) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;

-- Insertar datos de tabla
INSERT INTO GestorAlertas.proyecto (idCliente, nombre, FechaInicio, FechaFin, tipoContrato, idVendedor, idGestor, idUnidadNegocio, cobroMensual, idServicio1, idServicio2)
VALUES 
(1, 'Servicios SOC Monitoreo', '2021-07-15', '2025-07-15', 'Arrendamiento',111345356, 111345764, 1, 50000.00, 1, NULL);

-- Tabla 'Grupo de Escalacion'
CREATE TABLE GestorAlertas.grupoEscalacion (
    idGrupo INT NOT NULL AUTO_INCREMENT,
    idCliente INT NOT NULL,
    nombre VARCHAR(30) NOT NULL,
    lider_nombre VARCHAR(30) NOT NULL,
    lider_apellido VARCHAR(30) NOT NULL,
    correo VARCHAR(30) NOT NULL,
    numero_celular VARCHAR(15) NOT NULL,
    numero_oficina VARCHAR(15) NOT NULL,
    PRIMARY KEY (idGrupo),
    FOREIGN KEY (idCliente)
        REFERENCES GestorAlertas.cliente (idCliente)
        ON DELETE CASCADE
)  ENGINE=INNODB DEFAULT CHARACTER SET=UTF8MB4;

-- Insertar datos de tabla
INSERT INTO GestorAlertas.grupoEscalacion (idCliente, nombre, lider_nombre, lider_apellido, correo, numero_celular, numero_oficina)
VALUES 
(1, 'Infraestructura', 'Esteban', 'Campos Brenes', 'ecampos@mep.go.cr', '87852356', '78534587');

-- Tabla 'Alertas_procedimiento'
CREATE TABLE GestorAlertas.alertas_procedimiento (
    idAlerta INT NOT NULL AUTO_INCREMENT,
    idCliente INT NOT NULL,
    nombre  VARCHAR(100) NOT NULL,
    descripcion VARCHAR(1000) NOT NULL,
    idCriticidad INT NOT NULL,
    procedimiento VARCHAR(1000) NOT NULL,
    PRIMARY KEY (idAlerta),
    FOREIGN KEY (idCriticidad) REFERENCES GestorAlertas.criticidad(idCriticidad) ON DELETE CASCADE,
    FOREIGN KEY (idCliente) REFERENCES GestorAlertas.cliente(idCliente) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;

-- Insertar datos de tabla
INSERT INTO GestorAlertas.alertas_procedimiento (idCliente, nombre, descripcion, idCriticidad, procedimiento)
VALUES 
(1, 'Brute Force Host Login Success', 'Detecta una condición inusual en la que una fuente tiene fallas de autenticación seguidas de una autenticación exitosa en el mismo host en 15 minutos', 1, 
'Al recibir una alerta de "Brute Force Host Login Success", primero verifica los registros de autenticación para confirmar que hubo múltiples intentos fallidos seguidos de un inicio de sesión exitoso. Identifica la IP atacante y el usuario afectado. Si la IP es sospechosa, bloquea temporalmente la conexión. Cambia la contraseña del usuario afectado para evitar accesos no autorizados y revisa el sistema en busca de cambios inusuales. Es recomendable activar la autenticación de dos factores (2FA) y limitar los intentos de inicio de sesión fallidos. Procede a notificar al cliente sobre el incidente y las acciones tomadas, y recomienda que se sigan estos pasos de seguridad para mitigar futuros riesgos.');

-- Tabla 'Notificaciones'
CREATE TABLE GestorAlertas.notificaciones (
    codigoID INT NOT NULL AUTO_INCREMENT,
    idEmpleado INT NOT NULL,
    idCliente INT NOT NULL,
    idAlerta INT NOT NULL,
    descripcion VARCHAR(200) NOT NULL,
    dispositivo VARCHAR(200) NOT NULL,
    fechaNotificacion DATETIME NOT NULL,
    fechaIncidencia DATETIME NOT NULL,
    idCriticidad INT NOT NULL,
    canalComunicacion VARCHAR(30) NOT NULL,
    tiqueteSpeed_e VARCHAR(30) NULL, 
    vertical VARCHAR(30) NULL,
    PRIMARY KEY (codigoID),
    FOREIGN KEY (idEmpleado) REFERENCES GestorAlertas.empleado(idEmpleado) ON DELETE CASCADE,
    FOREIGN KEY (idCliente) REFERENCES GestorAlertas.cliente(idCliente) ON DELETE CASCADE,
    FOREIGN KEY (idAlerta) REFERENCES GestorAlertas.alertas_procedimiento(idAlerta) ON DELETE CASCADE,
    FOREIGN KEY (idCriticidad) REFERENCES GestorAlertas.criticidad(idCriticidad) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;

-- Insertar datos de tabla
INSERT INTO GestorAlertas.notificaciones (
    idEmpleado, idCliente, idAlerta, descripcion, dispositivo, 
    fechaNotificacion, fechaIncidencia, idCriticidad, canalComunicacion, 
    tiqueteSpeed_e, vertical
) 
VALUES (
    409850985, 
    1, 
    1, 
    'Alerta generada por fallo en la autenticación del servidor',
    'Correo',
    NOW(),  
    NOW(),  
    1,  
    'Correo',
    'TICKET-0001',  
    'Infraestructura'
);


