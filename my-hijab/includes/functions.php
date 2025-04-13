<?php
// Get cart items count
function getCartCount() {
    return isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
}

// Get cart total
function getCartTotal() {
    return 0; // Will implement later
}

// Get featured products
function getFeaturedProducts($limit = 8) {
    // Static product data for testing
    return [
        [
            'id' => 1,
            'name' => 'Pashmina Ceruty Premium',
            'description' => 'Hijab pashmina berbahan ceruty premium yang lembut dan nyaman dipakai.',
            'price' => 85000,
            'stock' => 50,
            'image' => 'pashmina-ceruty.jpg',
            'category' => 'pashmina',
            'color' => 'navy'
        ],
        [
            'id' => 2,
            'name' => 'Square Hijab Voal',
            'description' => 'Hijab square dengan bahan voal yang ringan dan tidak panas.',
            'price' => 65000,
            'stock' => 45,
            'image' => 'square-voal.jpg',
            'category' => 'square',
            'color' => 'pink'
        ],
        [
            'id' => 3,
            'name' => 'Instant Hijab Sport',
            'description' => 'Hijab instant yang cocok untuk olahraga dan aktivitas sehari-hari.',
            'price' => 55000,
            'stock' => 60,
            'image' => 'instant-sport.jpg',
            'category' => 'instant',
            'color' => 'black'
        ]
    ];
}

// Get product categories
function getCategories() {
    return [
        'pashmina' => 'Pashmina',
        'square' => 'Square',
        'instant' => 'Instant'
    ];
}

// Get product colors
function getColors() {
    return [
        'black' => 'Hitam',
        'white' => 'Putih',
        'navy' => 'Navy',
        'brown' => 'Coklat',
        'pink' => 'Pink',
        'gray' => 'Abu-abu'
    ];
}

// Get order status label
function getOrderStatusLabel($status) {
    $labels = [
        'pending' => '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">Pending</span>',
        'processing' => '<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Processing</span>',
        'shipped' => '<span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">Shipped</span>',
        'completed' => '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Completed</span>',
        'cancelled' => '<span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-sm">Cancelled</span>'
    ];
    
    return isset($labels[$status]) ? $labels[$status] : $labels['pending'];
}

// Add item to cart
function addToCart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    
    return true;
}

// Remove item from cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        return true;
    }
    return false;
}

// Update cart quantity
function updateCartQuantity($product_id, $quantity) {
    if ($quantity <= 0) {
        return removeFromCart($product_id);
    }
    
    $_SESSION['cart'][$product_id] = $quantity;
    return true;
}

// Clear cart
function clearCart() {
    $_SESSION['cart'] = [];
    return true;
}

// Get testimonials
function getTestimonials($limit = 3) {
    return [
        [
            'id' => 1,
            'customer_name' => 'Sarah Amalia',
            'content' => 'Kualitas hijabnya sangat bagus, bahannya adem dan nyaman dipakai. Pengiriman juga cepat!',
            'rating' => 5,
            'photo' => null
        ],
        [
            'id' => 2,
            'customer_name' => 'Dewi Kartika',
            'content' => 'Suka banget sama koleksi hijabnya, modelnya up to date. Pasti akan belanja lagi!',
            'rating' => 5,
            'photo' => null
        ],
        [
            'id' => 3,
            'customer_name' => 'Rina Safitri',
            'content' => 'Admin fast response dan sangat membantu. Pengiriman cepat dan packaging aman!',
            'rating' => 4,
            'photo' => null
        ]
    ];
}
