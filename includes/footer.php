<?php
/**
 * 手机测评中心 - 页脚组件
 * 
 * @package PhoneReviewCenter
 * @author Phone Review Center Team
 * @version 1.0.0
 */

if (!defined('BASE_URL')) {
    define('BASE_URL', '/');
}

if (!defined('CURRENT_YEAR')) {
    define('CURRENT_YEAR', date('Y'));
}

if (!defined('SITE_NAME')) {
    define('SITE_NAME', '手机测评中心');
}

?>

<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>关于我们</h3>
                <p>手机测评中心是专业的手机评测和对比平台，为用户提供客观、详细的手机参数对比和性能评测服务。</p>
                <div class="social-links">
                    <a href="#" class="social-link" title="微博">
                        <i class="fab fa-weibo"></i>
                    </a>
                    <a href="#" class="social-link" title="微信">
                        <i class="fab fa-weixin"></i>
                    </a>
                    <a href="#" class="social-link" title="QQ">
                        <i class="fab fa-qq"></i>
                    </a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>热门品牌</h3>
                <ul class="footer-links">
                    <li><a href="<?php echo BASE_URL; ?>brand.php?slug=apple">苹果 Apple</a></li>
                    <li><a href="<?php echo BASE_URL; ?>brand.php?slug=samsung">三星 Samsung</a></li>
                    <li><a href="<?php echo BASE_URL; ?>brand.php?slug=xiaomi">小米 Xiaomi</a></li>
                    <li><a href="<?php echo BASE_URL; ?>brand.php?slug=huawei">华为 Huawei</a></li>
                    <li><a href="<?php echo BASE_URL; ?>brand.php?slug=oppo">OPPO</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>功能服务</h3>
                <ul class="footer-links">
                    <li><a href="<?php echo BASE_URL; ?>search.php">手机搜索</a></li>
                    <li><a href="<?php echo BASE_URL; ?>compare.php">手机对比</a></li>
                    <li><a href="<?php echo BASE_URL; ?>brands.php">品牌大全</a></li>
                    <li><a href="#">跑分排行</a></li>
                    <li><a href="#">新品发布</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>联系我们</h3>
                <ul class="footer-links">
                    <li><i class="fas fa-envelope"></i> contact@phonereview.com</li>
                    <li><i class="fas fa-phone"></i> 400-123-4567</li>
                    <li><i class="fas fa-map-marker-alt"></i> 北京市朝阳区科技园区</li>
                    <li><a href="#">意见反馈</a></li>
                    <li><a href="#">商务合作</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p>&copy; <?php echo CURRENT_YEAR; ?> <?php echo SITE_NAME; ?>. 保留所有权利.</p>
                <div class="footer-bottom-links">
                    <a href="#">隐私政策</a>
                    <a href="#">使用条款</a>
                    <a href="#">免责声明</a>
                    <a href="#">网站地图</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- 返回顶部按钮 -->
<button id="back-to-top" class="back-to-top" title="返回顶部">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- 对比列表悬浮按钮 -->
<div id="compare-float" class="compare-float">
    <a href="<?php echo BASE_URL; ?>compare.php" class="compare-float-btn">
        <i class="fas fa-balance-scale"></i>
        <span class="compare-count">0</span>
    </a>
</div>

<script>
// 返回顶部功能
document.addEventListener('DOMContentLoaded', function() {
    const backToTopBtn = document.getElementById('back-to-top');
    
    // 监听滚动事件
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.classList.add('show');
        } else {
            backToTopBtn.classList.remove('show');
        }
    });
    
    // 点击返回顶部
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // 更新对比数量
    updateCompareCount();
});

// 更新对比数量
function updateCompareCount() {
    fetch('<?php echo BASE_URL; ?>api/index.php?action=compare')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const count = data.data.count;
                const countElement = document.querySelector('.compare-count');
                if (countElement) {
                    countElement.textContent = count;
                    if (count > 0) {
                        countElement.style.display = 'block';
                    } else {
                        countElement.style.display = 'none';
                    }
                }
            }
        })
        .catch(error => console.error('Error:', error));
}

// 添加到对比列表
function addToCompare(phoneId) {
    fetch('<?php echo BASE_URL; ?>api/index.php?action=compare', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'add',
            phone_id: phoneId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // 显示成功消息
            showNotification('success', data.message);
            updateCompareCount();
        } else {
            showNotification('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', '操作失败，请稍后重试');
    });
}

// 显示通知消息
function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    // 显示动画
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // 自动隐藏
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>

<style>
/* 页脚样式 */
.site-footer {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: #ecf0f1;
    padding: 60px 0 20px;
    margin-top: 80px;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
}

.footer-section h3 {
    color: #fff;
    font-size: 1.2rem;
    margin-bottom: 20px;
    font-weight: 600;
}

.footer-section p {
    line-height: 1.6;
    margin-bottom: 20px;
}

.footer-links {
    list-style: none;
    padding: 0;
}

.footer-links li {
    margin-bottom: 10px;
}

.footer-links a {
    color: #bdc3c7;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-links a:hover {
    color: #fff;
}

.footer-links i {
    margin-right: 8px;
    width: 16px;
}

.social-links {
    display: flex;
    gap: 15px;
}

.social-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 20px;
}

.footer-bottom-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.footer-bottom p {
    margin: 0;
    color: #bdc3c7;
}

.footer-bottom-links {
    display: flex;
    gap: 20px;
}

.footer-bottom-links a {
    color: #bdc3c7;
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.footer-bottom-links a:hover {
    color: #fff;
}

/* 返回顶部按钮 */
.back-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 1000;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.back-to-top.show {
    opacity: 1;
    visibility: visible;
}

.back-to-top:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

/* 对比列表悬浮按钮 */
.compare-float {
    position: fixed;
    bottom: 100px;
    right: 30px;
    z-index: 999;
}

.compare-float-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
    color: white;
    border-radius: 50%;
    text-decoration: none;
    box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
    transition: all 0.3s ease;
    position: relative;
}

.compare-float-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(255, 107, 107, 0.5);
}

.compare-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #28a745;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: bold;
    display: none;
}

/* 通知消息样式 */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 5px;
    color: white;
    font-weight: 500;
    z-index: 10000;
    transform: translateX(400px);
    transition: transform 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.notification.show {
    transform: translateX(0);
}

.notification-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.notification-error {
    background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
}

/* 响应式设计 */
@media (max-width: 768px) {
    .footer-content {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .footer-bottom-content {
        flex-direction: column;
        text-align: center;
    }
    
    .back-to-top {
        bottom: 20px;
        right: 20px;
        width: 45px;
        height: 45px;
    }
    
    .compare-float {
        bottom: 80px;
        right: 20px;
    }
    
    .compare-float-btn {
        width: 55px;
        height: 55px;
    }
}
</style>