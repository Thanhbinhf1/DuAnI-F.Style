<?php 
 class Database {
      private $servername = "localhost";
    private $username = "root";
    private $password = "";

    private $dbname = "F.Style";

    private $conn;
    function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connected successfully";
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    function queryOne($sql)
    {
        try{
            // thực thi câu lệnh truy vấn
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            
        }
    }
    function insert($sql,...$args)
    {
        try{
            // thực thi câu lệnh truy vấn
            $stmt = $this->conn->prepare($sql);
            // $stmt->execute();
            // $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->execute($args);
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            
        }
    }
}
?>
