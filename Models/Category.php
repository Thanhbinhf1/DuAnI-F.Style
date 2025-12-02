<?php
class Category {
    private $db;

    function __construct() {
        $this->db = new Database();
    }

    function getAllCategories() {
        $sql = "SELECT * FROM categories";
        return $this->db->query($sql);
    }

    function getCategoryById($id) {
        $sql = "SELECT * FROM categories WHERE id = ?";
        return $this->db->queryOne($sql, [$id]);
    }

    function insertCategory($name, $status) {
        $sql = "INSERT INTO categories (name, status) VALUES (?, ?)";
        return $this->db->execute($sql, [$name, $status]);
    }

    function updateCategory($id, $name, $status) {
        $sql = "UPDATE categories SET name = ?, status = ? WHERE id = ?";
        return $this->db->execute($sql, [$id, $name, $status]);
    }

    function deleteCategory($id) {
        $sql = "DELETE FROM categories WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
}
?>