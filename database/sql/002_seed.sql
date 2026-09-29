USE `colegio_santa_maria`;

INSERT INTO `usuarios` (`nombres`, `email`, `password`, `rol`, `estado`) VALUES
('Administrador', 'admin@santamariatumbes.edu.pe', '$2y$12$8h9fH6H9m8F0b4x0mP2AsO4I9E8cC4K7h3a9S3uM6kJ5YsvlXbV6', 'administrador', 'activo');

INSERT INTO `configuracion` (`clave`, `valor`) VALUES
('nombre_colegio', 'Institucion Educativa Particular Santa Maria de la Frontera'),
('lema', 'Todo por Jesus y en espiritu de reparacion'),
('direccion', 'Tumbes, Peru'),
('telefono', ''),
('email_contacto', ''),
('mision', ''),
('vision', '');

INSERT INTO `menus` (`titulo`, `slug`, `orden`) VALUES
('Inicio', 'inicio', 1),
('Institucional', 'institucional', 2),
('Academico', 'academico', 3),
('Niveles formativos', 'niveles-formativos', 4),
('Noticias y actualidad', 'noticias', 5),
('Servicios', 'servicios', 6),
('Ex alumnos', 'ex-alumnos', 7),
('Contacto', 'contacto', 8);

INSERT INTO `niveles` (`nombre`, `orden`) VALUES
('Inicial', 1),
('Primaria', 2),
('Secundaria', 3);

INSERT INTO `categorias_noticias` (`nombre`, `slug`) VALUES
('Institucional', 'institucional'),
('Academico', 'academico'),
('Pastoral', 'pastoral'),
('Deportes', 'deportes'),
('Fiestas congregacion', 'fiestas-congregacion');

INSERT INTO `enlaces` (`nombre`, `url`, `tipo`, `orden`) VALUES
('Ministerio de Educacion', 'https://www.gob.pe/minedu', 'institucional', 1),
('PerúEduca', 'https://www.perueduca.pe', 'institucional', 2),
('Pronabec', 'https://www.gob.pe/pronabec', 'institucional', 3),
('SiseVe', 'https://www.siseve.pe', 'institucional', 4),
('Siagie', 'https://siagie.minedu.gob.pe', 'institucional', 5),
('Senaju', 'https://www.gob.pe/senaju', 'institucional', 6),
('Facebook', 'https://www.facebook.com/', 'red_social', 1),
('YouTube', 'https://www.youtube.com/', 'red_social', 2);

INSERT INTO `galerias` (`titulo`) VALUES
('Ingreso'),
('Pastoral'),
('Fiestas congregacion');
