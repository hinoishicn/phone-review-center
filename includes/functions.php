<?php
/**
 * 手机测评中心 - 通用函数库
 * 
 * @package PhoneReviewCenter
 * @author Phone Review Center Team
 * @version 1.0.0
 */

/**
 * 获取所有品牌
 */
function getAllBrands($active_only = true) {
    global $pdo;
    $sql = "SELECT b.*, COUNT(p.id) as phone_count 
            FROM brands b 
            LEFT JOIN phones p ON b.id = p.brand_id AND p.status = 'active' 
            WHERE 1=1";
    
    if ($active_only) {
        $sql .= " AND b.status = 'active'";
    }
    
    $sql .= " GROUP BY b.id ORDER BY phone_count DESC, b.name ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * 获取品牌信息
 */
function getBrand($id, $active_only = true) {
    global $pdo;
    $sql = "SELECT * FROM brands WHERE id = ?";
    if ($active_only) {
        $sql .= " AND status = 'active'";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * 获取品牌下的手机
 */
function getBrandPhones($brand_id, $limit = null, $offset = null) {
    global $pdo;
    $sql = "SELECT p.*, b.name as brand_name, ps.processor_name, ps.battery_capacity 
            FROM phones p 
            JOIN brands b ON p.brand_id = b.id 
            LEFT JOIN phone_specs ps ON p.id = ps.phone_id 
            WHERE p.brand_id = ? AND p.status = 'active' 
            ORDER BY p.popularity DESC, p.release_date DESC";
    
    if ($limit) {
        $sql .= " LIMIT ?";
        if ($offset) {
            $sql .= " OFFSET ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$brand_id, $limit, $offset]);
        } else {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$brand_id, $limit]);
        }
    } else {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$brand_id]);
    }
    
    return $stmt->fetchAll();
}

/**
 * 获取所有手机
 */
function getAllPhones($filters = [], $limit = null, $offset = null) {
    global $pdo;
    
    $sql = "SELECT p.*, b.name as brand_name, ps.processor_name, ps.rear_camera_main, ps.battery_capacity 
            FROM phones p 
            JOIN brands b ON p.brand_id = b.id 
            LEFT JOIN phone_specs ps ON p.id = ps.phone_id 
            WHERE p.status = 'active'";
    
    $params = [];
    
    // 品牌筛选
    if (!empty($filters['brand_id'])) {
        $sql .= " AND p.brand_id = ?";
        $params[] = $filters['brand_id'];
    }
    
    // 价格筛选
    if (!empty($filters['price_min'])) {
        $sql .= " AND p.price >= ?";
        $params[] = $filters['price_min'];
    }
    
    if (!empty($filters['price_max'])) {
        $sql .= " AND p.price <= ?";
        $params[] = $filters['price_max'];
    }
    
    // 搜索关键词
    if (!empty($filters['search'])) {
        $sql .= " AND (p.name LIKE ? OR b.name LIKE ?)";
        $params[] = "%{$filters['search']}%";
        $params[] = "%{$filters['search']}%";
    }
    
    // 排序
    $order_by = $filters['order_by'] ?? 'popularity';
    $order_dir = $filters['order_dir'] ?? 'DESC';
    
    switch ($order_by) {
        case 'price':
            $sql .= " ORDER BY p.price $order_dir";
            break;
        case 'name':
            $sql .= " ORDER BY p.name $order_dir";
            break;
        case 'release_date':
            $sql .= " ORDER BY p.release_date $order_dir";
            break;
        default:
            $sql .= " ORDER BY p.popularity DESC, p.release_date DESC";
    }
    
    if ($limit) {
        $sql .= " LIMIT ?";
        $params[] = $limit;
        if ($offset) {
            $sql .= " OFFSET ?";
            $params[] = $offset;
        }
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * 获取热门手机
 */
function getPopularPhones($limit = 10) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.*, b.name as brand_name, ps.processor_name, ps.rear_camera_main, ps.battery_capacity 
                          FROM phones p 
                          JOIN brands b ON p.brand_id = b.id 
                          LEFT JOIN phone_specs ps ON p.id = ps.phone_id 
                          WHERE p.status = 'active' 
                          ORDER BY p.popularity DESC, p.release_date DESC 
                          LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

/**
 * 获取手机详细信息
 */
function getPhoneDetails($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.*, b.name as brand_name, b.name_en as brand_name_en, ps.* 
                          FROM phones p 
                          JOIN brands b ON p.brand_id = b.id 
                          LEFT JOIN phone_specs ps ON p.id = ps.phone_id 
                          WHERE p.id = ? AND p.status = 'active'");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * 获取手机跑分
 */
function getPhoneBenchmarks($phone_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM benchmarks WHERE phone_id = ? ORDER BY benchmark_name, score DESC");
    $stmt->execute([$phone_id]);
    return $stmt->fetchAll();
}

/**
 * 获取手机评测
 */
function getPhoneReviews($phone_id, $limit = null, $status = 'published') {
    global $pdo;
    $sql = "SELECT * FROM reviews WHERE phone_id = ? AND status = ? ORDER BY review_date DESC, created_at DESC";
    $params = [$phone_id, $status];
    
    if ($limit) {
        $sql .= " LIMIT ?";
        $params[] = $limit;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * 添加对比
 */
function addToCompare($phone_id) {
    if (!isset($_SESSION['compare'])) {
        $_SESSION['compare'] = [];
    }
    
    if (!in_array($phone_id, $_SESSION['compare']) && count($_SESSION['compare']) < 4) {
        $_SESSION['compare'][] = $phone_id;
        return true;
    }
    
    return false;
}

/**
 * 移除对比
 */
function removeFromCompare($phone_id) {
    if (isset($_SESSION['compare'])) {
        $_SESSION['compare'] = array_diff($_SESSION['compare'], [$phone_id]);
        $_SESSION['compare'] = array_values($_SESSION['compare']);
        return true;
    }
    
    return false;
}

/**
 * 获取对比列表
 */
function getCompareList() {
    if (!isset($_SESSION['compare']) || empty($_SESSION['compare'])) {
        return [];
    }
    
    global $pdo;
    $placeholders = str_repeat('?,', count($_SESSION['compare']) - 1) . '?';
    $stmt = $pdo->prepare("SELECT p.*, b.name as brand_name 
                          FROM phones p 
                          JOIN brands b ON p.brand_id = b.id 
                          WHERE p.id IN ($placeholders) AND p.status = 'active'");
    $stmt->execute($_SESSION['compare']);
    return $stmt->fetchAll();
}

/**
 * 获取对比数据
 */
function getCompareData($phone_ids) {
    if (empty($phone_ids)) {
        return [];
    }
    
    global $pdo;
    $placeholders = str_repeat('?,', count($phone_ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT p.*, b.name as brand_name, b.name_en as brand_name_en, ps.* 
                          FROM phones p 
                          JOIN brands b ON p.brand_id = b.id 
                          LEFT JOIN phone_specs ps ON p.id = ps.phone_id 
                          WHERE p.id IN ($placeholders) AND p.status = 'active' 
                          ORDER BY FIELD(p.id, $placeholders)");
    
    // 合并参数，先用于WHERE，再用于ORDER BY
    $params = array_merge($phone_ids, $phone_ids);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * 清空对比列表
 */
function clearCompare() {
    $_SESSION['compare'] = [];
}

/**
 * 格式化规格值
 */
function formatSpec($value, $unit = '', $default = '暂无数据') {
    if (empty($value) || $value === 'null' || $value === 'NULL') {
        return $default;
    }
    return $value . ($unit ? ' ' . $unit : '');
}

/**
 * 格式化价格
 */
function formatPrice($price, $currency = '¥') {
    if (empty($price) || $price <= 0) {
        return '价格待定';
    }
    return $currency . number_format($price, 0);
}

/**
 * 获取评分星星
 */
function getRatingStars($rating, $max_stars = 5) {
    if (empty($rating) || $rating <= 0) {
        return str_repeat('<i class="far fa-star text-muted"></i>', $max_stars);
    }
    
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5 ? 1 : 0;
    $empty_stars = $max_stars - $full_stars - $half_star;
    
    $stars = '';
    for ($i = 0; $i < $full_stars; $i++) {
        $stars .= '<i class="fas fa-star text-warning"></i>';
    }
    if ($half_star) {
        $stars .= '<i class="fas fa-star-half-alt text-warning"></i>';
    }
    for ($i = 0; $i < $empty_stars; $i++) {
        $stars .= '<i class="far fa-star text-muted"></i>';
    }
    
    return $stars;
}

/**
 * 安全输出
 */
function safe_echo($string, $encoding = 'UTF-8') {
    return htmlspecialchars($string ?? '', ENT_QUOTES | ENT_HTML5, $encoding);
}

/**
 * 生成SEO友好的URL
 */
function generateSlug($string) {
    $string = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
    $string = trim($string, '-');
    $string = strtolower($string);
    return $string;
}

/**
 * 分页函数
 */
function paginate($total_items, $items_per_page, $current_page, $url_pattern, $max_pages_to_show = 5) {
    if ($total_items <= $items_per_page) {
        return '';
    }
    
    $total_pages = ceil($total_items / $items_per_page);
    if ($total_pages <= 1) {
        return '';
    }
    
    $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
    
    // 上一页
    if ($current_page > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, $current_page - 1) . '">上一页</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">上一页</span></li>';
    }
    
    // 页码
    $start_page = max(1, $current_page - floor($max_pages_to_show / 2));
    $end_page = min($total_pages, $start_page + $max_pages_to_show - 1);
    
    if ($start_page > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, 1) . '">1</a></li>';
        if ($start_page > 2) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }
    
    for ($i = $start_page; $i <= $end_page; $i++) {
        $active = $i == $current_page ? ' active' : '';
        $html .= '<li class="page-item' . $active . '"><a class="page-link" href="' . sprintf($url_pattern, $i) . '">' . $i . '</a></li>';
    }
    
    if ($end_page < $total_pages) {
        if ($end_page < $total_pages - 1) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, $total_pages) . '">' . $total_pages . '</a></li>';
    }
    
    // 下一页
    if ($current_page < $total_pages) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, $current_page + 1) . '">下一页</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">下一页</span></li>';
    }
    
    $html .= '</ul></nav>';
    return $html;
}

/**
 * 记录对比历史
 */
function saveComparisonHistory($phone_ids) {
    if (empty($phone_ids)) {
        return;
    }
    
    global $pdo;
    
    $session_id = session_id() ?: uniqid('session_', true);
    $phone_ids_json = json_encode($phone_ids);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO user_comparisons (session_id, phone_ids) VALUES (?, ?) 
                              ON DUPLICATE KEY UPDATE phone_ids = ?, updated_at = CURRENT_TIMESTAMP");
        $stmt->execute([$session_id, $phone_ids_json, $phone_ids_json]);
    } catch (PDOException $e) {
        // 记录错误但不影响主要功能
        error_log("Failed to save comparison history: " . $e->getMessage());
    }
}

/**
 * 获取对比历史
 */
function getComparisonHistory($limit = 10) {
    global $pdo;
    
    $session_id = session_id() ?: uniqid('session_', true);
    
    $stmt = $pdo->prepare("SELECT * FROM user_comparisons WHERE session_id = ? ORDER BY updated_at DESC LIMIT ?");
    $stmt->execute([$session_id, $limit]);
    
    return $stmt->fetchAll();
}

/**
 * 获取手机基本信息（用于列表显示）
 */
function getPhoneBasicInfo($phone_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT p.*, b.name as brand_name, ps.processor_name, ps.rear_camera_main, ps.battery_capacity 
                          FROM phones p 
                          JOIN brands b ON p.brand_id = b.id 
                          LEFT JOIN phone_specs ps ON p.id = ps.phone_id 
                          WHERE p.id = ? AND p.status = 'active'");
    $stmt->execute([$phone_id]);
    return $stmt->fetch();
}

/**
 * 增加浏览量
 */
function incrementPhoneViews($phone_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE phones SET view_count = view_count + 1 WHERE id = ?");
        $stmt->execute([$phone_id]);
    } catch (PDOException $e) {
        error_log("Failed to increment phone views: " . $e->getMessage());
    }
}

/**
 * 获取相关手机推荐
 */
function getRelatedPhones($phone_id, $limit = 4) {
    global $pdo;
    
    // 获取当前手机的品牌和价格范围
    $current_phone = getPhoneDetails($phone_id);
    if (!$current_phone) {
        return [];
    }
    
    $price_min = $current_phone['price'] * 0.7;
    $price_max = $current_phone['price'] * 1.3;
    
    $stmt = $pdo->prepare("SELECT p.*, b.name as brand_name 
                          FROM phones p 
                          JOIN brands b ON p.brand_id = b.id 
                          WHERE p.id != ? AND p.status = 'active' 
                          AND (p.brand_id = ? OR (p.price BETWEEN ? AND ?)) 
                          ORDER BY p.popularity DESC, p.release_date DESC 
                          LIMIT ?");
    $stmt->execute([$phone_id, $current_phone['brand_id'], $price_min, $price_max, $limit]);
    
    return $stmt->fetchAll();
}

/**
 * 根据ID获取手机信息（别名函数）
 */
function getPhoneById($id) {
    return getPhoneDetails($id);
}

/**
 * 获取手机规格信息
 */
function getPhoneSpecs($phone_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM phone_specs WHERE phone_id = ?");
    $stmt->execute([$phone_id]);
    return $stmt->fetch();
}

/**
 * 根据品牌获取手机列表
 */
function getPhonesByBrand($brand_id, $limit = null, $offset = null) {
    return getBrandPhones($brand_id, $limit, $offset);
}

/**
 * 根据价格范围获取手机
 */
function getPhonesByPriceRange($min_price, $max_price, $limit = null, $offset = null) {
    global $pdo;
    
    $sql = "SELECT p.*, b.name as brand_name, ps.processor_name, ps.rear_camera_main, ps.battery_capacity 
            FROM phones p 
            JOIN brands b ON p.brand_id = b.id 
            LEFT JOIN phone_specs ps ON p.id = ps.phone_id 
            WHERE p.status = 'active' AND p.price BETWEEN ? AND ? 
            ORDER BY p.popularity DESC, p.release_date DESC";
    
    $params = [$min_price, $max_price];
    
    if ($limit) {
        $sql .= " LIMIT ?";
        $params[] = $limit;
        if ($offset) {
            $sql .= " OFFSET ?";
            $params[] = $offset;
        }
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * 获取数据库连接（兼容函数）
 */
function getDatabaseConnection() {
    global $pdo;
    return $pdo;
}
?>