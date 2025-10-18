<?php
$new_password = '123456789'; // 替换为要设置的密码（如12345678）
echo password_hash($new_password, PASSWORD_DEFAULT);
?>