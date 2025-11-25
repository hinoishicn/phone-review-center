<?php
/**
 * 手机测评中心 - 品牌大全页面
 * 
 * @package PhoneReviewCenter
 * @author Phone Review Center Team
 * @version 1.0.0
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// 获取所有品牌
$brands = getAllBrands();

// 设置页面标题
$page_title = '品牌大全 - 手机测评中心';
$page_description = '手机品牌大全，包含苹果、三星、小米、华为等所有手机品牌的详细信息、最新手机型号和评测数据';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo safe_echo($page_title); ?></title>
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

    <!-- 页面标题 -->
    <section class="bg-primary text-white py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">手机品牌大全</h1>
            <p class="lead mb-0">汇聚全球知名手机品牌，为您提供最全面的手机信息</p>
        </div>
    </section>

    <!-- 品牌列表 -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fw-bold mb-3">热门品牌</h2>
                    <p class="text-muted mb-0">共收录 <?php echo count($brands); ?> 个手机品牌</p>
                </div>
            </div>

            <?php if (empty($brands)): ?>
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted mb-3">暂无品牌数据</h4>
                        <p class="text-muted mb-4">品牌数据正在更新中，请稍后再试</p>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="row">
                <?php foreach ($brands as $brand): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card brand-card h-100 text-center">
                        <div class="card-body">
                            <div class="brand-logo mb-3">
                                <div class="brand-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                     style="width: 60px; height: 60px; font-size: 24px;">
                                    <?php echo mb_substr($brand['name'], 0, 1); ?>
                                </div>
                            </div>
                            <h5 class="card-title mb-2"><?php echo safe_echo($brand['name']); ?></h5>
                            <p class="card-text text-muted mb-3">
                                <small><?php echo safe_echo($brand['description']); ?></small>
                            </p>
                            <div class="mb-3">
                                <span class="badge bg-primary me-2"><?php echo $brand['phone_count']; ?> 款手机</span>
                                <span class="badge bg-secondary"><?php echo safe_echo($brand['country']); ?></span>
                            </div>
                            <a href="brand.php?id=<?php echo $brand['id']; ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>查看详情
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- 品牌分类 -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fw-bold mb-3">按国家分类</h2>
                </div>
            </div>
            
            <?php
            // 按国家分组品牌
            $brands_by_country = [];
            foreach ($brands as $brand) {
                $country = $brand['country'];
                if (!isset($brands_by_country[$country])) {
                    $brands_by_country[$country] = [];
                }
                $brands_by_country[$country][] = $brand;
            }
            ?>
            
            <div class="row">
                <?php foreach ($brands_by_country as $country => $country_brands): ?>
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-flag me-2"></i><?php echo safe_echo($country); ?>品牌
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach (array_slice($country_brands, 0, 6) as $brand): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="brand-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 30px; height: 30px; font-size: 12px;">
                                            <?php echo mb_substr($brand['name'], 0, 1); ?>
                                        </div>
                                        <a href="brand.php?id=<?php echo $brand['id']; ?>" class="text-decoration-none">
                                            <?php echo safe_echo($brand['name']); ?>
                                        </a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (count($country_brands) > 6): ?>
                            <div class="text-center mt-3">
                                <small class="text-muted">还有 <?php echo count($country_brands) - 6; ?> 个品牌</small>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
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
</body>
</html>