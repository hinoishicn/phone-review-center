<?php
/**
 * 手机测评中心 - 手机详情页面
 * 
 * @package PhoneReviewCenter
 * @author Phone Review Center Team
 * @version 1.0.0
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// 获取手机ID
$phone_id = $_GET['id'] ?? 0;

// 获取手机详细信息
$phone = getPhoneById($phone_id);
if (!$phone) {
    header('Location: index.php');
    exit;
}

// 获取手机规格
$specs = getPhoneSpecs($phone_id);

// 获取手机跑分
$benchmarks = getPhoneBenchmarks($phone_id);

// 获取手机评测
$reviews = getPhoneReviews($phone_id);

// 获取同品牌其他手机
$same_brand_phones = getPhonesByBrand($phone['brand_id'], 4, $phone_id);

// 获取相似价格手机
$similar_price_phones = getPhonesByPriceRange($phone['price'] * 0.8, $phone['price'] * 1.2, 4, $phone_id);

// 设置页面标题
$page_title = $phone['name'] . ' - 详细参数、价格、评测';
$page_description = $phone['name'] . '详细参数：' . $phone['processor_name'] . '处理器，' . $phone['rear_camera_main'] . '摄像头，' . $phone['battery_capacity'] . 'mAh电池，价格' . formatPrice($phone['price']);
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
                        <a class="nav-link" href="search.php">搜索</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">关于我们</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- 手机基本信息 -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div class="phone-image mb-3">
                                        <i class="fas fa-mobile-alt fa-6x text-primary"></i>
                                    </div>
                                    <div class="mb-3">
                                        <span class="badge bg-primary me-2"><?php echo safe_echo($phone['brand_name']); ?></span>
                                        <?php if ($phone['is_5g']): ?>
                                        <span class="badge bg-success">5G</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary" onclick="addToCompare(<?php echo $phone['id']; ?>)">
                                            <i class="fas fa-plus me-2"></i>加入对比
                                        </button>
                                        <a href="<?php echo safe_echo($phone['official_link']); ?>" target="_blank" class="btn btn-outline-primary">
                                            <i class="fas fa-external-link-alt me-2"></i>官方网站
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h1 class="h2 mb-3"><?php echo safe_echo($phone['name']); ?></h1>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <h4 class="text-primary mb-1"><?php echo formatPrice($phone['price']); ?></h4>
                                            <small class="text-muted">参考价格</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-success mb-1"><?php echo $phone['popularity']; ?></h4>
                                            <small class="text-muted">热门指数</small>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-microchip text-primary me-2"></i>
                                                <span><?php echo safe_echo($phone['processor_name']); ?></span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-camera text-primary me-2"></i>
                                                <span><?php echo safe_echo($phone['rear_camera_main']); ?></span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-battery-full text-primary me-2"></i>
                                                <span><?php echo formatSpec($phone['battery_capacity'], 'mAh'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-tint text-primary me-2"></i>
                                                <span><?php echo safe_echo($phone['water_resistance']); ?></span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-display text-primary me-2"></i>
                                                <span><?php echo safe_echo($phone['screen_size']); ?>" <?php echo safe_echo($phone['screen_resolution']); ?></span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-weight text-primary me-2"></i>
                                                <span><?php echo formatSpec($phone['weight'], 'g'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <span class="badge bg-light text-dark me-2">发布时间：<?php echo $phone['release_date']; ?></span>
                                        <span class="badge bg-light text-dark">操作系统：<?php echo safe_echo($phone['operating_system']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <!-- 跑分信息 -->
                    <?php if (!empty($benchmarks)): ?>
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>性能跑分
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($benchmarks as $benchmark): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span><?php echo safe_echo($benchmark['benchmark_name']); ?></span>
                                    <strong><?php echo number_format($benchmark['score']); ?></strong>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: <?php echo min(100, ($benchmark['score'] / 1000000) * 100); ?>%">
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- 快速对比 -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-balance-scale me-2"></i>快速对比
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">选择其他手机进行对比</p>
                            <div class="mb-3">
                                <select class="form-select" id="compare_phone_select">
                                    <option value="">选择要对比的手机</option>
                                    <?php foreach ($same_brand_phones as $same_phone): ?>
                                    <option value="<?php echo $same_phone['id']; ?>"><?php echo safe_echo($same_phone['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button class="btn btn-primary w-100" onclick="quickCompare()">
                                <i class="fas fa-balance-scale me-2"></i>开始对比
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 详细参数 -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="fw-bold mb-4">详细参数</h2>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">基本参数</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="30%" class="text-muted">手机型号</td>
                                            <td><?php echo safe_echo($phone['name']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">发布时间</td>
                                            <td><?php echo $phone['release_date']; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">操作系统</td>
                                            <td><?php echo safe_echo($phone['operating_system']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">机身尺寸</td>
                                            <td><?php echo safe_echo($phone['dimensions']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">机身重量</td>
                                            <td><?php echo formatSpec($phone['weight'], 'g'); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">机身颜色</td>
                                            <td><?php echo safe_echo($phone['colors']); ?></td>
                                        </tr>
                                    </table>
                                    
                                    <h5 class="text-primary mb-3 mt-4">屏幕参数</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="30%" class="text-muted">屏幕尺寸</td>
                                            <td><?php echo safe_echo($phone['screen_size']); ?>英寸</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">屏幕分辨率</td>
                                            <td><?php echo safe_echo($phone['screen_resolution']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">屏幕材质</td>
                                            <td><?php echo safe_echo($phone['screen_type']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">屏幕刷新率</td>
                                            <td><?php echo safe_echo($phone['screen_refresh_rate']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">屏幕亮度</td>
                                            <td><?php echo safe_echo($phone['screen_brightness']); ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">硬件参数</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="30%" class="text-muted">CPU型号</td>
                                            <td><?php echo safe_echo($phone['processor_name']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">CPU频率</td>
                                            <td><?php echo safe_echo($phone['processor_frequency']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">CPU核心数</td>
                                            <td><?php echo safe_echo($phone['processor_cores']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">GPU型号</td>
                                            <td><?php echo safe_echo($phone['gpu_model']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">运行内存</td>
                                            <td><?php echo safe_echo($phone['ram']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">存储容量</td>
                                            <td><?php echo safe_echo($phone['storage']); ?></td>
                                        </tr>
                                    </table>
                                    
                                    <h5 class="text-primary mb-3 mt-4">摄像头参数</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="30%" class="text-muted">后置主摄</td>
                                            <td><?php echo safe_echo($phone['rear_camera_main']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">后置副摄</td>
                                            <td><?php echo safe_echo($phone['rear_camera_secondary']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">前置摄像头</td>
                                            <td><?php echo safe_echo($phone['front_camera']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">视频录制</td>
                                            <td><?php echo safe_echo($phone['video_recording']); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3 mt-4">电池与充电</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="30%" class="text-muted">电池容量</td>
                                            <td><?php echo formatSpec($phone['battery_capacity'], 'mAh'); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">充电功率</td>
                                            <td><?php echo safe_echo($phone['charging_power']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">无线充电</td>
                                            <td><?php echo $phone['wireless_charging'] ? '支持' : '不支持'; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">反向充电</td>
                                            <td><?php echo $phone['reverse_charging'] ? '支持' : '不支持'; ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3 mt-4">其他功能</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="30%" class="text-muted">指纹识别</td>
                                            <td><?php echo $phone['fingerprint_sensor'] ? '支持' : '不支持'; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">面部识别</td>
                                            <td><?php echo $phone['face_unlock'] ? '支持' : '不支持'; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">防水等级</td>
                                            <td><?php echo safe_echo($phone['water_resistance']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">NFC</td>
                                            <td><?php echo $phone['nfc'] ? '支持' : '不支持'; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">红外遥控</td>
                                            <td><?php echo $phone['infrared'] ? '支持' : '不支持'; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 评测信息 -->
    <?php if (!empty($reviews)): ?>
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="fw-bold mb-4">专业评测</h2>
                    <div class="row">
                        <?php foreach ($reviews as $review): ?>
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="card-title mb-0"><?php echo safe_echo($review['title']); ?></h5>
                                        <span class="badge bg-primary"><?php echo $review['rating']; ?>/10</span>
                                    </div>
                                    <p class="card-text"><?php echo safe_echo($review['summary']); ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">来源：<?php echo safe_echo($review['source']); ?></small>
                                        <a href="<?php echo safe_echo($review['link']); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                            查看详情
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- 相关推荐 -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h3 class="fw-bold">同品牌推荐</h3>
                    <p class="text-muted mb-0"><?php echo safe_echo($phone['brand_name']); ?> 其他热门手机</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="brand.php?id=<?php echo $phone['brand_id']; ?>" class="btn btn-outline-primary">
                        查看全部 <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="row">
                <?php foreach ($same_brand_phones as $same_phone): ?>
                <div class="col-lg-3 col-md-4 mb-4">
                    <div class="card phone-card h-100">
                        <div class="phone-image text-center py-3">
                            <i class="fas fa-mobile-alt fa-3x text-primary"></i>
                        </div>
                        <div class="card-body text-center">
                            <h6 class="card-title"><?php echo safe_echo($same_phone['name']); ?></h6>
                            <p class="card-text text-muted mb-2">
                                <strong><?php echo formatPrice($same_phone['price']); ?></strong>
                            </p>
                            <div class="d-flex gap-2">
                                <a href="phone.php?id=<?php echo $same_phone['id']; ?>" class="btn btn-primary btn-sm flex-fill">
                                    查看详情
                                </a>
                                <button class="btn btn-outline-primary btn-sm" onclick="addToCompare(<?php echo $same_phone['id']; ?>)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
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
                        <?php
                        $all_brands = getAllBrands();
                        foreach (array_slice($all_brands, 0, 6) as $brand): 
                        ?>
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

    // 快速对比
    function quickCompare() {
        const select = document.getElementById('compare_phone_select');
        const phoneId = select.value;
        if (!phoneId) {
            alert('请选择要对比的手机');
            return;
        }
        window.location.href = 'compare.php?phones=<?php echo $phone['id']; ?>, ' + phoneId;
    }
    </script>
</body>
</html>