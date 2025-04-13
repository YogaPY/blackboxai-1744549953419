<?php
require_once 'includes/header.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

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

// Get product details
$product = isset($all_products[$product_id]) ? $all_products[$product_id] : null;

if (!$product) {
    redirect('products.php');
}

// Get related products (same category, excluding current product)
$relatedProducts = array_filter($all_products, function($p) use ($product, $product_id) {
    return $p['category'] === $product['category'] && $p['id'] !== $product_id;
});

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = (int)$_POST['quantity'];
    
    if ($quantity > 0 && $quantity <= $product['stock']) {
        addToCart($product_id, $quantity);
        
        $_SESSION['success'] = 'Produk berhasil ditambahkan ke keranjang!';
        redirect("product-detail.php?id=$product_id");
    } else {
        $_SESSION['error'] = 'Jumlah tidak valid.';
    }
}
?>

<!-- Product Detail -->
<div class="flex flex-col md:flex-row gap-8 mb-16">
    <!-- Product Image -->
    <div class="w-full md:w-1/2">
        <div class="relative aspect-square rounded-lg overflow-hidden">
            <img src="assets/images/products/<?php echo $product['image']; ?>" 
                 alt="<?php echo $product['name']; ?>" 
                 class="w-full h-full object-cover">
        </div>
    </div>
    
    <!-- Product Info -->
    <div class="w-full md:w-1/2">
        <!-- Category -->
        <div class="text-rose-600 font-medium mb-2">
            <?php echo ucfirst($product['category']); ?>
        </div>
        
        <!-- Product Name -->
        <h1 class="text-3xl font-bold mb-4">
            <?php echo $product['name']; ?>
        </h1>
        
        <!-- Price -->
        <div class="text-2xl font-semibold text-rose-600 mb-6">
            <?php echo formatPrice($product['price']); ?>
        </div>
        
        <!-- Description -->
        <div class="prose max-w-none mb-8">
            <p class="text-gray-600">
                <?php echo nl2br($product['description']); ?>
            </p>
        </div>
        
        <!-- Color -->
        <div class="mb-6">
            <h3 class="font-medium mb-2">Warna</h3>
            <div class="flex items-center">
                <span class="inline-block w-6 h-6 rounded-full border-2 border-gray-300"
                      style="background-color: <?php echo $product['color']; ?>">
                </span>
                <span class="ml-2 text-gray-700">
                    <?php echo ucfirst($product['color']); ?>
                </span>
            </div>
        </div>
        
        <!-- Stock Status -->
        <div class="mb-6">
            <h3 class="font-medium mb-2">Ketersediaan</h3>
            <?php if ($product['stock'] > 0): ?>
            <span class="text-green-600">
                <i class="fas fa-check-circle"></i> Stok tersedia (<?php echo $product['stock']; ?>)
            </span>
            <?php else: ?>
            <span class="text-red-600">
                <i class="fas fa-times-circle"></i> Stok habis
            </span>
            <?php endif; ?>
        </div>
        
        <!-- Add to Cart Form -->
        <?php if ($product['stock'] > 0): ?>
        <form method="POST" class="mb-8">
            <div class="flex items-center gap-4 mb-4">
                <label for="quantity" class="font-medium">Jumlah:</label>
                <input type="number" 
                       name="quantity" 
                       id="quantity" 
                       value="1" 
                       min="1" 
                       max="<?php echo $product['stock']; ?>" 
                       class="w-20 border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
            </div>
            
            <button type="submit" 
                    name="add_to_cart" 
                    class="w-full bg-rose-600 text-white py-3 rounded-md hover:bg-rose-700 transition-colors">
                <i class="fas fa-shopping-cart mr-2"></i> Tambah ke Keranjang
            </button>
        </form>
        <?php endif; ?>
        
        <!-- Share Buttons -->
        <div>
            <h3 class="font-medium mb-2">Bagikan:</h3>
            <div class="flex space-x-4">
                <a href="#" class="text-gray-600 hover:text-rose-600 transition-colors">
                    <i class="fab fa-facebook text-xl"></i>
                </a>
                <a href="#" class="text-gray-600 hover:text-rose-600 transition-colors">
                    <i class="fab fa-twitter text-xl"></i>
                </a>
                <a href="#" class="text-gray-600 hover:text-rose-600 transition-colors">
                    <i class="fab fa-pinterest text-xl"></i>
                </a>
                <a href="#" class="text-gray-600 hover:text-rose-600 transition-colors">
                    <i class="fab fa-whatsapp text-xl"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<?php if (isset($_SESSION['success'])): ?>
<div class="mb-8">
    <?php echo displaySuccess($_SESSION['success']); ?>
</div>
<?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="mb-8">
    <?php echo displayError($_SESSION['error']); ?>
</div>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Related Products -->
<?php if (!empty($relatedProducts)): ?>
<div class="mb-16">
    <h2 class="text-2xl font-bold mb-6">Produk Terkait</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($relatedProducts as $related): ?>
        <div class="bg-white rounded-lg shadow-sm overflow-hidden group">
            <a href="product-detail.php?id=<?php echo $related['id']; ?>" class="block">
                <div class="relative h-64 overflow-hidden">
                    <img src="assets/images/products/<?php echo $related['image']; ?>" 
                         alt="<?php echo $related['name']; ?>" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2"><?php echo $related['name']; ?></h3>
                    <p class="text-rose-600 font-medium">
                        <?php echo formatPrice($related['price']); ?>
                    </p>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
