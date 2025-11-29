<h1 style="color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; margin-bottom: 30px;">QUẢN LÝ NGƯỜI DÙNG</h1>

<table style="width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border-radius: 8px;">
    <thead>
        <tr style="background-color: #3498db; color: white;">
            <th style="padding: 15px; text-align: left;">ID</th>
            <th style="padding: 15px; text-align: left;">Tên đăng nhập</th>
            <th style="padding: 15px; text-align: left;">Họ tên</th>
            <th style="padding: 15px; text-align: left;">Email</th>
            <th style="padding: 15px; text-align: left;">Quyền</th>
            <th style="padding: 15px; text-align: center;">Ngày đăng ký</th>
            <th style="padding: 15px; text-align: center;">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 15px;"><?= $user['id'] ?></td>
            <td style="padding: 15px; font-weight: 600;"><?= $user['username'] ?></td>
            <td style="padding: 15px;"><?= $user['fullname'] ?></td>
            <td style="padding: 15px;"><?= $user['email'] ?></td>
            <td style="padding: 15px;">
                <?php if ($user['role'] == 1): ?>
                    <span style="color: green; font-weight: bold;">Admin</span>
                <?php else: ?>
                    Khách hàng
                <?php endif; ?>
            </td>
            <td style="padding: 15px; text-align: center;"><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
            <td style="padding: 15px; text-align: center;">
                <a href="?ctrl=admin&act=userDelete&id=<?= $user['id'] ?>" 
                   onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')" 
                   style="color: #e74c3c; text-decoration: none;">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>