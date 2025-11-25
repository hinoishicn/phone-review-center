# 手机测评中心 - Android APK 构建指南

## 快速开始

### 方法1：使用Android Studio（推荐）

1. **安装Android Studio**
   - 下载地址：https://developer.android.com/studio
   - 安装完成后启动Android Studio

2. **创建新项目**
   - 选择 "Empty Activity"
   - 项目名称：PhoneReview
   - 包名：com.phonereview.app
   - 语言：Java
   - 最低SDK：API 21 (Android 5.0)

3. **替换文件**
   将本目录下的文件复制到对应的Android Studio项目目录中：
   - `MainActivity.java` → `app/src/main/java/com/phonereview/app/`
   - `activity_main.xml` → `app/src/main/res/layout/`
   - `AndroidManifest.xml` → `app/src/main/`
   - `build.gradle` → `app/`

4. **修改网址**
   在 `MainActivity.java` 中修改这一行：
   ```java
   webView.loadUrl("https://your-domain.com");
   ```
   替换为你的实际网站地址

5. **构建APK**
   - 点击菜单 Build → Build Bundle(s) / APK(s) → Build APK(s)
   - APK文件将生成在 `app/build/outputs/apk/debug/`

### 方法2：使用在线APK构建工具

1. **WebViewGold** (推荐，付费)
   - 官网：https://webviewgold.com
   - 支持iOS和Android
   - 提供可视化配置界面

2. **GoNative.io**
   - 官网：https://gonative.io
   - 在线配置，自动生成APK
   - 支持高级功能

3. **免费在线工具**
   - https://www.appcreator24.com/
   - https://appmysite.com/

## 配置说明

### 修改应用信息

1. **应用名称**
   在 `strings.xml` 中修改：
   ```xml
   <string name="app_name">手机测评中心</string>
   ```

2. **应用图标**
   - 替换 `mipmap-hdpi/ic_launcher.png` (72x72)
   - 替换 `mipmap-mdpi/ic_launcher.png` (48x48)
   - 替换 `mipmap-xhdpi/ic_launcher.png` (96x96)
   - 替换 `mipmap-xxhdpi/ic_launcher.png` (144x144)
   - 替换 `mipmap-xxxhdpi/ic_launcher.png` (192x192)

3. **应用颜色**
   在 `colors.xml` 中修改：
   ```xml
   <color name="colorPrimary">#667eea</color>
   <color name="colorPrimaryDark">#764ba2</color>
   <color name="colorAccent">#667eea</color>
   ```

### 高级功能配置

#### 添加推送通知
1. 注册Firebase账户
2. 添加Firebase到Android项目
3. 集成Firebase Cloud Messaging

#### 添加离线功能
```java
// 在MainActivity.java中添加
webSettings.setAppCacheEnabled(true);
webSettings.setAppCachePath(getApplicationContext().getCacheDir().getPath());
```

#### 添加分享功能
```java
@JavascriptInterface
public void shareContent(String title, String text) {
    Intent shareIntent = new Intent(Intent.ACTION_SEND);
    shareIntent.setType("text/plain");
    shareIntent.putExtra(Intent.EXTRA_SUBJECT, title);
    shareIntent.putExtra(Intent.EXTRA_TEXT, text);
    startActivity(Intent.createChooser(shareIntent, "分享到"));
}
```

## 发布到应用商店

### Google Play Store
1. 注册开发者账户 ($25)
2. 准备应用截图和描述
3. 上传APK文件
4. 填写应用信息
5. 提交审核

### 国内应用商店
1. **华为应用市场**
2. **小米应用商店**
3. **OPPO应用商店**
4. **vivo应用商店**
5. **应用宝**

### 发布要求
- 应用截图：至少4张 (1080x1920)
- 应用图标：512x512 PNG
- 应用描述：简洁明了
- 隐私政策：必需
- 软件著作权（国内商店）

## 测试APK

### 本地测试
1. 启用手机的"开发者选项"
2. 开启"USB调试"
3. 连接手机到电脑
4. 在Android Studio中点击运行

### 分发测试
1. 生成测试APK：Build → Build APK(s)
2. 通过邮件或链接分发APK
3. 测试人员需要开启"未知来源"安装

## 注意事项

### 性能优化
- 启用缓存
- 优化图片加载
- 减少网络请求
- 使用CDN

### 安全设置
- 使用HTTPS
- 验证SSL证书
- 限制WebView功能
- 添加混淆配置

### 兼容性
- 测试不同Android版本
- 测试不同屏幕尺寸
- 测试不同网络环境

## 常见问题

### Q: APK安装失败？
A: 检查是否开启了"未知来源"安装权限

### Q: 网页加载慢？
A: 优化网站性能，使用CDN加速

### Q: 无法访问某些功能？
A: 检查WebView的JavaScript和权限设置

### Q: 如何更新APK？
A: 修改代码后重新构建，用户需要重新安装

## 技术支持

如有问题，请查看：
- [Android开发者文档](https://developer.android.com)
- [WebView开发指南](https://developer.android.com/guide/webapps/webview)
- 项目GitHub Issues

## 更新日志

### v1.0.0 (2024-01)
- 初始版本
- 基础WebView功能
- 支持JavaScript
- 离线缓存

---

**注意**：构建APK前请确保你的网站已经部署并可以正常访问。