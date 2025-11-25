-- 手机测评中心数据库结构
-- 创建数据库
CREATE DATABASE IF NOT EXISTS phone_review CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE phone_review;

-- 品牌表
CREATE TABLE IF NOT EXISTS brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    name_en VARCHAR(100),
    logo VARCHAR(255),
    description TEXT,
    country VARCHAR(50),
    founded_year INT,
    website VARCHAR(255),
    phone_count INT DEFAULT 0,
    status VARCHAR(20) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name),
    INDEX idx_status (status)
);

-- 手机表
CREATE TABLE IF NOT EXISTS phones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    model VARCHAR(100),
    price DECIMAL(10,2),
    market_price DECIMAL(10,2),
    image VARCHAR(255),
    images TEXT, -- JSON格式存储多张图片
    release_date DATE,
    status VARCHAR(20) DEFAULT 'active',
    popularity INT DEFAULT 0,
    view_count INT DEFAULT 0,
    rating DECIMAL(3,2) DEFAULT 0.00,
    review_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE CASCADE,
    INDEX idx_brand (brand_id),
    INDEX idx_price (price),
    INDEX idx_popularity (popularity),
    INDEX idx_release_date (release_date),
    INDEX idx_status (status)
);

-- 手机详细规格表
CREATE TABLE IF NOT EXISTS phone_specs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone_id INT NOT NULL,
    
    -- 处理器信息
    processor_name VARCHAR(100),
    processor_model VARCHAR(100),
    processor_cores VARCHAR(50),
    processor_frequency VARCHAR(50),
    processor_process VARCHAR(20), -- 制程工艺
    gpu VARCHAR(100),
    ai_processor VARCHAR(100),
    
    -- 内存和存储
    ram_options VARCHAR(200), -- JSON格式存储多个选项
    storage_options VARCHAR(200), -- JSON格式存储多个选项
    memory_type VARCHAR(50), -- LPDDR5, LPDDR4X等
    storage_type VARCHAR(50), -- UFS 3.1, NVMe等
    expandable_storage VARCHAR(50), -- 扩展存储支持
    
    -- 屏幕信息
    screen_size VARCHAR(20),
    screen_resolution VARCHAR(50),
    screen_type VARCHAR(50), -- OLED, AMOLED, LCD等
    screen_technology VARCHAR(100), -- Super Retina XDR等
    refresh_rate VARCHAR(20),
    touch_sampling_rate VARCHAR(20),
    brightness_max VARCHAR(20),
    contrast_ratio VARCHAR(50),
    color_gamut VARCHAR(100),
    hdr_support VARCHAR(50),
    screen_protection VARCHAR(100),
    
    -- 摄像头信息
    rear_camera_main VARCHAR(100),
    rear_camera_main_specs TEXT, -- 主摄详细规格 JSON
    rear_camera_ultrawide VARCHAR(100),
    rear_camera_ultrawide_specs TEXT,
    rear_camera_telephoto VARCHAR(100),
    rear_camera_telephoto_specs TEXT,
    rear_camera_macro VARCHAR(100),
    rear_camera_macro_specs TEXT,
    rear_camera_depth VARCHAR(100),
    rear_camera_depth_specs TEXT,
    rear_camera_count INT DEFAULT 1,
    
    front_camera VARCHAR(100),
    front_camera_specs TEXT,
    front_camera_count INT DEFAULT 1,
    
    -- 视频录制能力
    rear_video_specs TEXT, -- JSON格式
    front_video_specs TEXT,
    slow_motion_specs TEXT,
    
    -- 电池信息
    battery_capacity INT,
    battery_type VARCHAR(50),
    charging_power VARCHAR(50),
    charging_time VARCHAR(50),
    wireless_charging VARCHAR(50),
    wireless_charging_time VARCHAR(50),
    reverse_charging VARCHAR(50),
    battery_life_test TEXT, -- 续航测试结果
    
    -- 设计和材质
    weight VARCHAR(20),
    dimensions VARCHAR(50),
    build_material VARCHAR(200), -- 机身材质
    frame_material VARCHAR(50),
    back_material VARCHAR(50),
    colors VARCHAR(500), -- JSON格式存储颜色选项
    
    -- 防护等级
    water_resistance VARCHAR(50), -- IP68, IP67等
    dust_resistance VARCHAR(50),
    drop_test_rating VARCHAR(50),
    
    -- 系统和软件
    operating_system VARCHAR(100),
    os_version VARCHAR(50),
    ui_version VARCHAR(50),
    update_support VARCHAR(100), -- 更新支持时间
    
    -- 传感器
    sensors TEXT, -- JSON格式存储传感器列表
    
    -- 连接性
    connectivity TEXT, -- JSON格式存储连接选项
    network_support TEXT, -- 网络制式支持
    sim_support VARCHAR(100),
    wifi_support VARCHAR(100),
    bluetooth_support VARCHAR(100),
    nfc_support BOOLEAN DEFAULT FALSE,
    infrared_support BOOLEAN DEFAULT FALSE,
    usb_type VARCHAR(50),
    usb_version VARCHAR(20),
    audio_jack BOOLEAN DEFAULT FALSE,
    stereo_speakers BOOLEAN DEFAULT FALSE,
    
    -- 生物识别
    fingerprint_type VARCHAR(50),
    face_unlock BOOLEAN DEFAULT FALSE,
    
    -- 特殊功能
    special_features TEXT, -- JSON格式存储特殊功能
    
    -- 评测数据
    pros TEXT,
    cons TEXT,
    overall_rating DECIMAL(3,2),
    
    -- 跑分数据
    benchmarks TEXT, -- JSON格式存储跑分数据
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (phone_id) REFERENCES phones(id) ON DELETE CASCADE,
    UNIQUE KEY unique_phone (phone_id),
    INDEX idx_processor (processor_name)
);

-- 评测文章表
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE,
    content TEXT,
    summary TEXT,
    rating DECIMAL(3,2),
    author VARCHAR(100),
    author_avatar VARCHAR(255),
    review_date DATE,
    view_count INT DEFAULT 0,
    like_count INT DEFAULT 0,
    pros TEXT,
    cons TEXT,
    overall_score DECIMAL(5,2),
    images TEXT, -- JSON格式存储评测图片
    video_url VARCHAR(255),
    status VARCHAR(20) DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (phone_id) REFERENCES phones(id) ON DELETE CASCADE,
    INDEX idx_phone (phone_id),
    INDEX idx_rating (rating),
    INDEX idx_review_date (review_date),
    INDEX idx_status (status)
);

-- 跑分数据表
CREATE TABLE IF NOT EXISTS benchmarks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone_id INT NOT NULL,
    benchmark_name VARCHAR(100) NOT NULL,
    score INT NOT NULL,
    ranking INT,
    test_date DATE,
    test_conditions TEXT,
    comparison_average INT, -- 同类平均分数
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (phone_id) REFERENCES phones(id) ON DELETE CASCADE,
    INDEX idx_phone_benchmark (phone_id, benchmark_name),
    INDEX idx_score (score),
    INDEX idx_ranking (ranking)
);

-- 对比记录表
CREATE TABLE IF NOT EXISTS comparisons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone1_id INT NOT NULL,
    phone2_id INT NOT NULL,
    comparison_data TEXT, -- JSON格式存储对比数据
    winner VARCHAR(100),
    user_ip VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (phone1_id) REFERENCES phones(id),
    FOREIGN KEY (phone2_id) REFERENCES phones(id),
    INDEX idx_phones (phone1_id, phone2_id),
    INDEX idx_created_at (created_at)
);

-- 用户对比历史表
CREATE TABLE IF NOT EXISTS user_comparisons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(128),
    phone_ids TEXT, -- JSON格式存储手机ID数组
    comparison_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_session (session_id),
    INDEX idx_created_at (created_at)
);

-- 品牌数据
INSERT INTO brands (name, name_en, logo, description, country, founded_year, website) VALUES
('苹果', 'Apple', 'assets/images/brands/apple.png', '美国科技公司，iPhone制造商', '美国', 1976, 'https://www.apple.com'),
('三星', 'Samsung', 'assets/images/brands/samsung.png', '韩国科技巨头，Galaxy系列制造商', '韩国', 1938, 'https://www.samsung.com'),
('华为', 'Huawei', 'assets/images/brands/huawei.png', '中国科技公司，Mate和P系列制造商', '中国', 1987, 'https://consumer.huawei.com'),
('小米', 'Xiaomi', 'assets/images/brands/xiaomi.png', '中国智能手机制造商', '中国', 2010, 'https://www.mi.com'),
('OPPO', 'OPPO', 'assets/images/brands/oppo.png', '中国智能手机制造商', '中国', 2004, 'https://www.oppo.com'),
('vivo', 'vivo', 'assets/images/brands/vivo.png', '中国智能手机制造商', '中国', 2009, 'https://www.vivo.com'),
('一加', 'OnePlus', 'assets/images/brands/oneplus.png', '中国高端智能手机制造商', '中国', 2013, 'https://www.oneplus.com'),
('真我', 'realme', 'assets/images/brands/realme.png', '中国智能手机制造商', '中国', 2018, 'https://www.realme.com'),
('红米', 'Redmi', 'assets/images/brands/redmi.png', '小米子品牌，专注性价比', '中国', 2013, 'https://www.mi.com/redmi'),
('荣耀', 'Honor', 'assets/images/brands/honor.png', '中国智能手机品牌，原华为子品牌', '中国', 2013, 'https://www.hihonor.com');

-- 更新品牌手机数量
UPDATE brands b SET phone_count = (SELECT COUNT(*) FROM phones p WHERE p.brand_id = b.id AND p.status = 'active');