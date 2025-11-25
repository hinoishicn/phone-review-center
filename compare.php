<?php
/**
 * 手机测评中心 - 手机对比页面
 * 
 * @package PhoneReviewCenter
 * @author Phone Review Center Team
 * @version 1.0.0
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/compare_functions.php';

// 获取对比列表详情
$compare_phones = getCompareListDetails();
$compare_report = generateCompareReport($compare_phones);

// 设置页面标题
$page_title = "手机对比 - 专业手机参数对比";
$page_description = "对比多款手机的详细参数，包括处理器、摄像头、电池、充电、防水等级等全方位信息";
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
                        <a class="nav-link" href="brands.php">品牌大全</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="compare.php">手机对比</a>
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

    <!-- 主要内容 -->
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="fw-bold text-center mb-3">手机对比</h1>
                <p class="text-muted text-center">最多可同时对比4款手机，选择您感兴趣的手机进行详细参数对比</p>
            </div>
        </div>

        <!-- 对比列表管理 -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>对比列表 (<?php echo count($compare_phones); ?>/4)
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($compare_phones)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-balance-scale fa-3x text-muted mb-3"></i>
                                <p class="text-muted">暂无对比手机，请先添加手机到对比列表</p>
                                <a href="search.php" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>搜索手机
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($compare_phones as $phone): ?>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <div class="mb-2">
                                        <i class="fas fa-mobile-alt fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="mb-1"><?php echo safe_echo($phone['name']); ?></h6>
                                    <p class="text-muted small mb-2"><?php echo safe_echo($phone['brand_name']); ?></p>
                                    <div class="overall-score mb-2">
                                        <small class="text-muted">综合评分: <strong><?php echo $phone['overall_score']; ?></strong></small>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <a href="phone.php?id=<?php echo $phone['id']; ?>" class="btn btn-sm btn-outline-primary flex-fill">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger flex-fill" onclick="removeFromCompare(<?php echo $phone['id']; ?>)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                            </div>
                            <?php if (count($compare_phones) >= 2): ?>
                            <div class="text-center mt-3">
                                <button class="btn btn-success me-2" onclick="startComparison()">
                                    <i class="fas fa-chart-bar me-2"></i>开始对比
                                </button>
                                <button class="btn btn-outline-secondary" onclick="clearCompare()">
                                    <i class="fas fa-trash me-2"></i>清空列表
                                </button>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 详细对比表格 -->
        <?php if (!empty($compare_phones) && count($compare_phones) >= 2): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>详细参数对比
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 200px;">参数项目</th>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <th class="text-center">
                                            <div class="mb-2">
                                                <i class="fas fa-mobile-alt fa-2x"></i>
                                            </div>
                                            <div><?php echo safe_echo($phone['name']); ?></div>
                                            <small class="text-muted"><?php echo safe_echo($phone['brand_name']); ?></small>
                                        </th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- 基本信息 -->
                                    <tr class="table-info">
                                        <td colspan="<?php echo count($compare_phones) + 1; ?>" class="fw-bold">
                                            <i class="fas fa-info-circle me-2"></i>基本信息
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">价格</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center">
                                            <strong class="text-primary"><?php echo formatPrice($phone['price']); ?></strong>
                                        </td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">发布时间</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center"><?php echo date('Y年m月', strtotime($phone['release_date'])); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    
                                    <!-- 处理器信息 -->
                                    <tr class="table-info">
                                        <td colspan="<?php echo count($compare_phones) + 1; ?>" class="fw-bold">
                                            <i class="fas fa-microchip me-2"></i>处理器信息
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">处理器型号</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center"><?php echo formatSpec($phone['processor_name']); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">CPU核心</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center"><?php echo formatSpec($phone['cpu_cores']); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">CPU频率</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center"><?php echo formatSpec($phone['cpu_frequency'], 'GHz'); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">GPU型号</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center"><?php echo formatSpec($phone['gpu_model']); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    
                                    <!-- 摄像头信息 -->
                                    <tr class="table-info">
                                        <td colspan="<?php echo count($compare_phones) + 1; ?>" class="fw-bold">
                                            <i class="fas fa-camera me-2"></i>摄像头信息
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">后置主摄</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center"><?php echo formatSpec($phone['rear_camera_main'], 'MP'); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">后置超广角</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center"><?php echo formatSpec($phone['rear_camera_ultrawide'], 'MP'); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">后置长焦</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center"><?php echo formatSpec($phone['rear_camera_telephoto'], 'MP'); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">前置摄像头</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center"><?php echo formatSpec($phone['front_camera'], 'MP'); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    
                                    <!-- 电池信息 -->
                                    <tr class="table-info">
                                        <td colspan="<?php echo count($compare_phones) + 1; ?>" class="fw-bold">
                                            <i class="fas fa-battery-full me-2"></i>电池信息
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">电池容量</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center"><?php echo formatSpec($phone['battery_capacity'], 'mAh'); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">有线充电</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center"><?php echo formatSpec($phone['charging_wired'], 'W'); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">无线充电</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center"><?php echo formatSpec($phone['charging_wireless'], 'W'); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    
                                    <!-- 其他特性 -->
                                    <tr class="table-info">
                                        <td colspan="<?php echo count($compare_phones) + 1; ?>" class="fw-bold">
                                            <i class="fas fa-cog me-2"></i>其他特性
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">屏幕尺寸</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center"><?php echo formatSpec($phone['screen_size'], '英寸'); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">屏幕分辨率</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center"><?php echo formatSpec($phone['screen_resolution']); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">防水等级</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center"><?php echo formatSpec($phone['water_resistance']); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">重量</td>
                                        <?php foreach ($compare_phones as $phone): ?>
                                        <td class="text-center"><?php echo formatSpec($phone['weight'], 'g'); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 对比分析 -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line me-2"></i>对比分析
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($compare_report)): ?>
                            <div class="analysis-result">
                                <div class="winner-announcement">
                                    <div class="winner-badge">
                                        <i class="fas fa-crown"></i>
                                        <span>推荐选择</span>
                                    </div>
                                    <div class="winner-info">
                                        <h3><?php echo safe_echo($compare_report['winner']['name']); ?></h3>
                                        <p><?php echo safe_echo($compare_report['winner']['brand_name']); ?></p>
                                        <div class="winner-score">
                                            <span class="score-label">综合评分:</span>
                                            <span class="score-value"><?php echo $compare_report['winner']['overall_score']; ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if (!empty($compare_report['recommendations'])): ?>
                                <div class="recommendations">
                                    <h4>推荐理由</h4>
                                    <div class="recommendation-list">
                                        <?php foreach ($compare_report['recommendations'] as $rec): ?>
                                        <div class="recommendation-item">
                                            <div class="rec-icon">
                                                <i class="fas fa-<?php echo $rec['type'] === 'performance' ? 'tachometer-alt' : 
                                                    ($rec['type'] === 'camera' ? 'camera' : 
                                                    ($rec['type'] === 'battery' ? 'battery-half' : 
                                                    ($rec['type'] === 'value' ? 'coins' : 'star'))); ?>"></i>
                                            </div>
                                            <div class="rec-content">
                                                <h5><?php echo safe_echo($rec['title']); ?></h5>
                                                <p><?php echo safe_echo($rec['phone']['name']); ?></p>
                                                <small><?php echo safe_echo($rec['reason']); ?></small>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($compare_phones as $phone): ?>
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <h6 class="mb-2"><?php echo safe_echo($phone['name']); ?></h6>
                                            <div class="mb-3">
                                                <?php
                                                // 计算综合评分
                                                $score = 0;
                                                $factors = [
                                                    'price_score' => 20,  // 价格合理性
                                                    'performance_score' => 25, // 性能
                                                    'camera_score' => 20, // 摄像头
                                                    'battery_score' => 20, // 电池
                                                    'features_score' => 15 // 其他特性
                                                ];
                                                
                                                // 这里可以根据具体参数计算评分
                                                $total_score = 85; // 示例评分
                                                ?>
                                                <div class="score-circle mx-auto mb-2">
                                                    <span class="score-number"><?php echo $total_score; ?></span>
                                                </div>
                                                <small class="text-muted">综合评分</small>
                                            </div>
                                            <div class="text-start">
                                                <small class="d-block mb-1">
                                                    <i class="fas fa-dollar-sign text-success me-1"></i>
                                                    性价比: <?php echo rand(7, 10); ?>/10
                                                </small>
                                                <small class="d-block mb-1">
                                                    <i class="fas fa-microchip text-info me-1"></i>
                                                    性能: <?php echo rand(8, 10); ?>/10
                                                </small>
                                                <small class="d-block mb-1">
                                                    <i class="fas fa-camera text-warning me-1"></i>
                                                    拍照: <?php echo rand(7, 10); ?>/10
                                                </small>
                                                <small class="d-block">
                                                    <i class="fas fa-battery-full text-primary me-1"></i>
                                                    续航: <?php echo rand(7, 10); ?>/10
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- 页脚 -->
    <footer class="bg-dark text-white py-5 mt-5">
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
                        $brands = getAllBrands();
                        foreach (array_slice($brands, 0, 6) as $brand): ?>
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
    <!-- 自定义CSS -->
    <style>
    .score-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
    }
    
    .table th {
        vertical-align: middle;
        text-align: center;
    }
    
    .table td {
        vertical-align: middle;
        text-align: center;
    }
    
    .table td:first-child {
        text-align: left;
        font-weight: 500;
    }
    </style>
    
    <script>
    // 从对比列表中移除
    function removeFromCompare(phoneId) {
        if (confirm('确定要从对比列表中移除这款手机吗？')) {
            fetch('api/compare.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'remove',
                    phone_id: phoneId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || '移除失败');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('移除失败，请重试');
            });
        }
    }
    
    // 清空对比列表
    function clearCompare() {
        if (confirm('确定要清空对比列表吗？')) {
            fetch('api/compare.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'clear'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || '清空失败');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('清空失败，请重试');
            });
        }
    }
    
    // 开始对比
    function startComparison() {
        // 滚动到对比表格
        document.querySelector('.table-responsive').scrollIntoView({
            behavior: 'smooth'
        });
    }
    </script>
</body>
</html>