<?php
require_once 'includes/header.php';

// Get statistics
try {
    // Total products
    $stmt = $conn->query("SELECT COUNT(*) FROM products");
    $totalProducts = $stmt->fetchColumn();
    
    // Total orders
    $stmt = $conn->query("SELECT COUNT(*) FROM orders");
    $totalOrders = $stmt->fetchColumn();
    
    // Total revenue
    $stmt = $conn->query("SELECT SUM(total) FROM orders WHERE status != 'cancelled'");
    $totalRevenue = $stmt->fetchColumn() ?? 0;
    
    // Low stock products (less than 5)
    $stmt = $conn->query("SELECT COUNT(*) FROM products WHERE stock < 5");
    $lowStock = $stmt->fetchColumn();
    
    // Recent orders
    $stmt = $conn->query("
        SELECT o.*, COUNT(oi.id) as items 
        FROM orders o 
        LEFT JOIN order_items oi ON o.id = oi.order_id 
        GROUP BY o.id 
        ORDER BY o.order_date DESC 
        LIMIT 5
    ");
    $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Popular products
    $stmt = $conn->query("
        SELECT p.*, COUNT(oi.id) as total_sold 
        FROM products p 
        LEFT JOIN order_items oi ON p.id = oi.product_id 
        GROUP BY p.id 
        ORDER BY total_sold DESC 
        LIMIT 5
    ");
    $popularProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $_SESSION['error'] = "Error fetching dashboard data.";
}
?>

<div class="space-y-8">
    <!-- Page Title -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold">Dashboard</h1>
        <p class="text-gray-600">
            <i class="far fa-calendar-alt mr-2"></i>
            <?php echo date('d F Y'); ?>
        </p>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Produk</h3>
                    <p class="text-2xl font-semibold"><?php echo number_format($totalProducts); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Pesanan</h3>
                    <p class="text-2xl font-semibold"><?php echo number_format($totalOrders); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-money-bill-wave text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Total Pendapatan</h3>
                    <p class="text-2xl font-semibold"><?php echo formatPrice($totalRevenue); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Low Stock Alert -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 text-sm">Stok Menipis</h3>
                    <p class="text-2xl font-semibold"><?php echo number_format($lowStock); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4">Pesanan Terbaru</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="px-4 py-3">ID Pesanan</th>
                            <th class="px-4 py-3">Pelanggan</th>
                            <th class="px-4 py-3">Total Item</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td class="px-4 py-3">
                                #<?php echo str_pad($order['id'], 8, '0', STR_PAD_LEFT); ?>
                            </td>
                            <td class="px-4 py-3"><?php echo $order['customer_name']; ?></td>
                            <td class="px-4 py-3"><?php echo $order['items']; ?></td>
                            <td class="px-4 py-3"><?php echo formatPrice($order['total']); ?></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-sm rounded-full 
                                    <?php echo $order['status'] === 'completed' ? 'bg-green-100 text-green-800' : 
                                        ($order['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                        'bg-red-100 text-red-800'); ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <a href="orders.php" class="text-rose-600 hover:text-rose-700 transition-colors">
                    Lihat Semua Pesanan <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Popular Products -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4">Produk Terpopuler</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left bg-gray-50">
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3">Harga</th>
                            <th class="px-4 py-3">Stok</th>
                            <th class="px-4 py-3">Total Terjual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php foreach ($popularProducts as $product): ?>
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <img src="../assets/images/products/<?php echo $product['image']; ?>" 
                                         alt="<?php echo $product['name']; ?>"
                                         class="w-10 h-10 rounded object-cover">
                                    <span class="ml-3"><?php echo $product['name']; ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3"><?php echo formatPrice($product['price']); ?></td>
                            <td class="px-4 py-3">
                                <span class="<?php echo $product['stock'] < 5 ? 'text-red-600' : 'text-green-600'; ?>">
                                    <?php echo $product['stock']; ?>
                                </span>
                            </td>
                            <td class="px-4 py-3"><?php echo $product['total_sold']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <a href="products.php" class="text-rose-600 hover:text-rose-700 transition-colors">
                    Kelola Produk <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
