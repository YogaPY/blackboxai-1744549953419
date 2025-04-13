<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/config.php';
require_once 'includes/header.php';

// Get featured products
$featured_products = getFeaturedProducts(8);

// For testing, let's add some dummy products if database is not working
if (empty($featured_products)) {
    $featured_products = [
        [
            'id' => 1,
            'name' => 'Pashmina Ceruty Premium',
            'price' => 85000,
            'image' => 'pashmina-ceruty.jpg'
        ],
        [
            'id' => 2,
            'name' => 'Square Hijab Voal',
            'price' => 65000,
            'image' => 'square-voal.jpg'
        ],
        [
            'id' => 3,
            'name' => 'Instant Hijab Sport',
            'price' => 55000,
            'image' => 'instant-sport.jpg'
        ]
    ];
}
?>

<!-- Hero Banner -->
<div class="relative bg-rose-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                Temukan Koleksi Hijab Terbaru
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Tampil cantik dan stylish dengan koleksi hijab berkualitas dari My Hijab
            </p>
            <a href="products.php" 
               class="inline-block bg-rose-600 text-white px-8 py-3 rounded-md hover:bg-rose-700 transition-colors">
                Lihat Koleksi
            </a>
        </div>
    </div>
    
    <!-- Decorative elements -->
    <div class="absolute left-0 top-0 w-32 h-32 md:w-48 md:h-48 bg-rose-100 rounded-full -translate-x-1/2 -translate-y-1/2 opacity-50"></div>
    <div class="absolute right-0 bottom-0 w-24 h-24 md:w-40 md:h-40 bg-rose-100 rounded-full translate-x-1/2 translate-y-1/2 opacity-50"></div>
</div>

<!-- Categories -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-3xl font-bold text-center mb-12">Kategori Produk</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Pashmina -->
        <a href="products.php?category=pashmina" 
           class="group relative rounded-lg overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow">
            <div class="aspect-w-16 aspect-h-9">
                <img src="https://images.pexels.com/photos/6311550/pexels-photo-6311550.jpeg" 
                     alt="Pashmina" 
                     class="w-full h-full object-cover">
            </div>
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                <h3 class="text-2xl font-bold text-white">Pashmina</h3>
            </div>
        </a>
        
        <!-- Square -->
        <a href="products.php?category=square" 
           class="group relative rounded-lg overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow">
            <div class="aspect-w-16 aspect-h-9">
                <img src="https://images.pexels.com/photos/6311576/pexels-photo-6311576.jpeg" 
                     alt="Square" 
                     class="w-full h-full object-cover">
            </div>
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                <h3 class="text-2xl font-bold text-white">Square</h3>
            </div>
        </a>
        
        <!-- Instant -->
        <a href="products.php?category=instant" 
           class="group relative rounded-lg overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow">
            <div class="aspect-w-16 aspect-h-9">
                <img src="https://images.pexels.com/photos/6311584/pexels-photo-6311584.jpeg" 
                     alt="Instant" 
                     class="w-full h-full object-cover">
            </div>
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                <h3 class="text-2xl font-bold text-white">Instant</h3>
            </div>
        </a>
    </div>
</div>

<!-- Featured Products -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-3xl font-bold text-center mb-12">Produk Terlaris</h2>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php foreach ($featured_products as $product): ?>
        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <a href="product-detail.php?id=<?php echo $product['id']; ?>">
                <div class="aspect-w-1 aspect-h-1">
                    <img src="assets/images/products/<?php echo $product['image']; ?>" 
                         alt="<?php echo $product['name']; ?>" 
                         class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <h3 class="font-medium mb-2"><?php echo $product['name']; ?></h3>
                    <p class="text-rose-600 font-semibold"><?php echo formatPrice($product['price']); ?></p>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="text-center mt-12">
        <a href="products.php" 
           class="inline-block bg-gray-800 text-white px-8 py-3 rounded-md hover:bg-gray-700 transition-colors">
            Lihat Semua Produk
        </a>
    </div>
</div>

<!-- Features -->
<div class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Fast Delivery -->
            <div class="text-center">
                <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-truck text-2xl text-rose-600"></i>
                </div>
                <h3 class="font-semibold mb-2">Pengiriman Cepat</h3>
                <p class="text-gray-600 text-sm">
                    Pengiriman ke seluruh Indonesia dengan berbagai ekspedisi terpercaya
                </p>
            </div>
            
            <!-- Best Quality -->
            <div class="text-center">
                <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-medal text-2xl text-rose-600"></i>
                </div>
                <h3 class="font-semibold mb-2">Kualitas Terbaik</h3>
                <p class="text-gray-600 text-sm">
                    Bahan berkualitas premium yang nyaman dipakai sehari-hari
                </p>
            </div>
            
            <!-- Secure Payment -->
            <div class="text-center">
                <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-2xl text-rose-600"></i>
                </div>
                <h3 class="font-semibold mb-2">Pembayaran Aman</h3>
                <p class="text-gray-600 text-sm">
                    Transaksi aman dengan berbagai metode pembayaran
                </p>
            </div>
            
            <!-- Customer Service -->
            <div class="text-center">
                <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-2xl text-rose-600"></i>
                </div>
                <h3 class="font-semibold mb-2">Layanan Pelanggan</h3>
                <p class="text-gray-600 text-sm">
                    Customer service yang responsif dan siap membantu
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Preview -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-bold mb-4">Apa Kata Mereka?</h2>
        <p class="text-gray-600">
            Testimoni dari pelanggan setia My Hijab
        </p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Testimonial 1 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <img src="https://images.pexels.com/photos/6311475/pexels-photo-6311475.jpeg" 
                     alt="Customer" 
                     class="w-12 h-12 rounded-full object-cover">
                <div class="ml-4">
                    <h3 class="font-semibold">Sarah Amalia</h3>
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            <p class="text-gray-600">
                "Kualitas hijabnya sangat bagus, bahannya adem dan nyaman dipakai. 
                Pengiriman juga cepat dan pelayanannya ramah. Recommended banget!"
            </p>
        </div>
        
        <!-- Testimonial 2 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <img src="https://images.pexels.com/photos/6311587/pexels-photo-6311587.jpeg" 
                     alt="Customer" 
                     class="w-12 h-12 rounded-full object-cover">
                <div class="ml-4">
                    <h3 class="font-semibold">Dewi Kartika</h3>
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            <p class="text-gray-600">
                "Suka banget sama koleksi hijabnya, modelnya up to date dan harganya terjangkau. 
                Pasti akan belanja lagi di sini!"
            </p>
        </div>
        
        <!-- Testimonial 3 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <img src="https://images.pexels.com/photos/6311586/pexels-photo-6311586.jpeg" 
                     alt="Customer" 
                     class="w-12 h-12 rounded-full object-cover">
                <div class="ml-4">
                    <h3 class="font-semibold">Rina Safitri</h3>
                    <div class="flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
            <p class="text-gray-600">
                "Admin fast response dan sangat membantu dalam memilih hijab yang cocok. 
                Pengiriman cepat dan packaging aman!"
            </p>
        </div>
    </div>
    
    <div class="text-center mt-12">
        <a href="testimonials.php" 
           class="inline-block bg-gray-800 text-white px-8 py-3 rounded-md hover:bg-gray-700 transition-colors">
            Lihat Semua Testimoni
        </a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
