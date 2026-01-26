<?php
header('Content-Type: application/json');

// Handle CORS and OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

// Verify authentication
$token = null;
if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
  $parts = explode(' ', $_SERVER['HTTP_AUTHORIZATION']);
  if (count($parts) === 2 && $parts[0] === 'Bearer') {
    $token = $parts[1];
  }
}

if (!$token) {
  http_response_code(401);
  echo json_encode(['success' => false, 'message' => 'Unauthorized']);
  exit();
}

// For now, we accept any non-empty token (in production, verify against your auth system)
// You could add validation here against your actual auth system if needed
if (strlen($token) < 1) {
  http_response_code(401);
  echo json_encode(['success' => false, 'message' => 'Invalid token']);
  exit();
}

// Get request body
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['itemId']) || !isset($data['names']) || !isset($data['price'])) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Invalid request data']);
  exit();
}

$itemId = $data['itemId'];
$names = $data['names'];
$price = floatval($data['price']);

// Validate price
if ($price < 0) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Price cannot be negative']);
  exit();
}

// Load menu data
$menuFilePath = __DIR__ . '/menu-data.json';
if (!file_exists($menuFilePath)) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Menu file not found']);
  exit();
}

$menuContent = file_get_contents($menuFilePath);
$menuData = json_decode($menuContent, true);

if (!$menuData) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Invalid menu data']);
  exit();
}

// Find and update the item
$found = false;
foreach ($menuData['sections'] as &$section) {
  foreach ($section['items'] as &$item) {
    if ($item['id'] === $itemId) {
      // Update names for all languages
      if (isset($names['en'])) $item['names']['en'] = trim($names['en']);
      if (isset($names['zh'])) $item['names']['zh'] = trim($names['zh']);
      if (isset($names['ko'])) $item['names']['ko'] = trim($names['ko']);
      
      // Update price
      $item['price'] = $price;
      
      $found = true;
      break 2;
    }
  }
}

if (!$found) {
  http_response_code(404);
  echo json_encode(['success' => false, 'message' => 'Item not found']);
  exit();
}

// Save updated menu data back to file
$updatedContent = json_encode($menuData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
if (!file_put_contents($menuFilePath, $updatedContent)) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => 'Failed to save menu data']);
  exit();
}

// Success response
http_response_code(200);
echo json_encode([
  'success' => true,
  'message' => 'Item updated successfully',
  'itemId' => $itemId
]);
?>
