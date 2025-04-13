</main>
    
    <!-- Footer -->
    <footer class="bg-white border-t">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">My Hijab</h3>
                    <p class="text-gray-600 text-sm">
                        My Hijab adalah toko online yang menyediakan berbagai koleksi hijab berkualitas 
                        dengan harga terjangkau. Kami berkomitmen untuk memberikan produk dan layanan terbaik 
                        untuk customer kami.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="index.php" class="text-gray-600 hover:text-rose-600 transition-colors">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="products.php" class="text-gray-600 hover:text-rose-600 transition-colors">
                                Produk
                            </a>
                        </li>
                        <li>
                            <a href="testimonials.php" class="text-gray-600 hover:text-rose-600 transition-colors">
                                Testimoni
                            </a>
                        </li>
                        <li>
                            <a href="contact.php" class="text-gray-600 hover:text-rose-600 transition-colors">
                                Kontak
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Hubungi Kami</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-rose-600"></i>
                            <span>
                                Jl. Contoh No. 123<br>
                                Jakarta Selatan, 12345<br>
                                Indonesia
                            </span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3 text-rose-600"></i>
                            <span>+62 812-3456-7890</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-rose-600"></i>
                            <span>info@myhijab.com</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Social Media -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Ikuti Kami</h3>
                    <div class="flex space-x-4">
                        <a href="https://instagram.com/myhijab" 
                           target="_blank"
                           class="w-10 h-10 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://facebook.com/myhijab" 
                           target="_blank"
                           class="w-10 h-10 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://tiktok.com/@myhijab" 
                           target="_blank"
                           class="w-10 h-10 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a href="https://wa.me/6281234567890" 
                           target="_blank"
                           class="w-10 h-10 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                    
                    <!-- Payment Methods -->
                    <div class="mt-6">
                        <h4 class="text-sm font-semibold mb-3">Metode Pembayaran</h4>
                        <div class="flex items-center space-x-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" 
                                 alt="BCA" 
                                 class="h-6">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" 
                                 alt="Mandiri" 
                                 class="h-6">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/55/BNI_logo.svg" 
                                 alt="BNI" 
                                 class="h-6">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="border-t mt-12 pt-8 text-center text-sm text-gray-600">
                <p>&copy; <?php echo date('Y'); ?> My Hijab. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Mobile Menu Toggle Script -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
        
        // Close alert messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 5000);
    </script>
</body>
</html>
