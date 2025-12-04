<?php
// Views/admin/user_list.php
?>
<style>
/* CSS cho các badge số lượng */
.badge-info {
    background-color: #e3f2fd;
    color: #1976d2;
    padding: 4px 10px;
    border-radius: 12px;
    font-weight: bold;
    font-size: 13px;
}

.badge-danger {
    background-color: #fce4ec;
    color: #c2185b;
    padding: 4px 10px;
    border-radius: 12px;
    font-weight: bold;
    font-size: 13px;
}

.badge-zero {
    color: #bdc3c7;
    font-size: 13px;
}

/* CSS cho nút cập nhật */
.btn-update {
    background-color: #27ae60;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s;
}

.btn-update:hover {
    background-color: #219150;
}

.btn-update:disabled {
    background-color: #bdc3c7;
    cursor: not-allowed;
}

/* CSS cho nút Xem Lịch Sử */
.btn-history {
    background-color: #3498db;
    color: white;
    padding: 6px 10px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 13px;
    font-weight: bold;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: background 0.3s;
}

.btn-history:hover {
    background-color: #2980b9;
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<h1 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-bottom: 30px;">
    QUẢN LÝ NGƯỜI DÙNG (<?=count($users)?> người)
</h1>

<table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
    <thead style="background: #3498db; color: white;">
        <tr>
            <th style="padding: 15px; text-align: left;">ID</th>
            <th style="padding: 15px; text-align: left;">Tên đăng nhập</th>
            <th style="padding: 15px; text-align: left;">Email</th>
            <th style="padding: 15px; text-align: center;">Tổng mua</th>
            <th style="padding: 15px; text-align: center;">Đã hủy</th>
            <th style="padding: 15px; text-align: left;">Mật khẩu</th>
            <th style="padding: 15px; text-align: center;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): 
            // Lấy số liệu (Nếu Model chưa trả về thì mặc định là 0 để không lỗi)
            $totalOrders = $user['total_orders'] ?? 0;
            $cancelledOrders = $user['cancelled_orders'] ?? 0;
        ?>
        <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 15px;"><?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?></td>

            <td style="padding: 15px;">
                <div style="font-weight: bold; color: #2c3e50;">
                    <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div style="font-size: 12px; color: #7f8c8d;">
                    <?= htmlspecialchars($user['fullname'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            </td>

            <td style="padding: 15px;"><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>

            <td style="padding: 15px; text-align: center;">
                <?php if ($totalOrders > 0): ?>
                <span class="badge-info"><?= $totalOrders ?> đơn</span>
                <?php else: ?>
                <span class="badge-zero">-</span>
                <?php endif; ?>
            </td>

            <td style="padding: 15px; text-align: center;">
                <?php if ($cancelledOrders > 0): ?>
                <span class="badge-danger"><?= $cancelledOrders ?> đơn</span>
                <?php else: ?>
                <span class="badge-zero">-</span>
                <?php endif; ?>
            </td>

            <td style="padding: 15px;">
                <div
                    style="background: #f1f1f1; padding: 5px 10px; border-radius: 4px; display: inline-block; color: #7f8c8d;">
                    <span style="font-size: 18px; line-height: 10px; position: relative; top: 3px;">••••••</span>
                </div>
            </td>

            <td style="padding: 15px; text-align: center;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">

                    <a href="?ctrl=admin&act=userDetail&id=<?= $user['id'] ?>" class="btn-history"
                        title="Xem lịch sử mua hàng">
                        <i class="fas fa-eye"></i> Xem LS
                    </a>

                    <form action="?ctrl=admin&act=userUpdateRole" method="POST"
                        style="display: inline-flex; align-items: center; gap: 5px; margin: 0;">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

                        <select name="role"
                            style="padding: 6px; border-radius: 4px; border: 1px solid #ccc; font-size: 13px;"
                            <?php if ($user['id'] == $_SESSION['user']['id']) echo 'disabled'; ?>>
                            <option value="0" <?= $user['role'] == 0 ? 'selected' : '' ?>>Khách</option>
                            <option value="1" <?= $user['role'] == 1 ? 'selected' : '' ?>>ADMIN</option>
                        </select>

                        <button type="submit" class="btn-update"
                            <?php if ($user['id'] == $_SESSION['user']['id']) echo 'disabled'; ?>>
                            Lưu
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>