<?php
// 引入Markdown解析库
require 'Parsedown.php'; // 需下载Parsedown库放到同级目录

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$article = [];
$error = '';

// 数据库配置
$host = 'localhost';
$dbname = 'itops_help1_center';
$username = 'itops_help1_center';
$password = 'itops_help1_center';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $pdo->prepare("SELECT a.*, c.name as category_name 
                        FROM article a 
                        LEFT JOIN categories c ON a.category_id = c.id 
                        WHERE a.id = :id AND a.status = 1");
    $stmt->execute([':id' => $id]);
    $article = $stmt->fetch();

    if (!$article) {
        $error = "文章不存在或未发布";
    }
} catch (PDOException $e) {
    $error = "加载失败：" . $e->getMessage();
}

// 解析Markdown
$parsedown = new Parsedown();
$contentHtml = $article ? $parsedown->text($article['content']) : '';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $article ? htmlspecialchars($article['title']) : '文章不存在'; ?> - 知识库</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/common.css">
</head>
<body>
    <div class="front-container">
        <header style="margin-bottom: 2rem;">
            <a href="index.php" style="color: #3B82F6; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> 返回列表
            </a>
        </header>

        <?php if ($error): ?>
            <div class="message message-error"><i class="fas fa-exclamation-circle"></i><?php echo $error; ?></div>
        <?php elseif ($article): ?>
            <article class="article-detail">
                <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
                <div class="article-meta">
                    <span><i class="fas fa-folder"></i> <?php echo htmlspecialchars($article['category_name'] ?? '未分类'); ?></span>
                    <span><i class="fas fa-calendar"></i> 发布于：<?php echo $article['create_time']; ?></span>
                    <span><i class="fas fa-sync-alt"></i> 更新于：<?php echo $article['update_time']; ?></span>
                    <span>
                        <?php echo $article['article_type'] == 'steps' ? 
                            '<i class="fas fa-list-ol"></i> 操作步骤' : 
                            '<i class="fas fa-file-alt"></i> 说明文档'; ?>
                    </span>
                </div>
                <div class="article-content">
                    <?php echo $contentHtml; ?>
                </div>
            </article>
        <?php endif; ?>
    </div>
</body>
</html>