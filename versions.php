<?php
session_start();
// 数据库配置
$host = 'localhost';
$dbname = 'itops_help1_center';
$username = 'itops_help1_center';
$password = 'itops_help1_center';

$versions = [];
$versionError = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 查询所有版本记录（按发布时间倒序）
    $stmt = $pdo->query("SELECT id, version_code, release_date, new_features, bug_fixes 
                        FROM version_detail 
                        ORDER BY release_date DESC");
    $versions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($versions)) $versionError = "暂无版本更新记录";

} catch (PDOException $e) {
    $versionError = "数据加载失败：" . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>版本更新记录 - IT技术知识库</title>
    <!-- 引入字体（保持与前台一致） -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- 通用CSS（基础样式+响应式） -->
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/responsive.css">
    
    <!-- 版本页面专用CSS（重新整理的样式） -->
    <link rel="stylesheet" href="css/version.css">
</head>
<body>
    <!-- 导航栏（复用前台导航样式） -->
    <header class="header">
        <div class="header-container">
            <a href="../index.php" class="logo">IT技术知识库</a>
            <div class="nav">
                <a href="../index.php" class="nav-link">首页</a>
                <a href="versions.php" class="nav-link">版本记录</a>
                <a href="articles.php" class="nav-link">文章列表</a>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                    <a href="../admin/" class="nav-link">后台管理</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['username'])): ?>
                    <span class="nav-user"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="../admin/logout.php" class="nav-link">退出</a>
                <?php else: ?>
                    <a href="../admin/login.php" class="nav-link">登录</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- 主内容区（使用版本专用样式类） -->
    <div class="version-page">
        <div class="container">
            <h1 class="version-page-title">所有版本更新记录</h1>

            <?php if ($versionError): ?>
                <div class="error-hint"><?php echo $versionError; ?></div>
            <?php else: ?>
                <div class="version-list-container">
                    <?php foreach ($versions as $v): ?>
                        <div class="version-item">
                            <div class="version-code"><?php echo htmlspecialchars($v['version_code']); ?></div>
                            <div class="version-date">发布时间：<?php echo $v['release_date']; ?></div>
                            
                            <?php if (!empty($v['new_features'])): ?>
                                <div class="version-details">
                                    <h4 class="version-details-title">新增功能</h4>
                                    <ul class="version-details-list">
                                        <?php echo nl2br("<li>" . str_replace("\n", "</li><li>", htmlspecialchars($v['new_features'])) . "</li>"); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($v['bug_fixes'])): ?>
                                <div class="version-details">
                                    <h4 class="version-details-title">修复问题</h4>
                                    <ul class="version-details-list">
                                        <?php echo nl2br("<li>" . str_replace("\n", "</li><li>", htmlspecialchars($v['bug_fixes'])) . "</li>"); ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- 返回首页链接（使用版本专用样式） -->
            <a href="../index.php" class="version-back-link">返回首页</a>
        </div>
    </div>
</body>
</html>