<?php
require_once 'includes/config.php';
require_once 'includes/header.php';

// Get testimonials from functions.php
$testimonials = getTestimonials();
?>

<!-- Page Header -->
<div class="text-center mb-12">
    <h1 class="text-4xl font-bold mb-4">Testimoni Pelanggan</h1>
    <p class="text-gray-600">
        Apa kata mereka tentang produk dan layanan kami
    </p>
</div>

<!-- Testimonial Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-16">
    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
        <div class="text-3xl font-bold text-rose-600 mb-2">500+</div>
        <p class="text-gray-600">Pelanggan Puas</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
        <div class="text-3xl font-bold text-rose-600 mb-2">4.8</div>
        <p class="text-gray-600">Rating Rata-rata</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
        <div class="text-3xl font-bold text-rose-600 mb-2">1000+</div>
        <p class="text-gray-600">Produk Terjual</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-6 text-center">
        <div class="text-3xl font-bold text-rose-600 mb-2">98%</div>
        <p class="text-gray-600">Rekomendasi</p>
    </div>
</div>

<!-- Featured Testimonials -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
    <!-- Featured Testimonial 1 -->
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
    
    <!-- Featured Testimonial 2 -->
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
    
    <!-- Featured Testimonial 3 -->
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

<!-- Customer Reviews -->
<?php if (!empty($testimonials)): ?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <?php foreach ($testimonials as $testimonial): ?>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center mb-4">
            <?php if ($testimonial['photo']): ?>
            <img src="assets/images/testimonials/<?php echo $testimonial['photo']; ?>" 
                 alt="<?php echo $testimonial['customer_name']; ?>" 
                 class="w-12 h-12 rounded-full object-cover">
            <?php else: ?>
            <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                <i class="fas fa-user text-gray-400"></i>
            </div>
            <?php endif; ?>
            
            <div class="ml-4">
                <h3 class="font-semibold"><?php echo $testimonial['customer_name']; ?></h3>
                <div class="flex text-yellow-400">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php if ($i <= $testimonial['rating']): ?>
                        <i class="fas fa-star"></i>
                        <?php else: ?>
                        <i class="far fa-star"></i>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
        <p class="text-gray-600"><?php echo nl2br($testimonial['content']); ?></p>
        <p class="text-sm text-gray-500 mt-2">
            <?php echo date('d F Y', strtotime($testimonial['created_at'])); ?>
        </p>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Submit Testimonial CTA -->
<div class="bg-rose-50 rounded-lg p-8 text-center mt-16">
    <h2 class="text-2xl font-bold mb-4">Bagikan Pengalaman Anda</h2>
    <p class="text-gray-600 mb-6">
        Kami sangat menghargai feedback dari Anda untuk terus meningkatkan layanan kami
    </p>
    <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20memberikan%20testimoni" 
       target="_blank"
       class="inline-block bg-rose-600 text-white px-8 py-3 rounded-md hover:bg-rose-700 transition-colors">
        <i class="fab fa-whatsapp mr-2"></i> Kirim Testimoni
    </a>
</div>

<?php require_once 'includes/footer.php'; ?>
