<?php
// 数据库配置
define('DB_HOST', 'localhost');
define('DB_NAME', 'phone_review');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
define('DB_PORT', 3306);

// 网站配置
define('SITE_NAME', '手机测评中心');
define('SITE_URL', 'http://localhost');
define('UPLOAD_PATH', 'uploads/');
define('ASSETS_URL', 'assets/');

// 分页配置
define('ITEMS_PER_PAGE', 12);

// 错误报告
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 时区设置
date_default_timezone_set('Asia/Shanghai');

// 创建数据库连接
try {
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', 
        DB_HOST, DB_PORT, DB_NAME, DB_CHARSET);
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET . " COLLATE utf8mb4_unicode_ci"
    ]);
} catch (PDOException $e) {
    die("数据库连接失败: " . $e->getMessage() . "\n请检查数据库配置并确保MySQL服务正在运行。");
}

// 启动会话
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * 初始化数据库
 */
function initDatabase($pdo) {
    try {
        // 检查表是否存在
        $stmt = $pdo->query("SHOW TABLES LIKE 'brands'");
        if ($stmt->rowCount() === 0) {
            // 读取SQL文件并执行
            $sql_file = __DIR__ . '/../database/phone_review.sql';
            if (file_exists($sql_file)) {
                $sql = file_get_contents($sql_file);
                $pdo->exec($sql);
                
                // 导入手机数据
                $data_file = __DIR__ . '/../database/phone_data.sql';
                if (file_exists($data_file)) {
                    $data_sql = file_get_contents($data_file);
                    $pdo->exec($data_sql);
                }
                
                return true;
            }
        }
        return false;
    } catch (PDOException $e) {
        die("数据库初始化失败: " . $e->getMessage());
    }
}

// 初始化数据库（首次运行时）
if (!isset($_SESSION['db_initialized'])) {
    if (initDatabase($pdo)) {
        $_SESSION['db_initialized'] = true;
    }
}
?>