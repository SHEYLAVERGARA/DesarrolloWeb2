-- tabla usuarios --
CREATE TABLE IF NOT EXISTS usuarios  (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombres VARCHAR(255),
  apellidos VARCHAR(255),
  email VARCHAR(255),
  identificacion VARCHAR(255),
  fecha_nacimiento DATE,
  username VARCHAR(255),
  password VARCHAR(255),
  celular VARCHAR(15)
);

-- tabla cursos --
CREATE TABLE IF NOT EXISTS cursos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255),
  creditos INT
);

-- tabla unidades --
CREATE TABLE IF NOT EXISTS unidades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cursos_id INT,
  usuario_id INT,
  nombre VARCHAR(255),
  introduccion VARCHAR(255),
  fecha_creacion DATE,
  hora_creacion TIME,
  activa TINYINT,
  FOREIGN KEY (cursos_id) REFERENCES cursos(id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- tabla actividades --
CREATE TABLE IF NOT EXISTS actividades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  unidad_id INT,
  titulo VARCHAR(255),
  descripcion TEXT,
  actividadescol VARCHAR(255),
  FOREIGN KEY (unidad_id) REFERENCES unidades(id)
);
