<?php
header('Content-Type: application/json');
header('Cache-Control: public, max-age=300'); // Cache for 5 minutes

// Use cache to reduce filesystem scans
$cache_file = sys_get_temp_dir() . '/menu_images_list.json';
$cache_lifetime = 300; // 5 minutes

// Check if cache exists and is still valid
if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $cache_lifetime) {
    echo file_get_contents($cache_file);
    exit;
}

// Get list of available images in menu-images directory
$image_dir = __DIR__ . '/menu-images';

if (!is_dir($image_dir)) {
    http_response_code(404);
    $result = json_encode(['success' => false, 'error' => 'Images directory not found']);
    echo $result;
    exit;
}

// Allowed image extensions
$allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

// Get all files
$files = scandir($image_dir);
$images = [];

foreach ($files as $file) {
    // Skip hidden files and directories
    if ($file[0] === '.') continue;
    
    $filepath = $image_dir . '/' . $file;
    
    // Skip directories and system files
    if (is_dir($filepath) || $file === 'index.php' || $file === '.htaccess') {
        continue;
    }
    
    // Check file extension
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_ext)) {
        continue;
    }
    
    // Get file info
    $images[] = [
        'name' => $file,
        'url' => '/booking/menu-images/' . $file,
        'size' => filesize($filepath),
        'modified' => filemtime($filepath)
    ];
}

// Sort by name
usort($images, function($a, $b) {
    return strcmp($a['name'], $b['name']);
});

// Build response
$response_data = [
    'success' => true,
    'images' => $images,
    'count' => count($images)
];

$response_json = json_encode($response_data);

// Cache the response
@file_put_contents($cache_file, $response_json);

http_response_code(200);
echo $response_json;
?>
