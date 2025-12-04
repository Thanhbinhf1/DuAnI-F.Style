<?php 
// Views/admin/product_form.php

// Định nghĩa biến $galleryImages nếu không có (tránh lỗi PHP Notice)
$galleryImages = $galleryImages ?? [];
$isEdit = !empty($product); // Biến kiểm tra đang sửa hay thêm mới
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.form-container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #444;
}

.page-header {
    border-bottom: 2px solid #3498db;
    padding-bottom: 15px;
    margin-bottom: 30px;
}

.page-title {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

/* Layout Form 2 cột */
.form-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    /* Cột trái lớn gấp đôi cột phải */
    gap: 30px;
}

@media (max-width: 992px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    /* Mobile hiện 1 cột */
}

.card {
    background: #fff;
    border-radius: 8px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
}

.card-header {
    font-size: 16px;
    font-weight: 700;
    color: #2c3e50;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
    margin-bottom: 20px;
    text-transform: uppercase;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #555;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-control:focus {
    border-color: #3498db;
    outline: none;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

/* Row chứa 2 input cạnh nhau */
.form-row {
    display: flex;
    gap: 20px;
}

.form-col {
    flex: 1;
}

/* Phần upload ảnh */
.image-preview-box {
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    background: #f9f9f9;
    margin-top: 10px;
}

.current-img {
    max-width: 100%;
    height: auto;
    border-radius: 6px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-bottom: 10px;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-top: 10px;
}

.gallery-item img {
    width: 100%;
    aspect-ratio: 1/1;
    /* Ảnh vuông */
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #eee;
}

/* Nút bấm */
.btn-submit {
    background: #3498db;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-submit:hover {
    background: #2980b9;
}

.btn-cancel {
    background: #95a5a6;
    color: white;
    text-decoration: none;
    padding: 12px 25px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    display: inline-block;
    margin-left: 10px;
}

.btn-cancel:hover {
    background: #7f8c8d;
}

.required-star {
    color: #e74c3c;
    margin-left: 3px;
}
</style>

<div class="form-container">
    <div class="page-header">
        <h1 class="page-title">
            <?= $isEdit ? '<i class="fas fa-pen-square"></i> Cập Nhật Sản Phẩm' : '<i class="fas fa-plus-circle"></i> Thêm Sản Phẩm Mới' ?>
        </h1>
    </div>

    <form action="?ctrl=admin&act=productPost" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id"
            value="<?= isset($product['id']) ? htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8') : 0 ?>">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

        <div class="form-grid">
            <div>
                <div class="card">
                    <div class="card-header">Thông tin cơ bản</div>

                    <div class="form-group">
                        <label class="form-label" for="name">Tên sản phẩm <span class="required-star">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" required
                            placeholder="Nhập tên sản phẩm..."
                            value="<?= isset($product['name']) ? htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') : '' ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="category_id">Danh mục <span
                                        class="required-star">*</span></label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?= htmlspecialchars($cat['id'], ENT_QUOTES, 'UTF-8') ?>"
                                        <?= (isset($product['category_id']) && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="brand">Thương hiệu</label>
                                <input type="text" name="brand" id="brand" class="form-control"
                                    value="<?= isset($product['brand']) ? htmlspecialchars($product['brand'], ENT_QUOTES, 'UTF-8') : 'F.Style' ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="price">Giá gốc (VNĐ) <span
                                        class="required-star">*</span></label>
                                <input type="number" name="price" id="price" class="form-control" required min="0"
                                    step="1000"
                                    value="<?= isset($product['price']) ? htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8') : '' ?>">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="price_sale">Giá khuyến mãi (VNĐ)</label>
                                <input type="number" name="price_sale" id="price_sale" class="form-control" min="0"
                                    step="1000"
                                    value="<?= isset($product['price_sale']) ? htmlspecialchars($product['price_sale'], ENT_QUOTES, 'UTF-8') : 0 ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Chi tiết sản phẩm</div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="sku_code">Mã SKU (Mã kho)</label>
                                <input type="text" name="sku_code" id="sku_code" class="form-control"
                                    placeholder="VD: AO-001"
                                    value="<?= isset($product['sku_code']) ? htmlspecialchars($product['sku_code'], ENT_QUOTES, 'UTF-8') : '' ?>">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="material">Chất liệu</label>
                                <input type="text" name="material" id="material" class="form-control"
                                    placeholder="VD: Cotton 100%"
                                    value="<?= isset($product['material']) ? htmlspecialchars($product['material'], ENT_QUOTES, 'UTF-8') : '' ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="description">Mô tả sản phẩm</label>
                        <textarea name="description" id="description" class="form-control"
                            placeholder="Nhập mô tả chi tiết..."><?= isset($product['description']) ? htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8') : '' ?></textarea>
                    </div>
                </div>
            </div>

            <div>
                <div class="card">
                    <div class="card-header">Ảnh Đại diện</div>
                    <div class="form-group">
                        <input type="file" name="image_file" id="image_file" accept="image/*" class="form-control">
                        <input type="hidden" name="image_current"
                            value="<?= isset($product['image']) ? htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') : '' ?>">

                        <div class="image-preview-box">
                            <?php if (isset($product['image']) && $product['image']): ?>
                            <img src="<?= htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') ?>"
                                class="current-img" alt="Ảnh sản phẩm">
                            <div style="font-size: 12px; color: #7f8c8d;">Ảnh hiện tại</div>
                            <?php else: ?>
                            <i class="fas fa-image" style="font-size: 40px; color: #ddd; margin-bottom: 10px;"></i>
                            <div style="font-size: 13px; color: #999;">Chưa có ảnh</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Album ảnh (Gallery)</div>
                    <div class="form-group">
                        <input type="file" name="gallery_files[]" id="gallery_files" multiple accept="image/*"
                            class="form-control">
                        <div style="font-size: 11px; color: #e67e22; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> Lưu ý: Chọn ảnh mới sẽ <strong>thay thế</strong> toàn bộ
                            album cũ.
                        </div>

                        <?php if (!empty($galleryImages)): ?>
                        <div class="gallery-grid">
                            <?php foreach ($galleryImages as $gImg): ?>
                            <div class="gallery-item">
                                <img src="<?= htmlspecialchars($gImg['image_url'], ENT_QUOTES, 'UTF-8') ?>"
                                    alt="Gallery">
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-top: 20px; padding-bottom: 50px;">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> <?= $isEdit ? 'Lưu Thay Đổi' : 'Thêm Sản Phẩm' ?>
            </button>
            <a href="?ctrl=admin&act=listProducts" class="btn-cancel">Hủy bỏ</a>
        </div>
    </form>
</div>