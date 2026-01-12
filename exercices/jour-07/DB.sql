CREATE DATABASE IF NOT EXISTS boutique
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE boutique;

CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products (name, description, price, stock, category) VALUES
('T-shirt Blanc', 'T-shirt 100% coton', 29.99, 50, 'Vêtements'),
('Jean Slim', 'Jean stretch confortable', 79.99, 30, 'Vêtements'),
('Casquette NY', 'Casquette brodée', 19.99, 100, 'Accessoires'),
('Baskets Sport', 'Chaussures de running', 89.99, 25, 'Chaussures'),
('Sac à dos', 'Sac 20L étanche', 49.99, 15, 'Accessoires');