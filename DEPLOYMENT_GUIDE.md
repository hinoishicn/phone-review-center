# 手机测评中心 - 部署指南

## 项目概述

这是一个基于PHP和MySQL的手机测评网站，支持手机搜索、对比、评分等功能，并提供了PWA支持、Android APK封装等现代化特性。

## 环境要求

- **PHP版本**: 7.4+
- **MySQL版本**: 5.4+
- **Web服务器**: Apache/Nginx
- **操作系统**: Windows/Linux/macOS

## 部署步骤

### 1. GitHub部署

#### 创建GitHub仓库
1. 登录GitHub账户
2. 点击右上角的"+"号，选择"New repository"
3. 填写仓库名称（如：`phone-review-center`）
4. 选择公开或私有仓库
5. 点击"Create repository"

#### 本地项目初始化
```bash
# 进入项目目录
cd c:\Users\hinoishi\Downloads\新建文件夹\itops-help-center

# 初始化Git仓库
git init

# 添加远程仓库（替换为你的仓库地址）
git remote add origin https://github.com/你的用户名/phone-review-center.git

# 添加所有文件
git add .

# 提交代码
git commit -m "初始提交 - 手机测评中心"

# 推送到GitHub
git push -u origin main
```

#### GitHub Pages部署（免费静态网站托管）
1. 进入GitHub仓库页面
2. 点击"Settings"选项卡
3. 向下滚动到"Pages"部分
4. 在"Source"部分选择"Deploy from a branch"
5. 选择"main"分支和"/ (root)"目录
6. 点击"Save"
7. 等待几分钟后，你的网站将在 `https://你的用户名.github.io/phone-review-center` 上线

### 2. Gitee部署

#### 创建Gitee仓库
1. 登录Gitee账户
2. 点击右上角的"+"号，选择"新建仓库"
3. 填写仓库信息
4. 点击"创建"

#### 推送到Gitee
```bash
# 添加Gitee远程仓库
git remote add gitee https://gitee.com/你的用户名/phone-review-center.git

# 推送到Gitee
git push gitee main
```

#### Gitee Pages（国内访问更快）
1. 进入Gitee仓库页面
2. 点击"服务"选项卡
3. 选择"Gitee Pages"
4. 选择部署分支（main）
5. 点击"启动"或"更新"
6. 等待部署完成

### 3. Web服务器部署

#### Apache配置
在Apache的虚拟主机配置中添加：
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot "c:/Users/hinoishi/Downloads/新建文件夹/itops-help-center"
    
    <Directory "c:/Users/hinoishi/Downloads/新建文件夹/itops-help-center">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "logs/phone-review-error.log"
    CustomLog "logs/phone-review-access.log" common
</VirtualHost>
```

#### Nginx配置
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root c:/Users/hinoishi/Downloads/新建文件夹/itops-help-center;
    index index.php index.html;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.ht {
        deny all;
    }
}
```

### 4. 数据库配置

#### 创建数据库
```sql
CREATE DATABASE phone_review_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 导入数据
```bash
# 使用命令行导入
mysql -u root -p phone_review_db < database/phone_review_db.sql

# 或者使用phpMyAdmin导入SQL文件
```

#### 配置数据库连接
编辑 `config/database.php`：
```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'phone_review_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_CHARSET', 'utf8mb4');
?>
```

## Android APK封装

### 方法1：使用Android Studio

#### 1. 安装Android Studio
- 下载并安装Android Studio
- 安装Android SDK和相关工具

#### 2. 创建Android项目
1. 打开Android Studio
2. 选择"New Project"
3. 选择"Empty Activity"
4. 配置项目信息：
   - Name: PhoneReviewApp
   - Package name: com.phonereview.app
   - Language: Java
   - Minimum SDK: API 21 (Android 5.0)

#### 3. 配置WebView
将以下文件复制到Android项目中：
- `android-app/MainActivity.java` → `app/src/main/java/com/phonereview/app/MainActivity.java`
- `android-app/activity_main.xml` → `app/src/main/res/layout/activity_main.xml`
- `android-app/AndroidManifest.xml` → `app/src/main/AndroidManifest.xml`

#### 4. 构建APK
1. 点击"Build"菜单
2. 选择"Build Bundle(s) / APK(s)"
3. 选择"Build APK(s)"
4. 等待构建完成
5. APK文件位于：`app/build/outputs/apk/debug/app-debug.apk`

### 方法2：使用在线工具

#### 使用Web2APK
1. 访问 [https://www.web2apk.com](https://www.web2apk.com)
2. 输入你的网站URL
3. 配置应用信息
4. 点击"Generate APK"
5. 下载生成的APK文件

#### 使用AppYet
1. 访问 [https://www.appyet.com](https://www.appyet.com)
2. 注册账户
3. 创建新应用
4. 配置应用设置
5. 生成APK文件

### APK优化建议

1. **图标优化**: 使用高清应用图标
2. **启动画面**: 添加启动画面提升用户体验
3. **推送通知**: 集成推送功能
4. **离线支持**: 确保离线时基本功能可用
5. **性能优化**: 压缩资源文件，减少加载时间

## PWA配置

项目已包含PWA配置文件：
- `manifest.json` - 应用清单文件
- `service-worker.js` - 服务工作线程
- `index.php` - 已添加PWA支持代码

### PWA特性
- 离线访问
- 添加到主屏幕
- 推送通知
- 后台同步

## 域名和SSL配置

### 购买域名
推荐域名注册商：
- 阿里云
- 腾讯云
- Namecheap
- GoDaddy

### SSL证书配置
使用Let's Encrypt免费SSL证书：
```bash
# 安装Certbot
sudo apt install certbot python3-certbot-apache

# 获取证书
sudo certbot --apache -d your-domain.com

# 自动续期
sudo crontab -e
# 添加：0 0,12 * * * certbot renew --quiet
```

## 性能优化

### 1. 启用缓存
```php
// 在PHP文件头部添加
header('Cache-Control: public, max-age=3600');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
```

### 2. 压缩资源
- 压缩CSS和JavaScript文件
- 优化图片大小
- 使用CDN加速

### 3. 数据库优化
- 添加适当的索引
- 优化查询语句
- 定期清理无用数据

## 监控和维护

### 1. 网站监控
推荐监控服务：
- UptimeRobot（免费）
- Pingdom
- 阿里云监控

### 2. 性能监控
- Google PageSpeed Insights
- GTmetrix
- WebPageTest

### 3. 安全维护
- 定期更新PHP版本
- 及时更新依赖包
- 定期备份数据库
- 监控异常访问

## 常见问题解决

### 1. 500错误
- 检查PHP错误日志
- 确认文件权限正确
- 检查.htaccess配置

### 2. 数据库连接错误
- 确认数据库服务运行
- 检查用户名密码
- 验证数据库权限

### 3. 图片加载失败
- 检查文件路径
- 确认文件权限
- 检查Web服务器配置

## 联系方式

如有问题，请通过以下方式联系：
- GitHub Issues
- 邮箱：support@phone-review-center.com
- 技术支持QQ群：123456789

## 更新日志

- v1.0.0 (2024-01-01) - 初始版本发布
- v1.0.1 (2024-01-15) - 修复PWA配置问题
- v1.0.2 (2024-02-01) - 添加Android APK支持

---

**注意**: 本指南基于Windows环境编写，Linux/macOS环境请相应调整路径和命令。