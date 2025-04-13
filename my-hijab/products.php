<?php
require_once 'includes/config.php';
require_once 'includes/header.php';

// Get filter parameters
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$color = isset($_GET['color']) ? sanitize($_GET['color']) : '';
$sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'newest';

// Static product data
$all_products = [
    [
        'id' => 1,
        'name' => 'Pashmina Ceruty Premium',
        'description' => 'Hijab pashmina berbahan ceruty premium yang lembut dan nyaman dipakai.',
        'price' => 85000,
        'stock' => 50,
        'image' => 'pashmina-ceruty.jpg',
        'category' => 'pashmina',
        'color' => 'navy',
        'created_at' => '2025-04-01'
    ],
    [
        'id' => 2,
        'name' => 'Square Hijab Voal',
        'description' => 'Hijab square dengan bahan voal yang ringan dan tidak panas.',
        'price' => 65000,
        'stock' => 45,
        'image' => 'square-voal.jpg',
        'category' => 'square',
        'color' => 'pink',
        'created_at' => '2025-04-02'
    ],
    [
        'id' => 3,
        'name' => 'Instant Hijab Sport',
        'description' => 'Hijab instant yang cocok untuk olahraga dan aktivitas sehari-hari.',
        'price' => 55000,
        'stock' => 60,
        'image' => 'instant-sport.jpg',
        'category' => 'instant',
        'color' => 'black',
        'created_at' => '2025-04-03'
    ]
];

// Filter products
$products = $all_products;

if ($category) {
    $products = array_filter($products, function($p) use ($category) {
        return $p['category'] === $category;
    });
}

if ($color) {
    $products = array_filter($products, function($p) use ($color) {
        return $p['color'] === $color;
    });
}

// Sort products
usort($products, function($a, $b) use ($sort) {
    switch ($sort) {
        case 'price_low':
            return $a['price'] - $b['price'];
        case 'price_high':
            return $b['price'] - $a['price'];
        case 'oldest':
            return strtotime($a['created_at']) - strtotime($b['created_at']);
        default: // newest
            return strtotime($b['created_at']) - strtotime($a['created_at']);
    }
});

// Get unique categories and colors
$categories = array_unique(array_column($all_products, 'category'));
$colors = array_unique(array_column($all_products, 'color'));
?>

<!-- Products Page Header -->
<div class="text-center mb-12">
    <h1 class="text-4xl font-bold mb-4">Koleksi Hijab</h1>
    <p class="text-gray-600">Temukan koleksi hijab terbaik untuk tampilan sempurna Anda</p>
</div>

<!-- Filters and Products Container -->
<div class="flex flex-col md:flex-row gap-8">
    <!-- Filters Sidebar -->
    <div class="w-full md:w-64 bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold mb-6">Filter</h2>
        
        <form action="" method="GET" class="space-y-6">
            <!-- Categories -->
            <div>
                <h3 class="font-medium mb-3">Kategori</h3>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <input type="radio" 
                               name="category" 
                               id="cat_all" 
                               value="" 
                               <?php echo !$category ? 'checked' : ''; ?>
                               class="text-rose-600 focus:ring-rose-500">
                        <label for="cat_all" class="ml-2 text-gray-700">Semua</label>
                    </div>
                    <?php foreach ($categories as $cat): ?>
                    <div class="flex items-center">
                        <input type="radio" 
                               name="category" 
                               id="cat_<?php echo $cat; ?>" 
                               value="<?php echo $cat; ?>"
                               <?php echo $category === $cat ? 'checked' : ''; ?>
                               class="text-rose-600 focus:ring-rose-500">
                        <label for="cat_<?php echo $cat; ?>" class="ml-2 text-gray-700">
                            <?php echo ucfirst($cat); ?>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Colors -->
            <div>
                <h3 class="font-medium mb-3">Warna</h3>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <input type="radio" 
                               name="color" 
                               id="color_all" 
                               value="" 
                               <?php echo !$color ? 'checked' : ''; ?>
                               class="text-rose-600 focus:ring-rose-500">
                        <label for="color_all" class="ml-2 text-gray-700">Semua</label>
                    </div>
                    <?php foreach ($colors as $col): ?>
                    <div class="flex items-center">
                        <input type="radio" 
                               name="color" 
                               id="color_<?php echo $col; ?>" 
                               value="<?php echo $col; ?>"
                               <?php echo $color === $col ? 'checked' : ''; ?>
                               class="text-rose-600 focus:ring-rose-500">
                        <label for="color_<?php echo $col; ?>" class="ml-2 text-gray-700">
                            <?php echo ucfirst($col); ?>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Sort -->
            <div>
                <h3 class="font-medium mb-3">Urutkan</h3>
                <select name="sort" 
                        class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
                    <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>
                        Terbaru
                    </option>
                    <option value="oldest" <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>
                        Terlama
                    </option>
                    <option value="price_low" <?php echo $sort === 'price_low' ? 'selected' : ''; ?>>
                        Harga: Rendah ke Tinggi
                    </option>
                    <option value="price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>>
                        Harga: Tinggi ke Rendah
                    </option>
                </select>
            </div>

            <!-- Apply Filters Button -->
            <button type="submit" 
                    class="w-full bg-rose-600 text-white py-2 rounded-md hover:bg-rose-700 transition-colors">
                Terapkan Filter
            </button>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="flex-1">
        <?php if (!empty($products)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($products as $product): ?>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden group">
                <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="block">
                    <div class="relative h-64 overflow-hidden">
                        <img src="assets/images/products/<?php echo $product['image']; ?>" 
                             alt="<?php echo $product['name']; ?>" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2"><?php echo $product['name']; ?></h3>
                        <p class="text-gray-600 text-sm mb-2"><?php echo $product['category']; ?></p>
                        <div class="flex items-center justify-between">
                            <span class="text-rose-600 font-medium">
                                <?php echo formatPrice($product['price']); ?>
                            </span>
                            <span class="text-gray-500 text-sm">
                                Stok: <?php echo $product['stock']; ?>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <i class="fas fa-box-open text-4xl text-gray-400 mb-4"></i>
            <p class="text-gray-600">Tidak ada produk yang ditemukan.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
