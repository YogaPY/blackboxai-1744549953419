<?php
require_once 'config.php';
require_once 'functions.php';

// Get current page for navigation highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Hijab - Koleksi Hijab Terlengkap</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Top Bar -->
    <div class="bg-rose-600 text-white py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center space-x-4">
                    <a href="https://wa.me/6281234567890" target="_blank" class="hover:text-rose-200 transition-colors">
                        <i class="fab fa-whatsapp mr-1"></i> +62 812-3456-7890
                    </a>
                    <a href="mailto:info@myhijab.com" class="hover:text-rose-200 transition-colors">
                        <i class="fas fa-envelope mr-1"></i> info@myhijab.com
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="https://instagram.com/myhijab" target="_blank" class="hover:text-rose-200 transition-colors">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://facebook.com/myhijab" target="_blank" class="hover:text-rose-200 transition-colors">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="https://tiktok.com/@myhijab" target="_blank" class="hover:text-rose-200 transition-colors">
                        <i class="fab fa-tiktok"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="index.php" class="flex items-center">
                    <span class="text-2xl font-bold text-rose-600">My Hijab</span>
                </a>
                
                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="index.php" 
                       class="<?php echo $current_page === 'index.php' ? 'text-rose-600' : 'text-gray-600 hover:text-rose-600'; ?> transition-colors">
                        Home
                    </a>
                    <a href="products.php" 
                       class="<?php echo $current_page === 'products.php' ? 'text-rose-600' : 'text-gray-600 hover:text-rose-600'; ?> transition-colors">
                        Produk
                    </a>
                    <a href="testimonials.php" 
                       class="<?php echo $current_page === 'testimonials.php' ? 'text-rose-600' : 'text-gray-600 hover:text-rose-600'; ?> transition-colors">
                        Testimoni
                    </a>
                    <a href="contact.php" 
                       class="<?php echo $current_page === 'contact.php' ? 'text-rose-600' : 'text-gray-600 hover:text-rose-600'; ?> transition-colors">
                        Kontak
                    </a>
                </nav>
                
                <!-- Cart -->
                <div class="flex items-center">
                    <a href="cart.php" class="relative group">
                        <i class="fas fa-shopping-cart text-2xl text-gray-600 group-hover:text-rose-600 transition-colors"></i>
                        <?php if (getCartCount() > 0): ?>
                        <span class="absolute -top-2 -right-2 bg-rose-600 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                            <?php echo getCartCount(); ?>
                        </span>
                        <?php endif; ?>
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button type="button" 
                            onclick="toggleMobileMenu()"
                            class="text-gray-600 hover:text-rose-600 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" 
             class="hidden md:hidden bg-white border-t border-gray-200 px-4 py-2">
            <div class="space-y-1">
                <a href="index.php" 
                   class="block py-2 <?php echo $current_page === 'index.php' ? 'text-rose-600' : 'text-gray-600'; ?>">
                    Home
                </a>
                <a href="products.php" 
                   class="block py-2 <?php echo $current_page === 'products.php' ? 'text-rose-600' : 'text-gray-600'; ?>">
                    Produk
                </a>
                <a href="testimonials.php" 
                   class="block py-2 <?php echo $current_page === 'testimonials.php' ? 'text-rose-600' : 'text-gray-600'; ?>">
                    Testimoni
                </a>
                <a href="contact.php" 
                   class="block py-2 <?php echo $current_page === 'contact.php' ? 'text-rose-600' : 'text-gray-600'; ?>">
                    Kontak
                </a>
            </div>
        </div>
    </header>
    
    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <?php echo displaySuccess($_SESSION['success']); ?>
    </div>
    <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <?php echo displayError($_SESSION['error']); ?>
    </div>
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="py-8">
