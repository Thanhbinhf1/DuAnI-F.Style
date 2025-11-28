<h1>QUẢN LÝ TÀI KHOẢN</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên đăng nhập</th>
            <th>Email</th>
            <th>Quyền</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        // $users được truyền từ Controller
        if (isset($users) && is_array($users)): 
            foreach ($u as $users): 
        ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= $u['username'] ?></td>
                <td><?= $u['email'] ?></td>
                <td><?= $u['role'] == 1 ? 'Admin' : 'Khách hàng' ?></td>
                <td>
                    <a href="#" class="action-link">Sửa</a> | 
                    <a href="#" class="action-link" onclick="return confirm('Bạn có chắc chắn muốn khóa tài khoản này?');">Khóa</a>
                </td>
            </tr>
        <?php 
            endforeach; 
        else:
        ?>
            <tr><td colspan="5">Không có tài khoản nào.</td></tr>
        <?php endif; ?>
    </tbody>
</table>