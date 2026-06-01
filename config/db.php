<?php
$DB_HOST = getenv('DB_HOST') ?: '188.166.173.17';
$DB_NAME = getenv('DB_NAME') ?: 'db_canzi_main_3';
$DB_USER = getenv('DB_USER') ?: 'canzitech_remote_usr';
$DB_PASS = getenv('DB_PASS') ?: 'X7v!p9Rm#2Zq@LcY!!@';
$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// PDO connection (pour les nouveaux fichiers)
try {
    $pdo = new PDO(
        "mysql:host=188.166.173.17;dbname=db_canzi_main_3;charset=utf8mb4",
        "canzitech_remote_usr",
        "X7v!p9Rm#2Zq@LcY!!@",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die("PDO connection failed: " . $e->getMessage());
}
?>