<?php
/**
 * 版本号排序辅助函数：提取版本号数字部分
 * @param string $version 带V的版本号（如V1.2.1）
 * @return array 数字数组（如[1,2,1]）
 */
function getVersionNumberArray($version) {
    $numStr = ltrim($version, 'V'); // 去除前缀V
    return array_map('intval', explode('.', $numStr)); // 分割为数字数组
}

/**
 * 版本号比较函数（用于usort排序）
 * @param array $a 版本记录1
 * @param array $b 版本记录2
 * @return int 1: $a < $b; -1: $a > $b; 0: 相等
 */
function compareVersions($a, $b) {
    $aArr = getVersionNumberArray($a['version_code']);
    $bArr = getVersionNumberArray($b['version_code']);
    
    $maxLen = max(count($aArr), count($bArr));
    for ($i = 0; $i < $maxLen; $i++) {
        $aVal = $aArr[$i] ?? 0; // 不足的位补0
        $bVal = $bArr[$i] ?? 0;
        if ($aVal > $bVal) return -1; // $a大，排前面
        if ($aVal < $bVal) return 1;  // $b大，排前面
    }
    return 0;
}
?>