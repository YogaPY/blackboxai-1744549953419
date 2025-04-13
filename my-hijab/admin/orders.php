<?php
require_once 'includes/header.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    try {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$_POST['status'], $_POST['order_id']]);
        
        $_SESSION['success'] = "Status pesanan berhasil diupdate.";
        redirect('orders.php');
    } catch(PDOException $e) {
        $_SESSION['error'] = "Gagal mengupdate status pesanan.";
    }
}

// Get filter parameters
$status = isset($_GET['status']) ? sanitize($_GET['status']) : '';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'newest';

// Build query
try {
    $sql = "SELECT * FROM orders WHERE 1=1";
    $params = [];
    
    if ($status) {
        $sql .= " AND status = ?";
        $params[] = $status;
    }
    
    if ($search) {
        $sql .= " AND (customer_name LIKE ? OR phone LIKE ? OR email LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    switch ($sort) {
        case 'oldest':
            $sql .= " ORDER BY order_date ASC";
            break;
        case 'total_high':
            $sql .= " ORDER BY total DESC";
            break;
        case 'total_low':
            $sql .= " ORDER BY total ASC";
            break;
        default: // newest
            $sql .= " ORDER BY order_date DESC";
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $_SESSION['error'] = "Error fetching orders.";
    $orders = [];
}
?>

<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold">Manajemen Pesanan</h1>
    </div>
    
    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       placeholder="Cari berdasarkan nama, telepon, atau email..." 
                       value="<?php echo $search; ?>"
                       class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
            </div>
            
            <div class="w-48">
                <select name="status" 
                        class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
                    <option value="">Semua Status</option>
                    <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="processing" <?php echo $status === 'processing' ? 'selected' : ''; ?>>Processing</option>
                    <option value="shipped" <?php echo $status === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                    <option value="completed" <?php echo $status === 'completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>
            
            <div class="w-48">
                <select name="sort" 
                        class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
                    <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Terbaru</option>
                    <option value="oldest" <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>Terlama</option>
                    <option value="total_high" <?php echo $sort === 'total_high' ? 'selected' : ''; ?>>Total Tertinggi</option>
                    <option value="total_low" <?php echo $sort === 'total_low' ? 'selected' : ''; ?>>Total Terendah</option>
                </select>
            </div>
            
            <button type="submit" 
                    class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
        </form>
    </div>
    
    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left">ID Pesanan</th>
                        <th class="px-4 py-3 text-left">Pelanggan</th>
                        <th class="px-4 py-3 text-left">Kontak</th>
                        <th class="px-4 py-3 text-left">Total</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="px-4 py-3">
                                #<?php echo str_pad($order['id'], 8, '0', STR_PAD_LEFT); ?>
                            </td>
                            <td class="px-4 py-3">
                                <div>
                                    <p class="font-medium"><?php echo $order['customer_name']; ?></p>
                                    <p class="text-sm text-gray-500"><?php echo $order['address']; ?></p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div>
                                    <p><?php echo $order['phone']; ?></p>
                                    <p class="text-sm text-gray-500"><?php echo $order['email']; ?></p>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium">
                                <?php echo formatPrice($order['total']); ?>
                            </td>
                            <td class="px-4 py-3">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <input type="hidden" name="update_status" value="1">
                                    <select name="status" 
                                            onchange="this.form.submit()"
                                            class="border-gray-300 rounded-md text-sm focus:ring-rose-500 focus:border-rose-500 
                                                <?php echo $order['status'] === 'completed' ? 'bg-green-100' : 
                                                    ($order['status'] === 'cancelled' ? 'bg-red-100' : 
                                                    ($order['status'] === 'pending' ? 'bg-yellow-100' : 'bg-blue-100')); ?>">
                                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center space-x-2">
                                    <a href="order-detail.php?id=<?php echo $order['id']; ?>" 
                                       class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                Tidak ada pesanan yang ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
