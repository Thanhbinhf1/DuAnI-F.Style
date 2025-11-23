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
            echo "Connection failed: " . $e->getMessage();
        }
    }

    // Lấy danh sách nhiều dòng (Ví dụ: Danh sách sản phẩm)
    function query($sql) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Lấy 1 dòng duy nhất (Ví dụ: Chi tiết 1 sản phẩm)
    function queryOne($sql) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Thêm, sửa, xóa
    function execute($sql) {
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>