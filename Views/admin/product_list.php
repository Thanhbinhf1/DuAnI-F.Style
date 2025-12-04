<?php
// Views/admin/product_list.php
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #27ae60;
    padding-bottom: 15px;
    margin-bottom: 30px;
}

.page-title {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.btn-add {
    background: #27ae60;
    color: white;
    padding: 10px 20px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    box-shadow: 0 4px 6px rgba(39, 174, 96, 0.2);
    transition: transform 0.2s;
}

.btn-add:hover {
    background: #219150;
    transform: translateY(-2px);
}

/* BẢNG SẢN PHẨM */
.product-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    overflow: hidden;
}

.product-table thead {
    background: linear-gradient(45deg, #27ae60, #2ecc71);
    color: white;
}

.product-table th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
}

.product-table td {
    padding: 15px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
    color: #555;
}

.product-table tbody tr:hover {
    background-color: #f9f9f9;
}

/* Ảnh sản phẩm */
.img-wrapper {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #eee;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}

.img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Giá tiền */
.price-group {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.price-sale {
    color: #e74c3c;
    font-weight: 700;
    font-size: 15px;
}

.price-original {
    color: #95a5a6;
    font-size: 12px;
    text-decoration: line-through;
}

/* Badge Trạng thái */
.status-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}

.status-active {
    background: #eafaf1;
    color: #2ecc71;
    border: 1px solid #2ecc71;
}

.status-inactive {
    background: #fdedec;
    color: #e74c3c;
    border: 1px solid #e74c3c;
}

/* Nút thao tác */
.action-group {
    display: flex;
    justify-content: center;
    gap: 8px;
}

.btn-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: 0.2s;
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
    <h1 class="page-title"><i class="fas fa-box"></i> QUẢN LÝ SẢN PHẨM <span
            style="font-size: 16px; font-weight: normal; color: #7f8c8d;">(<?= count($products) ?> sp)</span></h1>
    <a href="?ctrl=admin&act=productForm" class="btn-add"><i class="fas fa-plus"></i> Thêm mới</a>
</div>

<table class="product-table">
    <thead>
        <tr>
            <th width="5%" style="text-align: center;">ID</th>
            <th width="10%" style="text-align: center;">Ảnh</th>
            <th width="30%">Tên sản phẩm</th>
            <th width="15%">Danh mục</th>
            <th width="15%" style="text-align: right;">Giá bán</th>
            <th width="10%" style="text-align: center;">Trạng thái</th>
            <th width="15%" style="text-align: center;">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        // Chuẩn bị danh mục để hiển thị tên
        $categoryNames = [];
        if (isset($categories)) {
            foreach ($categories as $cat) { $categoryNames[$cat['id']] = $cat['name']; }
        }

        foreach ($products as $sp): 
            $img = !empty($sp['image']) ? htmlspecialchars($sp['image'], ENT_QUOTES, 'UTF-8') : 'assets/images/no-image.png';
            $status = $sp['status'] ?? 1; 
        ?>
        <tr>
            <td style="text-align: center; font-weight: bold; color: #7f8c8d;">
                #<?= htmlspecialchars($sp['id'], ENT_QUOTES, 'UTF-8') ?></td>

            <td style="text-align: center;">
                <div class="img-wrapper" style="margin: 0 auto;">
                    <img src="<?= $img ?>" alt="Product Img" onerror="this.src='https://via.placeholder.com/60'">
                </div>
            </td>

            <td>
                <div style="font-weight: 600; color: #2c3e50; font-size: 15px;">
                    <?= htmlspecialchars($sp['name'], ENT_QUOTES, 'UTF-8') ?></div>
                <div style="font-size: 12px; color: #95a5a6; margin-top: 4px;">SKU:
                    <?= htmlspecialchars($sp['sku_code'] ?? 'N/A') ?></div>
            </td>

            <td>
                <span style="background: #f0f2f5; padding: 4px 10px; border-radius: 4px; font-size: 13px; color: #555;">
                    <?= isset($sp['category_id']) ? htmlspecialchars($categoryNames[$sp['category_id']] ?? 'Khác', ENT_QUOTES, 'UTF-8') : 'Khác' ?>
                </span>
            </td>

            <td style="text-align: right;">
                <div class="price-group">
                    <?php if (isset($sp['price_sale']) && $sp['price_sale'] > 0): ?>
                    <span class="price-sale"><?= number_format($sp['price_sale']) ?> ₫</span>
                    <span class="price-original"><?= number_format($sp['price']) ?> ₫</span>
                    <?php else: ?>
                    <span class="price-sale" style="color: #2c3e50;"><?= number_format($sp['price']) ?> ₫</span>
                    <?php endif; ?>
                </div>
            </td>

            <td style="text-align: center;">
                <?php if ($status == 1): ?>
                <span class="status-badge status-active"><i class="fas fa-check-circle"></i> Hiển thị</span>
                <?php else: ?>
                <span class="status-badge status-inactive"><i class="fas fa-eye-slash"></i> Đã ẩn</span>
                <?php endif; ?>
            </td>

            <td style="text-align: center;">
                <div class="action-group">
                    <a href="?ctrl=admin&act=productForm&id=<?= htmlspecialchars($sp['id'], ENT_QUOTES, 'UTF-8') ?>"
                        class="btn-icon btn-edit" title="Chỉnh sửa">
                        <i class="fas fa-pen"></i>
                    </a>

                    <form action="?ctrl=admin&act=productToggleStatus" method="POST" style="display: inline;"
                        onsubmit="return confirm('Bạn có muốn thay đổi trạng thái sản phẩm này?');">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($sp['id'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="current_status" value="<?= $status ?>">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                        <?php if ($status == 1): ?>
                        <button type="submit" class="btn-icon btn-hide" title="Ẩn sản phẩm">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                        <?php else: ?>
                        <button type="submit" class="btn-icon btn-show" title="Hiển thị sản phẩm">
                            <i class="fas fa-eye"></i>
                        </button>
                        <?php endif; ?>
                    </form>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>