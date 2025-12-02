<?php 
class Database {
    private $servername = "localhost";
    private $username   = "root";
    private $password   = "";
    private $dbname     = "F.Style"; // chỉnh lại đúng tên DB của bạn
    private $conn;

    function __construct() {
        try {
            $dsn = "mysql:host={$this->servername};dbname={$this->dbname};charset=utf8";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            error_log("Lỗi kết nối: " . $e->getMessage());
            // Có thể thêm một trang báo lỗi chung cho người dùng
            die("Không thể kết nối đến cơ sở dữ liệu.");
        }
    }

    // Truy vấn nhiều dòng
    function query($sql, $args = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($args);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Lỗi SQL: " . $e->getMessage());
            return null; // Trả về null khi có lỗi
        }
    }

    // Truy vấn 1 dòng
    function queryOne($sql, $args = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($args);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Lỗi SQL: " . $e->getMessage());
            return null; // Trả về null khi có lỗi
        }
    }

    // Thực thi INSERT / UPDATE / DELETE
    function execute($sql, $args = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($args);
        } catch(PDOException $e) {
            error_log("Lỗi SQL: " . $e->getMessage());
            return false; // Trả về false khi có lỗi
        }
    }

    function getLastId() {
        return $this->conn->lastInsertId();
    }
}
?>
