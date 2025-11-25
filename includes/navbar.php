<?php
/**
 * 手机测评中心 - 导航栏组件
 * 
 * @package PhoneReviewCenter
 * @author Phone Review Center Team
 * @version 1.0.0
 */

if (!defined('BASE_URL')) {
    define('BASE_URL', '/');
}

if (!defined('SITE_NAME')) {
    define('SITE_NAME', '手机测评中心');
}

?>

<nav class="navbar">
    <div class="container">
        <div class="navbar-content">
            <div class="navbar-brand">
                <a href="<?php echo BASE_URL; ?>" class="brand-link">
                    <i class="fas fa-mobile-alt brand-icon"></i>
                    <span class="brand-text"><?php echo SITE_NAME; ?></span>
                </a>
            </div>
            
            <div class="navbar-menu" id="navbarMenu">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>" class="nav-link">首页</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>search.php" class="nav-link">手机搜索</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>compare.php" class="nav-link">手机对比</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle">品牌大全</a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo BASE_URL; ?>brands.php" class="dropdown-item">所有品牌</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a href="<?php echo BASE_URL; ?>brand.php?slug=apple" class="dropdown-item">苹果 Apple</a></li>
                            <li><a href="<?php echo BASE_URL; ?>brand.php?slug=samsung" class="dropdown-item">三星 Samsung</a></li>
                            <li><a href="<?php echo BASE_URL; ?>brand.php?slug=xiaomi" class="dropdown-item">小米 Xiaomi</a></li>
                            <li><a href="<?php echo BASE_URL; ?>brand.php?slug=huawei" class="dropdown-item">华为 Huawei</a></li>
                            <li><a href="<?php echo BASE_URL; ?>brand.php?slug=oppo" class="dropdown-item">OPPO</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">跑分排行</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">新品发布</a>
                    </li>
                </ul>
                
                <div class="navbar-actions">
                    <div class="search-form">
                        <form action="<?php echo BASE_URL; ?>search.php" method="get" class="search-form-inline">
                            <input type="text" name="q" class="search-input" placeholder="搜索手机型号..." required>
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    
                    <div class="compare-indicator" id="compareIndicator">
                        <a href="<?php echo BASE_URL; ?>compare.php" class="compare-link">
                            <i class="fas fa-balance-scale"></i>
                            <span class="compare-count" id="compareCount">0</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="navbar-toggle" id="navbarToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
</nav>

<script>
// 导航栏功能
document.addEventListener('DOMContentLoaded', function() {
    const navbarToggle = document.getElementById('navbarToggle');
    const navbarMenu = document.getElementById('navbarMenu');
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    // 移动端菜单切换
    navbarToggle.addEventListener('click', function() {
        navbarMenu.classList.toggle('active');
        navbarToggle.classList.toggle('active');
    });
    
    // 下拉菜单功能
    dropdownToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // 关闭其他下拉菜单
            dropdownToggles.forEach(function(otherToggle) {
                if (otherToggle !== toggle) {
                    otherToggle.classList.remove('active');
                    otherToggle.nextElementSibling.classList.remove('show');
                }
            });
            
            // 切换当前下拉菜单
            toggle.classList.toggle('active');
            toggle.nextElementSibling.classList.toggle('show');
        });
    });
    
    // 点击外部关闭下拉菜单
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            dropdownToggles.forEach(function(toggle) {
                toggle.classList.remove('active');
                toggle.nextElementSibling.classList.remove('show');
            });
        }
    });
    
    // 滚动时添加阴影效果
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
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
                const countElement = document.getElementById('compareCount');
                const indicator = document.getElementById('compareIndicator');
                
                if (countElement) {
                    countElement.textContent = count;
                    if (count > 0) {
                        countElement.style.display = 'block';
                        indicator.classList.add('has-items');
                    } else {
                        countElement.style.display = 'none';
                        indicator.classList.remove('has-items');
                    }
                }
            }
        })
        .catch(error => console.error('Error:', error));
}
</script>

<style>
/* 导航栏样式 */
.navbar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
    transition: all 0.3s ease;
}

.navbar.scrolled {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.navbar-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
}

.navbar-brand .brand-link {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: white;
    font-size: 1.5rem;
    font-weight: bold;
}

.brand-icon {
    font-size: 2rem;
    margin-right: 10px;
}

.navbar-menu {
    display: flex;
    align-items: center;
    gap: 30px;
}

.navbar-nav {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 25px;
}

.nav-link {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    font-weight: 500;
    padding: 8px 12px;
    border-radius: 5px;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link:hover,
.nav-link.active {
    color: white;
    background: rgba(255, 255, 255, 0.1);
}

.nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
    width: 6px;
    height: 6px;
    background: white;
    border-radius: 50%;
}

/* 下拉菜单 */
.dropdown {
    position: relative;
}

.dropdown-toggle::after {
    content: '\f107';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    margin-left: 5px;
    transition: transform 0.3s ease;
}

.dropdown-toggle.active::after {
    transform: rotate(180deg);
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    min-width: 200px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1001;
    list-style: none;
    padding: 10px 0;
    margin: 0;
}

.dropdown-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-item {
    display: block;
    padding: 10px 20px;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
    border-radius: 0;
}

.dropdown-item:hover {
    background: #f8f9fa;
    color: #667eea;
    padding-left: 25px;
}

.dropdown-divider {
    height: 1px;
    background: #e9ecef;
    margin: 8px 0;
    border: none;
}

/* 搜索表单 */
.navbar-actions {
    display: flex;
    align-items: center;
    gap: 20px;
}

.search-form-inline {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 25px;
    padding: 5px;
    transition: all 0.3s ease;
}

.search-form-inline:focus-within {
    background: rgba(255, 255, 255, 0.3);
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
}

.search-input {
    background: none;
    border: none;
    color: white;
    padding: 8px 15px;
    font-size: 0.9rem;
    width: 200px;
    outline: none;
}

.search-input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.search-btn {
    background: none;
    border: none;
    color: white;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.search-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

/* 对比指示器 */
.compare-indicator {
    position: relative;
}

.compare-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
}

.compare-link:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

.compare-indicator.has-items .compare-link {
    background: #ff6b6b;
    animation: pulse 2s infinite;
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

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 107, 107, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(255, 107, 107, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 107, 107, 0);
    }
}

/* 移动端菜单按钮 */
.navbar-toggle {
    display: none;
    flex-direction: column;
    cursor: pointer;
    padding: 5px;
}

.navbar-toggle span {
    width: 25px;
    height: 3px;
    background: white;
    margin: 3px 0;
    transition: 0.3s;
    border-radius: 2px;
}

.navbar-toggle.active span:nth-child(1) {
    transform: rotate(-45deg) translate(-5px, 6px);
}

.navbar-toggle.active span:nth-child(2) {
    opacity: 0;
}

.navbar-toggle.active span:nth-child(3) {
    transform: rotate(45deg) translate(-5px, -6px);
}

/* 响应式设计 */
@media (max-width: 1200px) {
    .navbar-nav {
        gap: 15px;
    }
    
    .search-input {
        width: 150px;
    }
}

@media (max-width: 992px) {
    .navbar-menu {
        position: fixed;
        top: 0;
        right: -100%;
        width: 80%;
        max-width: 400px;
        height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        flex-direction: column;
        justify-content: flex-start;
        padding: 80px 30px 30px;
        transition: right 0.3s ease;
        z-index: 999;
        overflow-y: auto;
    }
    
    .navbar-menu.active {
        right: 0;
    }
    
    .navbar-nav {
        flex-direction: column;
        width: 100%;
        gap: 0;
        margin-bottom: 30px;
    }
    
    .nav-item {
        width: 100%;
    }
    
    .nav-link {
        display: block;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 5px;
    }
    
    .dropdown-menu {
        position: static;
        opacity: 1;
        visibility: visible;
        transform: none;
        box-shadow: none;
        background: rgba(255, 255, 255, 0.1);
        margin-top: 5px;
        display: none;
    }
    
    .dropdown-menu.show {
        display: block;
    }
    
    .dropdown-item {
        color: rgba(255, 255, 255, 0.9);
    }
    
    .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }
    
    .navbar-actions {
        flex-direction: column;
        width: 100%;
        gap: 15px;
    }
    
    .search-form-inline {
        width: 100%;
    }
    
    .search-input {
        flex: 1;
        width: auto;
    }
    
    .navbar-toggle {
        display: flex;
        z-index: 1000;
    }
}

@media (max-width: 576px) {
    .brand-text {
        display: none;
    }
    
    .search-input {
        width: 120px;
    }
}
</style>