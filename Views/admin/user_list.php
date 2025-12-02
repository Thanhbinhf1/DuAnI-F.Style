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
            <td style="padding: 15px;"><?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?></td>
            <td style="padding: 15px; font-weight: bold;"><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></td>
            <td style="padding: 15px;"><?= htmlspecialchars($user['fullname'], ENT_QUOTES, 'UTF-8') ?></td>
            <td style="padding: 15px;"><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
            <td style="padding: 15px; text-align: center;">
                <!-- FORM CẬP NHẬT VAI TRÒ -->
                <form action="?ctrl=admin&act=userUpdateRole" method="POST" style="display: inline-flex; align-items: center; gap: 10px;">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

                    <select name="role" style="padding: 5px; border-radius: 5px; border: 1px solid #ccc;"
                        <?php if ($user['id'] == $_SESSION['user']['id']) echo 'disabled'; // Tự vô hiệu hóa chính mình ?>>
                        <option value="0" <?= $user['role'] == 0 ? 'selected' : '' ?>>Khách hàng</option>
                        <option value="1" <?= $user['role'] == 1 ? 'selected' : '' ?>>ADMIN</option>
                    </select>

                    <button type="submit" class="btn-update"
                        <?php if ($user['id'] == $_SESSION['user']['id']) echo 'disabled'; // Tự vô hiệu hóa chính mình ?>>
                        Lưu
                    </button>
                </form>
            </td>
            <td style="padding: 15px; text-align: center;">
                <!-- FORM XÓA -->
                <form action="?ctrl=admin&act=userDelete" method="POST" style="display: inline;" onsubmit="return confirm('Xóa người dùng này? Thao tác không thể hoàn tác!');">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <button type="submit" class="btn-delete"
                        <?php if ($user['id'] == $_SESSION['user']['id']) echo 'disabled'; // Tự vô hiệu hóa chính mình ?>>
                        Xóa
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>