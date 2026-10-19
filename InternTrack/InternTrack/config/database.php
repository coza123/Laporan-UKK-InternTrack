<?php
$dbHost = getenv('DB_HOST');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASS');
$dbName = getenv('DB_NAME');

define('DB_HOST', $dbHost === false ? 'localhost' : $dbHost);
define('DB_USER', $dbUser === false ? 'root' : $dbUser);
define('DB_PASS', $dbPass === false ? '' : $dbPass);
define('DB_NAME', $dbName === false ? 'interntrack' : $dbName);

function getConnection() {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die("Koneksi database gagal: " . $conn->connect_error);
        }
        $conn->set_charset("utf8mb4");
    }
    return $conn;
}
?>