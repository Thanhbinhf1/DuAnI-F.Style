<?php
// Views/admin/category_list.php
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Header trang */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #1abc9c;
    /* Màu xanh ngọc cho Danh mục */
    padding-bottom: 15px;
    margin-bottom: 30px;
}

.page-title {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

/* Nút Thêm mới */
.btn-add {
    background: #1abc9c;
    color: white;
    padding: 10px 20px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    box-shadow: 0 4px 6px rgba(26, 188, 156, 0.2);
    transition: transform 0.2s, background 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-add:hover {
    background: #16a085;
    transform: translateY(-2px);
}

/* BẢNG DANH MỤC */
.category-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    overflow: hidden;
}

.category-table thead {
    background: linear-gradient(45deg, #1abc9c, #16a085);
    color: white;
}

.category-table th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.category-table td {
    padding: 15px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
    color: #555;
    font-size: 14px;
}

.category-table tbody tr:last-child td {
    border-bottom: none;
}

.category-table tbody tr:hover {
    background-color: #f9f9f9;
}

/* Badge Trạng thái */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
}

.status-active {
    background: #eafaf1;
    color: #2ecc71;
    border: 1px solid #a9dfbf;
}

.status-inactive {
    background: #fdedec;
    color: #e74c3c;
    border: 1px solid #fadbd8;
}

/* Nút hành động (Icon) */
.action-group {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.btn-icon {
    width: 35px;
    height: 35px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
}

.btn-edit {
    background: #ebf5fb;
    color: #3498db;
}

.btn-edit:hover {
    background: #3498db;
    color: white;
}

.btn-hide {
    background: #fdedec;
    color: #e74c3c;
}

.btn-hide:hover {
    background: #e74c3c;
    color: white;
}

.btn-show {
    background: #eafaf1;
    color: #2ecc71;
}

.btn-show:hover {
    background: #2ecc71;
    color: white;
}
</style>

<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-tags"></i> QUẢN LÝ DANH MỤC
        <span
            style="font-size: 16px; font-weight: normal; color: #95a5a6; margin-left: 10px;">(<?= count($categories) ?>
            mục)</span>
    </h1>
    <a href="?ctrl=admin&act=categoryForm" class="btn-add">
        <i class="fas fa-plus-circle"></i> Thêm Danh Mục
    </a>
</div>

<table class="category-table">
    <thead>
        <tr>
            <th width="10%" style="text-align: center;">ID</th>
            <th width="50%">Tên danh mục</th>
            <th width="20%" style="text-align: center;">Trạng thái</th>
            <th width="20%" style="text-align: center;">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $cat): 
            $status = $cat['status'] ?? 1;
        ?>
        <tr>
            <td style="text-align: center;">
                <strong style="color: #7f8c8d;">#<?= htmlspecialchars($cat['id'], ENT_QUOTES, 'UTF-8') ?></strong>
            </td>

            <td>
                <div style="font-weight: 700; color: #2c3e50; font-size: 15px;">
                    <?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            </td>

            <td style="text-align: center;">
                <?php if ($status == 1): ?>
                <span class="status-badge status-active">
                    <i class="fas fa-check-circle"></i> Hiển thị
                </span>
                <?php else: ?>
                <span class="status-badge status-inactive">
                    <i class="fas fa-eye-slash"></i> Đang ẩn
                </span>
                <?php endif; ?>
            </td>

            <td style="text-align: center;">
                <div class="action-group">
                    <a href="?ctrl=admin&act=categoryForm&id=<?= htmlspecialchars($cat['id'], ENT_QUOTES, 'UTF-8') ?>"
                        class="btn-icon btn-edit" title="Chỉnh sửa">
                        <i class="fas fa-pen"></i>
                    </a>

                    <form action="?ctrl=admin&act=categoryToggleStatus" method="POST" style="display: inline;"
                        onsubmit="return confirm('Bạn có muốn thay đổi trạng thái danh mục này?');">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($cat['id'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="current_status" value="<?= $status ?>">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                        <?php if ($status == 1): ?>
                        <button type="submit" class="btn-icon btn-hide" title="Ẩn danh mục">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                        <?php else: ?>
                        <button type="submit" class="btn-icon btn-show" title="Hiển thị danh mục">
                            <i class="fas fa-eye"></i>
                        </button>
                        <?php endif; ?>
                    </form>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>

        <?php if (empty($categories)): ?>
        <tr>
            <td colspan="4" style="text-align: center; padding: 30px; color: #999;">
                <i class="fas fa-folder-open" style="font-size: 40px; margin-bottom: 10px;"></i><br>
                Chưa có danh mục nào.
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>