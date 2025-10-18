<?php
session_start();
// 数据库配置
$host = 'localhost';
$dbname = 'itops_help1_center';
$username = 'itops_help1_center';
$password = 'itops_help1_center';

// 初始化变量
$versions = [];
$totalVersions = 0;
$versionError = '';
$categories = [];
$hotArticles = [];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 版本记录
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM version_detail");
    $totalVersions = (int)$stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT id, version_code, release_date, new_features, bug_fixes 
                        FROM version_detail 
                        ORDER BY release_date DESC 
                        LIMIT 3");
    $versions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($versions) && $totalVersions === 0) {
        $versionError = "暂无版本更新记录，敬请期待...";
    }

    // 文章分类
    $stmt = $pdo->query("SELECT id, name FROM article_category ORDER BY sort DESC LIMIT 10");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 热门文章
    $stmt = $pdo->query("SELECT a.id, a.title, a.cover_img, a.read_count, c.name as category_name 
                        FROM article a 
                        LEFT JOIN article_category c ON a.category_id = c.id 
                        WHERE a.status = 1 
                        ORDER BY a.read_count DESC 
                        LIMIT 10");
    $hotArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $versionError = "数据加载失败：" . $e->getMessage();
    $categories = [];
    $hotArticles = [];
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT技术知识库 - 首页</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <!-- 核心样式（无响应式/弹窗） -->
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="frontend/css/main.css">
    <link rel="stylesheet" href="frontend/css/article.css">
</head>
<body>
    <!-- 导航栏（无汉堡菜单，全显示） -->
    <header class="site-header">
        <div class="container">
            <div class="header-wrapper">
                <!-- LOGO -->
                <a href="index.php" class="header-logo">
                    <span class="logo-icon">📚</span>
                    <span class="logo-text">IT技术知识库</span>
                </a>

                <!-- 导航链接（全显示，小屏自动换行） -->
                <ul class="nav-list">
                    <li><a href="index.php" class="nav-link">首页</a></li>
                    <li><a href="frontend/versions.php" class="nav-link">版本记录</a></li>
                    <li><a href="frontend/articles.php" class="nav-link">文章列表</a></li>
                    <li><a href="frontend/search.php" class="nav-link">搜索</a></li>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                        <li><a href="admin/" class="nav-link">后台管理</a></li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li><span class="nav-user"><?php echo htmlspecialchars($_SESSION['username']); ?></span></li>
                        <li><a href="admin/logout.php" class="nav-link">退出</a></li>
                    <?php else: ?>
                        <li><a href="admin/login.php" class="nav-link">登录</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </header>

    <!-- 搜索栏（固定显示，不弹窗） -->
    <div class="search-container">
        <div class="container">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="搜索技术文档、代码示例..." id="searchInput">
                <button class="btn btn-primary" onclick="doSearch()">搜索</button>
            </div>
        </div>
    </div>

    <!-- 主内容区（流式布局） -->
    <div class="container">
        <h1 class="page-title">技术文档中心</h1>

        <!-- 文章分类（流式网格） -->
        <div class="categories-section">
            <h2 class="section-title" style="--before-content: '📂'">文章分类</h2>
            <?php if (!empty($categories)): ?>
                <div class="row">
                    <?php foreach ($categories as $cate): ?>
                        <div class="col">
                            <div class="category-item card">
                                <a href="frontend/category.php?category=<?php echo $cate['id']; ?>" class="category-link">
                                    <?php echo htmlspecialchars($cate['name']); ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="error-hint">暂无分类数据，请联系管理员添加</div>
            <?php endif; ?>
        </div>

        <!-- 热门文章（流式网格） -->
        <div class="hot-articles-section">
            <h2 class="section-title" style="--before-content: '🔍'">热门技术文档</h2>
            <?php if (!empty($hotArticles)): ?>
                <div class="row">
                    <?php foreach ($hotArticles as $article): ?>
                        <div class="col">
                            <div class="article-card card">
                                <a href="frontend/article_detail.php?id=<?php echo $article['id']; ?>" class="article-link">
                                    <?php if (!empty($article['cover_img'])): ?>
                                        <img src="<?php echo $article['cover_img']; ?>" class="article-cover" alt="<?php echo htmlspecialchars($article['title']); ?>">
                                    <?php endif; ?>
                                    
                                    <div class="article-content">
                                        <span class="article-category"><?php echo htmlspecialchars($article['category_name'] ?? '未分类'); ?></span>
                                        <h3 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                                        <div class="article-meta">
                                            <span>阅读</span>
                                            <span class="read-count"><?php echo $article['read_count']; ?></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="error-hint">暂无热门文章数据</div>
            <?php endif; ?>
        </div>

        <!-- 版本更新记录 -->
        <div class="version-section card">
            <h2 class="section-title" style="--before-content: '📌'">版本更新记录</h2>
            <?php if ($versionError): ?>
                <div class="error-hint"><?php echo $versionError; ?></div>
            <?php else: ?>
                <ul class="version-list">
                    <?php foreach ($versions as $v): ?>
                        <li class="version-item">
                            <div class="version-code"><?php echo htmlspecialchars($v['version_code']); ?></div>
                            <div class="version-date">发布时间：<?php echo $v['release_date']; ?></div>
                            
                            <?php if (!empty($v['new_features'])): ?>
                                <div class="version-details">
                                    <h4>新增功能</h4>
                                    <ul><?php echo nl2br("<li>" . str_replace("\n", "</li><li>", htmlspecialchars($v['new_features'])) . "</li>"); ?></ul>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($v['bug_fixes'])): ?>
                                <div class="version-details">
                                    <h4>修复问题</h4>
                                    <ul><?php echo nl2br("<li>" . str_replace("\n", "</li><li>", htmlspecialchars($v['bug_fixes'])) . "</li>"); ?></ul>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if ($totalVersions > 3): ?>
                    <a href="frontend/versions.php" class="history-link">查看历史更新记录</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // 搜索功能（无弹窗）
        function doSearch() {
            const keyword = document.getElementById('searchInput').value.trim();
            if (keyword) {
                window.location.href = `frontend/search.php?keyword=${encodeURIComponent(keyword)}`;
            } else {
                alert('请输入搜索关键词');
            }
        }

        document.getElementById('searchInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') doSearch();
        });
    </script>
</body>
</html>