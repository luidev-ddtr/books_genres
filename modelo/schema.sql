-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS biblioteca;
USE biblioteca;

-- Tabla libro
CREATE TABLE libro (
    id_libro INT PRIMARY KEY AUTO_INCREMENT,
    autor VARCHAR(100) NOT NULL,
    descripcion TEXT,
    titulo VARCHAR(200) NOT NULL,
    ruta_imagen LONGTEXT NOT NULL
);

-- Tabla genero
CREATE TABLE genero (
    id_genero INT PRIMARY KEY AUTO_INCREMENT,
    nombre_genero VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT
);

-- Tabla libro_genero (tabla intermedia)
CREATE TABLE libro_genero (
    id_libro INT NOT NULL,
    id_genero INT NOT NULL,
    prioridad TINYINT NOT NULL DEFAULT 1,
    PRIMARY KEY (id_libro, id_genero),
    FOREIGN KEY (id_libro) REFERENCES libro(id_libro) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_genero) REFERENCES genero(id_genero) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT chk_prioridad CHECK (prioridad BETWEEN 1 AND 3)
);

-- Insertar datos de ejemplo
INSERT INTO genero (nombre_genero, descripcion) VALUES
('Ciencia Ficción', 'Libros sobre futuros imaginarios, tecnología avanzada y espacio'),
('Fantasía', 'Obras con elementos mágicos y mundos imaginarios'),
('Romance', 'Historias centradas en relaciones amorosas'),
('Misterio', 'Narrativas con enigmas por resolver'),
('Terror', 'Obras diseñadas para causar miedo o suspense'),
('Biografía', 'Relatos de la vida de personas reales');

INSERT INTO libro (titulo, autor, descripcion) VALUES
('Fundación', 'Isaac Asimov', 'Saga sobre la caída de un imperio galáctico'),
('El Hobbit', 'J.R.R. Tolkien', 'Aventura de Bilbo Bolsón en la Tierra Media'),
('Orgullo y Prejuicio', 'Jane Austen', 'Historia de amor entre Elizabeth Bennet y Mr. Darcy');

INSERT INTO libro_genero (id_libro, id_genero, prioridad) VALUES
(1, 1, 1), -- Fundación - Ciencia Ficción (principal)
(1, 5, 2), -- Fundación - Terror (secundario)
(2, 2, 1), -- El Hobbit - Fantasía (principal)
(3, 3, 1), -- Orgullo y Prejuicio - Romance (principal)
(3, 4, 2); -- Orgullo y Prejuicio - Misterio (secundario)

-- Consultas de ejemplo
-- Libros con sus géneros y prioridad
SELECT 
    l.titulo,
    l.autor,
    g.nombre_genero,
    lg.prioridad
FROM libro l
JOIN libro_genero lg ON l.id_libro = lg.id_libro
JOIN genero g ON lg.id_genero = g.id_genero
ORDER BY l.titulo, lg.prioridad;

-- Géneros principales de cada libro
SELECT 
    l.titulo,
    l.autor,
    g.nombre_genero as genero_principal
FROM libro l
JOIN libro_genero lg ON l.id_libro = lg.id_libro
JOIN genero g ON lg.id_genero = g.id_genero
WHERE lg.prioridad = 1;