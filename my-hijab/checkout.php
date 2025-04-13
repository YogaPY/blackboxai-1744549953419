<?php
require_once 'includes/header.php';

// Static product data
$all_products = [
    1 => [
        'id' => 1,
        'name' => 'Pashmina Ceruty Premium',
        'description' => 'Hijab pashmina berbahan ceruty premium yang lembut dan nyaman dipakai.',
        'price' => 85000,
        'stock' => 50,
        'image' => 'pashmina-ceruty.jpg',
        'category' => 'pashmina',
        'color' => '#000080'
    ],
    2 => [
        'id' => 2,
        'name' => 'Square Hijab Voal',
        'description' => 'Hijab square dengan bahan voal yang ringan dan tidak panas.',
        'price' => 65000,
        'stock' => 45,
        'image' => 'square-voal.jpg',
        'category' => 'square',
        'color' => '#FFC0CB'
    ],
    3 => [
        'id' => 3,
        'name' => 'Instant Hijab Sport',
        'description' => 'Hijab instant yang cocok untuk olahraga dan aktivitas sehari-hari.',
        'price' => 55000,
        'stock' => 60,
        'image' => 'instant-sport.jpg',
        'category' => 'instant',
        'color' => '#000000'
    ]
];

// Redirect if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    redirect('cart.php');
}

// Get cart items for order summary
$cartItems = [];
$subtotal = 0;
$shipping = 20000; // Fixed shipping cost

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    if (isset($all_products[$product_id])) {
        $product = $all_products[$product_id];
        $cartItems[] = [
            'id' => $product_id,
            'product_id' => $product_id,
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => $quantity
        ];
        $subtotal += $product['price'] * $quantity;
    }
}

$total = $subtotal + $shipping;

// Handle checkout submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    
    // Validate input
    $name = sanitize($_POST['name']);
    $phone = sanitize($_POST['phone']);
    $email = sanitize($_POST['email']);
    $address = sanitize($_POST['address']);
    $city = sanitize($_POST['city']);
    $postal_code = sanitize($_POST['postal_code']);
    $payment_method = sanitize($_POST['payment_method']);
    
    if (empty($name)) $errors[] = 'Nama harus diisi';
    if (empty($phone)) $errors[] = 'Nomor telepon harus diisi';
    if (empty($email)) $errors[] = 'Email harus diisi';
    if (empty($address)) $errors[] = 'Alamat harus diisi';
    if (empty($city)) $errors[] = 'Kota harus diisi';
    if (empty($postal_code)) $errors[] = 'Kode pos harus diisi';
    if (empty($payment_method)) $errors[] = 'Metode pembayaran harus dipilih';
    
    if (empty($errors)) {
        // Create order
        $order = [
            'id' => time(), // Use timestamp as order ID
            'customer_name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'city' => $city,
            'postal_code' => $postal_code,
            'payment_method' => $payment_method,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'status' => 'pending',
            'order_date' => date('Y-m-d H:i:s'),
            'items' => $cartItems
        ];
        
        // Store order in session
        if (!isset($_SESSION['orders'])) {
            $_SESSION['orders'] = [];
        }
        $_SESSION['orders'][$order['id']] = $order;
        
        // Clear cart
        clearCart();
        
        // Redirect to success page
        $_SESSION['order_success'] = true;
        $_SESSION['order_id'] = $order['id'];
        redirect('checkout-success.php');
    }
}
?>

<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>
    
    <?php if (!empty($errors)): ?>
    <div class="mb-8">
        <?php foreach ($errors as $error): ?>
            <?php echo displayError($error); ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Checkout Form -->
        <div class="flex-1">
            <form method="POST" class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-6">Informasi Pengiriman</h2>
                
                <!-- Personal Information -->
                <div class="space-y-4 mb-8">
                    <div>
                        <label for="name" class="block text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               required 
                               class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500"
                               value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>">
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-gray-700 mb-2">Nomor Telepon *</label>
                        <input type="tel" 
                               name="phone" 
                               id="phone" 
                               required 
                               class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500"
                               value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-gray-700 mb-2">Email *</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               required 
                               class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500"
                               value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                    </div>
                </div>
                
                <!-- Shipping Address -->
                <div class="space-y-4 mb-8">
                    <div>
                        <label for="address" class="block text-gray-700 mb-2">Alamat Lengkap *</label>
                        <textarea name="address" 
                                  id="address" 
                                  required 
                                  rows="3" 
                                  class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500"><?php echo isset($_POST['address']) ? $_POST['address'] : ''; ?></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="city" class="block text-gray-700 mb-2">Kota *</label>
                            <input type="text" 
                                   name="city" 
                                   id="city" 
                                   required 
                                   class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500"
                                   value="<?php echo isset($_POST['city']) ? $_POST['city'] : ''; ?>">
                        </div>
                        
                        <div>
                            <label for="postal_code" class="block text-gray-700 mb-2">Kode Pos *</label>
                            <input type="text" 
                                   name="postal_code" 
                                   id="postal_code" 
                                   required 
                                   class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500"
                                   value="<?php echo isset($_POST['postal_code']) ? $_POST['postal_code'] : ''; ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method -->
                <div class="mb-8">
                    <h3 class="font-semibold mb-4">Metode Pembayaran *</h3>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="payment_method" 
                                   value="transfer" 
                                   required 
                                   class="text-rose-600 focus:ring-rose-500"
                                   <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'transfer') ? 'checked' : ''; ?>>
                            <span class="ml-2">Transfer Bank</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="payment_method" 
                                   value="cod" 
                                   required 
                                   class="text-rose-600 focus:ring-rose-500"
                                   <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'cod') ? 'checked' : ''; ?>>
                            <span class="ml-2">Cash on Delivery (COD)</span>
                        </label>
                    </div>
                </div>
                
                <button type="submit" 
                        class="w-full bg-rose-600 text-white py-3 rounded-md hover:bg-rose-700 transition-colors">
                    Buat Pesanan
                </button>
            </form>
        </div>
        
        <!-- Order Summary -->
        <div class="lg:w-96">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-6">Ringkasan Pesanan</h2>
                
                <!-- Cart Items -->
                <div class="space-y-4 mb-6">
                    <?php foreach ($cartItems as $item): ?>
                    <div class="flex items-start">
                        <img src="assets/images/products/<?php echo $item['image']; ?>" 
                             alt="<?php echo $item['name']; ?>" 
                             class="w-16 h-16 object-cover rounded">
                        <div class="ml-4 flex-1">
                            <h4 class="font-medium"><?php echo $item['name']; ?></h4>
                            <p class="text-gray-600 text-sm">
                                <?php echo $item['quantity']; ?> x <?php echo formatPrice($item['price']); ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">
                                <?php echo formatPrice($item['price'] * $item['quantity']); ?>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Totals -->
                <div class="border-t border-gray-200 pt-4 space-y-3">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span><?php echo formatPrice($subtotal); ?></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Ongkos Kirim</span>
                        <span><?php echo formatPrice($shipping); ?></span>
                    </div>
                    <div class="flex justify-between font-semibold text-lg border-t border-gray-200 pt-3">
                        <span>Total</span>
                        <span><?php echo formatPrice($total); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
