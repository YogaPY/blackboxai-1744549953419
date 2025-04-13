<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('index.php');
}

// Get current page for navigation highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - My Hijab</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="dashboard.php" class="text-xl font-bold text-rose-800">
                        My Hijab Admin
                    </a>
                </div>
                
                <!-- Right Navigation -->
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">
                        <i class="fas fa-user mr-2"></i>
                        <?php echo $_SESSION['admin_username']; ?>
                    </span>
                    <a href="logout.php" 
                       class="text-gray-600 hover:text-rose-600 transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Sidebar and Main Content -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm">
            <nav class="mt-8 px-4">
                <div class="space-y-1">
                    <a href="dashboard.php" 
                       class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-rose-50 hover:text-rose-600 transition-colors <?php echo $current_page === 'dashboard.php' ? 'bg-rose-50 text-rose-600' : ''; ?>">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                    
                    <a href="products.php" 
                       class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-rose-50 hover:text-rose-600 transition-colors <?php echo $current_page === 'products.php' ? 'bg-rose-50 text-rose-600' : ''; ?>">
                        <i class="fas fa-box w-5"></i>
                        <span class="ml-3">Produk</span>
                    </a>
                    
                    <a href="orders.php" 
                       class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-rose-50 hover:text-rose-600 transition-colors <?php echo $current_page === 'orders.php' ? 'bg-rose-50 text-rose-600' : ''; ?>">
                        <i class="fas fa-shopping-cart w-5"></i>
                        <span class="ml-3">Pesanan</span>
                    </a>
                    
                    <a href="testimonials.php" 
                       class="flex items-center px-4 py-2 text-gray-600 rounded-md hover:bg-rose-50 hover:text-rose-600 transition-colors <?php echo $current_page === 'testimonials.php' ? 'bg-rose-50 text-rose-600' : ''; ?>">
                        <i class="fas fa-star w-5"></i>
                        <span class="ml-3">Testimoni</span>
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline"><?php echo $_SESSION['success']; ?></span>
            </div>
            <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
            </div>
            <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
