# 手机测评中心 📱

一个专业的手机测评和对比平台，提供详细的手机参数对比、性能评测和用户评分功能。

## ✨ 功能特色

- 🔍 **手机搜索** - 快速搜索和筛选手机
- ⚖️ **手机对比** - 多维度参数对比
- 📊 **性能跑分** - 处理器和综合性能评分
- 🏷️ **品牌大全** - 汇集全球知名手机品牌
- 📱 **PWA支持** - 可安装为桌面应用
- 🤖 **Android APK** - 封装为原生应用

## 🚀 快速开始

### 环境要求

- PHP 7.4+
- MySQL 5.4+
- Apache/Nginx Web服务器

### 安装步骤

1. **克隆项目**
```bash
git clone https://github.com/你的用户名/phone-review-center.git
cd phone-review-center
```

2. **配置数据库**
```bash
# 创建数据库
mysql -u root -p -e "CREATE DATABASE phone_review_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 导入数据
mysql -u root -p phone_review_db < database/phone_review_db.sql
```

3. **配置连接**
编辑 `config/database.php`：
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'phone_review_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

4. **启动服务**
```bash
# 使用PHP内置服务器
php -S localhost:8000

# 或使用Apache/Nginx
# 将项目部署到Web服务器根目录
```

5. **访问网站**
打开浏览器访问 `http://localhost:8000`

## 📁 项目结构

```
phone-review-center/
├── api/                    # API接口
├── assets/                 # 静态资源
│   ├── css/               # 样式文件
│   ├── js/                # JavaScript文件
│   └── images/            # 图片资源
├── config/                 # 配置文件
├── database/               # 数据库文件
├── includes/               # 包含文件
├── android-app/            # Android应用文件
├── .github/workflows/      # GitHub Actions
├── index.php               # 首页
├── search.php              # 搜索页面
├── compare.php             # 对比页面
├── brands.php              # 品牌页面
├── phone.php               # 手机详情页
├── manifest.json           # PWA配置
├── service-worker.js       # Service Worker
└── DEPLOYMENT_GUIDE.md    # 部署指南
```

## 🛠️ 主要功能模块

### 1. 手机搜索 🔍
- 按品牌、价格、处理器等条件筛选
- 支持模糊搜索和精确匹配
- 实时搜索结果展示

### 2. 手机对比 ⚖️
- 最多支持5款手机同时对比
- 详细参数对比表格
- 可视化对比图表

### 3. 性能评测 📊
- 处理器性能跑分
- 摄像头评分
- 电池续航评分
- 综合评分系统

### 4. 品牌大全 🏷️
- 全球知名手机品牌
- 品牌详细介绍
- 品牌下所有手机列表

## 🎯 部署选项

### 1. GitHub Pages（免费）
```bash
# 推送到GitHub后自动部署
git push origin main
```

### 2. Gitee Pages（国内访问更快）
```bash
# 推送到Gitee后自动部署
git push gitee main
```

### 3. 自建服务器
按照 `DEPLOYMENT_GUIDE.md` 中的详细步骤进行部署。

### 4. Android APK
使用提供的Android应用模板，快速封装为原生应用。

## 📱 PWA特性

- ✅ 离线访问
- ✅ 添加到主屏幕
- ✅ 推送通知
- ✅ 后台同步
- ✅ 原生应用体验

## 🤖 Android应用

项目包含完整的Android WebView应用模板：

- `android-app/MainActivity.java` - 主活动文件
- `android-app/activity_main.xml` - 布局文件
- `android-app/AndroidManifest.xml` - 应用清单
- `android-app/build.gradle` - 构建配置

### 构建APK

1. **使用Android Studio**
   - 打开Android Studio
   - 导入android-app目录
   - 点击Build → Build APK(s)

2. **使用在线工具**
   - Web2APK: https://www.web2apk.com
   - AppYet: https://www.appyet.com

## 🎨 自定义主题

编辑 `assets/css/style.css` 来自定义网站外观：

```css
:root {
    --primary-color: #667eea;
    --secondary-color: #764ba2;
    --accent-color: #f093fb;
    --text-color: #333;
    --bg-color: #f8f9fa;
}
```

## 🔧 API接口

### 获取手机列表
```
GET /api/index.php?action=phones&page=1&limit=20
```

### 搜索手机
```
GET /api/index.php?action=search&q=iPhone&brand=apple
```

### 获取手机详情
```
GET /api/index.php?action=phone&id=123
```

### 添加到对比
```
POST /api/index.php?action=add_to_compare
Body: { "phone_id": 123 }
```

## 📊 数据库结构

### 主要数据表

- `brands` - 品牌表
- `phones` - 手机表
- `specifications` - 规格表
- `reviews` - 评测表
- `comparisons` - 对比表
- `users` - 用户表

## 🚀 性能优化

### 1. 缓存策略
- 浏览器缓存
- 数据库查询缓存
- CDN加速

### 2. 图片优化
- 图片压缩
- WebP格式
- 懒加载

### 3. 代码优化
- CSS/JS压缩
- 数据库索引优化
- 查询优化

## 🔒 安全建议

1. **数据库安全**
   - 使用预处理语句
   - 定期备份数据库
   - 限制数据库权限

2. **文件安全**
   - 设置正确的文件权限
   - 防止目录遍历
   - 文件上传验证

3. **Web安全**
   - 启用HTTPS
   - 防止XSS攻击
   - 防止SQL注入
   - 设置安全头

## 📈 监控和分析

### 网站监控
- UptimeRobot
- Google Analytics
- 百度统计

### 性能监控
- Google PageSpeed Insights
- GTmetrix
- WebPageTest

## 🤝 贡献指南

1. Fork 项目
2. 创建特性分支 (`git checkout -b feature/amazing-feature`)
3. 提交更改 (`git commit -m 'Add some amazing feature'`)
4. 推送到分支 (`git push origin feature/amazing-feature`)
5. 创建 Pull Request

## 📝 更新日志

### v1.0.0 (2024-01-01)
- ✅ 基础功能完成
- ✅ 手机搜索和对比
- ✅ 品牌展示
- ✅ PWA支持
- ✅ Android APK模板

### v1.0.1 (计划中)
- 🔄 用户系统
- 🔄 评论功能
- 🔄 评分系统
- 🔄 管理员后台

## 🐛 已知问题

- 某些老旧浏览器可能不支持PWA功能
- Android WebView在某些设备上可能需要额外配置

## 🎯 路线图

### 短期目标
- [ ] 完善用户系统
- [ ] 添加评论功能
- [ ] 优化移动端体验

### 长期目标
- [ ] AI智能推荐
- [ ] 社区功能
- [ ] 多语言支持
- [ ] 微信小程序

## 📞 支持

遇到问题？请通过以下方式获取帮助：

- 📧 邮箱：support@phone-review-center.com
- 💬 QQ群：123456789
- 🐛 GitHub Issues
- 📖 查看 DEPLOYMENT_GUIDE.md

## 📄 许可证

本项目采用 MIT 许可证 - 查看 [LICENSE](LICENSE) 文件了解详情。

## 🙏 致谢

- Bootstrap - 前端框架
- Font Awesome - 图标库
- jQuery - JavaScript库
- 所有贡献者和测试人员

---

⭐ 如果这个项目对你有帮助，请给我们一个Star！

Made with ❤️ by Phone Review Center Team