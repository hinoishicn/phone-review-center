<?php
session_start();
// 未登录用户强制跳转至首页
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// 引入数据库配置
require_once 'config/db.php';

$message = '';
$message_type = '';

// 处理表单提交（重置逻辑）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['new_username'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $current_user_id = $_SESSION['user_id'];

    // 输入验证
    if (empty($new_username)) {
        $message = '新用户名不能为空';
        $message_type = 'error';
    } elseif (strlen($new_password) < 6) {
        $message = '新密码长度不能少于6位';
        $message_type = 'error';
    } elseif ($new_password !== $confirm_password) {
        $message = '两次输入的密码不一致';
        $message_type = 'error';
    } else {
        try {
            // 密码加密（安全存储）
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // 更新数据库用户信息
            $stmt = $pdo->prepare("
                UPDATE users 
                SET username = ?, password = ? 
                WHERE id = ?
            ");
            $stmt->execute([$new_username, $hashed_password, $current_user_id]);

            // 同步更新session中的用户名
            $_SESSION['username'] = $new_username;
            
            $message = '用户名和密码已成功重置！';
            $message_type = 'success';
        } catch (PDOException $e) {
            $message = '重置失败：' . $e->getMessage();
            $message_type = 'error';
        }
    }
}

// 获取当前用户信息（用于表单默认值）
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$current_user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>重置用户名和密码</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>IT运维帮助中心 - 重置信息</h1>
    </header>

    <div id="user-info">
        <span>当前用户：<?php echo htmlspecialchars($current_user['username']); ?></span>
        <button onclick="location.href='index.php'">返回首页</button>
        <button onclick="logout()">退出登录</button>
    </div>

    <!-- 重置表单容器 -->
    <div id="reset-container">
        <h2>重置用户名和密码</h2>
        
        <!-- 提示消息（成功/错误） -->
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- 重置表单 -->
        <form id="reset-form" method="POST">
            <div class="form-group">
                <label>新用户名：</label>
                <input type="text" name="new_username" 
                       value="<?php echo htmlspecialchars($current_user['username']); ?>" 
                       required>
            </div>
            <div class="form-group">
                <label>新密码：</label>
                <input type="password" name="new_password" 
                       placeholder="请输入新密码（至少6位）" required>
            </div>
            <div class="form-group">
                <label>确认新密码：</label>
                <input type="password" name="confirm_password" 
                       placeholder="再次输入新密码" required>
            </div>
            <button type="submit" class="login-btn">提交重置</button>
        </form>
    </div>

    <script>
        // 退出登录函数
        async function logout() {
            try {
                const res = await fetch('./api/auth.php?action=logout');
                const data = await res.json();
                if (data.status === 'success') {
                    location.href = 'index.php';
                }
            } catch (err) {
                console.error('退出失败：', err);
            }
        }
    </script>
</body>
</html>