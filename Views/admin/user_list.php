<?php
// Views/admin/user_list.php
?>
<h1 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-bottom: 30px;">QUẢN LÝ NGƯỜI DÙNG (<?=count($users)?> người)</h1>

<table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
    <thead style="background: #3498db; color: white;">
        <tr>
            <th style="padding: 15px; text-align: left;">ID</th>
            <th style="padding: 15px; text-align: left;">Tên đăng nhập</th>
            <th style="padding: 15px; text-align: left;">Họ và tên</th>
            <th style="padding: 15px; text-align: left;">Email</th>
            <th style="padding: 15px; text-align: center;">Vai trò</th>
            <th style="padding: 15px; text-align: center;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 15px;"><?=$user['id']?></td>
            <td style="padding: 15px; font-weight: bold;"><?=$user['username']?></td>
            <td style="padding: 15px;"><?=$user['fullname']?></td>
            <td style="padding: 15px;"><?=$user['email']?></td>
            <td style="padding: 15px; text-align: center;">
                <span style="display: inline-block; padding: 5px 10px; border-radius: 5px; background: <?=$user['role'] == 1 ? '#e74c3c' : '#2ecc71'?>; color: white; font-size: 12px;">
                    <?=$user['role'] == 1 ? 'ADMIN' : 'Khách hàng'?>
                </span>
            </td>
            <td style="padding: 15px; text-align: center;">
                <a href="#" style="color: #2980b9; margin-right: 10px;">Sửa</a>
                <a href="#" onclick="return confirm('Xóa người dùng này?')" style="color: #c0392b;">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>