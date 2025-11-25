<?php
/**
 * 手机测评中心 - 对比功能增强
 * 
 * @package PhoneReviewCenter
 * @author Phone Review Center Team
 * @version 1.0.0
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

/**
 * 获取对比列表详细信息
 */
function getCompareListDetails() {
    if (!isset($_SESSION['compare_list'])) {
        $_SESSION['compare_list'] = [];
    }
    
    $compare_details = [];
    foreach ($_SESSION['compare_list'] as $phone_id) {
        $phone = getPhoneById($phone_id);
        if ($phone) {
            $specs = getPhoneSpecs($phone_id);
            $benchmarks = getPhoneBenchmarks($phone_id);
            
            $compare_details[] = [
                'id' => $phone['id'],
                'name' => $phone['name'],
                'brand_name' => $phone['brand_name'],
                'price' => $phone['price'],
                'image' => 'fas fa-mobile-alt',
                'specs' => $specs,
                'benchmarks' => $benchmarks,
                'overall_score' => calculateOverallScore($phone, $specs, $benchmarks)
            ];
        }
    }
    
    return $compare_details;
}

/**
 * 计算综合评分
 */
function calculateOverallScore($phone, $specs, $benchmarks) {
    $score = 0;
    $weights = [
        'performance' => 0.3,
        'camera' => 0.25,
        'battery' => 0.2,
        'display' => 0.15,
        'build_quality' => 0.1
    ];
    
    // 性能评分 (30%)
    if (!empty($benchmarks)) {
        $performance_score = 0;
        $benchmark_count = 0;
        
        foreach ($benchmarks as $benchmark) {
            if ($benchmark['score'] > 0) {
                $performance_score += min($benchmark['score'] / 1000, 100); // 标准化到100分
                $benchmark_count++;
            }
        }
        
        if ($benchmark_count > 0) {
            $score += ($performance_score / $benchmark_count) * $weights['performance'];
        }
    }
    
    // 摄像头评分 (25%)
    if (!empty($specs)) {
        $camera_score = 0;
        
        // 主摄像头像素评分
        if (!empty($specs['rear_camera_main'])) {
            $main_mp = extractMegapixels($specs['rear_camera_main']);
            $camera_score += min($main_mp / 100, 1) * 60; // 主摄占60分
        }
        
        // 副摄像头数量评分
        if (!empty($specs['rear_camera_other'])) {
            $camera_count = substr_count($specs['rear_camera_other'], 'MP') + 1;
            $camera_score += min($camera_count / 4, 1) * 25; // 摄像头数量占25分
        }
        
        // 前置摄像头评分
        if (!empty($specs['front_camera'])) {
            $front_mp = extractMegapixels($specs['front_camera']);
            $camera_score += min($front_mp / 50, 1) * 15; // 前置占15分
        }
        
        $score += $camera_score * $weights['camera'];
    }
    
    // 电池评分 (20%)
    if (!empty($specs['battery_capacity'])) {
        $battery_mah = extractBatteryCapacity($specs['battery_capacity']);
        $battery_score = min($battery_mah / 5000, 1) * 70; // 电池容量占70分
        
        // 快充加分
        if (!empty($specs['charging_power'])) {
            $charging_w = extractChargingPower($specs['charging_power']);
            if ($charging_w >= 65) {
                $battery_score += 20;
            } elseif ($charging_w >= 30) {
                $battery_score += 15;
            } elseif ($charging_w >= 18) {
                $battery_score += 10;
            }
        }
        
        // 无线充电加分
        if (!empty($specs['wireless_charging']) && strpos($specs['wireless_charging'], '支持') !== false) {
            $battery_score += 10;
        }
        
        $score += min($battery_score, 100) * $weights['battery'];
    }
    
    // 显示评分 (15%)
    if (!empty($specs['display_size'])) {
        $display_score = 50; // 基础分
        
        // 屏幕尺寸评分
        $size = extractDisplaySize($specs['display_size']);
        if ($size >= 6.5) {
            $display_score += 20;
        } elseif ($size >= 6.0) {
            $display_score += 15;
        }
        
        // 分辨率评分
        if (!empty($specs['display_resolution'])) {
            if (strpos($specs['display_resolution'], '2K') !== false || 
                strpos($specs['display_resolution'], '1440') !== false) {
                $display_score += 20;
            } elseif (strpos($specs['display_resolution'], '1080') !== false) {
                $display_score += 15;
            }
        }
        
        // 刷新率评分
        if (!empty($specs['display_refresh_rate'])) {
            if (strpos($specs['display_refresh_rate'], '120Hz') !== false) {
                $display_score += 10;
            } elseif (strpos($specs['display_refresh_rate'], '90Hz') !== false) {
                $display_score += 5;
            }
        }
        
        $score += min($display_score, 100) * $weights['display'];
    }
    
    // 做工质量评分 (10%)
    if (!empty($specs['build_material'])) {
        $build_score = 50; // 基础分
        
        // 材质评分
        if (strpos($specs['build_material'], '玻璃') !== false && 
            strpos($specs['build_material'], '金属') !== false) {
            $build_score += 30;
        } elseif (strpos($specs['build_material'], '玻璃') !== false) {
            $build_score += 20;
        } elseif (strpos($specs['build_material'], '金属') !== false) {
            $build_score += 15;
        }
        
        // 防水等级评分
        if (!empty($specs['water_resistance'])) {
            if (strpos($specs['water_resistance'], 'IP68') !== false) {
                $build_score += 20;
            } elseif (strpos($specs['water_resistance'], 'IP67') !== false) {
                $build_score += 15;
            } elseif (strpos($specs['water_resistance'], 'IP') !== false) {
                $build_score += 10;
            }
        }
        
        $score += min($build_score, 100) * $weights['build_quality'];
    }
    
    return round($score, 1);
}

/**
 * 提取像素数值
 */
function extractMegapixels($camera_str) {
    if (preg_match('/(\d+)MP/i', $camera_str, $matches)) {
        return intval($matches[1]);
    }
    return 0;
}

/**
 * 提取电池容量
 */
function extractBatteryCapacity($battery_str) {
    if (preg_match('/(\d+)mAh/i', $battery_str, $matches)) {
        return intval($matches[1]);
    }
    return 0;
}

/**
 * 提取充电功率
 */
function extractChargingPower($charging_str) {
    if (preg_match('/(\d+)W/i', $charging_str, $matches)) {
        return intval($matches[1]);
    }
    return 0;
}

/**
 * 提取屏幕尺寸
 */
function extractDisplaySize($display_str) {
    if (preg_match('/(\d+\.?\d*)英寸/i', $display_str, $matches)) {
        return floatval($matches[1]);
    }
    return 0;
}

/**
 * 生成对比报告
 */
function generateCompareReport($phones) {
    if (count($phones) < 2) {
        return null;
    }
    
    $report = [
        'winner' => null,
        'categories' => [],
        'recommendations' => []
    ];
    
    // 对比各个维度
    $categories = ['performance', 'camera', 'battery', 'display', 'build_quality', 'value'];
    
    foreach ($categories as $category) {
        $winner = compareCategory($phones, $category);
        $report['categories'][$category] = $winner;
    }
    
    // 找出综合获胜者
    $scores = [];
    foreach ($phones as $phone) {
        $scores[$phone['id']] = $phone['overall_score'];
    }
    
    arsort($scores);
    $winner_id = array_key_first($scores);
    
    foreach ($phones as $phone) {
        if ($phone['id'] == $winner_id) {
            $report['winner'] = $phone;
            break;
        }
    }
    
    // 生成推荐
    $report['recommendations'] = generateRecommendations($phones, $report['categories']);
    
    return $report;
}

/**
 * 对比特定类别
 */
function compareCategory($phones, $category) {
    $best_phone = null;
    $best_score = -1;
    
    foreach ($phones as $phone) {
        $score = 0;
        
        switch ($category) {
            case 'performance':
                if (!empty($phone['benchmarks'])) {
                    foreach ($phone['benchmarks'] as $benchmark) {
                        $score += $benchmark['score'];
                    }
                    $score = $score / count($phone['benchmarks']);
                }
                break;
                
            case 'camera':
                if (!empty($phone['specs']['rear_camera_main'])) {
                    $score = extractMegapixels($phone['specs']['rear_camera_main']);
                }
                break;
                
            case 'battery':
                if (!empty($phone['specs']['battery_capacity'])) {
                    $battery_mah = extractBatteryCapacity($phone['specs']['battery_capacity']);
                    $charging_w = !empty($phone['specs']['charging_power']) ? 
                        extractChargingPower($phone['specs']['charging_power']) : 0;
                    $score = $battery_mah + ($charging_w * 10);
                }
                break;
                
            case 'display':
                if (!empty($phone['specs']['display_size'])) {
                    $size = extractDisplaySize($phone['specs']['display_size']);
                    $resolution_score = 0;
                    if (!empty($phone['specs']['display_resolution'])) {
                        if (strpos($phone['specs']['display_resolution'], '2K') !== false) {
                            $resolution_score = 100;
                        } elseif (strpos($phone['specs']['display_resolution'], '1080') !== false) {
                            $resolution_score = 80;
                        }
                    }
                    $score = $size * 10 + $resolution_score;
                }
                break;
                
            case 'build_quality':
                $score = 50; // 基础分
                if (!empty($phone['specs']['build_material'])) {
                    if (strpos($phone['specs']['build_material'], '玻璃') !== false && 
                        strpos($phone['specs']['build_material'], '金属') !== false) {
                        $score += 30;
                    }
                }
                if (!empty($phone['specs']['water_resistance'])) {
                    if (strpos($phone['specs']['water_resistance'], 'IP68') !== false) {
                        $score += 20;
                    } elseif (strpos($phone['specs']['water_resistance'], 'IP') !== false) {
                        $score += 10;
                    }
                }
                break;
                
            case 'value':
                $score = $phone['overall_score'] - ($phone['price'] / 100); // 性价比评分
                break;
        }
        
        if ($score > $best_score) {
            $best_score = $score;
            $best_phone = $phone;
        }
    }
    
    return $best_phone;
}

/**
 * 生成推荐建议
 */
function generateRecommendations($phones, $categories) {
    $recommendations = [];
    
    // 性能推荐
    if (!empty($categories['performance'])) {
        $recommendations[] = [
            'type' => 'performance',
            'title' => '性能最强',
            'phone' => $categories['performance'],
            'reason' => '在各项跑分测试中表现最优秀，适合重度游戏和多任务处理'
        ];
    }
    
    // 拍照推荐
    if (!empty($categories['camera'])) {
        $recommendations[] = [
            'type' => 'camera',
            'title' => '拍照最佳',
            'phone' => $categories['camera'],
            'reason' => '拥有最高的主摄像头像素和丰富的拍照功能'
        ];
    }
    
    // 续航推荐
    if (!empty($categories['battery'])) {
        $recommendations[] = [
            'type' => 'battery',
            'title' => '续航最强',
            'phone' => $categories['battery'],
            'reason' => '大容量电池配合快充技术，提供最长的使用时间'
        ];
    }
    
    // 性价比推荐
    if (!empty($categories['value'])) {
        $recommendations[] = [
            'type' => 'value',
            'title' => '性价比最高',
            'phone' => $categories['value'],
            'reason' => '在价格和性能之间达到了最佳平衡'
        ];
    }
    
    return $recommendations;
}