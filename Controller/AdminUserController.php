<?php
// File: Controller/AdminUserController.php

include_once 'Models/User.php';

class AdminUserController {
    private $model;

    function __construct() {
        $this->model = new User();
        // TODO: Logic kiểm tra quyền ADMIN
    }

    // [GET] Danh sách tài khoản
    function listUsers() {
        // Giả định User.php đã có hàm getAllUsers()
        $users = $this->model->getAllUsers(); 
        include_once 'Views/admin/user_list.php';
    }

    // TODO: Cần thêm hàm editUser và deleteUser
}
?>