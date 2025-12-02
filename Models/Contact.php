<?php
class Contact {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create($name, $email, $phone, $subject, $message) {
        $sql = "INSERT INTO contacts (name, email, phone, subject, message)
                VALUES (?, ?, ?, ?, ?)";
        return $this->db->execute($sql, [$name, $email, $phone, $subject, $message]);
    }
}
