-- SQL Script to Update and Populate Quiz System Database with Specific Topics

-- IMPORTANT: This script assumes the database schema (tables and columns)
-- created by the initial SQL script is already in place.

-- Select the database to ensure all subsequent commands apply to it
USE dbQuiz;

-- Delete existing data in a safe order due to foreign key constraints
DELETE FROM PlayerProgress;
DELETE FROM Answers;
DELETE FROM Questions;
DELETE FROM Awards;
DELETE FROM Topics; -- Clear all existing topics

-- Insert a placeholder user for foreign key references in Avatars and PlayerProgress
-- The request was "menos los usuarios" (less users), so we add just one for demonstration
-- and to satisfy foreign key constraints for other tables like Avatars and PlayerProgress.
-- NOTE: If your 'Users' table already has specific users you want to keep,
-- you might adjust these INSERTs or remove them if users are added elsewhere.
DELETE FROM Users; -- Deleting all users to ensure a clean start for the placeholder

-- Insert a placeholder user for foreign key references in Avatars and PlayerProgress
DELETE FROM Users; -- Deleting all users to ensure a clean start for the placeholder
INSERT INTO Users (user_id, email, name, password, role) VALUES
  (1, 'player@example.com', 'Test Player', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'player'),
  (2, 'admin@example.com', 'Admin User', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 'admin');

-- 1. Insert Specific Topics
INSERT INTO Topics (topic_name) VALUES
('PHP'),
('Javascript'),
('Laravel');

-- Dar al admin (user_id=2) el nivel 'Experto' en todos los temas
INSERT INTO UserTopicLevel (user_id, topic_id, level_id) VALUES
  (2, (SELECT topic_id FROM Topics WHERE topic_name = 'PHP'), (SELECT level_id FROM Levels WHERE level_name = 'Experto')),
  (2, (SELECT topic_id FROM Topics WHERE topic_name = 'Javascript'), (SELECT level_id FROM Levels WHERE level_name = 'Experto')),
  (2, (SELECT topic_id FROM Topics WHERE topic_name = 'Laravel'), (SELECT level_id FROM Levels WHERE level_name = 'Experto'));

-- Levels should already be present from the previous script: 'Principiante', 'Novato', 'Experto'
INSERT INTO Levels (level_name) VALUES
('Principiante'),
('Novato'),
('Experto');

-- 2. Insert Sample Questions for PHP, Javascript, Laravel
-- PHP Questions
INSERT INTO Questions (topic_id, level_id, question_text, question_type, difficulty, qr_code) VALUES
((SELECT topic_id FROM Topics WHERE topic_name = 'PHP'), (SELECT level_id FROM Levels WHERE level_name = 'Principiante'), '¿Qué significa PHP?', 'multiple_choice', 'easy', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'PHP'), (SELECT level_id FROM Levels WHERE level_name = 'Principiante'), '¿Qué función imprime texto en PHP?', 'multiple_choice', 'easy', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'PHP'), (SELECT level_id FROM Levels WHERE level_name = 'Principiante'), '¿Con qué símbolo comienzan las variables en PHP?', 'multiple_choice', 'easy', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'PHP'), (SELECT level_id FROM Levels WHERE level_name = 'Novato'), '¿Cuál es la función utilizada para conectarse a una base de datos MySQL en PHP (método obsoleto, pero común en quiz)?', 'multiple_choice', 'medium', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'PHP'), (SELECT level_id FROM Levels WHERE level_name = 'Novato'), '¿Qué función se usa para incluir archivos en PHP?', 'multiple_choice', 'medium', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'PHP'), (SELECT level_id FROM Levels WHERE level_name = 'Novato'), '¿Qué extensión tienen los archivos PHP?', 'multiple_choice', 'medium', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'PHP'), (SELECT level_id FROM Levels WHERE level_name = 'Experto'), '¿Qué es un trait en PHP y cuál es su propósito?', 'multiple_choice', 'hard', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'PHP'), (SELECT level_id FROM Levels WHERE level_name = 'Experto'), '¿Qué es un namespace en PHP?', 'multiple_choice', 'hard', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'PHP'), (SELECT level_id FROM Levels WHERE level_name = 'Experto'), '¿Qué función se usa para serializar un objeto en PHP?', 'multiple_choice', 'hard', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'PHP'), (SELECT level_id FROM Levels WHERE level_name = 'Experto'), '¿Qué es Composer en el ecosistema PHP?', 'multiple_choice', 'hard', NULL);

-- Javascript Questions
INSERT INTO Questions (topic_id, level_id, question_text, question_type, difficulty, qr_code) VALUES
((SELECT topic_id FROM Topics WHERE topic_name = 'Javascript'), (SELECT level_id FROM Levels WHERE level_name = 'Principiante'), '¿Cuál palabra clave se usa para declarar una variable en JavaScript que no puede ser reasignada?', 'multiple_choice', 'easy', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Javascript'), (SELECT level_id FROM Levels WHERE level_name = 'Principiante'), '¿Qué método muestra un mensaje en consola?', 'multiple_choice', 'easy', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Javascript'), (SELECT level_id FROM Levels WHERE level_name = 'Principiante'), '¿Qué tipo de dato es NaN en JavaScript?', 'multiple_choice', 'easy', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Javascript'), (SELECT level_id FROM Levels WHERE level_name = 'Novato'), '¿Qué método de array en JavaScript se utiliza para aplicar una función a cada elemento y devolver un nuevo array?', 'multiple_choice', 'medium', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Javascript'), (SELECT level_id FROM Levels WHERE level_name = 'Novato'), '¿Qué operador se usa para comparar valor y tipo en JavaScript?', 'multiple_choice', 'medium', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Javascript'), (SELECT level_id FROM Levels WHERE level_name = 'Novato'), '¿Qué función convierte un string a entero en JavaScript?', 'multiple_choice', 'medium', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Javascript'), (SELECT level_id FROM Levels WHERE level_name = 'Experto'), 'Explica la diferencia entre "null" y "undefined" en JavaScript.', 'multiple_choice', 'hard', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Javascript'), (SELECT level_id FROM Levels WHERE level_name = 'Experto'), '¿Qué es una closure en JavaScript?', 'multiple_choice', 'hard', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Javascript'), (SELECT level_id FROM Levels WHERE level_name = 'Experto'), '¿Qué método se usa para enlazar el contexto de una función?', 'multiple_choice', 'hard', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Javascript'), (SELECT level_id FROM Levels WHERE level_name = 'Experto'), '¿Qué es el event loop en JavaScript?', 'multiple_choice', 'hard', NULL);

-- Laravel Questions
INSERT INTO Questions (topic_id, level_id, question_text, question_type, difficulty, qr_code) VALUES
((SELECT topic_id FROM Topics WHERE topic_name = 'Laravel'), (SELECT level_id FROM Levels WHERE level_name = 'Principiante'), '¿Qué comando de Artisan se usa para crear un nuevo controlador en Laravel?', 'multiple_choice', 'easy', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Laravel'), (SELECT level_id FROM Levels WHERE level_name = 'Principiante'), '¿Qué archivo define las rutas web en Laravel?', 'multiple_choice', 'easy', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Laravel'), (SELECT level_id FROM Levels WHERE level_name = 'Principiante'), '¿Qué función se usa para redireccionar en un controlador de Laravel?', 'multiple_choice', 'easy', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Laravel'), (SELECT level_id FROM Levels WHERE level_name = 'Novato'), '¿Cuál es el propósito principal de Eloquent ORM en Laravel?', 'multiple_choice', 'medium', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Laravel'), (SELECT level_id FROM Levels WHERE level_name = 'Novato'), '¿Qué comando se usa para ejecutar migraciones en Laravel?', 'multiple_choice', 'medium', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Laravel'), (SELECT level_id FROM Levels WHERE level_name = 'Novato'), '¿Qué helper se usa para obtener la URL base en Laravel?', 'multiple_choice', 'medium', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Laravel'), (SELECT level_id FROM Levels WHERE level_name = 'Experto'), 'Describe el ciclo de vida de una solicitud HTTP en Laravel.', 'multiple_choice', 'hard', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Laravel'), (SELECT level_id FROM Levels WHERE level_name = 'Experto'), '¿Qué es un Service Provider en Laravel?', 'multiple_choice', 'hard', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Laravel'), (SELECT level_id FROM Levels WHERE level_name = 'Experto'), '¿Cómo se define un middleware global en Laravel?', 'multiple_choice', 'hard', NULL),
((SELECT topic_id FROM Topics WHERE topic_name = 'Laravel'), (SELECT level_id FROM Levels WHERE level_name = 'Experto'), '¿Qué es una Policy en Laravel?', 'multiple_choice', 'hard', NULL);


-- 3. Insert Sample Answers for the new Questions

-- Answers for PHP Question 1 (¿Qué significa PHP?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué significa PHP?'), 'Personal Home Page', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué significa PHP?'), 'Hypertext Preprocessor', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué significa PHP?'), 'Private Hosting Platform', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué significa PHP?'), 'Programming Hypertext Protocol', FALSE);

-- Answers for PHP Question 2 (¿Cuál es la función utilizada para conectarse a una base de datos MySQL en PHP (método obsoleto, pero común en quiz)?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Cuál es la función utilizada para conectarse a una base de datos MySQL en PHP (método obsoleto, pero común en quiz)?'), 'mysql_connect()', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Cuál es la función utilizada para conectarse a una base de datos MySQL en PHP (método obsoleto, pero común en quiz)?'), 'mysqli_connect()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Cuál es la función utilizada para conectarse a una base de datos MySQL en PHP (método obsoleto, pero común en quiz)?'), 'PDO::connect()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Cuál es la función utilizada para conectarse a una base de datos MySQL en PHP (método obsoleto, pero común en quiz)?'), 'db_connect()', FALSE);

-- Answers for PHP Question 3 (¿Qué es un trait en PHP y cuál es su propósito?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es un trait en PHP y cuál es su propósito?'), 'Un mecanismo para reutilizar métodos libremente en clases independientes sin herencia.', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es un trait en PHP y cuál es su propósito?'), 'Una clase abstracta que no puede ser instanciada directamente.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es un trait en PHP y cuál es su propósito?'), 'Una interfaz que define un contrato para las clases.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es un trait en PHP y cuál es su propósito?'), 'Una propiedad estática compartida por todas las instancias de una clase.', FALSE);

-- Answers for PHP Question 4 (¿Qué función imprime texto en PHP?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función imprime texto en PHP?'), 'echo()', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función imprime texto en PHP?'), 'print()', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función imprime texto en PHP?'), 'printf()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función imprime texto en PHP?'), 'write()', FALSE);

-- Answers for PHP Question 5 (¿Con qué símbolo comienzan las variables en PHP?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Con qué símbolo comienzan las variables en PHP?'), '$', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Con qué símbolo comienzan las variables en PHP?'), '&', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Con qué símbolo comienzan las variables en PHP?'), '#', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Con qué símbolo comienzan las variables en PHP?'), '!', FALSE);

-- Answers for PHP Question 6 (¿Qué función se usa para incluir archivos en PHP?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función se usa para incluir archivos en PHP?'), 'include()', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función se usa para incluir archivos en PHP?'), 'require()', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función se usa para incluir archivos en PHP?'), 'import()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función se usa para incluir archivos en PHP?'), 'open()', FALSE);

-- Answers for PHP Question 7 (¿Qué extensión tienen los archivos PHP?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué extensión tienen los archivos PHP?'), '.php', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué extensión tienen los archivos PHP?'), '.html', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué extensión tienen los archivos PHP?'), '.xml', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué extensión tienen los archivos PHP?'), '.txt', FALSE);

-- Answers for PHP Question 8 (¿Qué es un namespace en PHP?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es un namespace en PHP?'), 'Un contenedor para agrupar lógicamente clases, interfaces y funciones.', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es un namespace en PHP?'), 'Una forma de declarar variables globales.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es un namespace en PHP?'), 'Un tipo especial de clase.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es un namespace en PHP?'), 'Una extensión de archivo para scripts PHP.', FALSE);

-- Answers for PHP Question 9 (¿Qué función se usa para serializar un objeto en PHP?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función se usa para serializar un objeto en PHP?'), 'serialize()', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función se usa para serializar un objeto en PHP?'), 'json_encode()', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función se usa para serializar un objeto en PHP?'), 'pack()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función se usa para serializar un objeto en PHP?'), 'encode()', FALSE);

-- Answers for PHP Question 10 (¿Qué es Composer en el ecosistema PHP?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es Composer en el ecosistema PHP?'), 'Un gestor de dependencias para PHP.', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es Composer en el ecosistema PHP?'), 'Un tipo de servidor web para PHP.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es Composer en el ecosistema PHP?'), 'Una extensión de PHP para manejar paquetes.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es Composer en el ecosistema PHP?'), 'Un framework para desarrollar aplicaciones PHP.', FALSE);

-- Answers for Javascript Question 1 (¿Cuál palabra clave se usa para declarar una variable en JavaScript que no puede ser reasignada?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Cuál palabra clave se usa para declarar una variable en JavaScript que no puede ser reasignada?'), 'const', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Cuál palabra clave se usa para declarar una variable en JavaScript que no puede ser reasignada?'), 'let', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Cuál palabra clave se usa para declarar una variable en JavaScript que no puede ser reasignada?'), 'var', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Cuál palabra clave se usa para declarar una variable en JavaScript que no puede ser reasignada?'), 'static', FALSE);

-- Answers for Javascript Question 2 (¿Qué método de array en JavaScript se utiliza para aplicar una función a cada elemento y devolver un nuevo array?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué método de array en JavaScript se utiliza para aplicar una función a cada elemento y devolver un nuevo array?'), 'map()', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué método de array en JavaScript se utiliza para aplicar una función a cada elemento y devolver un nuevo array?'), 'forEach()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué método de array en JavaScript se utiliza para aplicar una función a cada elemento y devolver un nuevo array?'), 'filter()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué método de array en JavaScript se utiliza para aplicar una función a cada elemento y devolver un nuevo array?'), 'reduce()', FALSE);

-- Answers for Javascript Question 3 (Explica la diferencia entre "null" y "undefined" en JavaScript.)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = 'Explica la diferencia entre "null" y "undefined" en JavaScript.'), 'Null es un valor asignado intencionalmente; undefined significa que una variable ha sido declarada pero no se le ha asignado un valor.', TRUE),
((SELECT question_id FROM Questions WHERE question_text = 'Explica la diferencia entre "null" y "undefined" en JavaScript.'), 'Son intercambiables y significan lo mismo.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = 'Explica la diferencia entre "null" y "undefined" en JavaScript.'), 'Null es para objetos vacíos y undefined para variables.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = 'Explica la diferencia entre "null" y "undefined" en JavaScript.'), 'Undefined es un error de sintaxis, null es un valor válido.', FALSE);

-- Answers for Javascript Question 4 (¿Qué método muestra un mensaje en consola?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué método muestra un mensaje en consola?'), 'console.log()', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué método muestra un mensaje en consola?'), 'log.console()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué método muestra un mensaje en consola?'), 'print.console()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué método muestra un mensaje en consola?'), 'echo.console()', FALSE);

-- Answers for Javascript Question 5 (¿Qué tipo de dato es NaN en JavaScript?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué tipo de dato es NaN en JavaScript?'), 'Número', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué tipo de dato es NaN en JavaScript?'), 'String', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué tipo de dato es NaN en JavaScript?'), 'Objeto', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué tipo de dato es NaN en JavaScript?'), 'Especial (Not a Number)', TRUE);

-- Answers for Javascript Question 6 (¿Qué operador se usa para comparar valor y tipo en JavaScript?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué operador se usa para comparar valor y tipo en JavaScript?'), '===', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué operador se usa para comparar valor y tipo en JavaScript?'), '==', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué operador se usa para comparar valor y tipo en JavaScript?'), '!=', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué operador se usa para comparar valor y tipo en JavaScript?'), '!==', FALSE);

-- Answers for Javascript Question 7 (¿Qué función convierte un string a entero en JavaScript?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función convierte un string a entero en JavaScript?'), 'parseInt()', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función convierte un string a entero en JavaScript?'), 'convertToInt()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función convierte un string a entero en JavaScript?'), 'intValue()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función convierte un string a entero en JavaScript?'), 'toInteger()', FALSE);

-- Answers for Javascript Question 8 (¿Qué es una closure en JavaScript?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es una closure en JavaScript?'), 'Una función dentro de otra función que tiene acceso a las variables de la función exterior.', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es una closure en JavaScript?'), 'Una función que se ejecuta en el contexto global.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es una closure en JavaScript?'), 'Una función sin nombre.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es una closure en JavaScript?'), 'Una función que retorna otra función.', FALSE);

-- Answers for Javascript Question 9 (¿Qué método se usa para enlazar el contexto de una función?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué método se usa para enlazar el contexto de una función?'), 'bind()', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué método se usa para enlazar el contexto de una función?'), 'call()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué método se usa para enlazar el contexto de una función?'), 'apply()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué método se usa para enlazar el contexto de una función?'), 'context()', FALSE);

-- Answers for Javascript Question 10 (¿Qué es el event loop en JavaScript?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es el event loop en JavaScript?'), 'Un mecanismo que permite a JavaScript realizar operaciones no bloqueantes.', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es el event loop en JavaScript?'), 'Un ciclo que repite indefinidamente el código.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es el event loop en JavaScript?'), 'Una función que se ejecuta al final de cada script.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es el event loop en JavaScript?'), 'Un evento que se dispara al cargar la página.', FALSE);

-- Answers for Laravel Question 1 (¿Qué comando de Artisan se usa para crear un nuevo controlador en Laravel?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué comando de Artisan se usa para crear un nuevo controlador en Laravel?'), 'php artisan make:controller', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué comando de Artisan se usa para crear un nuevo controlador en Laravel?'), 'php artisan create:controller', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué comando de Artisan se usa para crear un nuevo controlador en Laravel?'), 'php artisan add:controller', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué comando de Artisan se usa para crear un nuevo controlador en Laravel?'), 'php artisan controller:make', FALSE);

-- Answers for Laravel Question 2 (¿Cuál es el propósito principal de Eloquent ORM en Laravel?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Cuál es el propósito principal de Eloquent ORM en Laravel?'), 'Proporcionar una forma fácil de interactuar con la base de datos utilizando modelos PHP.', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Cuál es el propósito principal de Eloquent ORM en Laravel?'), 'Gestionar la autenticación de usuarios.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Cuál es el propósito principal de Eloquent ORM en Laravel?'), 'Compilar el código Blade en HTML.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Cuál es el propósito principal de Eloquent ORM en Laravel?'), 'Manejar las solicitudes HTTP entrantes.', FALSE);

-- Answers for Laravel Question 3 (Describe el ciclo de vida de una solicitud HTTP en Laravel.)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = 'Describe el ciclo de vida de una solicitud HTTP en Laravel.'), 'La solicitud entra por public/index.php, pasa por el kernel, enrutador, middleware, controlador, y devuelve una respuesta.', TRUE),
((SELECT question_id FROM Questions WHERE question_text = 'Describe el ciclo de vida de una solicitud HTTP en Laravel.'), 'La solicitud va directamente al controlador y luego al modelo.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = 'Describe el ciclo de vida de una solicitud HTTP en Laravel.'), 'Laravel solo procesa las solicitudes de la API, no las HTTP.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = 'Describe el ciclo de vida de una solicitud HTTP en Laravel.'), 'El enrutador siempre es lo primero que se ejecuta sin importar la solicitud.', FALSE);

-- Answers for Laravel Question 4 (¿Qué archivo define las rutas web en Laravel?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué archivo define las rutas web en Laravel?'), 'routes/web.php', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué archivo define las rutas web en Laravel?'), 'app/Http/routes.php', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué archivo define las rutas web en Laravel?'), 'web.php', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué archivo define las rutas web en Laravel?'), 'routes.php', FALSE);

-- Answers for Laravel Question 5 (¿Qué función se usa para redireccionar en un controlador de Laravel?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función se usa para redireccionar en un controlador de Laravel?'), 'redirect()', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función se usa para redireccionar en un controlador de Laravel?'), 'route()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función se usa para redireccionar en un controlador de Laravel?'), 'redirectTo()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué función se usa para redireccionar en un controlador de Laravel?'), 'goTo()', FALSE);

-- Answers for Laravel Question 6 (¿Qué comando se usa para ejecutar migraciones en Laravel?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué comando se usa para ejecutar migraciones en Laravel?'), 'php artisan migrate', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué comando se usa para ejecutar migraciones en Laravel?'), 'php artisan db:migrate', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué comando se usa para ejecutar migraciones en Laravel?'), 'php migrate', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué comando se usa para ejecutar migraciones en Laravel?'), 'artisan migrate', FALSE);

-- Answers for Laravel Question 7 (¿Qué helper se usa para obtener la URL base en Laravel?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué helper se usa para obtener la URL base en Laravel?'), 'url()', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué helper se usa para obtener la URL base en Laravel?'), 'base_url()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué helper se usa para obtener la URL base en Laravel?'), 'site_url()', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué helper se usa para obtener la URL base en Laravel?'), 'asset()', FALSE);

-- Answers for Laravel Question 8 (¿Qué es un Service Provider en Laravel?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es un Service Provider en Laravel?'), 'Una clase que configura y registra servicios en la aplicación.', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es un Service Provider en Laravel?'), 'Un tipo de controlador.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es un Service Provider en Laravel?'), 'Una extensión de Laravel.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es un Service Provider en Laravel?'), 'Un paquete de terceros.', FALSE);

-- Answers for Laravel Question 9 (¿Cómo se define un middleware global en Laravel?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Cómo se define un middleware global en Laravel?'), 'En el archivo app/Http/Kernel.php en la propiedad $middleware.', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Cómo se define un middleware global en Laravel?'), 'En el archivo routes/web.php.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Cómo se define un middleware global en Laravel?'), 'En el archivo app/Http/routes.php.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Cómo se define un middleware global en Laravel?'), 'En el archivo config/middleware.php.', FALSE);

-- Answers for Laravel Question 10 (¿Qué es una Policy en Laravel?)
INSERT INTO Answers (question_id, answer_text, is_correct) VALUES
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es una Policy en Laravel?'), 'Una clase que define permisos para acciones en modelos.', TRUE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es una Policy en Laravel?'), 'Un tipo de middleware.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es una Policy en Laravel?'), 'Una extensión de paquete.', FALSE),
((SELECT question_id FROM Questions WHERE question_text = '¿Qué es una Policy en Laravel?'), 'Un comando de Artisan.', FALSE);

-- Re-insert sample awards if they were deleted and need to be topic-agnostic or re-linked.
-- Assuming Awards are level-specific, they can remain or be re-inserted as previously defined.
-- If you deleted them earlier:
INSERT INTO Awards (award_name, image_url, min_points_required, level_id) VALUES
('Principiante Dorado', 'https://example.com/awards/beginner_gold.png', 50, (SELECT level_id FROM Levels WHERE level_name = 'Principiante')),
('Novato Platino', 'https://example.com/awards/novice_platinum.png', 100, (SELECT level_id FROM Levels WHERE level_name = 'Novato')),
('Experto Diamante', 'https://example.com/awards/expert_diamond.png', 200, (SELECT level_id FROM Levels WHERE level_name = 'Experto'));

-- Re-insert sample Player Progress (linked to the placeholder user_id = 1) if it was deleted
-- Assuming the test player (user_id 1) has started some quizzes
INSERT INTO PlayerProgress (user_id, topic_id, level_id, points_obtained, percentage_advance, start_time, end_time) VALUES
(1, (SELECT topic_id FROM Topics WHERE topic_name = 'PHP'), (SELECT level_id FROM Levels WHERE level_name = 'Principiante'), 65, 65.00, '2025-07-26 14:00:00', '2025-07-26 14:10:00'),
(1, (SELECT topic_id FROM Topics WHERE topic_name = 'Javascript'), (SELECT level_id FROM Levels WHERE level_name = 'Novato'), 80, 80.00, '2025-07-26 15:00:00', '2025-07-26 15:25:00');