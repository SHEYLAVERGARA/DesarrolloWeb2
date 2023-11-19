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