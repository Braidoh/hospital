CREATE DATABASE IF NOT EXISTS hospital DEFAULT CHARACTER SET "utf8" DEFAULT COLLATE "utf8_spanish_ci";
USE hospital;

/*
Persona: id_persona (PK), DNI, nombre, apellido1, apellido2, fecha_nacimiento, direccion, contacto.
Usuario: id_persona (FK), login, contrasenya, categoria (paciente, doctor o admin)
Paciente: id_persona (FK), NSS (numero de seguridad social), doctor, estado_ingresado (boolean), ubicacion (si está ingresado), familiares.
Doctor: id_persona (FK), pacientes (array de id_paciente), especialidad.
Ficha Médica: id_ficha (PK), historial(FK), id_paciente (FK), doctor (id_doctor FK), fecha, diagnostico, tratamiento, observaciones.
Historial: id_historial (PK), id_paciente (FK), grupo_sanguineo, alergias
Cita: id_cita (PK), id_paciente (FK), id_doctor (FK), fecha (datetime), motivo, observaciones.
Beeper: id (PK), receptor (FK), fecha (datetime), mensaje.
Parking: id (PK), plaza (int), ocupada (boolean), matricula.
*/

CREATE TABLE IF NOT EXISTS persona (
  id_persona INT AUTO_INCREMENT PRIMARY KEY,
  DNI VARCHAR(20) UNIQUE,
  nombre VARCHAR(50),
  apellido1 VARCHAR(50),
  apellido2 VARCHAR(50),
  fecha_nacimiento DATE,
  direccion VARCHAR(100),
  contacto VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS usuario (
  id_persona INT,
  login VARCHAR(100) UNIQUE,
  contrasenya VARCHAR(20),
  categoria VARCHAR(8),
  FOREIGN KEY (id_persona) REFERENCES persona(id_persona) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS paciente (
  id_persona INT,
  NSS VARCHAR(20),
  doctor INT,
  estado_ingresado VARCHAR(2),
  ubicacion VARCHAR(100),
  familiares TEXT,
  FOREIGN KEY (id_persona) REFERENCES persona(id_persona) ON DELETE CASCADE,
  FOREIGN KEY (doctor) REFERENCES persona(id_persona)
);

CREATE TABLE IF NOT EXISTS doctor (
  id_persona INT,
  especialidad VARCHAR(100),
  FOREIGN KEY (id_persona) REFERENCES persona(id_persona) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS historial (
  id_paciente INT PRIMARY KEY,
  grupo_sanguineo VARCHAR(3),
  alergias TEXT,
  FOREIGN KEY (id_paciente) REFERENCES persona(id_persona) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS ficha_medica (
  id_ficha INT AUTO_INCREMENT PRIMARY KEY,
  id_paciente INT,
  doctor INT,
  fecha DATE,
  diagnostico VARCHAR(50),
  tratamiento VARCHAR(50),
  observaciones TEXT,
  FOREIGN KEY (id_paciente) REFERENCES historial(id_paciente),
  FOREIGN KEY (doctor) REFERENCES persona(id_persona)
);

CREATE TABLE IF NOT EXISTS cita (
  id_cita INT AUTO_INCREMENT PRIMARY KEY,
  paciente INT,
  doctor INT,
  fecha DATETIME,
  motivo VARCHAR(50),
  observaciones TEXT,
  FOREIGN KEY (paciente) REFERENCES persona(id_persona),
  FOREIGN KEY (doctor) REFERENCES persona(id_persona)
);

CREATE TABLE IF NOT EXISTS buscapersonas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  receptor VARCHAR(3),
  mensaje TEXT,
  FOREIGN KEY (receptor) REFERENCES persona(id_persona)
);

CREATE TABLE IF NOT EXISTS parking (
  id INT AUTO_INCREMENT PRIMARY KEY,
  plaza INT,
  ocupada BOOLEAN,
  matricula_coche VARCHAR(20),
);