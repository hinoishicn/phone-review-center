<?php
session_start();
// 已登录用户直接跳转首页
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户登录 - 帮助中心</title>
    <style>
        .login-box {
            width: 320px;
            margin: 100px auto;
            padding: 25px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-login {
            width: 100%;
            padding: 10px;
            background: #4285f4;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-login:hover {
            background: #3367d6;
        }
        .error-message {
            color: #ff4444;
            text-align: center;
            margin: 10px 0;
            height: 20px;
        }
        .back-home {
            text-align: center;
            margin-top: 15px;
        }
        .back-home a {
            color: #4285f4;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>用户登录</h2>
        <div class="error-message" id="errorMsg"></div>
        <form id="loginForm">
            <div class="form-group">
                <label for="username">用户名</label>
                <input type="text" id="username" name="username" required placeholder="请输入用户名">
            </div>
            <div class="form-group">
                <label for="password">密码</label>
                <input type="password" id="password" name="password" required placeholder="请输入密码">
            </div>
            <button type="submit" class="btn-login">登录</button>
            <div class="back-home">
                <a href="index.php">返回首页</a>
            </div>
        </form>
    </div>

    <script>
        // 登录表单提交处理
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const errorMsg = document.getElementById('errorMsg');
            errorMsg.textContent = '';

            try {
                // 提交到登录接口
                const response = await fetch('api/login.php', {
                    method: 'POST',
                    body: new FormData(e.target)
                });

                // 解析接口响应
                const result = await response.json();
                if (result.status === 'success') {
                    // 登录成功，跳转首页
                    window.location.href = 'index.php';
                } else {
                    // 显示错误信息（如用户名密码错误、数据库异常等）
                    errorMsg.textContent = result.msg;
                }
            } catch (error) {
                // 网络错误或接口无法访问
                errorMsg.textContent = '网络异常，请检查连接';
                console.error('登录请求失败：', error);
            }
        });
    </script>
</body>
</html>