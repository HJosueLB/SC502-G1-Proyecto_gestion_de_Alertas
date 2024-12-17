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



------------------------------------------
-- MODULOS DE ALERTAS
------------------------------------------


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

USE gestoralertas;


-- PROCEDIMIENTO ALMACENADO PARA OBTENER LA ALERTA POR ID
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

-- PROCEDIMIENTO ALMACENADO PARA OBTENER LA ALERTA POR ID

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
    
    
-- VISTA PARA VISUALIZAR vista_notificaciones
CREATE VIEW vista_notificaciones AS
SELECT 
    n.codigo_id AS Codigo_ID,
    n.marca_temporal AS Fecha_Incidencia,
    n.analista AS Analista,
    p.nombreCliente AS Cliente,
    da.nombre AS Alerta
FROM 
    gestoralertas.notificaciones n
INNER JOIN gestoralertas.alertasCliente ac 
    ON n.codigo_id = ac.codigoAlertaCliente
INNER JOIN gestoralertas.diccionarioAlertas da 
    ON ac.id_AlertaDiccionario = da.id_AlertaDiccionario
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

DELIMITER $$

-- PROCEDIMIENTO ALMACENADO PARA REGISTRAR CLIENTES
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

DELIMITER ;

-- alert tabñe
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

foreign key (id_Alerta) references gestoralertas.diccionarioalertas(id_AlertaDiccionario),
foreign key (id_Medio) references gestoralertas.medionotificacion(id_Medio),
foreign key (id_cliente) references gestoralertas.proyectos(id_Proyecto)   
);


-- Notifications table
 USE GestorAlertas; 

DELIMITER $$
CREATE TABLE alertasCliente (
    id_Alerta INT AUTO_INCREMENT PRIMARY KEY,
    id_AlertaDiccionario INT NOT NULL,
    id_Criticidad INT NOT NULL,
    id_Proyecto INT NOT NULL,
    id_Medio INT NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


DELIMITER $$

CREATE PROCEDURE P_CRUD_Notifications(
    IN p_action VARCHAR(10),         -- Acción a realizar: 'CREATE', 'READ', 'UPDATE', 'DELETE'
    IN p_id_notification INT,        -- ID de la notificación (requerido para UPDATE y DELETE)
    IN p_id_alert INT,               -- ID de la alerta
    IN p_id_client INT,              -- ID del cliente
    IN p_id_communication_channel INT, -- ID del canal de comunicación
    IN p_description TEXT,           -- Descripción de la notificación
    IN p_notification_date DATETIME, -- Fecha de la notificación
    IN p_incident_date DATETIME,     -- Fecha del incidente
    IN p_comments TEXT               -- Comentarios adicionales
)
BEGIN
    -- Use CASE para el manejo de acciones
    CASE
        -- Operación CREATE
        WHEN p_action = 'CREATE' THEN
            INSERT INTO notifications (
                id_alert, id_client, id_communication_channel, description, 
                notification_date, incident_date, comments
            ) VALUES (
                p_id_alert, p_id_client, p_id_communication_channel, p_description, 
                p_notification_date, p_incident_date, p_comments
            );

        -- Operación READ
        WHEN p_action = 'READ' THEN
            IF p_id_notification IS NULL THEN
                SELECT * FROM notifications; -- Recuperar todas las notificaciones
            ELSE
                SELECT * FROM notifications WHERE id_notification = p_id_notification; -- Recuperar una notificación específica
            END IF;

        -- Operación UPDATE
        WHEN p_action = 'UPDATE' THEN
            UPDATE notifications
            SET 
                id_alert = p_id_alert,
                id_client = p_id_client,
                id_communication_channel = p_id_communication_channel,
                description = p_description,
                notification_date = p_notification_date,
                incident_date = p_incident_date,
                comments = p_comments
            WHERE id_notification = p_id_notification;

        -- Operación DELETE
        WHEN p_action = 'DELETE' THEN
            DELETE FROM notifications
            WHERE id_notification = p_id_notification;

        ELSE
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Acción inválida especificada para P_CRUD_Notifications';
    END CASE;
END $$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_insertarNotificacion (
    IN p_codigo_id VARCHAR(20),
    IN p_marca_temporal DATETIME,
    IN p_analista VARCHAR(100),
    IN p_cliente VARCHAR(100),
    IN p_alerta VARCHAR(100),
    IN p_descripcion TEXT,
    IN p_id_medio INT
)
BEGIN
    INSERT INTO gestoralertas.notificaciones (
        codigo_id, 
        marca_temporal, 
        analista, 
        cliente, 
        alerta, 
        descripcion, 
        id_medio
    )
    VALUES (
        p_codigo_id, 
        p_marca_temporal, 
        p_analista, 
        p_cliente, 
        p_alerta, 
        p_descripcion, 
        p_id_medio
    );
END //

DELIMITER ;

CALL sp_insertarNotificacion(
    'NOT-0000001', 
    '2024-12-15 10:30:00', 
    'Harlyn Josue Luna Brenes', 
    'Ministerio de Educación Pública', 
    'Brute Force Host Login Success', 
    'MEP - FORTISIEM - Brute Force Host Login Success', 
    1
);
