<?php
require_once 'includes/header.php';

// Get product ID
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get product details
try {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        $_SESSION['error'] = "Produk tidak ditemukan.";
        redirect('products.php');
    }
} catch(PDOException $e) {
    $_SESSION['error'] = "Error fetching product.";
    redirect('products.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    
    // Validate input
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $category = sanitize($_POST['category']);
    $color = sanitize($_POST['color']);
    
    if (empty($name)) $errors[] = 'Nama produk harus diisi';
    if (empty($description)) $errors[] = 'Deskripsi produk harus diisi';
    if ($price <= 0) $errors[] = 'Harga produk harus lebih dari 0';
    if ($stock < 0) $errors[] = 'Stok tidak boleh negatif';
    if (empty($category)) $errors[] = 'Kategori harus diisi';
    if (empty($color)) $errors[] = 'Warna harus diisi';
    
    // Handle image upload if new image is provided
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = "../assets/images/products/";
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $upload_result = uploadImage($_FILES['image'], $upload_dir);
        
        if (isset($upload_result['error'])) {
            $errors[] = $upload_result['error'];
        } else {
            // Delete old image
            if ($product['image']) {
                $old_image = $upload_dir . $product['image'];
                if (file_exists($old_image)) {
                    unlink($old_image);
                }
            }
        }
    }
    
    if (empty($errors)) {
        try {
            $sql = "UPDATE products SET 
                    name = ?, 
                    description = ?, 
                    price = ?, 
                    stock = ?, 
                    category = ?, 
                    color = ?";
            
            $params = [
                $name,
                $description,
                $price,
                $stock,
                $category,
                $color
            ];
            
            // Add image to update if new image was uploaded
            if (!empty($_FILES['image']['name'])) {
                $sql .= ", image = ?";
                $params[] = $upload_result['filename'];
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $product_id;
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            
            $_SESSION['success'] = "Produk berhasil diupdate.";
            redirect('products.php');
            
        } catch(PDOException $e) {
            $errors[] = "Gagal mengupdate produk.";
        }
    }
}
?>

<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-semibold">Edit Produk</h1>
        <a href="products.php" class="text-gray-600 hover:text-gray-800 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    
    <!-- Error Messages -->
    <?php if (!empty($errors)): ?>
    <div class="mb-8">
        <?php foreach ($errors as $error): ?>
            <?php echo displayError($error); ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <!-- Edit Product Form -->
    <form method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-6">
        <!-- Basic Information -->
        <div class="space-y-6">
            <div>
                <label for="name" class="block text-gray-700 mb-2">Nama Produk *</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       required 
                       class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500"
                       value="<?php echo isset($_POST['name']) ? $_POST['name'] : $product['name']; ?>">
            </div>
            
            <div>
                <label for="description" class="block text-gray-700 mb-2">Deskripsi *</label>
                <textarea id="description" 
                          name="description" 
                          rows="4" 
                          required 
                          class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500"><?php echo isset($_POST['description']) ? $_POST['description'] : $product['description']; ?></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="price" class="block text-gray-700 mb-2">Harga (Rp) *</label>
                    <input type="number" 
                           id="price" 
                           name="price" 
                           required 
                           min="0" 
                           step="1000"
                           class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500"
                           value="<?php echo isset($_POST['price']) ? $_POST['price'] : $product['price']; ?>">
                </div>
                
                <div>
                    <label for="stock" class="block text-gray-700 mb-2">Stok *</label>
                    <input type="number" 
                           id="stock" 
                           name="stock" 
                           required 
                           min="0"
                           class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500"
                           value="<?php echo isset($_POST['stock']) ? $_POST['stock'] : $product['stock']; ?>">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="category" class="block text-gray-700 mb-2">Kategori *</label>
                    <select id="category" 
                            name="category" 
                            required 
                            class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
                        <option value="">Pilih Kategori</option>
                        <option value="pashmina" <?php echo ($product['category'] == 'pashmina') ? 'selected' : ''; ?>>Pashmina</option>
                        <option value="square" <?php echo ($product['category'] == 'square') ? 'selected' : ''; ?>>Square</option>
                        <option value="instant" <?php echo ($product['category'] == 'instant') ? 'selected' : ''; ?>>Instant</option>
                    </select>
                </div>
                
                <div>
                    <label for="color" class="block text-gray-700 mb-2">Warna *</label>
                    <select id="color" 
                            name="color" 
                            required 
                            class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
                        <option value="">Pilih Warna</option>
                        <option value="black" <?php echo ($product['color'] == 'black') ? 'selected' : ''; ?>>Hitam</option>
                        <option value="white" <?php echo ($product['color'] == 'white') ? 'selected' : ''; ?>>Putih</option>
                        <option value="navy" <?php echo ($product['color'] == 'navy') ? 'selected' : ''; ?>>Navy</option>
                        <option value="brown" <?php echo ($product['color'] == 'brown') ? 'selected' : ''; ?>>Coklat</option>
                        <option value="pink" <?php echo ($product['color'] == 'pink') ? 'selected' : ''; ?>>Pink</option>
                        <option value="gray" <?php echo ($product['color'] == 'gray') ? 'selected' : ''; ?>>Abu-abu</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label for="image" class="block text-gray-700 mb-2">Gambar Produk</label>
                <input type="file" 
                       id="image" 
                       name="image" 
                       accept="image/*" 
                       class="w-full"
                       onchange="previewImage(this)">
                <p class="text-sm text-gray-500 mt-1">
                    Format: JPG, PNG, atau WebP. Maksimal 5MB. Biarkan kosong jika tidak ingin mengubah gambar.
                </p>
                
                <!-- Current Image Preview -->
                <div class="mt-4">
                    <p class="text-gray-700 mb-2">Gambar Saat Ini:</p>
                    <img src="../assets/images/products/<?php echo $product['image']; ?>" 
                         alt="<?php echo $product['name']; ?>"
                         class="max-w-xs rounded">
                </div>
                
                <!-- New Image Preview -->
                <img id="imagePreview" 
                     src="#" 
                     alt="Preview" 
                     class="mt-4 max-w-xs hidden">
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="mt-8">
            <button type="submit" 
                    class="w-full bg-rose-600 text-white py-2 rounded-md hover:bg-rose-700 transition-colors">
                Update Produk
            </button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
