<?php
require_once __DIR__ . '/../includes/config.php'; // Use absolute path
require_once '../includes/functions.php';

// Redirect if already logged in
if (isAdminLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Silakan isi semua field.';
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($admin && password_verify($password, $admin['password_hash'])) {
                $_SESSION['admin_id'] = $admin['id'];
                error_log('Admin found: ' . print_r($admin, true)); // Debug log
                $_SESSION['admin_username'] = $admin['username'];
                redirect('dashboard.php');
            } else {
                $error = 'Username atau password salah.';
            }
        } catch(PDOException $e) {
            $error = 'Terjadi kesalahan. Silakan coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - My Hijab</title>
    
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
<body class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-rose-800">My Hijab</h1>
            <p class="text-gray-600">Admin Panel</p>
        </div>
        
        <!-- Login Form -->
        <div class="bg-white rounded-lg shadow-sm p-8">
            <h2 class="text-2xl font-semibold mb-6">Login</h2>
            
            <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-gray-700 mb-2">Username</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           required 
                           class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500"
                           value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>">
                </div>
                
                <div>
                    <label for="password" class="block text-gray-700 mb-2">Password</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           class="w-full border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500">
                </div>
                
                <button type="submit" 
                        class="w-full bg-rose-600 text-white py-2 rounded-md hover:bg-rose-700 transition-colors">
                    Login
                </button>
            </form>
        </div>
        
        <!-- Back to Store -->
        <div class="text-center mt-6">
            <a href="../index.php" class="text-gray-600 hover:text-rose-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Toko
            </a>
        </div>
    </div>
</body>
</html>
