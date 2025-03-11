-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type_name VARCHAR(50) DEFAULT 'Category'
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    in_stock BOOLEAN DEFAULT true,
    description TEXT,
    category_id VARCHAR(50),
    brand VARCHAR(100),
    type VARCHAR(50) DEFAULT 'SimpleProduct',
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Product Gallery
CREATE TABLE IF NOT EXISTS product_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(50),
    image_url TEXT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Attribute Sets
CREATE TABLE IF NOT EXISTS attribute_sets (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL
);

-- Attribute Items
CREATE TABLE IF NOT EXISTS attribute_items (
    id VARCHAR(50) PRIMARY KEY,
    attribute_set_id VARCHAR(50),
    display_value VARCHAR(100) NOT NULL,
    value VARCHAR(100) NOT NULL,
    FOREIGN KEY (attribute_set_id) REFERENCES attribute_sets(id)
);

-- Product Attributes
CREATE TABLE IF NOT EXISTS product_attributes (
    product_id VARCHAR(50),
    attribute_item_id VARCHAR(50),
    PRIMARY KEY (product_id, attribute_item_id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (attribute_item_id) REFERENCES attribute_items(id)
);

-- Prices
CREATE TABLE IF NOT EXISTS prices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(50),
    amount DECIMAL(10, 2) NOT NULL,
    currency_label VARCHAR(10) NOT NULL,
    currency_symbol VARCHAR(5) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Orders
CREATE TABLE IF NOT EXISTS orders (
    id VARCHAR(50) PRIMARY KEY,
    total_amount DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order Items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(50),
    product_id VARCHAR(50),
    quantity INT NOT NULL,
    selected_attributes TEXT,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
