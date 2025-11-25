<?php
/**
 * 手机测评中心 - 品牌页面
 * 
 * @package PhoneReviewCenter
 * @author Phone Review Center Team
 * @version 1.0.0
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// 获取品牌ID
$brand_id = $_GET['id'] ?? 0;

// 获取品牌信息
$brand_sql = "SELECT * FROM brands WHERE id = ? AND status = 'active'";
$brand_stmt = $pdo->prepare($brand_sql);
$brand_stmt->execute([$brand_id]);
$brand = $brand_stmt->fetch();

if (!$brand) {
    header('Location: brands.php');
    exit;
}

// 获取该品牌的手机
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 12;
$offset = ($page - 1) * $limit;

$phones_sql = "SELECT p.*, b.name as brand_name, b.slug as brand_slug 
               FROM phones p 
               JOIN brands b ON p.brand_id = b.id 
               WHERE p.brand_id = ? AND p.status = 'active' 
               ORDER BY p.popularity DESC 
               LIMIT ? OFFSET ?";
$phones_stmt = $pdo->prepare($phones_sql);
$phones_stmt->execute([$brand_id, $limit, $offset]);
$phones = $phones_stmt->fetchAll();

// 获取手机总数
$total_sql = "SELECT COUNT(*) as total FROM phones WHERE brand_id = ? AND status = 'active'";
$total_stmt = $pdo->prepare($total_sql);
$total_stmt->execute([$brand_id]);
$total_phones = $total_stmt->fetch()['total'];

// 获取品牌统计信息
$stats_sql = "SELECT 
                COUNT(*) as total_phones,
                AVG(price) as avg_price,
                MAX(price) as max_price,
                MIN(price) as min_price
              FROM phones 
              WHERE brand_id = ? AND status = 'active'";
$stats_stmt = $pdo->prepare($stats_sql);
$stats_stmt->execute([$brand_id]);
$stats = $stats_stmt->fetch();

// 设置页面标题
$page_title = $brand['name'] . ' - 手机大全';
$page_description = $brand['name'] . '手机大全，包含' . $brand['name'] . '所有手机型号的详细参数、价格、评测信息';
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
                        <a class="nav-link active" href="brands.php">品牌大全</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="compare.php">手机对比</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search.php">搜索</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">关于我们</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- 品牌信息 -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <div class="brand-logo me-4">
                                    <div class="brand-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px; font-size: 24px;">
                                        <?php echo mb_substr($brand['name'], 0, 1); ?>
                                    </div>
                                </div>
                                <div>
                                    <h1 class="h2 mb-2"><?php echo safe_echo($brand['name']); ?></h1>
                                    <p class="text-muted mb-0"><?php echo safe_echo($brand['description']); ?></p>
                                </div>
                            </div>
                            
                            <!-- 品牌统计 -->
                            <div class="row text-center">
                                <div class="col-md-3 mb-3">
                                    <div class="bg-light rounded p-3">
                                        <h4 class="text-primary mb-1"><?php echo $stats['total_phones']; ?></h4>
                                        <p class="text-muted mb-0">在售机型</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="bg-light rounded p-3">
                                        <h4 class="text-primary mb-1"><?php echo formatPrice($stats['avg_price']); ?></h4>
                                        <p class="text-muted mb-0">平均价格</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="bg-light rounded p-3">
                                        <h4 class="text-primary mb-1"><?php echo formatPrice($stats['min_price']); ?></h4>
                                        <p class="text-muted mb-0">最低价格</p>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="bg-light rounded p-3">
                                        <h4 class="text-primary mb-1"><?php echo formatPrice($stats['max_price']); ?></h4>
                                        <p class="text-muted mb-0">最高价格</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>品牌信息
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>品牌名称：</strong>
                                <span><?php echo safe_echo($brand['name']); ?></span>
                            </div>
                            <div class="mb-3">
                                <strong>品牌标识：</strong>
                                <span><?php echo safe_echo($brand['slug']); ?></span>
                            </div>
                            <div class="mb-3">
                                <strong>官方网站：</strong>
                                <a href="<?php echo safe_echo($brand['website']); ?>" target="_blank" class="text-decoration-none">
                                    <?php echo safe_echo($brand['website']); ?>
                                </a>
                            </div>
                            <div class="mb-0">
                                <strong>总部位置：</strong>
                                <span><?php echo safe_echo($brand['headquarters']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 手机列表 -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fw-bold mb-3"><?php echo safe_echo($brand['name']); ?> 手机大全</h2>
                    <p class="text-muted mb-0">共找到 <?php echo $total_phones; ?> 款手机</p>
                </div>
            </div>

            <?php if (empty($phones)): ?>
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-mobile-alt fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted mb-3">暂无<?php echo safe_echo($brand['name']); ?>手机</h4>
                        <p class="text-muted mb-4">该品牌暂时没有在售手机</p>
                        <a href="brands.php" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>返回品牌列表
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
                    echo paginate($total_phones, $limit, $page, 'brand.php?id=' . $brand_id . '&page=%d');
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
                        <?php
                        $all_brands = getAllBrands();
                        foreach (array_slice($all_brands, 0, 6) as $b): 
                        ?>
                        <li class="mb-2"><a href="brand.php?id=<?php echo $b['id']; ?>" class="text-muted text-decoration-none"><?php echo safe_echo($b['name']); ?></a></li>
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