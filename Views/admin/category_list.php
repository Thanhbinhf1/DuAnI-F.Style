<?php
// Views/admin/category_list.php
?>
<h1 style="color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; margin-bottom: 30px;">QUẢN LÝ DANH MỤC</h1>

<a href="?ctrl=admin&act=categoryForm" style="display: inline-block; padding: 10px 15px; background: #27ae60; color: white; text-decoration: none; border-radius: 5px; margin-bottom: 20px;">+ Thêm Danh Mục Mới</a>

<table style="width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border-radius: 8px;">
    <thead>
        <tr style="background-color: #f1c40f; color: #333;">
            <th style="padding: 15px; text-align: left;">ID</th>
            <th style="padding: 15px; text-align: left;">Tên danh mục</th>
            <th style="padding: 15px; text-align: center;">Trạng thái</th>
            <th style="padding: 15px; text-align: center;">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $cat): 
            $status = $cat['status'] ?? 1; // Lấy trạng thái, mặc định là 1 nếu cột bị thiếu
            $statusLabel = $status == 1 ? '<span style="color: green; font-weight: 600;">Hiển thị</span>' : '<span style="color: red; font-weight: 600;">Ẩn</span>';
            $buttonText = $status == 1 ? 'ẨN' : 'HIỆN';
            $buttonClass = $status == 1 ? 'btn-delete' : 'btn-update'; // Tái sử dụng CSS
        ?>
        <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 15px;"><?= htmlspecialchars($cat['id'], ENT_QUOTES, 'UTF-8') ?></td>
            <td style="padding: 15px; font-weight: 600;"><?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td style="padding: 15px; text-align: center;">
                <?= $statusLabel ?>
            </td>
            <td style="padding: 15px; text-align: center;">
                <a href="?ctrl=admin&act=categoryForm&id=<?= htmlspecialchars($cat['id'], ENT_QUOTES, 'UTF-8') ?>" style="color: #3498db; text-decoration: none; margin-right: 10px;">Sửa</a>
                
                <form action="?ctrl=admin&act=categoryToggleStatus" method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn chuyển trạng thái danh mục này sang <?= $buttonText ?>?');">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($cat['id'], ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="current_status" value="<?= $status ?>">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <button type="submit" class="<?= $buttonClass ?>" style="padding: 5px 10px; border-radius: 5px; font: inherit; cursor: pointer;">
                        <?= $buttonText ?>
                    </button>
                </form>

                <form action="?ctrl=admin&act=categoryDelete" method="POST" style="display: inline;" onsubmit="return confirm('Xóa danh mục này sẽ XÓA VĨNH VIỄN tất cả sản phẩm thuộc nó . Bạn CÓ CHẮC CHẮN muốn XÓA?');">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($cat['id'], ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <button type="submit" style="color: #e74c3c; background: none; border: none; padding: 0; font: inherit; cursor: pointer; text-decoration: underline; margin-left: 10px;">
                        Xóa
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>