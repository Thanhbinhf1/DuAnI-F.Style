<?php 
class Database {
    private $servername = "localhost";
    private $username   = "root";
    private $password   = "";
    private $dbname     = "F.Style"; 
    private $conn;

    function __construct() {
        try {
            $dsn = "mysql:host={$this->servername};dbname={$this->dbname};charset=utf8";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            // SỬA: Không echo lỗi ra màn hình
            error_log("Lỗi kết nối DB: " . $e->getMessage());
            die("Lỗi kết nối cơ sở dữ liệu. Vui lòng thử lại sau.");
        }
    }

    function query($sql, $args = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($args);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Lỗi SQL (query): " . $e->getMessage());
            return []; // Trả về mảng rỗng để không crash vòng lặp
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