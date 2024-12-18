-- Base de datos para el sistema de Gestor de Alertas
CREATE SCHEMA IF NOT EXISTS GestorAlertas;

-- Se realiza query para remplazar la base de datos en caso que ya se encuentre creada
DROP SCHEMA IF EXISTS GestorAlertas; 
DROP USER IF EXISTS 'clienteGA'@'%'; 
CREATE SCHEMA GestorAlertas;

-- Se crea usuario para utilizarse como medio de conexion con el sistema
CREATE USER 'clienteGA'@'%' IDENTIFIED BY 'usuario_GA'; 
GRANT ALL PRIVILEGES ON GestorAlertas.* TO 'clienteGA'@'%'; 
FLUSH PRIVILEGES;

-- Tabla 'criticidad'
CREATE TABLE GestorAlertas.criticidad (
    idCriticidad INT NOT NULL AUTO_INCREMENT,
    nivel VARCHAR(20) NOT NULL,
    PRIMARY KEY (idCriticidad)
) ENGINE=INNODB DEFAULT CHARSET=UTF8MB4;

-- Insertar datos en la tabla
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
    contraseña VARCHAR(10) NOT NULL,
    rol ENUM('administrador') DEFAULT 'administrador' NOT NULL,
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
INSERT INTO GestorAlertas.empleado (idEmpleado,nombre, apellido, correo, contraseña, rol, idPuesto, idDepartamento) 
VALUES 
(409850985,'Harlyn', 'Luna', 'hluna@corp.com', '12345aa***', 'administrador', 1, 1),
(111345356,'Carmen', 'Fonseca Espinoza', 'cfonsecae@corp.com', '123456a***', 'administrador', 4, 3),
(111345764,'Esther', 'Murillo Calderón', 'emurilloc@corp.com', '1234567a**', 'administrador', 3, 2),
(809867065,'Roberto', 'Zambrana', 'rzambrana@corp.com', '12345678a*', 'administrador', 2, 1);

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
    correo VARCHAR(50) NOT NULL UNIQUE,
    contraseña VARCHAR(10) NOT NULL,
    rol ENUM('cliente') DEFAULT 'cliente' NOT NULL,
    PRIMARY KEY (idCliente)
)  ENGINE=INNODB DEFAULT CHARACTER SET=UTF8MB4;

-- Insertar datos de tabla
INSERT INTO GestorAlertas.cliente (nombre, correo, contraseña, rol)
VALUES 
('Jean Pool Pérez Carranza','jeanpoolperez@gmail.com', '12345qwer*', 'cliente');

-- -----------------------------------------------------------------------
-- MODULOS DE ALERTAS
-- -----------------------------------------------------------------------

-- TABLA DE PROYECTOS
CREATE TABLE gestoralertas.proyectos (
    id_Proyecto INT AUTO_INCREMENT PRIMARY KEY,
    nombreCliente VARCHAR(100) NOT NULL
);

INSERT INTO gestoralertas.proyectos (nombreCliente) VALUES ('MINISTERIO DE EDUCACIÓN PÚBLICA');

-- TABLAS E INSERTS DEL MODULO DE ALERTAS

-- TABLA DE TIPO DE SERVICIO DE MONITOREO
CREATE TABLE gestoralertas.servicio (
    id_servicio INT AUTO_INCREMENT PRIMARY KEY,
    nombre CHAR(3) NOT NULL,
    descripcion VARCHAR(100) NOT NULL
);

INSERT INTO gestoralertas.servicio (nombre, descripcion) VALUES ('NOC', 'NETWORK OPERATIONS CENTER');
INSERT INTO gestoralertas.servicio (nombre, descripcion) VALUES ('SOC', 'SECURITY OPERATIONS CENTER');

-- TABLA DE SISTEMAS DE MONITOREO
CREATE TABLE gestoralertas.sistemas (
    id_Sistema INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    id_servicio INT NOT NULL,
    FOREIGN KEY (id_servicio) REFERENCES gestoralertas.servicio(id_servicio)
);

INSERT INTO gestoralertas.sistemas (nombre, id_servicio) VALUES ('FortiSIEM', 2);
INSERT INTO gestoralertas.sistemas (nombre, id_servicio) VALUES ('PRTG Monitoring', 1);
INSERT INTO gestoralertas.sistemas (nombre, id_servicio) VALUES ('SolarWings Monitoring', 1);

-- TABLA DE LISTADO DE ALERTAS
CREATE TABLE gestoralertas.diccionarioAlertas (
    id_AlertaDiccionario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    id_Sistema INT NOT NULL,
    FOREIGN KEY (id_Sistema) REFERENCES gestoralertas.sistemas(id_Sistema)
);

INSERT INTO gestoralertas.diccionarioAlertas (nombre, id_Sistema) VALUES
('Brute Force Host Login Success', 1),
('Ping no response', 2),
('Device Unreachable', 3);

-- TABLA DE HORARIOS DE MONITOREO
CREATE TABLE gestoralertas.horario (
    id_Horario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL
);

INSERT INTO gestoralertas.horario (nombre) VALUES ('24/7');
INSERT INTO gestoralertas.horario (nombre) VALUES ('8x5');

-- TABLA DE MEDIOS DE NOTIFICACION
CREATE TABLE gestoralertas.medioNotificacion (
    id_Medio INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

INSERT INTO gestoralertas.medioNotificacion (nombre) VALUES ('Correo - Teléfono');
INSERT INTO gestoralertas.medioNotificacion (nombre) VALUES ('Correo - Mensaje Microsoft Teams');
INSERT INTO gestoralertas.medioNotificacion (nombre) VALUES ('Correo - Mensaje WhatsApp');
INSERT INTO gestoralertas.medioNotificacion (nombre) VALUES ('Solo Correo');

-- TABLA DE ALERTAS CREADAS PARA EL MONITOREO CLIENTE
CREATE TABLE gestoralertas.alertasCliente (
    id_Alerta INT AUTO_INCREMENT PRIMARY KEY,
    id_AlertaDiccionario INT NOT NULL,
    id_Criticidad INT NOT NULL,
    id_Horario INT NOT NULL,
    descripcion VARCHAR(500) NOT NULL,
    id_Medio INT NOT NULL,
    id_Proyecto INT NOT NULL,
    FOREIGN KEY (id_AlertaDiccionario) REFERENCES gestoralertas.diccionarioAlertas(id_AlertaDiccionario),
    FOREIGN KEY (id_Criticidad) REFERENCES gestoralertas.criticidad(idCriticidad),
    FOREIGN KEY (id_Horario) REFERENCES gestoralertas.horario(id_Horario),
    FOREIGN KEY (id_Medio) REFERENCES gestoralertas.medioNotificacion(id_Medio),
    FOREIGN KEY (id_Proyecto) REFERENCES gestoralertas.proyectos(id_Proyecto)
);

INSERT INTO gestoralertas.alertasCliente (
    id_AlertaDiccionario, 
    id_Criticidad, 
    id_Horario, 
    descripcion, 
    id_Medio, 
    id_Proyecto
) 
VALUES 
(1, 1, 1, 'Detecta una condición inusual en la que una fuente tiene fallas de autenticación seguidas de una autenticación exitosa en el mismo host en 15 minutos', 1, 1),
(2, 1, 1, 'Se detecta una desconexión del servidor mediante el sensor de ping. El evento ocurrió dentro de un intervalo de 10 minutos.', 1, 1);

-- PROCEDIMIENTO ALMACENADO PARA OBTENER LA ALERTA POR ID

USE gestoralertas;

DELIMITER $$

CREATE PROCEDURE P_ObtenerAlerta(IN p_idAlerta INT)
BEGIN
    SELECT 
        ac.id_Alerta AS ID_Alerta,
        da.nombre AS Nombre_Alerta,
        ac.descripcion AS Descripcion,
        c.nivel AS Criticidad,
        p.nombreCliente AS Nombre_Cliente,
        mn.nombre AS Medio_Notificacion,
        s.nombre AS Sistema,
        sv.nombre AS Servicio,
        h.nombre AS Horario
    FROM 
        alertasCliente ac
    INNER JOIN diccionarioAlertas da ON ac.id_AlertaDiccionario = da.id_AlertaDiccionario
    INNER JOIN criticidad c ON ac.id_Criticidad = c.idCriticidad
    INNER JOIN proyectos p ON ac.id_Proyecto = p.id_Proyecto
    INNER JOIN medioNotificacion mn ON ac.id_Medio = mn.id_Medio
    INNER JOIN sistemas s ON da.id_Sistema = s.id_Sistema
    INNER JOIN servicio sv ON s.id_servicio = sv.id_servicio
    INNER JOIN horario h ON ac.id_Horario = h.id_Horario
    WHERE 
        ac.id_Alerta = p_idAlerta;
END$$

DELIMITER ;

-- VISTA PARA VISUALIZAR COMO TABLA LAS ALERTAS
CREATE VIEW vista_alertas_cliente AS
SELECT 
    ac.id_Alerta AS ID_Alerta,
    da.nombre AS Nombre_Alerta,
    ac.descripcion AS Descripcion,
    c.nivel AS Criticidad,
    p.nombreCliente AS Nombre_Cliente
FROM 
    gestoralertas.alertasCliente ac
INNER JOIN gestoralertas.diccionarioAlertas da 
    ON ac.id_AlertaDiccionario = da.id_AlertaDiccionario
INNER JOIN gestoralertas.criticidad c 
    ON ac.id_Criticidad = c.idCriticidad
INNER JOIN gestoralertas.proyectos p 
    ON ac.id_Proyecto = p.id_Proyecto;
    
-- PROCEDIMIENTO ALMACENADO PARA ACTUALIZAR LA ALERTA POR ID
DELIMITER $$

CREATE PROCEDURE P_EditarAlertaCliente(
    IN p_id_Alerta INT,
    IN p_nombreAlerta VARCHAR(255),
    IN p_id_Criticidad INT,
    IN p_id_Horario INT,
    IN p_descripcion VARCHAR(500),
    IN p_id_Medio INT,
    IN p_id_Proyecto INT
)
BEGIN
    UPDATE alertasCliente
    SET 
        id_AlertaDiccionario = (SELECT id_AlertaDiccionario FROM diccionarioAlertas WHERE nombre = p_nombreAlerta),
        id_Criticidad = p_id_Criticidad,
        id_Horario = p_id_Horario,
        descripcion = p_descripcion,
        id_Medio = p_id_Medio,
        id_Proyecto = p_id_Proyecto
    WHERE id_Alerta = p_id_Alerta;
END$$

DELIMITER ;

-- PROCEDIMIENTO ALMACENADO PARA ELIMINAR UNA LA ALERTA POR ID
DELIMITER $$

CREATE PROCEDURE P_EliminarAlerta(IN p_id_Alerta INT)
BEGIN
    DELETE FROM alertasCliente
    WHERE id_Alerta = p_id_Alerta;
END$$

DELIMITER ;

-- PROCEDIMIENTO ALMACENADO PARA REGISTRAR CLIENTES
DELIMITER $$

CREATE PROCEDURE P_RegistrarAlertaCliente(
    IN p_id_AlertaDiccionario INT,
    IN p_id_Criticidad INT,
    IN p_id_Horario INT,
    IN p_descripcion VARCHAR(500),
    IN p_id_Medio INT,
    IN p_id_Proyecto INT
)
BEGIN
    INSERT INTO gestoralertas.alertasCliente (
        id_AlertaDiccionario, 
        id_Criticidad, 
        id_Horario, 
        descripcion, 
        id_Medio, 
        id_Proyecto
    )
    VALUES (
        p_id_AlertaDiccionario, 
        p_id_Criticidad, 
        p_id_Horario, 
        p_descripcion, 
        p_id_Medio, 
        p_id_Proyecto
    );
END$$

DELIMITER ;

-- PROCEDIMIENTO PARA OBTENER ALERTAS
DELIMITER $$

CREATE PROCEDURE P_ObtenerDiccionarioAlertas()
BEGIN
    SELECT id_AlertaDiccionario, nombre
    FROM diccionarioAlertas;
END $$

-- -------------------------------------------------------------------------
-- MODULO PROYECTOS
-- -----------------------------------------------------------------------

DELIMITER ;

USE gestoralertas;

CREATE TABLE vendedor( 
vendedor_id INT AUTO_INCREMENT PRIMARY KEY, 
nombre_vendedor VARCHAR(255) NOT NULL, 
activo BOOLEAN DEFAULT 1 
);

INSERT INTO vendedor (nombre_vendedor, activo) VALUES ('Juan Pérez', 1);
INSERT INTO vendedor (nombre_vendedor, activo) VALUES ('Ana Gómez', 1);
INSERT INTO vendedor (nombre_vendedor, activo) VALUES ('Carlos Rodríguez', 1);


ALTER TABLE proyectos
ADD COLUMN nombre_proyecto VARCHAR(255),
ADD COLUMN fecha_inicio DATE NULL AFTER nombre_proyecto,
ADD COLUMN fecha_fin DATE NULL AFTER fecha_inicio,
ADD COLUMN tipo_contrato VARCHAR(100) NULL AFTER fecha_fin,
ADD COLUMN gestor_contrato VARCHAR(255) NULL AFTER tipo_contrato,
ADD COLUMN cobros_mensuales DECIMAL(10,2) NULL AFTER gestor_contrato,
ADD COLUMN vendedor_id INT NULL AFTER cobros_mensuales,
ADD COLUMN idUnidadNegocio INT NULL AFTER vendedor_id,
ADD COLUMN id_servicio INT NULL AFTER idUnidadNegocio;

ALTER TABLE proyectos
ADD CONSTRAINT fk_vendedor
    FOREIGN KEY (vendedor_id) REFERENCES vendedor(vendedor_id),
ADD CONSTRAINT fk_unidadnegocio
    FOREIGN KEY (idUnidadNegocio) REFERENCES unidadnegocio(idUnidadNegocio),
ADD CONSTRAINT fk_servicio
    FOREIGN KEY (id_servicio) REFERENCES servicio(id_servicio);

CREATE OR REPLACE VIEW vw_clientes AS
SELECT idCliente, nombre
FROM cliente;

CREATE OR REPLACE VIEW vw_unidades_negocio AS
SELECT idUnidadNegocio, nombre
FROM unidadnegocio;


CREATE OR REPLACE VIEW vw_servicios AS
SELECT id_servicio, nombre
FROM servicio;


CREATE OR REPLACE VIEW vw_vendedores AS
SELECT vendedor_id, nombre_vendedor
FROM vendedor;


DELIMITER $$

CREATE PROCEDURE sp_agregar_proyecto(
    IN p_nombre_proyecto VARCHAR(255),
    IN p_fecha_inicio DATE,
    IN p_fecha_fin DATE,
    IN p_tipo_contrato VARCHAR(100),
    IN p_gestor_contrato VARCHAR(255),
    IN p_cobros_mensuales DECIMAL(10,2),
    IN p_vendedor_id INT,
    IN p_idUnidadNegocio INT,
    IN p_id_servicio INT,
    IN p_nombreCliente VARCHAR(255)
)
BEGIN
    INSERT INTO proyectos (
        nombre_proyecto,
        fecha_inicio,
        fecha_fin,
        tipo_contrato,
        gestor_contrato,
        cobros_mensuales,
        vendedor_id,
        idUnidadNegocio,
        id_servicio,
        nombreCliente
    )
    VALUES (
        p_nombre_proyecto,
        p_fecha_inicio,
        p_fecha_fin,
        p_tipo_contrato,
        p_gestor_contrato,
        p_cobros_mensuales,
        p_vendedor_id,
        p_idUnidadNegocio,
        p_id_servicio,
        p_nombreCliente
    );
END$$

DELIMITER ;

CREATE OR REPLACE VIEW vista_proyectos_detalle AS
SELECT
	p.id_Proyecto AS ID,
	p.nombre_proyecto AS NombreProyecto,
	p.fecha_inicio AS FechaInicio,
	p.fecha_fin AS FechaFin,
	p.tipo_contrato AS TipoContrato,
	v.nombre_vendedor AS Vendedor,
	p.gestor_contrato AS GestorContrato,
	u.nombre AS UnidadNegocio,
	p.cobros_mensuales AS CobrosMensuales,
	s.nombre AS Servicio
FROM
	proyectos p 
LEFT JOIN vendedor v ON p.vendedor_id = v.vendedor_id
LEFT JOIN unidadnegocio u ON p.idUnidadNegocio = u.idUnidadNegocio 
LEFT JOIN servicio s ON p.id_servicio = s.id_servicio;


DELIMITER $$ 
CREATE PROCEDURE editar_proyecto(
	IN p_id_Proyecto INT, 
	IN p_nombre_proyecto VARCHAR(255), 
	IN p_nombreCliente VARCHAR(255), 
	IN p_vendedor_id INT, 
	IN p_gestor_contrato VARCHAR(255), 
	IN p_fecha_inicio DATE, 
	IN p_fecha_fin DATE, 
	IN p_tipo_contrato VARCHAR(255), 
	IN p_idUnidadNegocio VARCHAR(255), 
	IN p_cobros_mensuales DECIMAL(10, 2), 
	IN p_id_servicio VARCHAR(255) 
) 
BEGIN
    UPDATE proyectos
    SET nombre_proyecto = p_nombre_proyecto,
        nombreCliente = p_nombreCliente,
        vendedor_id = p_vendedor_id,
        gestor_contrato = p_gestor_contrato,
        fecha_inicio = p_fecha_inicio,
        fecha_fin = p_fecha_fin,
        tipo_contrato = p_tipo_contrato,
        idUnidadNegocio = p_idUnidadNegocio,
        cobros_mensuales = p_cobros_mensuales,
         id_servicio = p_id_servicio
    WHERE id_Proyecto = p_id_Proyecto;
END$$ 

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE eliminar_proyecto(
    IN p_id_Proyecto INT
)
BEGIN
    DELETE FROM proyectos WHERE id_Proyecto = p_id_Proyecto;
END$$

DELIMITER ;

-- -----------------------------------------------------------------------
-- MODULOS DE NOTIFICACIONES
-- -----------------------------------------------------------------------

-- CREACION DE TABLA NOTIFICACIONES
CREATE TABLE gestoralertas.notificaciones (
	id_notificacion INT auto_increment primary key,
    id_Alerta INT NOT NULL,
    id_Medio INT NOT NULL,
    id_cliente INT NOT NULL,
    fechaNotificacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fechaIncidencianotificaciones DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    comentarios VARCHAR(255),
    analista VARCHAR(30), 
    dispositivo VARCHAR(30),
    contactoPersonanotificada VARCHAR(30),
    descripcion varchar(500), 
    tiqueteSpeede varchar(500)   
);

ALTER TABLE gestoralertas.notificaciones
ADD CONSTRAINT fk_id_Alerta FOREIGN KEY (id_Alerta) REFERENCES  gestoralertas.diccionarioalertas(id_AlertaDiccionario),
ADD CONSTRAINT fk_id_Medio FOREIGN KEY (id_Medio) REFERENCES gestoralertas.medionotificacion(id_Medio),
ADD CONSTRAINT fk_id_cliente FOREIGN KEY (id_cliente) REFERENCES gestoralertas.proyectos(id_Proyecto);

--PROCEDIMIENTO PARA AGREGAR NOTIFICACION
DELIMITER //
CREATE PROCEDURE sp_agregar_notificacion(
    IN p_id_Alerta INT,
    IN p_id_Medio INT,
    IN p_id_cliente INT,
    IN p_comentarios VARCHAR(255),
    IN p_analista VARCHAR(30),
    IN p_dispositivo VARCHAR(30),
    IN p_contactoPersonaNotificada VARCHAR(30),
    IN p_descripcion VARCHAR(500),
    IN p_tiqueteSpeede VARCHAR(500)
)
BEGIN
    INSERT INTO gestoralertas.notificaciones (
        id_Alerta, id_Medio, id_cliente, comentarios, analista, dispositivo,
        contactoPersonanotificada, descripcion, tiqueteSpeede
    ) VALUES (
        p_id_Alerta, p_id_Medio, p_id_cliente, p_comentarios, p_analista, p_dispositivo,
        p_contactoPersonaNotificada, p_descripcion, p_tiqueteSpeede
    );
END //
DELIMITER ;

--PROCEDIMIENTO PARA EDITAR
DELIMITER //
CREATE PROCEDURE sp_editar_notificacion(
    IN p_id_notificacion INT,
    IN p_comentarios VARCHAR(255),
    IN p_analista VARCHAR(30),
    IN p_dispositivo VARCHAR(30),
    IN p_contactoPersonaNotificada VARCHAR(30),
    IN p_descripcion VARCHAR(500),
    IN p_tiqueteSpeede VARCHAR(500)
)
BEGIN
    UPDATE gestoralertas.notificaciones
    SET comentarios = p_comentarios,
        analista = p_analista,
        dispositivo = p_dispositivo,
        contactoPersonanotificada = p_contactoPersonaNotificada,
        descripcion = p_descripcion,
        tiqueteSpeede = p_tiqueteSpeede
    WHERE id_notificacion = p_id_notificacion;
END //
DELIMITER ;

--PROCEDIMIENTO PARA ELIMINAR
DELIMITER //
CREATE PROCEDURE sp_eliminar_notificacion(
    IN p_id_notificacion INT
)
BEGIN
    DELETE FROM gestoralertas.notificaciones WHERE id_notificacion = p_id_notificacion;
END //
DELIMITER ;

