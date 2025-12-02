<?php 
class Database {
    private $servername = "localhost";
    private $username   = "root";
    private $password   = "";
    private $dbname     = "F.Style"; // sửa đúng tên DB nếu khác
    private $conn;

    function __construct() {
        try {
            $dsn = "mysql:host={$this->servername};dbname={$this->dbname};charset=utf8";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Lỗi kết nối: " . $e->getMessage();
        }
    }

    // SELECT nhiều dòng
    function query($sql, $args = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($args);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Lỗi SQL: " . $e->getMessage();
        }
    }

    // SELECT 1 dòng
    function queryOne($sql, $args = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($args);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Lỗi SQL: " . $e->getMessage();
        }
    }

    // INSERT / UPDATE / DELETE
    function execute($sql, $args = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($args);
        } catch(PDOException $e) {
            echo "Lỗi SQL: " . $e->getMessage();
        }
    }

    function getLastId() {
        return $this->conn->lastInsertId();
    }
}
?>
