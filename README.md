itops-help-center/
├── index.php                  # 前台入口文件
├── admin.php                  # 后台入口文件
├── README.md                  # 项目说明文档
├── .gitignore                 # Git忽略文件
├── config/                    # 配置文件目录
│   ├── db.php                 # 数据库配置
│   ├── system.php             # 系统基础配置（版本号、站点名等）
│   └── auth.php               # 权限认证配置
├── public/                    # 公共静态资源（对外访问目录）
│   ├── css/                   # 样式文件
│   │   ├── common.css         # 公共样式（响应式基础）
│   │   ├── frontend/          # 前台样式
│   │   └── backend/           # 后台样式
│   ├── js/                    # 脚本文件
│   │   ├── common.js          # 公共脚本（搜索展开/收缩、登录态判断等）
│   │   ├── frontend/          # 前台脚本
│   │   └── backend/           # 后台脚本
│   ├── images/                # 图片资源（LOGO、图标等）
│   └── uploads/               # 上传文件存储（文章图片等）
├── assets/                    # 第三方资源
│   ├── bootstrap/             # Bootstrap响应式框架
│   ├── jquery/                # jQuery库
│   └── editor/                # Markdown编辑器（如editor.md）
├── includes/                  # 公共引入文件
│   ├── header.php             # 前台头部（LOGO、导航栏）
│   ├── footer.php             # 底部信息
│   ├── db_connect.php         # 数据库连接
│   └── auth_check.php         # 登录状态验证
├── frontend/                  # 前台模块
│   ├── views/                 # 前台视图
│   │   ├── index.php          # 首页
│   │   ├── article_list.php   # 文章列表页
│   │   ├── article_detail.php # 文章详情页
│   │   ├── search.php         # 搜索结果页
│   │   ├── user_center.php    # 用户中心（发布文章等）
│   │   ├── login.php          # 登录页
│   │   ├── register.php       # 注册页
│   │   └── versions.php       # 版本更新记录页
│   └── controllers/           # 前台业务逻辑
│       ├── article.php        # 文章相关处理
│       ├── search.php         # 搜索逻辑（含记录存储）
│       └── user.php           # 用户前台操作
├── backend/                   # 后台模块
│   ├── views/                 # 后台视图
│   │   ├── dashboard.php      # 后台首页
│   │   ├── article_manage.php # 文章管理
│   │   ├── category_manage.php# 分类管理
│   │   ├── user_manage.php    # 用户管理
│   │   ├── group_manage.php   # 用户组管理
│   │   ├── points_manage.php  # 积分管理
│   │   ├── badge_manage.php   # 徽章管理
│   │   └── mall_manage.php    # 商城管理
│   └── controllers/           # 后台业务逻辑
│       ├── article.php        # 文章管理逻辑
│       ├── category.php       # 分类管理逻辑
│       └── user.php           # 用户权限管理
├── models/                    # 数据模型
│   ├── User.php               # 用户模型
│   ├── Article.php            # 文章模型
│   ├── Category.php           # 分类模型
│   ├── SearchRecord.php       # 搜索记录模型
│   └── Version.php            # 版本更新模型
└── utils/                     # 工具类
    ├── auth.php               # 登录/注册工具
    ├── markdown.php           # Markdown解析工具
    └── upload.php             # 文件上传工具
