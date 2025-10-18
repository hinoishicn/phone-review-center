<?php
// index.php - 保留原有内容的首页，新增版本记录链接
header("Content-Type: text/html; charset=utf-8");
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>itops-help-center - 首页</title>
    <!-- 保留原有的CSS样式或外部样式表链接 -->
    <style>
        /* 保留项目原有的样式，此处仅为示例 */
        body { 
            font-family: "Microsoft YaHei", sans-serif; 
            margin: 0; 
            padding: 20px; 
            background-color: #f5f5f5;
        }
        .header { 
            border-bottom: 2px solid #333; 
            padding-bottom: 10px; 
            margin-bottom: 30px;
        }
        .nav { 
            margin: 20px 0; 
            padding: 10px; 
            background-color: #fff;
        }
        .nav a { 
            margin-right: 15px; 
            text-decoration: none; 
            color: #0066cc;
        }
        .nav a:hover { 
            text-decoration: underline;
        }
        .content { 
            background-color: #fff; 
            padding: 20px; 
            border-radius: 5px;
        }
        /* 原有其他样式... */
    </style>
</head>
<body>
    <!-- 保留原有的页头内容 -->
    <div class="header">
        <h1>itops-help-center</h1>
        <p>IT运维帮助中心，提供运维支持与指南</p>
    </div>

    <!-- 保留原有的导航栏，新增版本记录链接 -->
    <div class="nav">
        <!-- 原有导航链接 -->
        <a href="index.php">首页</a>
        <a href="docs.php">帮助文档</a>
        <a href="tools.php">运维工具</a>
        <!-- 新增：版本记录链接（指向同目录的version.php） -->
        <a href="version.php">版本记录</a>
        <!-- 其他原有链接... -->
    </div>

    <!-- 保留原有的主要内容区域 -->
    <div class="content">
        <h2>欢迎使用 itops-help-center</h2>
        <p>这里是首页原有的内容，例如：</p>
        <ul>
            <li>最新运维动态</li>
            <li>常用操作指南</li>
            <li>故障排查流程</li>
            <!-- 其他原有内容... -->
        </ul>
    </div>

    <!-- 保留原有的页脚内容 -->
    <div class="footer" style="margin-top: 30px; text-align: center; color: #666;">
        <p>© 2024 itops-help-center 版权所有</p>
    </div>
</body>
</html>
