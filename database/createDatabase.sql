-- tabla usuarios --
CREATE TABLE IF NOT EXISTS usuarios  (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombres VARCHAR(255) NOT NULL,
  apellidos VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  identificacion VARCHAR(255) NOT NULL,
  fecha_nacimiento DATE NOT NULL,
  username VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  celular VARCHAR(15) NOT NULL
);

-- tabla cursos --
CREATE TABLE IF NOT EXISTS cursos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  creditos INT NOT NULL
);

-- tabla unidades --
CREATE TABLE IF NOT EXISTS unidades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cursos_id INT NOT NULL,
  usuario_id INT NOT NULL,
  nombre VARCHAR(255) NOT NULL,
  introduccion VARCHAR(255) NOT NULL,
  fecha_creacion DATE NOT NULL,
  hora_creacion TIME NOT NULL,
  activa TINYINT NOT NULL,
  FOREIGN KEY (cursos_id) REFERENCES cursos(id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- tabla actividades --
CREATE TABLE IF NOT EXISTS actividades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  unidad_id INT NOT NULL,
  titulo VARCHAR(255) NOT NULL,
  descripcion TEXT NOT NULL,
  actividadescol VARCHAR(255) NOT NULL,
  FOREIGN KEY (unidad_id) REFERENCES unidades(id)
);


-- Insertar datos en la tabla usuarios --
INSERT INTO usuarios (nombres, apellidos, email, identificacion, fecha_nacimiento, username, password, celular)
VALUES
    ('Juan', 'Pérez', 'juan@example.com', '123456789', '1990-01-15', 'juanito123', 'contrasena123', '123-456-7890'),
    ('María', 'Gómez', 'maria@example.com', '987654321', '1988-05-22', 'maria88', 'password456', '987-654-3210'),
    ('Carlos', 'López', 'carlos@example.com', '456789012', '1995-09-10', 'carlitos', 'clave789', '456-789-0123');

-- Insertar datos en la tabla cursos --
INSERT INTO cursos (nombre, creditos)
VALUES
    ('Matemáticas', 4),
    ('Historia', 3),
    ('Programación', 5);

-- Insertar datos en la tabla unidades --
INSERT INTO unidades (cursos_id, usuario_id, nombre, introduccion, fecha_creacion, hora_creacion, activa)
VALUES
    (1, 1, 'Álgebra', 'Introducción al álgebra', '2023-01-05', '08:30:00', 1),
    (1, 2, 'Geometría', 'Introducción a la geometría', '2023-02-10', '10:15:00', 1),
    (2, 3, 'Edad Antigua', 'Introducción a la edad antigua', '2023-03-20', '14:00:00', 1);

-- Insertar datos en la tabla actividades --
INSERT INTO actividades (unidad_id, titulo, descripcion, actividadescol)
VALUES
    (1, 'Ecuaciones lineales', 'Resolver ecuaciones lineales', 'Actividad 1'),
    (1, 'Operaciones con matrices', 'Realizar operaciones con matrices', 'Actividad 2'),
    (2, 'Figuras geométricas', 'Identificar figuras geométricas', 'Actividad 3');