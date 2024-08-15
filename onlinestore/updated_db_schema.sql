CREATE DATABASE IF NOT EXISTS online_store;
USE online_store;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,  -- Updated: Added product description
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),  -- Updated: Added product image
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);



CREATE TABLE order_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255),
    product_image VARCHAR(255),
    quantity INT,
    order_date DATETIME,
    shipping_address TEXT,
    order_status ENUM('pending', 'shipped', 'delivered', 'canceled') DEFAULT 'pending',
    total_price DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);




-- //UPDATE TABLES

ALTER TABLE orders
ADD COLUMN customer_name VARCHAR(255),
ADD COLUMN order_date DATETIME,
ADD COLUMN product_status ENUM('pending', 'shipped', 'delivered', 'canceled') DEFAULT 'pending',
ADD COLUMN product_name VARCHAR(255),
ADD COLUMN product_image VARCHAR(255),
ADD COLUMN product_id INT,
ADD COLUMN total DECIMAL(10, 2);


ALTER TABLE orders
ADD COLUMN customer_name VARCHAR(255),
ADD COLUMN order_date DATETIME,
ADD COLUMN product_status ENUM('pending', 'shipped', 'delivered', 'canceled') DEFAULT 'pending',
ADD COLUMN product_name VARCHAR(255),
ADD COLUMN product_image VARCHAR(255),
ADD COLUMN product_id INT,
ADD COLUMN total DECIMAL(10, 2);




ALTER TABLE order_items
ADD COLUMN order_date DATETIME,
ADD COLUMN product_image VARCHAR(255),
ADD COLUMN shipping_address TEXT;



ALTER TABLE cart
ADD COLUMN product_name VARCHAR(255),
ADD COLUMN product_image VARCHAR(255),
ADD COLUMN total_price DECIMAL(10, 2);

