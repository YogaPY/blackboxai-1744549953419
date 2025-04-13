<?php
require_once 'includes/header.php';

// Handle product deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    try {
        // Get product image filename before deletion
        $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$_POST['delete_id']]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete product
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$_POST['delete_id']]);
        
        // Delete product image if exists
        if ($product && $product['image']) {
            $image_path = "../assets/images/products/" . $product['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        $_SESSION['success'] = "Produk berhasil dihapus.";
        redirect('products.php');
    } catch(PDOException $e) {
        $_SESSION['error'] = "Gagal menghapus produk.";
    }
}

// Get all products with sorting
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

try {
    $sql = "SELECT * FROM products WHERE 1=1";
    $params = [];
    
    if ($search) {
        $sql .= " AND (name LIKE ? OR description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    switch ($sort) {
        case 'name_asc':
            $sql .= " ORDER BY name ASC";
            break;
        case 'name_desc':
            $sql .= " ORDER BY name DESC";
            break;
        case 'price_low':
            $sql .= " ORDER BY price ASC";
            break;
        case 'price_high':
            $sql .= " ORDER BY price DESC";
            break;
        case 'stock_low':
            $sql .= " ORDER BY stock ASC";
            break;
        case 'stock_high':
            $sql .= " ORDER BY stock DESC";
            break;
        case 'oldest':
            $sql .= " ORDER BY created_at ASC";
            break;
        default: // newest
            $sql .= " ORDER BY created_at DESC";
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $_SESSION['error'] = "Error fetching products.";
    $products = [];
}
?>

<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold">Manajemen Produk</h1>
        <a href="product-add.php" 
           class="bg-rose-600 text-white px-4 py-2 rounded-md hover:bg-rose-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Tambah Produk
        </a>
    </div>
    
    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       placeholder="Cari produk..." 
                       value="<?php echo $search; ?>"
                       class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
            </div>
            
            <div class="w-48">
                <select name="sort" 
                        class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
                    <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Terbaru</option>
                    <option value="oldest" <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>Terlama</option>
                    <option value="name_asc" <?php echo $sort === 'name_asc' ? 'selected' : ''; ?>>Nama (A-Z)</option>
                    <option value="name_desc" <?php echo $sort === 'name_desc' ? 'selected' : ''; ?>>Nama (Z-A)</option>
                    <option value="price_low" <?php echo $sort === 'price_low' ? 'selected' : ''; ?>>Harga Terendah</option>
                    <option value="price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>>Harga Tertinggi</option>
                    <option value="stock_low" <?php echo $sort === 'stock_low' ? 'selected' : ''; ?>>Stok Terendah</option>
                    <option value="stock_high" <?php echo $sort === 'stock_high' ? 'selected' : ''; ?>>Stok Tertinggi</option>
                </select>
            </div>
            
            <button type="submit" 
                    class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
        </form>
    </div>
    
    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left">Produk</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Harga</th>
                        <th class="px-4 py-3 text-left">Stok</th>
                        <th class="px-4 py-3 text-left">Tanggal Dibuat</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <img src="../assets/images/products/<?php echo $product['image']; ?>" 
                                         alt="<?php echo $product['name']; ?>"
                                         class="w-12 h-12 rounded object-cover">
                                    <div class="ml-3">
                                        <p class="font-medium"><?php echo $product['name']; ?></p>
                                        <p class="text-sm text-gray-500">
                                            <?php echo substr($product['description'], 0, 50) . '...'; ?>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3"><?php echo $product['category']; ?></td>
                            <td class="px-4 py-3"><?php echo formatPrice($product['price']); ?></td>
                            <td class="px-4 py-3">
                                <span class="<?php echo $product['stock'] < 5 ? 'text-red-600' : 'text-green-600'; ?>">
                                    <?php echo $product['stock']; ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <?php echo date('d/m/Y H:i', strtotime($product['created_at'])); ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center space-x-2">
                                    <a href="product-edit.php?id=<?php echo $product['id']; ?>" 
                                       class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="showDeleteModal(<?php echo $product['id']; ?>, 'products.php')" 
                                            class="text-red-600 hover:text-red-800 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                Tidak ada produk yang ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
