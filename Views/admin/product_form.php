<?php 
// Views/admin/product_form.php

// Định nghĩa biến $galleryImages nếu không có (tránh lỗi PHP Notice)
$galleryImages = $galleryImages ?? [];
$isEdit = !empty($product); // Biến kiểm tra đang sửa hay thêm mới
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* ... (CSS CŨ GIỮ NGUYÊN) ... */
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

.form-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}

@media (max-width: 992px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
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

.form-row {
    display: flex;
    gap: 20px;
}

.form-col {
    flex: 1;
}

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
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #eee;
}

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

/* CSS cho thông báo lỗi Validation */
.error-msg {
    color: #e74c3c;
    font-size: 12px;
    margin-top: 5px;
    display: none;
    /* Mặc định ẩn */
}

.form-control.is-invalid {
    border-color: #e74c3c;
    background-color: #fff8f8;
}
</style>

<div class="form-container">
    <div class="page-header">
        <h1 class="page-title">
            <?= $isEdit ? '<i class="fas fa-pen-square"></i> Cập Nhật Sản Phẩm' : '<i class="fas fa-plus-circle"></i> Thêm Sản Phẩm Mới' ?>
        </h1>
    </div>

    <form id="productForm" action="?ctrl=admin&act=productPost" method="post" enctype="multipart/form-data"
        onsubmit="return validateForm()">
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
                        <div id="error-name" class="error-msg">Tên sản phẩm này đã tồn tại!</div>
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
                                    step="1000" onchange="validatePrice()"
                                    value="<?= isset($product['price']) ? htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8') : '' ?>">
                                <div id="error-price" class="error-msg">Giá gốc không được để trống hoặc âm.</div>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="price_sale">Giá khuyến mãi (VNĐ)</label>
                                <input type="number" name="price_sale" id="price_sale" class="form-control" min="0"
                                    step="1000" onchange="validatePrice()"
                                    value="<?= isset($product['price_sale']) ? htmlspecialchars($product['price_sale'], ENT_QUOTES, 'UTF-8') : 0 ?>">
                                <div id="error-price_sale" class="error-msg">Giá khuyến mãi phải nhỏ hơn giá gốc!</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Chi tiết sản phẩm</div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label class="form-label" for="sku_code">Mã SKU</label>
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
                        <input type="file" name="image_file" id="image_file" accept="image/*" class="form-control"
                            onchange="previewImage(this)">
                        <input type="hidden" name="image_current"
                            value="<?= isset($product['image']) ? htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') : '' ?>">

                        <div class="image-preview-box">
                            <?php if (isset($product['image']) && $product['image']): ?>
                            <img id="preview-img" src="<?= htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') ?>"
                                class="current-img">
                            <div style="font-size: 12px; color: #7f8c8d;">Ảnh hiện tại</div>
                            <?php else: ?>
                            <img id="preview-img" src=""
                                style="display:none; max-width:100%; border-radius:6px; margin-bottom:10px;">
                            <div id="no-img-text">
                                <i class="fas fa-image" style="font-size: 40px; color: #ddd; margin-bottom: 10px;"></i>
                                <div style="font-size: 13px; color: #999;">Chưa có ảnh</div>
                            </div>
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
                            <i class="fas fa-info-circle"></i> Chọn ảnh mới sẽ <strong>thay thế</strong> toàn bộ album
                            cũ.
                        </div>
                        <?php if (!empty($galleryImages)): ?>
                        <div class="gallery-grid">
                            <?php foreach ($galleryImages as $gImg): ?>
                            <div class="gallery-item">
                                <img src="<?= htmlspecialchars($gImg['image_url'], ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mb-3" style="border: 1px solid #ddd; padding: 15px; border-radius: 5px; background: #f9f9f9;">
    <label class="form-label fw-bold">Phân loại hàng (Màu sắc & Kích thước)</label>
    
    <table class="table table-bordered" id="variantTable" style="background: white;">
        <thead>
            <tr>
                <th>Màu sắc</th>
                <th>Kích thước (Size)</th>
                <th>Số lượng kho</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Nếu đang Sửa, lấy biến thể cũ từ DB hiện ra
            // Cần gọi Model trong Controller trước khi include View để có biến $variantsList
            // Tuy nhiên, để nhanh gọn, ta giả sử mảng này trống nếu thêm mới
            
            // Bạn cần thêm dòng này vào productForm() trong AdminController:
            // $variantsList = [];
            // if ($id > 0) $variantsList = $this->model->getVariants($id);

            $variantsList = isset($variantsList) ? $variantsList : [];
            if (!empty($variantsList)) {
                foreach ($variantsList as $index => $v) {
                    echo "<tr>
                        <td><input type='text' name='variants[$index][color]' class='form-control' value='{$v['color']}' placeholder='Ví dụ: Đen' required></td>
                        <td><input type='text' name='variants[$index][size]' class='form-control' value='{$v['size']}' placeholder='Ví dụ: XL' required></td>
                        <td><input type='number' name='variants[$index][quantity]' class='form-control' value='{$v['quantity']}' min='0' required></td>
                        <td><button type='button' class='btn btn-danger btn-sm' onclick='removeRow(this)'>Xóa</button></td>
                    </tr>";
                }
            }
            ?>
        </tbody>
    </table>
    <button type="button" class="btn btn-primary btn-sm" onclick="addVariantRow()">+ Thêm phân loại</button>
</div>

<script>
    function addVariantRow() {
        var table = document.getElementById("variantTable").getElementsByTagName('tbody')[0];
        var rowCount = table.rows.length; // Dùng làm index
        var row = table.insertRow(rowCount);
        
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        
        // Tạo input name="variants[0][color]", variants[1][color]...
        cell1.innerHTML = `<input type="text" name="variants[${rowCount}][color]" class="form-control" placeholder="Màu (Đen, Trắng...)" required>`;
        cell2.innerHTML = `<input type="text" name="variants[${rowCount}][size]" class="form-control" placeholder="Size (S, M, L...)" required>`;
        cell3.innerHTML = `<input type="number" name="variants[${rowCount}][quantity]" class="form-control" value="10" min="0" required>`;
        cell4.innerHTML = `<button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Xóa</button>`;
    }

    function removeRow(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }
</script>

        <div style="margin-top: 20px; padding-bottom: 50px;">
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> <?= $isEdit ? 'Lưu Thay Đổi' : 'Thêm Sản Phẩm' ?>
            </button>
            <a href="?ctrl=admin&act=listProducts" class="btn-cancel">Hủy bỏ</a>
        </div>
    </form>
</div>

<script>
// 1. Hàm kiểm tra Giá tiền
function validatePrice() {
    const price = document.getElementById('price');
    const priceSale = document.getElementById('price_sale');
    const errPrice = document.getElementById('error-price');
    const errSale = document.getElementById('error-price_sale');
    let isValid = true;

    // Reset lỗi
    price.classList.remove('is-invalid');
    priceSale.classList.remove('is-invalid');
    errPrice.style.display = 'none';
    errSale.style.display = 'none';

    // Kiểm tra Giá gốc
    const valPrice = parseFloat(price.value) || 0;
    if (valPrice <= 0) {
        price.classList.add('is-invalid');
        errPrice.style.display = 'block';
        isValid = false;
    }

    // Kiểm tra Giá khuyến mãi (nếu có nhập)
    const valSale = parseFloat(priceSale.value) || 0;
    if (valSale > 0) {
        if (valSale >= valPrice) {
            priceSale.classList.add('is-invalid');
            errSale.innerText = "Giá khuyến mãi phải NHỎ HƠN giá gốc (" + valPrice.toLocaleString() + " đ)";
            errSale.style.display = 'block';
            isValid = false;
        }
    }

    return isValid;
}

// 2. Hàm Preview ảnh khi chọn file
function previewImage(input) {
    const preview = document.getElementById('preview-img');
    const noImgText = document.getElementById('no-img-text');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (noImgText) noImgText.style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// 3. Hàm kiểm tra tổng quát khi bấm Submit
function validateForm() {
    let isPriceValid = validatePrice();
    if (!isPriceValid) return false;

    // Lưu ý: Kiểm tra trùng tên phải làm bằng AJAX hoặc PHP (Server-side)
    // Code bên dưới chỉ là validation cơ bản phía Client
    return true;
}
</script>