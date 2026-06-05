-- Database: music_stream
CREATE DATABASE IF NOT EXISTS music_stream;
USE music_stream;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Songs table
CREATE TABLE songs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    artist VARCHAR(100) NOT NULL,
    cover_image VARCHAR(255),
    file_audio VARCHAR(255),
    file_video VARCHAR(255),
    genre VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data
-- Password is 'admin123' hashed with bcrypt
INSERT INTO users (username, email, password, role) VALUES 
('Admin', 'admin@musicstream.com', '$2y$10$/C2lIT4DPBVMLsC69fEfLe8ce9xoYsiNp6kBfDn9WxlegNiLBrBrS', 'admin'),
('User Test', 'user@example.com', '$2y$10$/C2lIT4DPBVMLsC69fEfLe8ce9xoYsiNp6kBfDn9WxlegNiLBrBrS', 'user');

-- Insert sample songs (paths will point to uploads folder)
INSERT INTO songs (title, artist, cover_image, file_audio, file_video, genre) VALUES 
('Blinding Lights', 'The Weeknd', 'default_cover.jpg', 'sample.mp3', NULL, 'Pop'),
('Bohemian Rhapsody', 'Queen', 'default_cover.jpg', 'sample.mp3', NULL, 'Rock');
