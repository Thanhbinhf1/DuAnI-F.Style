<?php 
class Database {
    // 1. QUAN TRỌNG: Đổi localhost thành 127.0.0.1 để tránh bị treo
    private $servername = "127.0.0.1"; 
    private $username   = "root";
    private $password   = "";
    
    // 2. Kiểm tra kỹ tên DB này có khớp trong phpMyAdmin không
    private $dbname     = "F.Style"; 
    
    private $conn;

    function __construct() {
        try {
            // Thêm port=3306 để chắc chắn
            $dsn = "mysql:host={$this->servername};port=3306;dbname={$this->dbname};charset=utf8";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            // Khi lỗi, dòng này sẽ giúp bạn biết sai cái gì thay vì treo máy
            die("Lỗi kết nối Database: " . $e->getMessage()); 
        }
    }

    function query($sql, $args = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($args);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Lỗi SQL (query): " . $e->getMessage());
            return []; 
        }
    }

    function queryOne($sql, $args = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($args);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Lỗi SQL (queryOne): " . $e->getMessage());
            return null;
        }
    }

    function execute($sql, $args = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($args);
        } catch(PDOException $e) {
            error_log("Lỗi SQL (execute): " . $e->getMessage());
            return false;
        }
    }

    function getLastId() {
        return $this->conn->lastInsertId();
    }
}
?>