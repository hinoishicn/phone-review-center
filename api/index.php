<?php
/**
 * 手机测评中心 - API接口
 * 
 * @package PhoneReviewCenter
 * @author Phone Review Center Team
 * @version 1.0.0
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// 设置响应头
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// 处理预检请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 获取请求参数
$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// 处理对比相关API
if ($action === 'compare') {
    handleCompareAPI();
} elseif ($action === 'search') {
    handleSearchAPI();
} elseif ($action === 'phones') {
    handlePhonesAPI();
} elseif ($action === 'brands') {
    handleBrandsAPI();
} else {
    sendResponse(['success' => false, 'message' => '无效的API端点'], 404);
}

/**
 * 处理对比相关API请求
 */
function handleCompareAPI() {
    global $method;
    
    switch ($method) {
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['action'])) {
                sendResponse(['success' => false, 'message' => '缺少操作参数'], 400);
                return;
            }
            
            switch ($data['action']) {
                case 'add':
                    addToCompare($data);
                    break;
                case 'remove':
                    removeFromCompare($data);
                    break;
                case 'clear':
                    clearCompareList();
                    break;
                default:
                    sendResponse(['success' => false, 'message' => '无效的操作'], 400);
            }
            break;
            
        case 'GET':
            getCompareList();
            break;
            
        default:
            sendResponse(['success' => false, 'message' => '不支持的请求方法'], 405);
    }
}

/**
 * 添加到对比列表
 */
function addToCompare($data) {
    if (!isset($data['phone_id'])) {
        sendResponse(['success' => false, 'message' => '缺少手机ID'], 400);
        return;
    }
    
    $phone_id = intval($data['phone_id']);
    
    // 验证手机是否存在
    $phone = getPhoneById($phone_id);
    if (!$phone) {
        sendResponse(['success' => false, 'message' => '手机不存在'], 404);
        return;
    }
    
    // 初始化对比列表
    if (!isset($_SESSION['compare_list'])) {
        $_SESSION['compare_list'] = [];
    }
    
    // 检查是否已存在
    if (in_array($phone_id, $_SESSION['compare_list'])) {
        sendResponse(['success' => false, 'message' => '该手机已在对比列表中'], 400);
        return;
    }
    
    // 检查对比列表数量限制
    if (count($_SESSION['compare_list']) >= 4) {
        sendResponse(['success' => false, 'message' => '对比列表最多只能添加4款手机'], 400);
        return;
    }
    
    // 添加到对比列表
    $_SESSION['compare_list'][] = $phone_id;
    
    sendResponse([
        'success' => true,
        'message' => '已添加到对比列表',
        'data' => [
            'phone_id' => $phone_id,
            'phone_name' => $phone['name'],
            'compare_count' => count($_SESSION['compare_list'])
        ]
    ]);
}

/**
 * 从对比列表移除
 */
function removeFromCompare($data) {
    if (!isset($data['phone_id'])) {
        sendResponse(['success' => false, 'message' => '缺少手机ID'], 400);
        return;
    }
    
    $phone_id = intval($data['phone_id']);
    
    if (!isset($_SESSION['compare_list'])) {
        sendResponse(['success' => false, 'message' => '对比列表为空'], 400);
        return;
    }
    
    // 从对比列表移除
    $key = array_search($phone_id, $_SESSION['compare_list']);
    if ($key !== false) {
        unset($_SESSION['compare_list'][$key]);
        $_SESSION['compare_list'] = array_values($_SESSION['compare_list']); // 重新索引数组
        
        sendResponse([
            'success' => true,
            'message' => '已从对比列表移除',
            'data' => [
                'phone_id' => $phone_id,
                'compare_count' => count($_SESSION['compare_list'])
            ]
        ]);
    } else {
        sendResponse(['success' => false, 'message' => '该手机不在对比列表中'], 404);
    }
}

/**
 * 清空对比列表
 */
function clearCompareList() {
    $_SESSION['compare_list'] = [];
    
    sendResponse([
        'success' => true,
        'message' => '对比列表已清空',
        'data' => ['compare_count' => 0]
    ]);
}

/**
 * 获取对比列表
 */
function getCompareList() {
    if (!isset($_SESSION['compare_list'])) {
        $_SESSION['compare_list'] = [];
    }
    
    $compare_list = [];
    foreach ($_SESSION['compare_list'] as $phone_id) {
        $phone = getPhoneById($phone_id);
        if ($phone) {
            $compare_list[] = [
                'id' => $phone['id'],
                'name' => $phone['name'],
                'brand_name' => $phone['brand_name'],
                'price' => $phone['price'],
                'image' => 'fas fa-mobile-alt' // 使用图标代替图片
            ];
        }
    }
    
    sendResponse([
        'success' => true,
        'data' => [
            'phones' => $compare_list,
            'count' => count($compare_list)
        ]
    ]);
}

/**
 * 处理搜索API
 */
function handleSearchAPI() {
    $query = $_GET['q'] ?? '';
    $brand_id = $_GET['brand'] ?? '';
    $price_min = $_GET['price_min'] ?? '';
    $price_max = $_GET['price_max'] ?? '';
    $limit = min(50, intval($_GET['limit'] ?? 10));
    $page = max(1, intval($_GET['page'] ?? 1));
    $offset = ($page - 1) * $limit;
    
    if (empty($query)) {
        sendResponse(['success' => false, 'message' => '搜索关键词不能为空'], 400);
        return;
    }
    
    $filters = [];
    if (!empty($query)) {
        $filters['search'] = $query;
    }
    if (!empty($brand_id)) {
        $filters['brand_id'] = $brand_id;
    }
    if (!empty($price_min)) {
        $filters['price_min'] = intval($price_min);
    }
    if (!empty($price_max)) {
        $filters['price_max'] = intval($price_max);
    }
    
    $phones = getAllPhones($filters, $limit, $offset);
    
    $result = [];
    foreach ($phones as $phone) {
        $result[] = [
            'id' => $phone['id'],
            'name' => $phone['name'],
            'brand_name' => $phone['brand_name'],
            'price' => $phone['price'],
            'processor' => $phone['processor_name'],
            'camera' => $phone['rear_camera_main'],
            'battery' => $phone['battery_capacity'],
            'image' => 'fas fa-mobile-alt'
        ];
    }
    
    sendResponse([
        'success' => true,
        'data' => [
            'phones' => $result,
            'count' => count($result),
            'query' => $query
        ]
    ]);
}

/**
 * 处理手机列表API
 */
function handlePhonesAPI() {
    $brand_id = $_GET['brand_id'] ?? '';
    $limit = min(50, intval($_GET['limit'] ?? 10));
    $page = max(1, intval($_GET['page'] ?? 1));
    $offset = ($page - 1) * $limit;
    $order_by = $_GET['order'] ?? 'popularity';
    
    $filters = [];
    if (!empty($brand_id)) {
        $filters['brand_id'] = $brand_id;
    }
    $filters['order_by'] = $order_by;
    
    $phones = getAllPhones($filters, $limit, $offset);
    
    $result = [];
    foreach ($phones as $phone) {
        $result[] = [
            'id' => $phone['id'],
            'name' => $phone['name'],
            'brand_name' => $phone['brand_name'],
            'price' => $phone['price'],
            'popularity' => $phone['popularity'],
            'release_date' => $phone['release_date'],
            'image' => 'fas fa-mobile-alt'
        ];
    }
    
    sendResponse([
        'success' => true,
        'data' => [
            'phones' => $result,
            'count' => count($result)
        ]
    ]);
}

/**
 * 处理品牌列表API
 */
function handleBrandsAPI() {
    $brands = getAllBrands();
    
    $result = [];
    foreach ($brands as $brand) {
        $result[] = [
            'id' => $brand['id'],
            'name' => $brand['name'],
            'slug' => $brand['slug'],
            'description' => $brand['description'],
            'country' => $brand['country'],
            'phone_count' => $brand['phone_count'],
            'logo' => 'fas fa-building'
        ];
    }
    
    sendResponse([
        'success' => true,
        'data' => [
            'brands' => $result,
            'count' => count($result)
        ]
    ]);
}

/**
 * 发送JSON响应
 */
function sendResponse($data, $status_code = 200) {
    http_response_code($status_code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}