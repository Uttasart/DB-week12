<?php
// ข้อมูลการเชื่อมต่อยึดตามไฟล์หลัก (s68160218)
$host   = getenv('DB_HOST') ?: 'localhost';
$user   = getenv('DB_USER') ?: 's68160218';
$pass   = getenv('DB_PASS') ?: 'Ud3KZjfB';
$dbname = getenv('DB_NAME') ?: 's68160218';

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    error_log(mysqli_connect_error());   // log ไว้ฝั่งเซิร์ฟเวอร์ ไม่โชว์รายละเอียดให้ผู้ใช้
    http_response_code(500);
    exit('Database Connection Error');
}
mysqli_set_charset($conn, 'utf8mb4');
