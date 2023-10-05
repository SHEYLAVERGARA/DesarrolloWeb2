# Taller 1
CREATE TABLE IF NOT EXISTS users (
                       identificacion INT NOT NULL,
                       nombres VARCHAR(30) NOT NULL,
                       apellidos VARCHAR(30),
                       fecha_nacimiento DATE NOT NULL,
                       sexo ENUM('MASCULINO', 'FEMENINO') NOT NULL,
                       email VARCHAR(100),
                       hora_registro TIME NOT NULL,
                       tiempo_accion DATETIME NOT NULL,
                       observaciones VARCHAR(255),
                       PRIMARY KEY (identificacion)
);
# end Taller 1

# Taller 2
CREATE TABLE IF NOT EXISTS ciudades (
                       id INT NOT NULL AUTO_INCREMENT,
                       nombre VARCHAR(45) NOT NULL,
                       codigo VARCHAR(45) NOT NULL,
                       PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS persona_tipos (
                       id INT NOT NULL AUTO_INCREMENT,
                       nombre VARCHAR(45) NOT NULL,
                       PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS sexos (
                       id INT NOT NULL AUTO_INCREMENT,
                       nombre VARCHAR(45) NOT NULL,
                       PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS personas (
                        id INT NOT NULL AUTO_INCREMENT,
                        ciudad_id INT NOT NULL,
                        persona_tipo_id INT NOT NULL,
                        sexo_id INT NOT NULL,
                        identificacion VARCHAR(10) NOT NULL,
                        nombres VARCHAR(45) NOT NULL,
                        apellidos VARCHAR(45) NOT NULL,
                        email VARCHAR(45) NOT NULL,
                        fecha_nacimiento DATE NOT NULL,
                        hora_registro TIME NOT NULL,
                        tiempo_evento DATETIME NOT NULL,
                        observaciones VARCHAR(255),
                        PRIMARY KEY (id),
                        CONSTRAINT fk_personas_ciudades
                        FOREIGN KEY (ciudad_id)
                        REFERENCES ciudades (id)
                        ON DELETE NO ACTION
                        ON UPDATE NO ACTION,
                        CONSTRAINT fk_personas_persona_tipos
                        FOREIGN KEY (persona_tipo_id)
                        REFERENCES persona_tipos (id)
                        ON DELETE NO ACTION
                        ON UPDATE NO ACTION,
                        CONSTRAINT fk_personas_sexos
                        FOREIGN KEY (sexo_id)
                        REFERENCES sexos (id)
                        ON DELETE NO ACTION
                        ON UPDATE NO ACTION
);
# end Taller 2

# Insertar datos en la tabla sexos, MASCULINO, FEMENINO
INSERT INTO sexos (nombre) VALUES ('MASCULINO');
INSERT INTO sexos (nombre) VALUES ('FEMENINO');

# Insertar datos en la tabla persona_tipos, ESTUDIANTE, PROFESOR, ADMINISTRATIVO
INSERT INTO persona_tipos (nombre) VALUES ('ESTUDIANTE');
INSERT INTO persona_tipos (nombre) VALUES ('PROFESOR');
INSERT INTO persona_tipos (nombre) VALUES ('ADMINISTRATIVO');

# Insertar datos en la tabla ciudades, BOGOTA, MEDELLIN, CALI, MONTERIA, SINCELEJO
INSERT INTO ciudades (nombre, codigo) VALUES ('BOGOTA', '11001');
INSERT INTO ciudades (nombre, codigo) VALUES ('MEDELLIN', '05001');
INSERT INTO ciudades (nombre, codigo) VALUES ('CALI', '76001');
INSERT INTO ciudades (nombre, codigo) VALUES ('MONTERIA', '23001');
INSERT INTO ciudades (nombre, codigo) VALUES ('SINCELEJO', '70001');
