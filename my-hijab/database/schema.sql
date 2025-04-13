-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INTEGER NOT NULL DEFAULT 0,
    image TEXT,
    category TEXT CHECK(category IN ('pashmina', 'square', 'instant')) NOT NULL,
    color TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    customer_name TEXT NOT NULL,
    email TEXT NOT NULL,
    phone TEXT NOT NULL,
    address TEXT NOT NULL,
    city TEXT NOT NULL,
    postal_code TEXT NOT NULL,
    payment_method TEXT CHECK(payment_method IN ('transfer', 'cod')) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    shipping DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status TEXT CHECK(status IN ('pending', 'processing', 'shipped', 'completed', 'cancelled')) NOT NULL DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order items table
CREATE TABLE IF NOT EXISTS order_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
);

-- Cart table
CREATE TABLE IF NOT EXISTS cart (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    session_id TEXT NOT NULL,
    product_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Admin table
CREATE TABLE IF NOT EXISTS admin (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Testimonials table
CREATE TABLE IF NOT EXISTS testimonials (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    customer_name TEXT NOT NULL,
    photo TEXT,
    content TEXT NOT NULL,
    rating INTEGER NOT NULL CHECK (rating BETWEEN 1 AND 5),
    status TEXT CHECK(status IN ('pending', 'approved', 'rejected')) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO admin (username, password_hash) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample products
INSERT INTO products (name, description, price, stock, image, category, color) VALUES
('Pashmina Ceruty Premium', 'Hijab pashmina berbahan ceruty premium yang lembut dan nyaman dipakai.', 85000, 50, 'pashmina-ceruty.jpg', 'pashmina', 'navy'),
('Square Hijab Voal', 'Hijab square dengan bahan voal yang ringan dan tidak panas.', 65000, 45, 'square-voal.jpg', 'square', 'pink'),
('Instant Hijab Sport', 'Hijab instant yang cocok untuk olahraga dan aktivitas sehari-hari.', 55000, 60, 'instant-sport.jpg', 'instant', 'black');

-- Insert sample testimonials
INSERT INTO testimonials (customer_name, content, rating, status) VALUES
('Sarah Amalia', 'Kualitas hijabnya sangat bagus, bahannya adem dan nyaman dipakai. Pengiriman juga cepat!', 5, 'approved'),
('Dewi Kartika', 'Suka banget sama koleksi hijabnya, modelnya up to date. Pasti akan belanja lagi!', 5, 'approved'),
('Rina Safitri', 'Admin fast response dan sangat membantu. Pengiriman cepat dan packaging aman!', 4, 'approved');
