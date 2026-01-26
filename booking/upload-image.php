<?php
// Image Upload Handler for Menu Items
// This file handles authenticated image uploads for menu items

// Set up error handling to return errors as JSON
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $error_msg = "Error [$errno] in $errfile:$errline - $errstr";
    error_log("[ERROR-HANDLER] " . $error_msg);
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $errstr,
        'details' => [
            'errno' => $errno,
            'file' => $errfile,
            'line' => $errline
        ]
    ]);
    exit;
});

set_exception_handler(function($exception) {
    error_log("[EXCEPTION] " . $exception->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Server exception: ' . $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine()
    ]);
    exit;
});

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to users

// Log errors
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Debug logging function
function debug_log($msg) {
  $timestamp = date('Y-m-d H:i:s');
  error_log("[{$timestamp}] [UPLOAD-DEBUG] {$msg}");
}

debug_log('=== Upload request started ===');
debug_log('Method: ' . $_SERVER['REQUEST_METHOD']);
debug_log('Remote IP: ' . $_SERVER['REMOTE_ADDR']);

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    debug_log('OPTIONS request received');
    http_response_code(200);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    debug_log('Invalid method: ' . $_SERVER['REQUEST_METHOD']);
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// ==================== Authentication ====================
// Check if user is authenticated via token
function verifyAuthToken() {
    // Get authorization header
    $headers = getallheaders();
    debug_log('Headers received: ' . json_encode(array_keys($headers)));
    
    $auth_header = isset($headers['Authorization']) ? $headers['Authorization'] : '';
    debug_log('Authorization header: ' . ($auth_header ? 'YES (length: ' . strlen($auth_header) . ')' : 'MISSING'));
    
    if (!preg_match('/Bearer\s+(.+)/', $auth_header, $matches)) {
        debug_log('Bearer token parsing failed');
        return false;
    }
    
    $token = $matches[1];
    debug_log('Token extracted: ' . substr($token, 0, 20) . '...');
    
    // Verify with Cognito (you'll need to implement this)
    // For now, we'll do basic validation
    // In production, verify against Cognito or your auth service
    
    // Simple check: token should not be empty
    $result = !empty($token);
    debug_log('Token validation result: ' . ($result ? 'VALID' : 'INVALID'));
    return $result;
}

// ==================== Image Handling ====================
function uploadImage() {
    debug_log('uploadImage() called');
    
    // Verify authentication
    if (!verifyAuthToken()) {
        debug_log('Auth verification failed');
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Unauthorized - Authentication failed']);
        exit;
    }
    
    debug_log('Auth verification passed');
    
    // Check if file was uploaded
    if (!isset($_FILES['image'])) {
        debug_log('No file in $_FILES[image]');
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'No image file provided']);
        exit;
    }
    
    debug_log('File received: ' . $_FILES['image']['name'] . ' (size: ' . $_FILES['image']['size'] . ')');
    
    $file = $_FILES['image'];
    $itemId = isset($_POST['itemId']) ? preg_replace('/[^a-z0-9\-_]/', '', $_POST['itemId']) : null;
    
    debug_log('ItemId: ' . ($itemId ? $itemId : 'MISSING'));
    
    if (!$itemId) {
        debug_log('ItemId validation failed');
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Item ID is required']);
        exit;
    }
    
    // Validate file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        debug_log('File upload error: ' . $file['error']);
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'File upload error: ' . $file['error']]);
        exit;
    }
    
    // Check file type (only images allowed) using getimagesize instead of finfo
    $image_info = @getimagesize($file['tmp_name']);
    if ($image_info === false) {
        debug_log('getimagesize failed - not a valid image file');
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid image file. File is not a valid image.']);
        exit;
    }
    
    // getimagesize returns [0=>width, 1=>height, 2=>type, 3=>"width=X height=Y"]
    $image_type = $image_info[2];
    
    // Allowed image types (PHP constants)
    $allowed_types = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP];
    
    debug_log('Image type detected: ' . $image_type . ' (JPEG=' . IMAGETYPE_JPEG . ', PNG=' . IMAGETYPE_PNG . ', GIF=' . IMAGETYPE_GIF . ', WEBP=' . IMAGETYPE_WEBP . ')');
    
    if (!in_array($image_type, $allowed_types)) {
        debug_log('Invalid image type: ' . $image_type);
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid image type. Only JPEG, PNG, GIF, and WebP allowed']);
        exit;
    }
    
    debug_log('Image type validation passed');
    
    // Check file size (max 5MB)
    $max_size = 5 * 1024 * 1024;
    if ($file['size'] > $max_size) {
        debug_log('File too large: ' . $file['size'] . ' bytes');
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'File too large. Maximum 5MB allowed']);
        exit;
    }
    
    debug_log('File validation passed');
    
    // Create upload directory if it doesn't exist
    $upload_dir = __DIR__ . '/menu-images';
    
    // Log actual server path info
    debug_log('=== PATH DEBUG INFO ===');
    debug_log('__DIR__: ' . __DIR__);
    debug_log('__FILE__: ' . __FILE__);
    debug_log('realpath(__DIR__): ' . realpath(__DIR__));
    debug_log('getcwd(): ' . getcwd());
    debug_log('upload_dir: ' . $upload_dir);
    debug_log('realpath(upload_dir): ' . realpath($upload_dir));
    debug_log('=== END PATH DEBUG ===');
    
    debug_log('Directory exists: ' . (is_dir($upload_dir) ? 'YES' : 'NO'));
    
    if (!is_dir($upload_dir)) {
        debug_log('Creating directory with permissions 0777...');
        if (!@mkdir($upload_dir, 0777, true)) {
            debug_log('Failed to create directory - trying with 0755...');
            if (!@mkdir($upload_dir, 0755, true)) {
                debug_log('Failed to create directory even with 0755');
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Failed to create upload directory. Check server permissions.']);
                exit;
            }
        }
        debug_log('Directory created successfully');
        
        // Create index.php to prevent directory listing
        @file_put_contents($upload_dir . '/index.php', '<?php // Directory for menu images ?>');
        debug_log('index.php created in menu-images directory');
    }
    
    debug_log('Directory exists after check: ' . (is_dir($upload_dir) ? 'YES' : 'NO'));
    debug_log('Directory is writable: ' . (is_writable($upload_dir) ? 'YES' : 'NO'));
    
    // Ensure proper permissions
    if (is_dir($upload_dir) && !is_writable($upload_dir)) {
        debug_log('Directory not writable - attempting chmod...');
        @chmod($upload_dir, 0777);
        debug_log('After chmod - writable: ' . (is_writable($upload_dir) ? 'YES' : 'NO'));
    }
    
    // Create filename (safe, unique)
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $safe_extension = strtolower($extension);
    $filename = $itemId . '-' . time() . '.' . $safe_extension;
    $filepath = $upload_dir . '/' . $filename;
    
    debug_log('=== FILE SAVE ATTEMPT ===');
    debug_log('Filename: ' . $filename);
    debug_log('Filepath: ' . $filepath);
    debug_log('Filepath realpath: ' . realpath($filepath));
    debug_log('Temp file path: ' . $file['tmp_name']);
    debug_log('Temp file exists: ' . (file_exists($file['tmp_name']) ? 'YES' : 'NO'));
    debug_log('Temp file size: ' . (file_exists($file['tmp_name']) ? filesize($file['tmp_name']) : 'N/A') . ' bytes');
    debug_log('=== END FILE INFO ===');
    
    // Move uploaded file
    debug_log('Attempting to move file from temp location...');
    debug_log('is_uploaded_file check: ' . (is_uploaded_file($file['tmp_name']) ? 'YES' : 'NO'));
    
    $move_result = false;
    $move_method = '';
    
    if (is_uploaded_file($file['tmp_name'])) {
        debug_log('Using move_uploaded_file()...');
        $move_result = @move_uploaded_file($file['tmp_name'], $filepath);
        $move_method = 'move_uploaded_file';
        debug_log('move_uploaded_file() returned: ' . ($move_result ? 'TRUE' : 'FALSE'));
    } else {
        debug_log('Not an uploaded file, trying copy()...');
        $move_result = @copy($file['tmp_name'], $filepath);
        $move_method = 'copy';
        debug_log('copy() returned: ' . ($move_result ? 'TRUE' : 'FALSE'));
    }
    
    if (!$move_result) {
        debug_log('Both move_uploaded_file and copy failed');
        debug_log('Last PHP error: ' . (error_get_last() ? error_get_last()['message'] : 'None'));
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'error' => 'Failed to save image using ' . $move_method . '.',
            'details' => [
                'temp_path' => $file['tmp_name'],
                'target_path' => $filepath,
                'directory_writable' => is_writable($upload_dir),
                'directory_exists' => is_dir($upload_dir),
                'upload_dir_realpath' => realpath($upload_dir)
            ]
        ]);
        exit;
    }
    
    debug_log('File operation succeeded with ' . $move_method);
    
    // IMPORTANT: Verify file was actually written before continuing
    debug_log('=== IMMEDIATE VERIFICATION AFTER SAVE ===');
    debug_log('Filepath: ' . $filepath);
    debug_log('File exists (file_exists): ' . (file_exists($filepath) ? 'YES' : 'NO'));
    debug_log('File exists (is_file): ' . (is_file($filepath) ? 'YES' : 'NO'));
    
    // Try to get actual file info
    if (is_file($filepath)) {
        debug_log('File size: ' . filesize($filepath) . ' bytes');
        debug_log('File readable: ' . (is_readable($filepath) ? 'YES' : 'NO'));
    } else {
        debug_log('File not detected by is_file()');
        // List directory to see if it's there
        if (is_dir($upload_dir)) {
            $files = @scandir($upload_dir);
            debug_log('Files in directory: ' . json_encode($files));
            
            // Check if our file is in the list
            if (in_array($filename, $files)) {
                debug_log('File IS in directory listing but is_file returned false!');
                debug_log('This might be a permissions issue');
            }
        }
        debug_log('Trying realpath of target: ' . realpath($filepath));
    }
    debug_log('=== END IMMEDIATE VERIFICATION ===');
    
    if (!is_file($filepath)) {
        debug_log('ERROR: File not created after move_uploaded_file/copy');
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'File was not created. Move/copy reported success but file does not exist.',
            'filepath' => $filepath,
            'upload_dir' => $upload_dir,
            'method_used' => $move_method,
            'dir_contents' => is_dir($upload_dir) ? scandir($upload_dir) : 'N/A'
        ]);
        exit;
    }
    
    $file_size = filesize($filepath);
    debug_log('File size verification: ' . $file_size . ' bytes');
    
    if ($file_size == 0) {
        debug_log('WARNING: File is 0 bytes - upload failed');
        @unlink($filepath);
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'File upload resulted in empty file (0 bytes).'
        ]);
        exit;
    }
    
    debug_log('SUCCESS: File exists and has content');
    
    // Delete old image for this item (if exists)
    deleteOldImages($itemId);
    
    // Return success with image path
    http_response_code(200);
    debug_log('=== UPLOAD COMPLETE ===');
    
    $imageUrl = '/booking/menu-images/' . $filename;
    debug_log('Image URL: ' . $imageUrl);
    debug_log('File saved to: ' . $filepath);
    
    echo json_encode([
        'success' => true,
        'message' => 'Image uploaded successfully',
        'imageUrl' => $imageUrl,
        'filename' => $filename
    ]);
    exit;
}

// Delete old images for a menu item
function deleteOldImages($itemId) {
    $upload_dir = __DIR__ . '/menu-images';
    
    if (!is_dir($upload_dir)) {
        return;
    }
    
    $files = glob($upload_dir . '/' . preg_quote($itemId) . '-*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
    foreach ($files as $file) {
        @unlink($file);
    }
}

// ==================== Main ====================
try {
    debug_log('Calling uploadImage function');
    uploadImage();
    debug_log('uploadImage completed successfully');
} catch (Exception $e) {
    debug_log('Exception caught: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Upload failed: ' . $e->getMessage()
    ]);
}
?>
