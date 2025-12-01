<?php 
class Database {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "F.Style";
    private $conn;

    function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname;charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Lỗi kết nối: " . $e->getMessage();
        }
    }

    // Hỗ trợ truyền tham số $args để chống hack
    function query($sql, $args = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($args);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Lỗi SQL: " . $e->getMessage();
        }
    }

    function queryOne($sql, $args = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($args);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Lỗi SQL: " . $e->getMessage();
        }
    }

   function execute($sql, $args = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($args);
        } catch(PDOException $e) {
            echo "Lỗi SQL: " . $e->getMessage();
            // KHẮC PHỤC CỐT LÕI: Trả về false khi có lỗi
            return false; 
        }
    }
}
?>