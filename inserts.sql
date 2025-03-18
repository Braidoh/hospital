/*
Persona: id_persona (PK), DNI, nombre, apellido1, apellido2, fecha_nacimiento, direccion, contacto.
Usuario: id_persona (FK), login, contrasenya, categoria (paciente, doctor o admin)
Paciente: id_persona (FK), NSS (numero de seguridad social), doctor, estado_ingresado (boolean), ubicacion (si está ingresado), familiares.
Doctor: id_persona (FK), especialidad.
Ficha Médica: id_ficha (PK), historial(FK), id_paciente (FK), doctor (id_doctor FK), fecha, diagnostico, tratamiento, observaciones.
Historial: id_historial (PK), id_paciente (FK), grupo_sanguineo, alergias
Cita: id_cita (PK), id_paciente (FK), id_doctor (FK), fecha (datetime), motivo, observaciones.
*/

USE hospital;

INSERT INTO persona VALUES (NULL, '12345678A', 'Usuario', 'Test', 'Admin', '2000-01-01', 'FalsitoLandia', 'admin@gmail.com');
INSERT INTO usuario VALUES (1, 'admin', '1234', 'admin');
INSERT INTO persona VALUES (NULL, '12345678A', 'Usuario', 'Test', 'Admin', '2000-01-01', 'FalsitoLandia', 'admin@gmail.com');


INSERT INTO usuario VALUES (LAST_INSERT_ID(), 'test', 'test', 'guest');

INSERT INTO paciente VALUES (1, '122333', NULL, 0, NULL, NULL);
INSERT INTO paciente VALUES (2, '122333', NULL, 1, 'ala_norte', NULL);

INSERT INTO historial VALUES (NULL, LAST_INSERT_ID(), 'A', 'Ninguna');

INSERT INTO ficha_medica VALUES (NULL, LAST_INSERT_ID(), LAST_INSERT_ID(), NULL, '2000-01-01', NULL, NULL, NULL);

INSERT INTO cita VALUES (NULL, LAST_INSERT_ID(), 1, '2000-01-01', 'Test', NULL);

INSERT INTO doctor VALUES (LAST_INSERT_ID(), 'Nothing');

UPDATE usuario SET categoria = 'admin' WHERE id_persona = 1;

