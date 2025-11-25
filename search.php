<?php
/**
 * 手机测评中心 - 搜索页面
 * 
 * @package PhoneReviewCenter
 * @author Phone Review Center Team
 * @version 1.0.0
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// 获取搜索参数
$search_query = $_GET['q'] ?? '';
$brand_id = $_GET['brand'] ?? '';
$price_min = $_GET['price_min'] ?? '';
$price_max = $_GET['price_max'] ?? '';
$order_by = $_GET['order'] ?? 'popularity';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 12;
$offset = ($page - 1) * $limit;

// 构建筛选条件
$filters = [];
if (!empty($search_query)) {
    $filters['search'] = $search_query;
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
$filters['order_by'] = $order_by;

// 获取所有品牌
$brands = getAllBrands();

// 获取手机总数
$total_sql = "SELECT COUNT(DISTINCT p.id) as total FROM phones p JOIN brands b ON p.brand_id = b.id WHERE p.status = 'active'";
$total_params = [];

if (!empty($search_query)) {
    $total_sql .= " AND (p.name LIKE ? OR b.name LIKE ?)";
    $total_params[] = "%{$search_query}%";
    $total_params[] = "%{$search_query}%";
}
if (!empty($brand_id)) {
    $total_sql .= " AND p.brand_id = ?";
    $total_params[] = $brand_id;
}
if (!empty($price_min)) {
    $total_sql .= " AND p.price >= ?";
    $total_params[] = $price_min;
}
if (!empty($price_max)) {
    $total_sql .= " AND p.price <= ?";
    $total_params[] = $price_max;
}

$total_stmt = $pdo->prepare($total_sql);
$total_stmt->execute($total_params);
$total_phones = $total_stmt->fetch()['total'];

// 获取手机列表
$phones = getAllPhones($filters, $limit, $offset);

// 设置页面标题
$page_title = "搜索手机" . (!empty($search_query) ? " - " . $search_query : "");
$page_description = "搜索手机参数、价格、品牌等信息，找到最适合您的手机";
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo safe_echo($page_title); ?> - 手机测评中心</title>
    <meta name="description" content="<?php echo safe_echo($page_description); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- 自定义CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- 导航栏 -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-mobile-alt me-2"></i>手机测评中心
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">首页</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="brands.php">品牌大全</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="compare.php">手机对比</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="search.php">搜索</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">关于我们</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- 搜索区域 -->
    <section class="bg-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="search.php" class="row g-3">
                                <div class="col-lg-4">
                                    <label for="search" class="form-label">搜索关键词</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" id="search" name="q" 
                                               placeholder="输入手机型号或品牌名称" value="<?php echo safe_echo($search_query); ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <label for="brand" class="form-label">品牌</label>
                                    <select class="form-select" id="brand" name="brand">
                                        <option value="">全部品牌</option>
                                        <?php foreach ($brands as $brand): ?>
                                        <option value="<?php echo $brand['id']; ?>" 
                                                <?php echo $brand_id == $brand['id'] ? 'selected' : ''; ?>>
                                            <?php echo safe_echo($brand['name']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="price_min" class="form-label">最低价格</label>
                                    <input type="number" class="form-control" id="price_min" name="price_min" 
                                           placeholder="0" value="<?php echo safe_echo($price_min); ?>">
                                </div>
                                <div class="col-lg-2">
                                    <label for="price_max" class="form-label">最高价格</label>
                                    <input type="number" class="form-control" id="price_max" name="price_max" 
                                           placeholder="不限" value="<?php echo safe_echo($price_max); ?>">
                                </div>
                                <div class="col-lg-2">
                                    <label for="order" class="form-label">排序方式</label>
                                    <select class="form-select" id="order" name="order">
                                        <option value="popularity" <?php echo $order_by == 'popularity' ? 'selected' : ''; ?>>热门程度</option>
                                        <option value="price" <?php echo $order_by == 'price' ? 'selected' : ''; ?>>价格</option>
                                        <option value="name" <?php echo $order_by == 'name' ? 'selected' : ''; ?>>名称</option>
                                        <option value="release_date" <?php echo $order_by == 'release_date' ? 'selected' : ''; ?>>发布时间</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-2"></i>搜索
                                        </button>
                                        <a href="search.php" class="btn btn-outline-secondary">
                                            <i class="fas fa-redo me-2"></i>重置
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 搜索结果 -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fw-bold mb-3">
                        搜索结果
                        <?php if (!empty($search_query)): ?>
                        <span class="text-muted">"<?php echo safe_echo($search_query); ?>"</span>
                        <?php endif; ?>
                    </h2>
                    <p class="text-muted mb-0">找到 <?php echo $total_phones; ?> 款手机</p>
                </div>
            </div>

            <?php if (empty($phones)): ?>
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted mb-3">未找到相关手机</h4>
                        <p class="text-muted mb-4">请尝试调整搜索条件或关键词</p>
                        <a href="search.php" class="btn btn-primary">
                            <i class="fas fa-redo me-2"></i>重新搜索
                        </a>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="row">
                <?php foreach ($phones as $phone): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card phone-card h-100">
                        <div class="phone-image text-center py-3">
                            <i class="fas fa-mobile-alt fa-4x text-primary"></i>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo safe_echo($phone['name']); ?></h5>
                            <p class="card-text text-muted">
                                <small><?php echo safe_echo($phone['brand_name']); ?></small><br>
                                <strong><?php echo formatPrice($phone['price']); ?></strong>
                            </p>
                            <div class="phone-specs mb-3">
                                <small class="d-block">
                                    <i class="fas fa-microchip me-1"></i><?php echo formatSpec($phone['processor_name']); ?>
                                </small>
                                <small class="d-block">
                                    <i class="fas fa-camera me-1"></i><?php echo formatSpec($phone['rear_camera_main']); ?>
                                </small>
                                <small class="d-block">
                                    <i class="fas fa-battery-full me-1"></i><?php echo formatSpec($phone['battery_capacity'], 'mAh'); ?>
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="phone.php?id=<?php echo $phone['id']; ?>" class="btn btn-primary btn-sm flex-fill">
                                    <i class="fas fa-info-circle me-1"></i>查看详情
                                </a>
                                <button class="btn btn-outline-primary btn-sm" onclick="addToCompare(<?php echo $phone['id']; ?>)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- 分页 -->
            <?php if ($total_phones > $limit): ?>
            <div class="row">
                <div class="col-12">
                    <?php
                    $url_pattern = 'search.php?';
                    $params = [];
                    if (!empty($search_query)) $params[] = 'q=' . urlencode($search_query);
                    if (!empty($brand_id)) $params[] = 'brand=' . $brand_id;
                    if (!empty($price_min)) $params[] = 'price_min=' . $price_min;
                    if (!empty($price_max)) $params[] = 'price_max=' . $price_max;
                    if (!empty($order_by)) $params[] = 'order=' . $order_by;
                    
                    if (!empty($params)) {
                        $url_pattern .= implode('&', $params) . '&page=%d';
                    } else {
                        $url_pattern .= 'page=%d';
                    }
                    
                    echo paginate($total_phones, $limit, $page, $url_pattern);
                    ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- 页脚 -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="mb-3">
                        <i class="fas fa-mobile-alt me-2"></i>手机测评中心
                    </h5>
                    <p class="text-muted">专业的手机评测平台，为您提供最真实、最详细的手机对比信息。</p>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6 class="mb-3">快速导航</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-muted text-decoration-none">首页</a></li>
                        <li class="mb-2"><a href="brands.php" class="text-muted text-decoration-none">品牌大全</a></li>
                        <li class="mb-2"><a href="compare.php" class="text-muted text-decoration-none">手机对比</a></li>
                        <li class="mb-2"><a href="search.php" class="text-muted text-decoration-none">搜索</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6 class="mb-3">热门品牌</h6>
                    <ul class="list-unstyled">
                        <?php foreach (array_slice($brands, 0, 6) as $brand): ?>
                        <li class="mb-2"><a href="brand.php?id=<?php echo $brand['id']; ?>" class="text-muted text-decoration-none"><?php echo safe_echo($brand['name']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="mb-3">联系我们</h6>
                    <p class="text-muted">
                        <i class="fas fa-envelope me-2"></i>contact@phonereview.com<br>
                        <i class="fas fa-phone me-2"></i>400-123-4567
                    </p>
                    <div class="mt-3">
                        <a href="#" class="text-muted me-3"><i class="fab fa-weibo fa-lg"></i></a>
                        <a href="#" class="text-muted me-3"><i class="fab fa-weixin fa-lg"></i></a>
                        <a href="#" class="text-muted"><i class="fab fa-qq fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; 2024 手机测评中心. 保留所有权利.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">基于真实数据 · 专业评测</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // 添加到对比
    function addToCompare(phoneId) {
        fetch('api/compare.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'add',
                phone_id: phoneId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('已添加到对比列表');
            } else {
                alert(data.message || '添加失败');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('添加失败，请重试');
        });
    }
    </script>
</body>
</html>