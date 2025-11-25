<?php
/**
 * 手机测评中心 - 初始化文件
 * 
 * @package PhoneReviewCenter
 * @author Phone Review Center Team
 * @version 1.0.0
 */

// 启动会话
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 定义常量
if (!defined('BASE_URL')) {
    define('BASE_URL', '/');
}

if (!defined('SITE_NAME')) {
    define('SITE_NAME', '手机测评中心');
}

if (!defined('SITE_DESCRIPTION')) {
    define('SITE_DESCRIPTION', '专业的手机测评和对比平台，提供详细的手机参数对比和性能评测');
}

if (!defined('CURRENT_YEAR')) {
    define('CURRENT_YEAR', date('Y'));
}

if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', false); // 生产环境设置为false，开发环境可以设置为true
}

/**
 * 自动加载类文件
 */
function autoload($class_name) {
    $directories = [
        __DIR__ . '/../config/',
        __DIR__ . '/../includes/',
        __DIR__ . '/../api/',
        __DIR__ . '/../classes/'
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
}

spl_autoload_register('autoload');

/**
 * 错误处理函数
 */
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return false;
    }
    
    switch ($errno) {
        case E_USER_ERROR:
            echo "<b>错误</b> [$errno] $errstr<br>\n";
            echo "  在文件 $errfile 的第 $errline 行<br>\n";
            exit(1);
            break;
            
        case E_USER_WARNING:
            echo "<b>警告</b> [$errno] $errstr<br>\n";
            break;
            
        case E_USER_NOTICE:
            echo "<b>通知</b> [$errno] $errstr<br>\n";
            break;
            
        default:
            echo "未知错误类型: [$errno] $errstr<br>\n";
            break;
    }
    
    return true;
}

// 设置错误处理函数
set_error_handler('customErrorHandler');

/**
 * 异常处理函数
 */
function customExceptionHandler($exception) {
    $message = $exception->getMessage();
    $file = $exception->getFile();
    $line = $exception->getLine();
    $trace = $exception->getTraceAsString();
    
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        echo "<div style='background: #f8f9fa; border: 1px solid #dee2e6; padding: 20px; margin: 20px; border-radius: 5px;'>";
        echo "<h3 style='color: #dc3545; margin-top: 0;'>系统错误</h3>";
        echo "<p><strong>错误信息:</strong> " . htmlspecialchars($message) . "</p>";
        echo "<p><strong>错误文件:</strong> " . htmlspecialchars($file) . "</p>";
        echo "<p><strong>错误行号:</strong> " . $line . "</p>";
        echo "<p><strong>错误追踪:</strong></p>";
        echo "<pre style='background: #f1f3f4; padding: 10px; border-radius: 3px; overflow-x: auto;'>" . htmlspecialchars($trace) . "</pre>";
        echo "</div>";
    } else {
        echo "<div style='background: #f8f9fa; border: 1px solid #dee2e6; padding: 20px; margin: 20px; border-radius: 5px; text-align: center;'>";
        echo "<h3 style='color: #dc3545; margin-top: 0;'>系统错误</h3>";
        echo "<p>很抱歉，系统遇到了一个错误。请稍后重试或联系技术支持。</p>";
        echo "</div>";
    }
}

// 设置异常处理函数
set_exception_handler('customExceptionHandler');

/**
 * 安全输出函数
 */
function safe_echo($string, $encoding = 'UTF-8') {
    return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
}

/**
 * 生成随机字符串
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $randomString;
}

/**
 * 验证邮箱地址
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * 验证手机号
 */
function validatePhone($phone) {
    return preg_match('/^1[3-9]\d{9}$/', $phone);
}

/**
 * 获取客户端IP地址
 */
function getClientIP() {
    $ip_keys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
    
    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }
    
    return '0.0.0.0';
}

/**
 * 记录日志
 */
function logMessage($level, $message, $context = []) {
    $log_file = __DIR__ . '/../logs/app.log';
    $log_dir = dirname($log_file);
    
    // 创建日志目录
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $context_str = !empty($context) ? json_encode($context) : '';
    $log_entry = "[{$timestamp}] {$level}: {$message} {$context_str}" . PHP_EOL;
    
    // 写入日志文件
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

/**
 * 获取网站配置
 */
function getSiteConfig() {
    return [
        'site_name' => SITE_NAME,
        'site_description' => SITE_DESCRIPTION,
        'base_url' => BASE_URL,
        'current_year' => CURRENT_YEAR
    ];
}

/**
 * 初始化数据库连接
 */
function initDatabase() {
    try {
        return getDatabaseConnection();
    } catch (Exception $e) {
        logMessage('ERROR', '数据库连接失败', ['error' => $e->getMessage()]);
        throw new Exception('数据库连接失败，请检查配置');
    }
}