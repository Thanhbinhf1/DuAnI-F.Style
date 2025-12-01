// Thay thế đoạn code này:
// <div class="admin-sidebar">
//     <ul>
//         <li><a href="?ctrl=admin&act=dashboard" class="active">📊 Tổng quan</a></li>
//         <li><a href="#">📦 Sản phẩm</a></li>
//         <li><a href="#">📋 Đơn hàng</a></li>
//         <li><a href="#">👥 Người dùng</a></li>
//         <li><a href="#">🗃️ Danh mục</a></li>
//     </ul>
// </div>

// Bằng đoạn code có logic active mới:
    
    <?php
    // Xác định action hiện tại để đánh dấu menu active
    $current_act = $_GET['act'] ?? 'dashboard';
    ?>
    
    <div class="admin-sidebar">
        <ul>
            <li><a href="?ctrl=admin&act=dashboard" class="<?= $current_act == 'dashboard' ? 'active' : '' ?>">📊 Tổng quan</a></li>
            <li><a href="?ctrl=admin&act=listProducts" class="<?= $current_act == 'listProducts' ? 'active' : '' ?>">📦 Sản phẩm</a></li>
            <li><a href="?ctrl=admin&act=listOrders" class="<?= $current_act == 'listOrders' || $current_act == 'orderDetail' ? 'active' : '' ?>">📋 Đơn hàng</a></li>
            <li><a href="?ctrl=admin&act=listUsers" class="<?= $current_act == 'listUsers' ? 'active' : '' ?>">👥 Người dùng</a></li>
            <li><a href="#">🗃️ Danh mục (Chưa làm)</a></li>
        </ul>
    </div>

    <main class="admin-content"></main>