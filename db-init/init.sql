-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample users
INSERT INTO users (username, name, email, password, profile_picture) VALUES
('john_doe', 'John Doe', 'john@example.com', '$2y$12$pKn/FeJQXjXfTzYRlrBRyun4.CmQg042mUMpfiduM6C.DxUE8E6dC', 'uploads/default.png'),
('jane_smith', 'Jane Smith', 'jane@example.com', '$2y$12$H7hTEE729KUklJCcfm2.JOJX3miV5y.THd/XBO13sVaJLiSSoqKCK', 'uploads/default.png'),
('bob_wilson', 'Bob Wilson', 'bob@example.com', '$2y$12$jt86LriVQIWA6DXwPUgNN.KIGBh5RerkcJNxNhYAGZuW9IOB7.9aS', 'uploads/default.png');

-- Insert sample products
INSERT INTO products (name, description, price, stock, image) VALUES
('Wireless Headphones', 'High-quality wireless headphones with noise cancellation', 79.99, 50, 'uploads/headphones.jpg'),
('USB-C Cable', 'Durable USB-C charging and data cable (2 meters)', 12.99, 200, 'uploads/usb-cable.jpg'),
('Laptop Stand', 'Adjustable aluminum laptop stand for better ergonomics', 34.99, 30, 'uploads/laptop-stand.jpg'),
('Mechanical Keyboard', 'RGB mechanical keyboard with custom switches', 129.99, 25, 'uploads/keyboard.jpg'),
('Mouse Pad', 'Large extended mouse pad with non-slip base', 19.99, 100, 'uploads/mousepad.jpg'),
('USB Hub', '7-port USB 3.0 hub with power adapter', 39.99, 45, 'uploads/usb-hub.jpg'),
('Phone Stand', 'Adjustable phone stand for desk and travel', 14.99, 75, 'uploads/phone-stand.jpg'),
('Monitor Light Bar', 'Auto-dimming monitor light bar for reduced eye strain', 99.99, 20, 'uploads/light-bar.jpg');
