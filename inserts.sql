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

INSERT INTO persona VALUES (1, '12345678A', 'Usuario', 'Test', 'Admin', '2000-01-01', 'Oleopolis', 'admin@gmail.com');
INSERT INTO usuario VALUES (1, 'admin', '1234', 'admin');
INSERT INTO usuario VALUES (1, 'doctor', '1234', 'doctor');
INSERT INTO usuario VALUES (1, 'paciente', '1234', 'paciente');

INSERT INTO persona VALUES (2, '12345678B', 'Pepe', 'Albares', 'Doctor', '2000-01-01', 'Fariopolis', 'pepealbares@gmail.com');
INSERT INTO usuario VALUES (2, 'goldman', '1234', 'doctor');

INSERT INTO persona VALUES (3, '12345678C', 'Marta', 'Penelope', 'Paciente', '2000-01-01', 'Caleopolis', 'martapenelope@gmail.com');
INSERT INTO usuario VALUES (3, 'aries', '1234', 'paciente');

INSERT INTO persona VALUES (4, '12345678D', 'Juan', 'Lopez', 'Paciente', '2000-01-01', 'Poliopolis', 'juanlopez@gmail.com');

INSERT INTO paciente VALUES (1, '122333', NULL, 'si', 'habitacion_0', NULL);
INSERT INTO paciente VALUES (2, '123456', NULL, 'no', NULL, NULL);
INSERT INTO paciente VALUES (3, '313131', NULL, 'si', 'habitacion_1', NULL);

INSERT INTO historial VALUES (1,'A', 'Ninguna');
INSERT INTO historial VALUES (2,'B', 'Frutos secos');
INSERT INTO historial VALUES (3,'O', 'Ninguna');

INSERT INTO ficha_medica VALUES (1, 1, 2, '2000-01-01', 'Trauma craneal', 'Cirugia', 'El paciente presenta un fuerte dolor de cabeza.');
INSERT INTO ficha_medica VALUES (2, 2, 1, '2000-01-02', 'Cáncer de páncreas', 'Quimioterapia', 'El paciente presenta un fuerte dolor de estomago.');
INSERT INTO ficha_medica VALUES (3, 3, 2, '2000-01-03', 'Femur roto', 'Escayola', NULL);
INSERT INTO ficha_medica VALUES (3, 4, 2, '2000-01-04', 'Insuficiencia renal crónica', 'Diálisis, control de dieta y medicación antihipertensiva', 'El paciente requiere seguimiento mensual y evaluación para trasplante renal.');

INSERT INTO cita VALUES (1, 1, 2, '2000-01-01', 'Dolor de cabeza', 'El paciente presenta un fuerte dolor de cabeza.');
INSERT INTO cita VALUES (2, 2, 1, '2000-01-02', 'Dolor de estomago', 'El paciente presenta un fuerte dolor de estomago.');
INSERT INTO cita VALUES (3, 3, 2, '2000-01-03', 'Laringitis', 'El paciente presenta un fuerte dolor de garganta.');
INSERT INTO cita VALUES (4, 4, 2, '2000-01-04', 'Fiebre', 'El paciente presenta fiebre.');