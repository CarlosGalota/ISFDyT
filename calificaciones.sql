create database system_calificaciones;

use system_calificaciones;


CREATE TABLE roles (
    id_rol INT(2) AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(50)
);
CREATE TABLE carreras (
    id_carrera INT(4) AUTO_INCREMENT PRIMARY KEY,
    nombre_carrera VARCHAR(100)
);


 CREATE TABLE usuarios (
    id_usuario INT(4) AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    apellido VARCHAR(50),
    email VARCHAR(100),
    dni INT(8),
    pass VARCHAR(255),
    rol INT(2),
    id_carrera INT(4),
    FOREIGN KEY (id_carrera) REFERENCES carreras(id_carrera),
	FOREIGN KEY (rol) REFERENCES roles(id_rol)
    
);

CREATE TABLE materias (
    id_materia INT(4) AUTO_INCREMENT PRIMARY KEY,
    nombre_materia VARCHAR(100),
    id_carrera INT(4),
    FOREIGN KEY (id_carrera) REFERENCES carreras(id_carrera)
);



-- Tabla de notas para los profesores
CREATE TABLE notas (
    id_nota INT(4) AUTO_INCREMENT PRIMARY KEY,
    id_profesor INT(4),
    id_alumno INT(2),
    nota DECIMAL(4,2),
    id_materia INT(4),
    FOREIGN KEY (id_profesor) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_alumno) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_materia) REFERENCES materias(id_materia)
);



-- Relacionar profesores con materias y carreras
CREATE TABLE profesores_materias (
    id_profesor INT(4),
    id_materia INT(4),
    FOREIGN KEY (id_profesor) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_materia) REFERENCES materias(id_materia),
    PRIMARY KEY (id_profesor, id_materia)
);

-- Relacionar alumnos con materias
CREATE TABLE alumnos_materias (
    id_alumno INT(4),
    id_materia INT(4),
    FOREIGN KEY (id_alumno) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_materia) REFERENCES materias(id_materia),
    PRIMARY KEY (id_alumno, id_materia)
);

insert into roles values (1, "profesor"), (2, "preceptor"), (3, "alumno");

insert into carreras values (1,"Profesorado de Matematicas"),
                    (2,"Profesorado de Ingles"),
                    (3,"Profesorado de ciencias naturales"),
                    (4,"Profesorado de Educacion Inicial"),
                    (5,"Profesorado de Educacion Primaria"),
                    (6,"Tecnicatura de Sistemas");
			

insert into materia values 
                    (1,"Ingles I"),
                    (2,"Ciencia Tecnologia y Sociedad"),
                    (3,"Analisis Matematico I"),
                    (4,"Algebra"),
                    (5,"Algoritmo y estructura de datos I"),
                    (6,"Sistemas y Organizaciones"),
                    (7,"Arquitectura de Computadores"),
                    (8,"Practicas Profesionalizantes I"),
					(9,"Ingles II"),
                    (10,"Analisis Matematico II"),
                    (11,"Estadisticas"),
                    (12,"Ingenieria de Software I"),
                    (13,"Algoritmo y estructura de datos II"),
                    (14,"Sistemas Operativos"),
                    (15,"Base de Datos"),
                    (16,"Practicas Profesionalizantes II"),
					(17,"Ingles III"),
                    (18,"Aspectos legales de la Profesion"),
                    (19,"Seminario de Actualizacion"),
                    (20,"Redes y Comunicaciones"),
                    (21,"Ingenieria de Software II"),
                    (22,"Algoritmo y Estructura de datos III"),
                    (23,"Practicas Profesionalizantes III");
INSERT INTO materias (nombre_materia, id_carrera) VALUES 
('Matemáticas', 1),
('Física', 1),
('Química', 3),
('Biología', 3),
('Historia', 3),
('Geografía', 3),
('Literatura', 4),
('Filosofía', 4);


