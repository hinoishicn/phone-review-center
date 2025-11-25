<?php
/**
 * 手机测评中心 - 首页
 * 
 * @package PhoneReviewCenter
 * @author Phone Review Center Team
 * @version 1.0.0
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// 获取热门手机
$popular_phones = getPopularPhones(8);

// 获取热门品牌
$popular_brands = getAllBrands();

// 获取最新手机
$latest_phones = getAllPhones([], 6, 0);

// 设置页面标题
$page_title = "手机测评中心 - 专业手机对比评测平台";
$page_description = "提供最新手机详细对比评测，包括处理器性能、摄像头参数、电池续航、充电速度、防水等级等全方位信息";
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
    
    <!-- PWA配置 -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#667eea">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="手机测评中心">
    <link rel="apple-touch-icon" href="assets/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/icons/icon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/icons/icon-16x16.png">
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
                        <a class="nav-link active" href="index.php">首页</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="brands.php">品牌大全</a>
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

    <!-- 英雄区域 -->
    <section class="hero-section bg-gradient-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">专业手机测评平台</h1>
                    <p class="lead mb-4">提供最新、最真实的手机对比评测信息，包括处理器性能、摄像头参数、电池续航、充电速度、防水等级等全方位数据</p>
                    <div class="d-flex gap-3">
                        <a href="search.php" class="btn btn-light btn-lg px-4">
                            <i class="fas fa-search me-2"></i>搜索手机
                        </a>
                        <a href="compare.php" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-balance-scale me-2"></i>开始对比
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="hero-image">
                        <i class="fas fa-mobile-alt fa-10x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 热门品牌 -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h2 class="fw-bold mb-3">热门品牌</h2>
                    <p class="text-muted">汇聚全球知名手机品牌，为您提供最全面的产品信息</p>
                </div>
            </div>
            <div class="row">
                <?php foreach ($popular_brands as $brand): ?>
                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
                    <a href="brand.php?id=<?php echo $brand['id']; ?>" class="text-decoration-none">
                        <div class="card brand-card h-100 text-center p-3">
                            <div class="brand-logo mb-2">
                                <i class="fas fa-mobile-alt fa-2x text-primary"></i>
                            </div>
                            <h6 class="mb-1"><?php echo safe_echo($brand['name']); ?></h6>
                            <small class="text-muted"><?php echo $brand['phone_count']; ?> 款机型</small>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- 热门手机推荐 -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h2 class="fw-bold mb-3">热门手机推荐</h2>
                    <p class="text-muted">基于真实用户数据和专业评测，为您推荐最受欢迎的手机</p>
                </div>
            </div>
            <div class="row">
                <?php foreach ($popular_phones as $phone): ?>
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
            <div class="text-center">
                <a href="search.php" class="btn btn-outline-primary">查看更多手机</a>
            </div>
        </div>
    </section>

    <!-- 功能特色 -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h2 class="fw-bold mb-3">功能特色</h2>
                    <p class="text-muted">专业的评测体系，为您提供最准确的信息</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-microchip fa-3x text-primary"></i>
                        </div>
                        <h5>处理器性能</h5>
                        <p class="text-muted">详细的处理器参数和跑分数据，让您了解手机的真实性能表现</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-camera fa-3x text-primary"></i>
                        </div>
                        <h5>摄像头评测</h5>
                        <p class="text-muted">专业的摄像头参数分析和样张对比，帮您选择拍照效果最好的手机</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-battery-full fa-3x text-primary"></i>
                        </div>
                        <h5>电池续航</h5>
                        <p class="text-muted">真实的电池容量和续航测试数据，让您了解手机的续航能力</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-bolt fa-3x text-primary"></i>
                        </div>
                        <h5>充电速度</h5>
                        <p class="text-muted">详细的充电功率和充电时间测试，帮您选择充电最快的手机</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-shield-alt fa-3x text-primary"></i>
                        </div>
                        <h5>防水等级</h5>
                        <p class="text-muted">准确的防水等级信息，让您了解手机的防水性能</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card text-center p-4">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-balance-scale fa-3x text-primary"></i>
                        </div>
                        <h5>智能对比</h5>
                        <p class="text-muted">一键对比多款手机，直观展示各项参数差异</p>
                    </div>
                </div>
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
                        <?php foreach (array_slice($popular_brands, 0, 6) as $brand): ?>
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
    <!-- 自定义JS -->
    <script src="assets/js/main.js"></script>
    
    <!-- PWA Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('service-worker.js')
                    .then(registration => {
                        console.log('ServiceWorker 注册成功:', registration.scope);
                    })
                    .catch(error => {
                        console.log('ServiceWorker 注册失败:', error);
                    });
            });
        }
    </script>
    
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