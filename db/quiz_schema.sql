-- SQL Script for Quiz System Database with database creation and selection

-- Create the database if it doesn't already exist
CREATE DATABASE IF NOT EXISTS dbQuiz;

-- Use the newly created or existing database
USE dbQuiz; -- This command is for MySQL/MariaDB.
             -- If you are using PostgreSQL, you might use \c dbQuiz; in the psql client.
             -- If you are using SQL Server, use USE dbQuiz; GO

-- Drop tables if they exist to allow for clean re-creation
-- Order of dropping tables is important due to foreign key constraints
-- This ensures tables with foreign key dependencies are dropped first.
DROP TABLE IF EXISTS PlayerProgress;
DROP TABLE IF EXISTS Answers;
DROP TABLE IF EXISTS Questions;
DROP TABLE IF EXISTS Awards;
DROP TABLE IF EXISTS Avatars;
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Topics;
DROP TABLE IF EXISTS Levels;
DROP TABLE IF EXISTS UserTopicLevel;


-- 1. Users Table (Módulo de Usuarios - CRUD)
CREATE TABLE Users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(191) UNIQUE NOT NULL, -- Changed from VARCHAR(255) to VARCHAR(191) to resolve index length error
    name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL, -- In a real application, store hashed passwords
    role ENUM('admin', 'operative', 'player') NOT NULL DEFAULT 'player'
);

-- 2. Topics Table (CRUD de temas)
CREATE TABLE Topics (
    topic_id INT PRIMARY KEY AUTO_INCREMENT,
    topic_name VARCHAR(100) UNIQUE NOT NULL
);

-- 3. Levels Table (Nivel de Conocimiento)
CREATE TABLE Levels (
    level_id INT PRIMARY KEY AUTO_INCREMENT,
    level_name VARCHAR(50) UNIQUE NOT NULL -- e.g., 'Experto', 'Novato', 'Principiante'
);

-- 4. Questions Table (Tipo de preguntas puede variar)
CREATE TABLE Questions (
    question_id INT PRIMARY KEY AUTO_INCREMENT,
    topic_id INT NOT NULL,
    level_id INT NOT NULL,
    question_text TEXT NOT NULL,
    question_type ENUM('multiple_choice', 'true_false') NOT NULL,
    difficulty ENUM('easy', 'medium', 'hard') NOT NULL, -- Added difficulty field based on previous discussion
    qr_code VARCHAR(255), -- To store QR code data or a reference to it
    FOREIGN KEY (topic_id) REFERENCES Topics(topic_id),
    FOREIGN KEY (level_id) REFERENCES Levels(level_id)
);

-- 5. Answers Table (for multiple choice questions)
CREATE TABLE Answers (
    answer_id INT PRIMARY KEY AUTO_INCREMENT,
    question_id INT NOT NULL,
    answer_text TEXT NOT NULL,
    is_correct BOOLEAN NOT NULL,
    FOREIGN KEY (question_id) REFERENCES Questions(question_id)
);

-- 6. Avatars Table (Permitir por usuario crear un avatar o imagen)
CREATE TABLE Avatars (
    avatar_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- 7. Awards Table (Módulo de premios)
CREATE TABLE Awards (
    award_id INT PRIMARY KEY AUTO_INCREMENT,
    award_name VARCHAR(255) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    min_points_required INT NOT NULL,
    level_id INT NOT NULL, -- Awards correspond to success at a certain level
    FOREIGN KEY (level_id) REFERENCES Levels(level_id)
);

-- 8. PlayerProgress Table (To track player's points and progress)
CREATE TABLE PlayerProgress (
    player_progress_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL, -- References the player
    topic_id INT NOT NULL,
    level_id INT NOT NULL,
    points_obtained INT NOT NULL DEFAULT 0, -- Points obtained for that level in that topic
    percentage_advance DECIMAL(5,2) NOT NULL DEFAULT 0.00, -- % of advance with respect to topic/level
    start_time DATETIME, -- Time when the player started the level/quiz
    end_time DATETIME, -- Time when the player finished the level/quiz
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (topic_id) REFERENCES Topics(topic_id),
    FOREIGN KEY (level_id) REFERENCES Levels(level_id),
    UNIQUE (user_id, topic_id, level_id) -- Ensures only one progress entry per user-topic-level

);

-- 9. UserTopicLevel Table (Relación usuario-tema-nivel/rango alcanzado)
CREATE TABLE UserTopicLevel (
    user_topic_level_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    topic_id INT NOT NULL,
    level_id INT NOT NULL, -- Nivel/rango alcanzado por el usuario en ese tema
    achieved_at DATETIME DEFAULT CURRENT_TIMESTAMP, -- Fecha en que alcanzó el nivel
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (topic_id) REFERENCES Topics(topic_id),
    FOREIGN KEY (level_id) REFERENCES Levels(level_id),
    UNIQUE (user_id, topic_id) -- Solo un rango por usuario y tema
);
CREATE INDEX idx_users_email ON Users(email);
CREATE INDEX idx_answers_question_id ON Answers(question_id);
CREATE INDEX idx_playerprogress_user_topic_level ON PlayerProgress(user_id, topic_id, level_id);