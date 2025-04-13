<?php
// Database connection (SQLite)
$db_path = __DIR__ . '/../database/my_hijab.db';
$db_dir = dirname($db_path);

// Create database directory if it doesn't exist
if (!file_exists($db_dir)) {
    mkdir($db_dir, 0777, true);
}

try {
    $conn = new PDO("sqlite:$db_path");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Enable foreign key support
    $conn->exec('PRAGMA foreign_keys = ON;');
} catch (PDOException $e) {
    error_log('Database connection error: ' . $e->getMessage());
    die('Database connection error.');
}

// Start output buffering
ob_start();

// Enable error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set up error logging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Start session with strict settings
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart session if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Debug session
error_log('Session ID: ' . session_id());
error_log('Session Data: ' . print_r($_SESSION, true));

// Helper functions
function redirect($path) {
    ob_end_clean(); // Clean output buffer before redirect
    header("Location: $path");
    exit();
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function formatPrice($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
}

function displaySuccess($message) {
    return '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">' . $message . '</span>
    </div>';
}

function displayError($message) {
    return '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">' . $message . '</span>
    </div>';
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function uploadImage($file, $upload_dir) {
    $result = ['error' => null, 'filename' => null];
    
    // Check file size (5MB max)
    if ($file['size'] > 5 * 1024 * 1024) {
        $result['error'] = 'File terlalu besar (maksimal 5MB)';
        return $result;
    }
    
    // Check file type
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $allowed)) {
        $result['error'] = 'Format file tidak didukung (JPG, PNG, atau WebP)';
        return $result;
    }
    
    // Generate unique filename
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    
    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $upload_dir . $filename)) {
        $result['error'] = 'Gagal mengupload file';
        return $result;
    }
    
    $result['filename'] = $filename;
    return $result;
}
