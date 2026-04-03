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

-- Create review table
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
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

-- Insert sample reviews
INSERT INTO reviews (product_id, user_id, rating, comment, created_at) VALUES
(1, 1, 5, 'Excellent sound quality and the noise cancellation is top-notch. Battery lasts all day!', '2026-03-28 10:30:00'),
(1, 2, 4, 'Great headphones overall, but a bit pricey. Comfort is good for long sessions.', '2026-03-25 14:15:00'),
(1, 3, 3, 'Decent quality but connectivity drops occasionally. Good value for the price.', '2026-03-20 09:45:00'),
(2, 1, 5, 'Perfect cable, very durable. Been using it for months with no issues.', '2026-03-22 16:20:00'),
(2, 2, 5, 'Fast charging, sturdy build. Highly recommend!', '2026-03-18 11:00:00'),
(3, 3, 4, 'Solid laptop stand. Does exactly what it promises. Assembly was straightforward.', '2026-03-15 13:30:00'),
(4, 1, 5, 'Love this keyboard! The switches feel premium and RGB lighting is customizable.', '2026-03-10 08:00:00'),
(4, 2, 4, 'Great keyboard but quite loud. Not ideal if you work in shared spaces.', '2026-03-08 15:45:00'),
(5, 3, 5, 'Large surface area, non-slip base works perfectly. Best mouse pad.', '2026-03-05 10:15:00'),
(6, 1, 4, 'Good hub with plenty of ports. Stable connection and good build quality.', '2026-02-28 12:30:00'),
(6, 2, 3, 'Works fine but gets warm during heavy use. Power adapter is a bit bulky.', '2026-02-25 09:00:00'),
(7, 3, 5, 'Perfect for my desk setup. Adjustable and very portable. Great value!', '2026-02-20 14:45:00'),
(8, 1, 5, 'Eye strain reduced significantly. The auto-dimming feature is brilliant!', '2026-02-15 11:20:00'),
(8, 2, 4, 'Good product but pricey. The light quality is excellent though.', '2026-02-10 16:00:00');
