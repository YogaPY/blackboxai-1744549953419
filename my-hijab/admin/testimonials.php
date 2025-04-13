<?php
require_once 'includes/header.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    try {
        $stmt = $conn->prepare("UPDATE testimonials SET status = ? WHERE id = ?");
        $stmt->execute([$_POST['status'], $_POST['testimonial_id']]);
        
        $_SESSION['success'] = "Status testimoni berhasil diupdate.";
        redirect('testimonials.php');
    } catch(PDOException $e) {
        $_SESSION['error'] = "Gagal mengupdate status testimoni.";
    }
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    try {
        // Get testimonial photo before deletion
        $stmt = $conn->prepare("SELECT photo FROM testimonials WHERE id = ?");
        $stmt->execute([$_POST['delete_id']]);
        $testimonial = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete testimonial
        $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
        $stmt->execute([$_POST['delete_id']]);
        
        // Delete photo if exists
        if ($testimonial && $testimonial['photo']) {
            $photo_path = "../assets/images/testimonials/" . $testimonial['photo'];
            if (file_exists($photo_path)) {
                unlink($photo_path);
            }
        }
        
        $_SESSION['success'] = "Testimoni berhasil dihapus.";
        redirect('testimonials.php');
    } catch(PDOException $e) {
        $_SESSION['error'] = "Gagal menghapus testimoni.";
    }
}

// Get filter parameters
$status = isset($_GET['status']) ? sanitize($_GET['status']) : '';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'newest';

// Build query
try {
    $sql = "SELECT * FROM testimonials WHERE 1=1";
    $params = [];
    
    if ($status) {
        $sql .= " AND status = ?";
        $params[] = $status;
    }
    
    if ($search) {
        $sql .= " AND (customer_name LIKE ? OR content LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    switch ($sort) {
        case 'oldest':
            $sql .= " ORDER BY created_at ASC";
            break;
        case 'rating_high':
            $sql .= " ORDER BY rating DESC";
            break;
        case 'rating_low':
            $sql .= " ORDER BY rating ASC";
            break;
        default: // newest
            $sql .= " ORDER BY created_at DESC";
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $_SESSION['error'] = "Error fetching testimonials.";
    $testimonials = [];
}
?>

<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold">Manajemen Testimoni</h1>
    </div>
    
    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       placeholder="Cari berdasarkan nama atau konten..." 
                       value="<?php echo $search; ?>"
                       class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
            </div>
            
            <div class="w-48">
                <select name="status" 
                        class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
                    <option value="">Semua Status</option>
                    <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="approved" <?php echo $status === 'approved' ? 'selected' : ''; ?>>Approved</option>
                    <option value="rejected" <?php echo $status === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                </select>
            </div>
            
            <div class="w-48">
                <select name="sort" 
                        class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
                    <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Terbaru</option>
                    <option value="oldest" <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>Terlama</option>
                    <option value="rating_high" <?php echo $sort === 'rating_high' ? 'selected' : ''; ?>>Rating Tertinggi</option>
                    <option value="rating_low" <?php echo $sort === 'rating_low' ? 'selected' : ''; ?>>Rating Terendah</option>
                </select>
            </div>
            
            <button type="submit" 
                    class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
        </form>
    </div>
    
    <!-- Testimonials Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if (!empty($testimonials)): ?>
            <?php foreach ($testimonials as $testimonial): ?>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        <?php if ($testimonial['photo']): ?>
                        <img src="../assets/images/testimonials/<?php echo $testimonial['photo']; ?>" 
                             alt="<?php echo $testimonial['customer_name']; ?>" 
                             class="w-12 h-12 rounded-full object-cover">
                        <?php else: ?>
                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <?php endif; ?>
                        
                        <div class="ml-4">
                            <h3 class="font-medium"><?php echo $testimonial['customer_name']; ?></h3>
                            <div class="flex text-yellow-400">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $testimonial['rating']): ?>
                                    <i class="fas fa-star"></i>
                                    <?php else: ?>
                                    <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <form method="POST" class="inline">
                            <input type="hidden" name="testimonial_id" value="<?php echo $testimonial['id']; ?>">
                            <input type="hidden" name="update_status" value="1">
                            <select name="status" 
                                    onchange="this.form.submit()"
                                    class="border-gray-300 rounded-md text-sm focus:ring-rose-500 focus:border-rose-500 
                                        <?php echo $testimonial['status'] === 'approved' ? 'bg-green-100' : 
                                            ($testimonial['status'] === 'rejected' ? 'bg-red-100' : 'bg-yellow-100'); ?>">
                                <option value="pending" <?php echo $testimonial['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="approved" <?php echo $testimonial['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                                <option value="rejected" <?php echo $testimonial['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                        </form>
                        
                        <button onclick="showDeleteModal(<?php echo $testimonial['id']; ?>, 'testimonials.php')" 
                                class="text-red-600 hover:text-red-800 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <p class="text-gray-600 mb-2"><?php echo nl2br($testimonial['content']); ?></p>
                
                <p class="text-sm text-gray-500">
                    <?php echo date('d F Y H:i', strtotime($testimonial['created_at'])); ?>
                </p>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-2 text-center py-12 bg-white rounded-lg">
                <div class="w-16 h-16 mx-auto mb-4 text-gray-400">
                    <i class="fas fa-comments text-4xl"></i>
                </div>
                <p class="text-gray-500">Tidak ada testimoni yang ditemukan.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
