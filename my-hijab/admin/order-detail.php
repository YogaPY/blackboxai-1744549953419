<?php
require_once 'includes/header.php';

// Get order ID
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get order details
try {
    // Get order information
    $stmt = $conn->prepare("
        SELECT * FROM orders 
        WHERE id = ?
    ");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        $_SESSION['error'] = "Pesanan tidak ditemukan.";
        redirect('orders.php');
    }
    
    // Get order items
    $stmt = $conn->prepare("
        SELECT oi.*, p.name, p.image 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $_SESSION['error'] = "Error fetching order details.";
    redirect('orders.php');
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    try {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$_POST['status'], $order_id]);
        
        $_SESSION['success'] = "Status pesanan berhasil diupdate.";
        redirect("order-detail.php?id=$order_id");
    } catch(PDOException $e) {
        $_SESSION['error'] = "Gagal mengupdate status pesanan.";
    }
}
?>

<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-semibold">Detail Pesanan #<?php echo str_pad($order['id'], 8, '0', STR_PAD_LEFT); ?></h1>
        <a href="orders.php" class="text-gray-600 hover:text-gray-800 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    
    <!-- Order Information -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <div class="grid grid-cols-2 gap-8">
            <!-- Customer Information -->
            <div>
                <h2 class="text-lg font-semibold mb-4">Informasi Pelanggan</h2>
                <div class="space-y-2">
                    <p><span class="text-gray-600">Nama:</span> <?php echo $order['customer_name']; ?></p>
                    <p><span class="text-gray-600">Email:</span> <?php echo $order['email']; ?></p>
                    <p><span class="text-gray-600">Telepon:</span> <?php echo $order['phone']; ?></p>
                    <p><span class="text-gray-600">Alamat:</span> <?php echo $order['address']; ?></p>
                    <p><span class="text-gray-600">Kota:</span> <?php echo $order['city']; ?></p>
                    <p><span class="text-gray-600">Kode Pos:</span> <?php echo $order['postal_code']; ?></p>
                </div>
            </div>
            
            <!-- Order Information -->
            <div>
                <h2 class="text-lg font-semibold mb-4">Informasi Pesanan</h2>
                <div class="space-y-2">
                    <p>
                        <span class="text-gray-600">Tanggal Pesanan:</span> 
                        <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?>
                    </p>
                    <p>
                        <span class="text-gray-600">Metode Pembayaran:</span> 
                        <?php echo ucfirst($order['payment_method']); ?>
                    </p>
                    <p>
                        <span class="text-gray-600">Status:</span>
                        <form method="POST" class="inline-block ml-2">
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
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Items -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
        <h2 class="text-lg font-semibold p-6 border-b">Produk yang Dipesan</h2>
        
        <div class="divide-y">
            <?php foreach ($orderItems as $item): ?>
            <div class="p-6 flex items-center">
                <img src="../assets/images/products/<?php echo $item['image']; ?>" 
                     alt="<?php echo $item['name']; ?>"
                     class="w-16 h-16 rounded object-cover">
                     
                <div class="ml-4 flex-1">
                    <h3 class="font-medium"><?php echo $item['name']; ?></h3>
                    <p class="text-gray-500">
                        <?php echo $item['quantity']; ?> x <?php echo formatPrice($item['price']); ?>
                    </p>
                </div>
                
                <div class="text-right">
                    <p class="font-medium"><?php echo formatPrice($item['price'] * $item['quantity']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Order Summary -->
        <div class="p-6 bg-gray-50">
            <div class="space-y-2">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span><?php echo formatPrice($order['subtotal']); ?></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Ongkos Kirim</span>
                    <span><?php echo formatPrice($order['shipping']); ?></span>
                </div>
                <div class="flex justify-between font-semibold text-lg pt-2 border-t">
                    <span>Total</span>
                    <span><?php echo formatPrice($order['total']); ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex justify-end space-x-4">
        <?php if ($order['status'] === 'pending'): ?>
        <form method="POST" class="inline">
            <input type="hidden" name="update_status" value="1">
            <input type="hidden" name="status" value="processing">
            <button type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                Proses Pesanan
            </button>
        </form>
        <?php endif; ?>
        
        <?php if ($order['status'] === 'processing'): ?>
        <form method="POST" class="inline">
            <input type="hidden" name="update_status" value="1">
            <input type="hidden" name="status" value="shipped">
            <button type="submit" 
                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                Kirim Pesanan
            </button>
        </form>
        <?php endif; ?>
        
        <?php if ($order['status'] !== 'cancelled' && $order['status'] !== 'completed'): ?>
        <form method="POST" class="inline">
            <input type="hidden" name="update_status" value="1">
            <input type="hidden" name="status" value="cancelled">
            <button type="submit" 
                    class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors">
                Batalkan Pesanan
            </button>
        </form>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
