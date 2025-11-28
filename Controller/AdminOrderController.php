<?php
// File: Controller/AdminOrderController.php

include_once 'Models/Order.php';

class AdminOrderController {
    private $model;

    function __construct() {
        $this->model = new Order();
        // TODO: Logic kiểm tra quyền ADMIN
    }

    // [GET] Danh sách đơn hàng
    function listOrders() {
        $orders = $this->model->getAllOrders();
        include_once 'Views/admin/order_list.php';
    }

    // [GET] Chi tiết đơn hàng
    function viewOrder() {
        $order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($order_id > 0) {
            $orderDetails = $this->model->getOrderDetail($order_id);
            // $orderInfo = $this->model->getOrderInfo($order_id); // Cần thêm hàm này vào Order.php
        } else {
            $error = "ID đơn hàng không hợp lệ.";
        }
        
        include_once 'Views/admin/order_view.php';
    }
    
    // TODO: Cần thêm hàm updateOrderStatus
}
?>