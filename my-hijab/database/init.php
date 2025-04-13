<?php
require_once __DIR__ . '/../includes/config.php';

try {
    // Read and execute the SQL schema
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    
    // Split the SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    // Execute each statement
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $conn->exec($statement);
        }
    }
    
    echo "Database initialized successfully!\n";
} catch (PDOException $e) {
    die("Database initialization failed: " . $e->getMessage() . "\n");
}
