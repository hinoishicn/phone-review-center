# 📱 手机测评中心 (Phone Review Center)

一个专业的手机测评和对比平台，提供真实、详细的手机规格对比和评测数据。

## 🌟 项目特色

- ✅ **真实数据**: 所有手机规格数据均来自官方和权威评测机构
- ✅ **详细对比**: 处理器、摄像头、电池、充电、防水等级等全方位对比
- ✅ **专业评测**: 基于实际测试的专业评测数据
- ✅ **跑分对比**: 安兔兔、Geekbench等权威跑分数据
- ✅ **用户友好**: 直观的对比界面和详细的规格展示

## 🚀 功能特性

### 📊 详细规格对比
- **处理器**: 型号、核心数、频率、制程工艺
- **摄像头**: 主摄、超广角、长焦、微距等详细参数
- **屏幕**: 尺寸、分辨率、刷新率、屏幕类型
- **电池**: 容量、充电功率、无线充电
- **防护**: 防水等级、防尘等级
- **性能**: 跑分数据、游戏性能测试

### 🔍 智能搜索筛选
- 按品牌筛选
- 按价格区间筛选
- 按发布时间筛选
- 按特定规格筛选（如电池容量、摄像头像素等）

### 📈 性能评测
- 安兔兔跑分
- Geekbench 单核/多核成绩
- 3DMark 图形性能
- 实际游戏帧率测试

## 🛠️ 技术栈

- **后端**: PHP 7.4+
- **数据库**: MySQL 5.4+
- **前端**: HTML5, CSS3, JavaScript, Bootstrap 5
- **图标**: Font Awesome
- **图表**: Chart.js

## 📦 安装部署

### 环境要求
- PHP 7.4 或更高版本
- MySQL 5.4 或更高版本
- Apache/Nginx Web服务器

### 安装步骤

1. **克隆项目**
```bash
git clone https://github.com/yourusername/phone-review-center.git
cd phone-review-center
```

2. **配置数据库**
- 创建MySQL数据库
- 导入 `database/phone_review.sql` 文件
- 修改 `config/database.php` 中的数据库连接信息

3. **配置Web服务器**
- 将项目目录设置为Web根目录
- 确保 `uploads` 目录有写入权限

4. **访问网站**
- 打开浏览器访问 `http://localhost/phone-review-center`

## 📁 项目结构

```
phone-review-center/
├── assets/                   # 静态资源
│   ├── css/                 # 样式文件
│   ├── js/                  # JavaScript文件
│   └── images/              # 图片资源
├── config/                    # 配置文件
│   └── database.php          # 数据库配置
├── includes/                  # 包含文件
│   ├── functions.php        # 通用函数
│   └── header.php           # 页面头部
├── uploads/                   # 上传文件目录
├── database/                  # 数据库文件
│   └── phone_review.sql     # 数据库结构
├── index.php                 # 首页
├── phones.php                # 手机列表
├── phone.php                 # 手机详情
├── compare.php               # 手机对比
├── brands.php                # 品牌列表
├── brand.php                 # 品牌详情
├── reviews.php               # 评测文章
├── search.php                # 搜索结果
└── README.md                 # 项目说明
```

## 📱 支持品牌

- Apple iPhone
- Samsung 三星
- Huawei 华为
- Xiaomi 小米
- OPPO
- vivo
- OnePlus 一加
- realme 真我
- Redmi 红米
- Honor 荣耀
- 其他主流品牌

## 📊 数据指标

### 处理器信息
- 型号名称
- 制造工艺
- CPU架构和频率
- GPU型号
- AI性能

### 摄像头规格
- 主摄像头：像素、光圈、传感器尺寸、OIS
- 超广角：像素、光圈、视角
- 长焦：像素、光圈、变焦倍数
- 前置摄像头：像素、光圈
- 视频录制能力

### 电池和充电
- 电池容量 (mAh)
- 有线充电功率 (W)
- 无线充电功率 (W)
- 反向充电支持
- 充电时间测试

### 防护等级
- IP68/IP67防水等级
- 防尘等级
- 材质耐用性
- 跌落测试

## 🤝 贡献指南

欢迎提交Issue和Pull Request来改进项目！

### 提交Issue
- 报告Bug
- 建议新功能
- 数据更新请求

### 提交代码
1. Fork项目
2. 创建特性分支 (`git checkout -b feature/amazing-feature`)
3. 提交更改 (`git commit -m 'Add some amazing feature'`)
4. 推送到分支 (`git push origin feature/amazing-feature`)
5. 创建Pull Request

## 📄 许可证

本项目采用 MIT 许可证 - 查看 [LICENSE](LICENSE) 文件了解详情。

## 📞 联系我们

- 📧 邮箱: contact@phonereview.com
- 🌐 网站: https://phonereview.com
- 📱 微信: PhoneReviewCenter

## ⭐ 支持项目

如果这个项目对您有帮助，请给我们一个⭐ Star！

---

**Made with ❤️ by Phone Review Center Team**