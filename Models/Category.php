<?php
// Models/Category.php
class Category {
    private $db;

    function __construct() {
        // Giả định class Database đã được include ở nơi khác (ví dụ index.php)
        $this->db = new Database(); 
    }

    function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY id ASC";
        return $this->db->query($sql);
    }
}
?>