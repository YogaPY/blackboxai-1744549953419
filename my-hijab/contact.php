<?php require_once 'includes/header.php'; ?>

<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold mb-4">Hubungi Kami</h1>
        <p class="text-gray-600">
            Kami siap membantu Anda. Silakan hubungi kami melalui salah satu kontak di bawah ini.
        </p>
    </div>
    
    <!-- Contact Methods Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <!-- WhatsApp -->
        <div class="bg-white rounded-lg shadow-sm p-6 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fab fa-whatsapp text-3xl text-green-500"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">WhatsApp</h3>
            <p class="text-gray-600 mb-4">
                Chat dengan kami untuk bantuan cepat
            </p>
            <a href="https://wa.me/6281234567890" 
               target="_blank"
               class="inline-block bg-green-500 text-white px-6 py-2 rounded-full hover:bg-green-600 transition-colors">
                <i class="fab fa-whatsapp mr-2"></i> Chat Sekarang
            </a>
        </div>
        
        <!-- Instagram -->
        <div class="bg-white rounded-lg shadow-sm p-6 text-center">
            <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fab fa-instagram text-3xl text-pink-500"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Instagram</h3>
            <p class="text-gray-600 mb-4">
                Ikuti kami untuk update terbaru
            </p>
            <a href="https://instagram.com/myhijab" 
               target="_blank"
               class="inline-block bg-pink-500 text-white px-6 py-2 rounded-full hover:bg-pink-600 transition-colors">
                <i class="fab fa-instagram mr-2"></i> Follow Kami
            </a>
        </div>
        
        <!-- Email -->
        <div class="bg-white rounded-lg shadow-sm p-6 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-envelope text-3xl text-blue-500"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Email</h3>
            <p class="text-gray-600 mb-4">
                Kirim email untuk pertanyaan detail
            </p>
            <a href="mailto:info@myhijab.com" 
               class="inline-block bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition-colors">
                <i class="fas fa-envelope mr-2"></i> Kirim Email
            </a>
        </div>
    </div>
    
    <!-- Contact Form -->
    <div class="bg-white rounded-lg shadow-sm p-8 mb-16">
        <h2 class="text-2xl font-semibold mb-6">Kirim Pesan</h2>
        
        <form class="space-y-6" method="POST" action="https://formspree.io/f/your_formspree_id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           required 
                           class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
                </div>
                
                <div>
                    <label for="email" class="block text-gray-700 mb-2">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required 
                           class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
                </div>
            </div>
            
            <div>
                <label for="subject" class="block text-gray-700 mb-2">Subjek</label>
                <input type="text" 
                       id="subject" 
                       name="subject" 
                       required 
                       class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
            </div>
            
            <div>
                <label for="message" class="block text-gray-700 mb-2">Pesan</label>
                <textarea id="message" 
                          name="message" 
                          rows="5" 
                          required 
                          class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500"></textarea>
            </div>
            
            <button type="submit" 
                    class="bg-rose-600 text-white px-8 py-3 rounded-md hover:bg-rose-700 transition-colors">
                Kirim Pesan
            </button>
        </form>
    </div>
    
    <!-- Store Location -->
    <div class="bg-white rounded-lg shadow-sm p-8">
        <h2 class="text-2xl font-semibold mb-6">Lokasi Toko</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <div class="aspect-video rounded-lg overflow-hidden bg-gray-100">
                    <!-- Replace with actual map embed -->
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <i class="fas fa-map-marked-alt text-6xl"></i>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="font-semibold mb-2">My Hijab Store</h3>
                <div class="space-y-2 text-gray-600">
                    <p class="flex items-start">
                        <i class="fas fa-map-marker-alt mt-1 mr-3 text-rose-600"></i>
                        Jl. Contoh No. 123<br>
                        Jakarta Selatan, 12345<br>
                        Indonesia
                    </p>
                    <p class="flex items-center">
                        <i class="fas fa-phone mr-3 text-rose-600"></i>
                        +62 812-3456-7890
                    </p>
                    <p class="flex items-center">
                        <i class="fas fa-clock mr-3 text-rose-600"></i>
                        Senin - Minggu: 09:00 - 21:00
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
