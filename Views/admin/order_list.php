<h1>QUẢN LÝ ĐƠN HÀNG</h1>

<table>
    <thead>
        <tr>
            <th>ID Đơn hàng</th>
            <th>Tên Khách hàng</th> <th>Tổng tiền</th>
            <th>Ngày đặt</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        // $orders được truyền từ Controller
        if (isset($orders) && is_array($orders)): 
            foreach ($orders as $o): 
        ?>
            <tr>
                <td><?= $o['id'] ?></td>
                <td><?= $o['user_id'] ?></td> <td><?= number_format($o['total_amount']) ?> VNĐ</td>
                <td><?= $o['order_date'] ?></td>
                <td><?= $o['status'] ?></td>
                <td>
                    <a href="?ctrl=AdminOrder&act=viewOrder&id=<?= $o['id'] ?>" class="action-link">Xem chi tiết</a> |
                    <a href="#" class="action-link">Cập nhật TT</a>
                </td>
            </tr>
        <?php 
            endforeach; 
        else:
        ?>
            <tr><td colspan="6">Không có đơn hàng nào.</td></tr>
        <?php endif; ?>
    </tbody>
</table>