<?php
require_once 'includes/header.php';

// Redirect if not coming from successful checkout
if (!isset($_SESSION['order_success']) || !isset($_SESSION['order_id'])) {
    redirect('index.php');
}

// Get order details from session
if (!isset($_SESSION['orders']) || !isset($_SESSION['orders'][$_SESSION['order_id']])) {
    redirect('index.php');
}

$order = $_SESSION['orders'][$_SESSION['order_id']];
$orderItems = $order['items'];

// Clear session variables
unset($_SESSION['order_success']);
unset($_SESSION['order_id']);
?>

<div class="max-w-3xl mx-auto">
    <!-- Success Message -->
    <div class="text-center mb-12">
        <div class="w-20 h-20 mx-auto mb-6 bg-green-100 rounded-full flex items-center justify-center">
            <i class="fas fa-check-circle text-4xl text-green-500"></i>
        </div>
        <h1 class="text-3xl font-bold mb-4">Terima Kasih!</h1>
        <p class="text-gray-600">
            Pesanan Anda telah berhasil dibuat. Nomor pesanan Anda adalah:
        </p>
        <p class="text-2xl font-semibold text-rose-600 mt-2">
            #<?php echo str_pad($order['id'], 8, '0', STR_PAD_LEFT); ?>
        </p>
    </div>
    
    <!-- Order Details -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
        <div class="p-6">
            <h2 class="text-xl font-semibold mb-6">Detail Pesanan</h2>
            
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-medium mb-2">Informasi Pengiriman</h3>
                    <div class="text-gray-600">
                        <p><?php echo $order['customer_name']; ?></p>
                        <p><?php echo $order['phone']; ?></p>
                        <p><?php echo $order['email']; ?></p>
                        <p><?php echo $order['address']; ?></p>
                        <p><?php echo $order['city']; ?>, <?php echo $order['postal_code']; ?></p>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-medium mb-2">Informasi Pembayaran</h3>
                    <div class="text-gray-600">
                        <p>Metode: <?php echo ucfirst($order['payment_method']); ?></p>
                        <p>Status: <span class="text-yellow-600 font-medium">Menunggu Pembayaran</span></p>
                        <p>Tanggal: <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></p>
                    </div>
                </div>
            </div>
            
            <?php if ($order['payment_method'] == 'transfer'): ?>
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="font-medium mb-2">Instruksi Pembayaran</h3>
                <p class="text-gray-600 mb-4">
                    Silakan transfer ke salah satu rekening berikut:
                </p>
                <div class="space-y-2">
                    <div class="flex items-center justify-between p-3 bg-white rounded border border-gray-200">
                        <div>
                            <p class="font-medium">Bank BCA</p>
                            <p class="text-gray-600">1234567890</p>
                        </div>
                        <p class="text-gray-600">a.n. My Hijab</p>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-white rounded border border-gray-200">
                        <div>
                            <p class="font-medium">Bank Mandiri</p>
                            <p class="text-gray-600">0987654321</p>
                        </div>
                        <p class="text-gray-600">a.n. My Hijab</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Order Items -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="font-medium mb-4">Produk yang Dipesan</h3>
                <div class="space-y-4">
                    <?php foreach ($orderItems as $item): ?>
                    <div class="flex items-center">
                        <img src="assets/images/products/<?php echo $item['image']; ?>" 
                             alt="<?php echo $item['name']; ?>" 
                             class="w-16 h-16 object-cover rounded">
                        <div class="ml-4 flex-1">
                            <h4 class="font-medium"><?php echo $item['name']; ?></h4>
                            <p class="text-gray-600">
                                <?php echo $item['quantity']; ?> x <?php echo formatPrice($item['price']); ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium">
                                <?php echo formatPrice($item['price'] * $item['quantity']); ?>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Order Summary -->
                <div class="border-t border-gray-200 mt-6 pt-6">
                    <div class="space-y-2">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span><?php echo formatPrice($order['subtotal']); ?></span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Ongkos Kirim</span>
                            <span><?php echo formatPrice($order['shipping']); ?></span>
                        </div>
                        <div class="flex justify-between font-semibold text-lg pt-2">
                            <span>Total</span>
                            <span><?php echo formatPrice($order['total']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="text-center space-x-4">
        <a href="index.php" 
           class="inline-block bg-gray-800 text-white px-8 py-3 rounded-md hover:bg-gray-700 transition-colors">
            Kembali ke Beranda
        </a>
        <?php if ($order['payment_method'] == 'transfer'): ?>
        <a href="https://wa.me/6281234567890?text=<?php echo urlencode('Halo, saya ingin konfirmasi pembayaran untuk pesanan #' . str_pad($order['id'], 8, '0', STR_PAD_LEFT)); ?>" 
           target="_blank"
           class="inline-block bg-green-500 text-white px-8 py-3 rounded-md hover:bg-green-600 transition-colors">
            <i class="fab fa-whatsapp mr-2"></i> Konfirmasi Pembayaran
        </a>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
