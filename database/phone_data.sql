-- 添加真实的手机数据
USE phone_review;

-- iPhone 15 Pro Max
INSERT INTO phones (brand_id, name, model, price, market_price, image, release_date, popularity, rating) VALUES
(1, 'iPhone 15 Pro Max', 'A2849', 9999, 9999, 'assets/images/phones/iphone-15-pro-max.jpg', '2023-09-22', 95, 4.8);

INSERT INTO phone_specs (phone_id, processor_name, processor_model, processor_cores, processor_frequency, processor_process, gpu, ram_options, storage_options, memory_type, storage_type, screen_size, screen_resolution, screen_type, screen_technology, refresh_rate, brightness_max, contrast_ratio, rear_camera_main, rear_camera_main_specs, rear_camera_ultrawide, rear_camera_ultrawide_specs, rear_camera_telephoto, rear_camera_telephoto_specs, rear_camera_count, front_camera, front_camera_specs, rear_video_specs, front_video_specs, battery_capacity, battery_type, charging_power, wireless_charging, reverse_charging, weight, dimensions, build_material, water_resistance, operating_system, sensors, connectivity, special_features, pros, cons, overall_rating) VALUES
(LAST_INSERT_ID(), 'A17 Pro', 'A17 Pro', '6核CPU', '3.78GHz', '3nm', '6核GPU', '["8GB"]', '["256GB","512GB","1TB"]', 'LPDDR5', 'NVMe', '6.7英寸', '2796×1290', 'OLED', 'Super Retina XDR', '120Hz', '1000尼特', '2000000:1', '48MP主摄', '{"像素":"48MP","光圈":"f/1.78","传感器尺寸":"1/1.28英寸","OIS":true,"像素尺寸":"1.22μm"}', '12MP超广角', '{"像素":"12MP","光圈":"f/2.2","视角":"120°"}', '12MP长焦', '{"像素":"12MP","光圈":"f/2.8","变焦":"5倍光学变焦"}', 3, '12MP原深感', '{"像素":"12MP","光圈":"f/1.9"}', '{"4K":"60fps","1080p":"240fps慢动作"}', '{"4K":"60fps"}', 4422, '锂离子', '27W', '15W MagSafe', '4.5W反向充电', '221g', '159.9×76.7×8.25mm', '钛金属+超瓷晶面板', 'IP68', 'iOS 17', '["Face ID","激光雷达扫描仪","气压计","陀螺仪","加速度计"]', '{"5G":"支持","WiFi":"6E","蓝牙":"5.3","NFC":"支持","USB-C":"支持"}', '["灵动岛","Action按钮","卫星紧急求救","车祸检测"]', '["性能强劲","摄像头出色","续航优秀","做工精良"]', '["价格昂贵","充电速度一般","重量较重"]', 4.8);

-- iPhone 15 Pro
INSERT INTO phones (brand_id, name, model, price, market_price, image, release_date, popularity, rating) VALUES
(1, 'iPhone 15 Pro', 'A2848', 7999, 7999, 'assets/images/phones/iphone-15-pro.jpg', '2023-09-22', 92, 4.7);

INSERT INTO phone_specs (phone_id, processor_name, processor_model, processor_cores, processor_frequency, processor_process, gpu, ram_options, storage_options, memory_type, storage_type, screen_size, screen_resolution, screen_type, screen_technology, refresh_rate, brightness_max, contrast_ratio, rear_camera_main, rear_camera_main_specs, rear_camera_ultrawide, rear_camera_ultrawide_specs, rear_camera_telephoto, rear_camera_telephoto_specs, rear_camera_count, front_camera, front_camera_specs, rear_video_specs, front_video_specs, battery_capacity, battery_type, charging_power, wireless_charging, reverse_charging, weight, dimensions, build_material, water_resistance, operating_system, sensors, connectivity, special_features, pros, cons, overall_rating) VALUES
(LAST_INSERT_ID(), 'A17 Pro', 'A17 Pro', '6核CPU', '3.78GHz', '3nm', '6核GPU', '["8GB"]', '["128GB","256GB","512GB","1TB"]', 'LPDDR5', 'NVMe', '6.1英寸', '2556×1179', 'OLED', 'Super Retina XDR', '120Hz', '1000尼特', '2000000:1', '48MP主摄', '{"像素":"48MP","光圈":"f/1.78","传感器尺寸":"1/1.28英寸","OIS":true,"像素尺寸":"1.22μm"}', '12MP超广角', '{"像素":"12MP","光圈":"f/2.2","视角":"120°"}', '12MP长焦', '{"像素":"12MP","光圈":"f/2.8","变焦":"3倍光学变焦"}', 3, '12MP原深感', '{"像素":"12MP","光圈":"f/1.9"}', '{"4K":"60fps","1080p":"240fps慢动作"}', '{"4K":"60fps"}', 3274, '锂离子', '27W', '15W MagSafe', '4.5W反向充电', '187g', '146.6×70.6×8.25mm', '钛金属+超瓷晶面板', 'IP68', 'iOS 17', '["Face ID","激光雷达扫描仪","气压计","陀螺仪","加速度计"]', '{"5G":"支持","WiFi":"6E","蓝牙":"5.3","NFC":"支持","USB-C":"支持"}', '["灵动岛","Action按钮","卫星紧急求救","车祸检测"]', '["性能顶级","摄像头优秀","手感出色","做工精良"]', '["价格较高","电池容量偏小","充电速度一般"]', 4.7);

-- Samsung Galaxy S24 Ultra
INSERT INTO phones (brand_id, name, model, price, market_price, image, release_date, popularity, rating) VALUES
(2, 'Galaxy S24 Ultra', 'SM-S9280', 9699, 9699, 'assets/images/phones/galaxy-s24-ultra.jpg', '2024-01-31', 93, 4.6);

INSERT INTO phone_specs (phone_id, processor_name, processor_model, processor_cores, processor_frequency, processor_process, gpu, ram_options, storage_options, memory_type, storage_type, screen_size, screen_resolution, screen_type, screen_technology, refresh_rate, brightness_max, contrast_ratio, rear_camera_main, rear_camera_main_specs, rear_camera_ultrawide, rear_camera_ultrawide_specs, rear_camera_telephoto, rear_camera_telephoto_specs, rear_camera_count, front_camera, front_camera_specs, rear_video_specs, front_video_specs, battery_capacity, battery_type, charging_power, wireless_charging, reverse_charging, weight, dimensions, build_material, water_resistance, operating_system, sensors, connectivity, special_features, pros, cons, overall_rating) VALUES
(LAST_INSERT_ID(), 'Snapdragon 8 Gen 3', 'SM8650-AB', '8核CPU', '3.39GHz', '4nm', 'Adreno 750', '["12GB"]', '["256GB","512GB","1TB"]', 'LPDDR5X', 'UFS 4.0', '6.8英寸', '3120×1440', 'OLED', 'Dynamic AMOLED 2X', '120Hz', '2600尼特', '5000000:1', '200MP主摄', '{"像素":"200MP","光圈":"f/1.7","传感器尺寸":"1/1.3英寸","OIS":true,"像素尺寸":"0.6μm"}', '12MP超广角', '{"像素":"12MP","光圈":"f/2.2","视角":"120°"}', '50MP潜望长焦', '{"像素":"50MP","光圈":"f/3.4","变焦":"5倍光学变焦"}', 4, '12MP自拍', '{"像素":"12MP","光圈":"f/2.2"}', '{"8K":"30fps","4K":"120fps"}', '{"4K":"60fps"}', 5000, '锂离子', '45W', '15W Qi无线充电', '4.5W反向充电', '232g', '162.3×79.0×8.6mm', '钛合金+Gorilla Glass Armor', 'IP68', 'Android 14', '["超声波指纹","心率传感器","气压计","陀螺仪"]', '{"5G":"支持","WiFi":"7","蓝牙":"5.3","NFC":"支持","USB-C":"支持"}', '["S Pen支持","Galaxy AI","UWB超宽带"]', '["屏幕顶级","摄像头强大","功能丰富","做工优秀"]', '["价格昂贵","充电速度一般","系统优化待提升"]', 4.6);

-- Xiaomi 14 Ultra
INSERT INTO phones (brand_id, name, model, price, market_price, image, release_date, popularity, rating) VALUES
(4, 'Xiaomi 14 Ultra', '24030PN60G', 6499, 6499, 'assets/images/phones/xiaomi-14-ultra.jpg', '2024-02-22', 88, 4.5);

INSERT INTO phone_specs (phone_id, processor_name, processor_model, processor_cores, processor_frequency, processor_process, gpu, ram_options, storage_options, memory_type, storage_type, screen_size, screen_resolution, screen_type, screen_technology, refresh_rate, brightness_max, contrast_ratio, rear_camera_main, rear_camera_main_specs, rear_camera_ultrawide, rear_camera_ultrawide_specs, rear_camera_telephoto, rear_camera_telephoto_specs, rear_camera_count, front_camera, front_camera_specs, rear_video_specs, front_video_specs, battery_capacity, battery_type, charging_power, wireless_charging, reverse_charging, weight, dimensions, build_material, water_resistance, operating_system, sensors, connectivity, special_features, pros, cons, overall_rating) VALUES
(LAST_INSERT_ID(), 'Snapdragon 8 Gen 3', 'SM8650-AB', '8核CPU', '3.3GHz', '4nm', 'Adreno 750', '["12GB","16GB"]', '["256GB","512GB","1TB"]', 'LPDDR5X', 'UFS 4.0', '6.73英寸', '3200×1440', 'OLED', 'LTPO AMOLED', '120Hz', '3000尼特', '5000000:1', '50MP主摄', '{"像素":"50MP","光圈":"f/1.6","传感器尺寸":"1英寸","OIS":true,"像素尺寸":"1.6μm"}', '50MP超广角', '{"像素":"50MP","光圈":"f/1.8","视角":"122°"}', '50MP潜望长焦', '{"像素":"50MP","光圈":"f/2.5","变焦":"5倍光学变焦"}', 4, '32MP自拍', '{"像素":"32MP","光圈":"f/2.0"}', '{"8K":"30fps","4K":"120fps"}', '{"4K":"60fps"}', 5300, '锂离子', '90W', '80W无线充电', '10W反向充电', '229g', '161.4×75.3×9.2mm', '铝合金+龙晶玻璃', 'IP68', 'HyperOS', '["光学屏下指纹","心率传感器","气压计","陀螺仪"]', '{"5G":"支持","WiFi":"7","蓝牙":"5.4","NFC":"支持","USB-C":"支持"}', '["徕卡影像","双向卫星通信","钛金属版本"]', '["影像能力顶级","充电快速","屏幕优秀","性价比高"]', '["重量较重","系统广告较多","品牌影响力待提升"]', 4.5);

-- Huawei Mate 60 Pro+
INSERT INTO phones (brand_id, name, model, price, market_price, image, release_date, popularity, rating) VALUES
(3, 'Mate 60 Pro+', 'ALN-AL00', 8999, 8999, 'assets/images/phones/huawei-mate-60-pro-plus.jpg', '2023-09-08', 85, 4.4);

INSERT INTO phone_specs (phone_id, processor_name, processor_model, processor_cores, processor_frequency, processor_process, gpu, ram_options, storage_options, memory_type, storage_type, screen_size, screen_resolution, screen_type, screen_technology, refresh_rate, brightness_max, contrast_ratio, rear_camera_main, rear_camera_main_specs, rear_camera_ultrawide, rear_camera_ultrawide_specs, rear_camera_telephoto, rear_camera_telephoto_specs, rear_camera_count, front_camera, front_camera_specs, rear_video_specs, front_video_specs, battery_capacity, battery_type, charging_power, wireless_charging, reverse_charging, weight, dimensions, build_material, water_resistance, operating_system, sensors, connectivity, special_features, pros, cons, overall_rating) VALUES
(LAST_INSERT_ID(), '麒麟9000S', '麒麟9000S', '8核CPU', '2.62GHz', '7nm', 'Maleoon 910 GPU', '["16GB"]', '["512GB","1TB"]', 'LPDDR5', 'UFS 3.1', '6.82英寸', '2720×1260', 'OLED', 'LTPO AMOLED', '120Hz', '1800尼特', '5000000:1', '48MP主摄', '{"像素":"48MP","光圈":"f/1.4","传感器尺寸":"1/1.43英寸","OIS":true,"像素尺寸":"1.22μm"}', '40MP超广角', '{"像素":"40MP","光圈":"f/2.2","视角":"120°"}', '48MP潜望长焦', '{"像素":"48MP","光圈":"f/2.1","变焦":"3.5倍光学变焦"}', 3, '13MP自拍', '{"像素":"13MP","光圈":"f/2.4"}', '{"4K":"60fps","1080p":"960fps慢动作"}', '{"4K":"60fps"}', 5000, '锂离子', '88W', '50W无线充电', '20W反向充电', '225g', '163.7×79×8.1mm', '铝合金+第二代昆仑玻璃', 'IP68', 'HarmonyOS 4.0', '["光学屏下指纹","3D人脸识别","气压计","陀螺仪"]', '{"5G":"支持","WiFi":"6","蓝牙":"5.2","NFC":"支持","USB-C":"支持"}', '["双向北斗卫星消息","玄武架构","Xmage影像"]', '["通信能力强","做工精良","系统流畅","影像能力优秀"]', '["缺少5G支持","应用生态待完善","价格较高"]', 4.4);

-- OPPO Find X7 Ultra
INSERT INTO phones (brand_id, name, model, price, market_price, image, release_date, popularity, rating) VALUES
(5, 'Find X7 Ultra', 'PHY110', 5999, 5999, 'assets/images/phones/oppo-find-x7-ultra.jpg', '2024-01-12', 82, 4.3);

INSERT INTO phone_specs (phone_id, processor_name, processor_model, processor_cores, processor_frequency, processor_process, gpu, ram_options, storage_options, memory_type, storage_type, screen_size, screen_resolution, screen_type, screen_technology, refresh_rate, brightness_max, contrast_ratio, rear_camera_main, rear_camera_main_specs, rear_camera_ultrawide, rear_camera_ultrawide_specs, rear_camera_telephoto, rear_camera_telephoto_specs, rear_camera_count, front_camera, front_camera_specs, rear_video_specs, front_video_specs, battery_capacity, battery_type, charging_power, wireless_charging, reverse_charging, weight, dimensions, build_material, water_resistance, operating_system, sensors, connectivity, special_features, pros, cons, overall_rating) VALUES
(LAST_INSERT_ID(), 'Snapdragon 8 Gen 3', 'SM8650-AB', '8核CPU', '3.3GHz', '4nm', 'Adreno 750', '["12GB","16GB"]', '["256GB","512GB"]', 'LPDDR5X', 'UFS 4.0', '6.82英寸', '3168×1440', 'OLED', 'LTPO AMOLED', '120Hz', '2600尼特', '5000000:1', '50MP主摄', '{"像素":"50MP","光圈":"f/1.8","传感器尺寸":"1英寸","OIS":true,"像素尺寸":"1.6μm"}', '50MP超广角', '{"像素":"50MP","光圈":"f/2.0","视角":"123°"}', '50MP潜望长焦', '{"像素":"50MP","光圈":"f/2.6","变焦":"6倍光学变焦"}', 4, '32MP自拍', '{"像素":"32MP","光圈":"f/2.4"}', '{"4K":"60fps","1080p":"240fps慢动作"}', '{"4K":"60fps"}', 5000, '锂离子', '100W', '50W无线充电', '10W反向充电', '221g', '164.3×76.2×9.5mm', '铝合金+玻璃', 'IP68', 'ColorOS 14', '["光学屏下指纹","气压计","陀螺仪","加速度计"]', '{"5G":"支持","WiFi":"7","蓝牙":"5.4","NFC":"支持","USB-C":"支持"}', '["哈苏影像","双向卫星通信","超级夜景"]', '["影像能力出色","充电快速","屏幕优秀","设计精美"]', '["价格偏高","系统优化空间","品牌影响力"]', 4.3);

-- 添加跑分数据
INSERT INTO benchmarks (phone_id, benchmark_name, score, ranking, test_date) VALUES
(1, 'AnTuTu V10', 1520000, 3, '2023-09-25'),
(1, 'Geekbench 6 Single', 2950, 2, '2023-09-25'),
(1, 'Geekbench 6 Multi', 7500, 2, '2023-09-25'),
(2, 'AnTuTu V10', 1480000, 5, '2023-09-25'),
(2, 'Geekbench 6 Single', 2900, 4, '2023-09-25'),
(2, 'Geekbench 6 Multi', 7300, 4, '2023-09-25'),
(3, 'AnTuTu V10', 1800000, 1, '2024-02-01'),
(3, 'Geekbench 6 Single', 3200, 1, '2024-02-01'),
(3, 'Geekbench 6 Multi', 8000, 1, '2024-02-01'),
(4, 'AnTuTu V10', 1750000, 2, '2024-03-01'),
(4, 'Geekbench 6 Single', 3150, 3, '2024-03-01'),
(4, 'Geekbench 6 Multi', 7900, 3, '2024-03-01'),
(5, 'AnTuTu V10', 1650000, 4, '2024-01-15'),
(5, 'Geekbench 6 Single', 3050, 5, '2024-01-15'),
(5, 'Geekbench 6 Multi', 7700, 5, '2024-01-15');

-- 更新品牌手机数量
UPDATE brands b SET phone_count = (SELECT COUNT(*) FROM phones p WHERE p.brand_id = b.id AND p.status = 'active');