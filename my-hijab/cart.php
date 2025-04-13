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

// Handle cart updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantity'] as $product_id => $quantity) {
            updateCartQuantity($product_id, $quantity);
        }
        $_SESSION['success'] = 'Keranjang berhasil diupdate!';
        redirect('cart.php');
    }
    
    if (isset($_POST['remove_item'])) {
        $product_id = $_POST['remove_item'];
        if (removeFromCart($product_id)) {
            $_SESSION['success'] = 'Item berhasil dihapus dari keranjang!';
        }
        redirect('cart.php');
    }
}

// Get cart items
$cartItems = [];
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        if (isset($all_products[$product_id])) {
            $product = $all_products[$product_id];
            $cartItems[] = [
                'id' => $product_id,
                'product_id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'stock' => $product['stock'],
                'quantity' => $quantity
            ];
        }
    }
}

// Calculate totals
$subtotal = 0;
$shipping = 20000; // Fixed shipping cost
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$total = $subtotal + $shipping;
?>

<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Keranjang Belanja</h1>
    
    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
    <div class="mb-6">
        <?php echo displaySuccess($_SESSION['success']); ?>
    </div>
    <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="mb-6">
        <?php echo displayError($_SESSION['error']); ?>
    </div>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!empty($cartItems)): ?>
    <form method="POST" class="mb-8">
        <!-- Cart Items -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
            <?php foreach ($cartItems as $item): ?>
            <div class="flex items-center p-6 border-b border-gray-200 last:border-0">
                <!-- Product Image -->
                <div class="w-24 h-24 flex-shrink-0">
                    <img src="assets/images/products/<?php echo $item['image']; ?>" 
                         alt="<?php echo $item['name']; ?>" 
                         class="w-full h-full object-cover rounded">
                </div>
                
                <!-- Product Details -->
                <div class="flex-1 ml-6">
                    <h3 class="text-lg font-semibold mb-1">
                        <a href="product-detail.php?id=<?php echo $item['product_id']; ?>" 
                           class="hover:text-rose-600 transition-colors">
                            <?php echo $item['name']; ?>
                        </a>
                    </h3>
                    <p class="text-rose-600 font-medium mb-4">
                        <?php echo formatPrice($item['price']); ?>
                    </p>
                    
                    <div class="flex items-center">
                        <div class="w-24">
                            <input type="number" 
                                   name="quantity[<?php echo $item['id']; ?>]" 
                                   value="<?php echo $item['quantity']; ?>" 
                                   min="1" 
                                   max="<?php echo $item['stock']; ?>" 
                                   class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
                        </div>
                        <button type="submit" 
                                name="remove_item" 
                                value="<?php echo $item['id']; ?>" 
                                class="ml-4 text-gray-500 hover:text-red-600 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Subtotal -->
                <div class="text-right ml-6">
                    <p class="text-gray-600 text-sm mb-1">Subtotal:</p>
                    <p class="text-lg font-semibold">
                        <?php echo formatPrice($item['price'] * $item['quantity']); ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Update Cart Button -->
        <div class="text-right mb-8">
            <button type="submit" 
                    name="update_cart" 
                    class="bg-gray-800 text-white px-6 py-2 rounded hover:bg-gray-700 transition-colors">
                Update Keranjang
            </button>
        </div>
    </form>
    
    <!-- Order Summary -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Ringkasan Pesanan</h2>
        
        <div class="space-y-3 text-gray-600">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span class="font-medium"><?php echo formatPrice($subtotal); ?></span>
            </div>
            <div class="flex justify-between">
                <span>Ongkos Kirim</span>
                <span class="font-medium"><?php echo formatPrice($shipping); ?></span>
            </div>
            <div class="border-t border-gray-200 pt-3">
                <div class="flex justify-between text-gray-800">
                    <span class="font-semibold">Total</span>
                    <span class="font-semibold"><?php echo formatPrice($total); ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Checkout Button -->
    <div class="text-center">
        <a href="checkout.php" 
           class="inline-block bg-rose-600 text-white px-8 py-3 rounded-md hover:bg-rose-700 transition-colors">
            Lanjutkan ke Pembayaran
        </a>
    </div>
    
    <?php else: ?>
    <!-- Empty Cart -->
    <div class="text-center py-12">
        <div class="w-24 h-24 mx-auto mb-6">
            <i class="fas fa-shopping-cart text-6xl text-gray-400"></i>
        </div>
        <h2 class="text-2xl font-semibold mb-4">Keranjang Belanja Kosong</h2>
        <p class="text-gray-600 mb-8">Anda belum menambahkan produk ke keranjang.</p>
        <a href="products.php" 
           class="inline-block bg-rose-600 text-white px-8 py-3 rounded-md hover:bg-rose-700 transition-colors">
            Mulai Belanja
        </a>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
