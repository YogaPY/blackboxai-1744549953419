<?php
require_once 'includes/header.php';

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
    
    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = "../assets/images/products/";
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $upload_result = uploadImage($_FILES['image'], $upload_dir);
        
        if (isset($upload_result['error'])) {
            $errors[] = $upload_result['error'];
        }
    } else {
        $errors[] = 'Gambar produk harus diupload';
    }
    
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO products (
                    name, description, price, stock, category, color, image, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $name,
                $description,
                $price,
                $stock,
                $category,
                $color,
                $upload_result['filename']
            ]);
            
            $_SESSION['success'] = "Produk berhasil ditambahkan.";
            redirect('products.php');
            
        } catch(PDOException $e) {
            $errors[] = "Gagal menambahkan produk.";
        }
    }
}
?>

<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-semibold">Tambah Produk</h1>
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
    
    <!-- Add Product Form -->
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
                       value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>">
            </div>
            
            <div>
                <label for="description" class="block text-gray-700 mb-2">Deskripsi *</label>
                <textarea id="description" 
                          name="description" 
                          rows="4" 
                          required 
                          class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500"><?php echo isset($_POST['description']) ? $_POST['description'] : ''; ?></textarea>
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
                           value="<?php echo isset($_POST['price']) ? $_POST['price'] : ''; ?>">
                </div>
                
                <div>
                    <label for="stock" class="block text-gray-700 mb-2">Stok *</label>
                    <input type="number" 
                           id="stock" 
                           name="stock" 
                           required 
                           min="0"
                           class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500"
                           value="<?php echo isset($_POST['stock']) ? $_POST['stock'] : ''; ?>">
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
                        <option value="pashmina" <?php echo (isset($_POST['category']) && $_POST['category'] == 'pashmina') ? 'selected' : ''; ?>>Pashmina</option>
                        <option value="square" <?php echo (isset($_POST['category']) && $_POST['category'] == 'square') ? 'selected' : ''; ?>>Square</option>
                        <option value="instant" <?php echo (isset($_POST['category']) && $_POST['category'] == 'instant') ? 'selected' : ''; ?>>Instant</option>
                    </select>
                </div>
                
                <div>
                    <label for="color" class="block text-gray-700 mb-2">Warna *</label>
                    <select id="color" 
                            name="color" 
                            required 
                            class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
                        <option value="">Pilih Warna</option>
                        <option value="black" <?php echo (isset($_POST['color']) && $_POST['color'] == 'black') ? 'selected' : ''; ?>>Hitam</option>
                        <option value="white" <?php echo (isset($_POST['color']) && $_POST['color'] == 'white') ? 'selected' : ''; ?>>Putih</option>
                        <option value="navy" <?php echo (isset($_POST['color']) && $_POST['color'] == 'navy') ? 'selected' : ''; ?>>Navy</option>
                        <option value="brown" <?php echo (isset($_POST['color']) && $_POST['color'] == 'brown') ? 'selected' : ''; ?>>Coklat</option>
                        <option value="pink" <?php echo (isset($_POST['color']) && $_POST['color'] == 'pink') ? 'selected' : ''; ?>>Pink</option>
                        <option value="gray" <?php echo (isset($_POST['color']) && $_POST['color'] == 'gray') ? 'selected' : ''; ?>>Abu-abu</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label for="image" class="block text-gray-700 mb-2">Gambar Produk *</label>
                <input type="file" 
                       id="image" 
                       name="image" 
                       accept="image/*" 
                       required 
                       class="w-full"
                       onchange="previewImage(this)">
                <p class="text-sm text-gray-500 mt-1">
                    Format: JPG, PNG, atau WebP. Maksimal 5MB.
                </p>
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
                Tambah Produk
            </button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
