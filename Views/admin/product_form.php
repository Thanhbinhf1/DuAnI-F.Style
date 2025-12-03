<?php 
// Views/admin/product_form.php

// Định nghĩa biến $galleryImages nếu không có (tránh lỗi PHP Notice)
$galleryImages = $galleryImages ?? [];
?>
<h1 style="color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; margin-bottom: 30px;">
    <?= $product ? 'Sửa Sản Phẩm: ' . htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') : 'Thêm Sản Phẩm Mới' ?>
</h1>

<div style="max-width: 800px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
    <form action="?ctrl=admin&act=productPost" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= isset($product['id']) ? htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8') : 0 ?>">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

        <div style="margin-bottom: 15px;">
            <label for="category_id">Danh mục:</label>
            <select name="category_id" id="category_id" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['id'], ENT_QUOTES, 'UTF-8') ?>" 
                        <?= (isset($product['category_id']) && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="name">Tên sản phẩm:</label>
            <input type="text" name="name" id="name" required 
                value="<?= isset($product['name']) ? htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') : '' ?>" 
                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        
        <div style="display: flex; gap: 20px;">
            <div style="margin-bottom: 15px; flex: 1;">
                <label for="price">Giá gốc:</label>
                <input type="number" name="price" id="price" required 
                    value="<?= isset($product['price']) ? htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8') : '' ?>" 
                    style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 15px; flex: 1;">
                <label for="price_sale">Giá khuyến mãi (0 nếu không có):</label>
                <input type="number" name="price_sale" id="price_sale" 
                    value="<?= isset($product['price_sale']) ? htmlspecialchars($product['price_sale'], ENT_QUOTES, 'UTF-8') : 0 ?>" 
                    style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="image_file">Tải lên Ảnh CHÍNH (để trống nếu không muốn thay đổi):</label>
            <input type="file" name="image_file" id="image_file" accept="image/*"
                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            <input type="hidden" name="image_current" value="<?= isset($product['image']) ? htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') : '' ?>">
            
            <?php if (isset($product['image']) && $product['image']): ?>
                <div style="margin-top: 15px;">
                    <label>Ảnh sản phẩm hiện tại:</label>
                    <img src="<?= htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') ?>" alt="Ảnh sản phẩm hiện tại" style="max-width: 100px; display: block; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
                </div>
            <?php endif; ?>
        </div>

        <div style="margin-bottom: 25px;">
            <label for="gallery_files" style="font-weight: bold; color: #2980b9;">Tải lên Ảnh GALLERY (Chọn nhiều file):</label>
            <input type="file" name="gallery_files[]" id="gallery_files" multiple accept="image/*"
                style="width: 100%; padding: 8px; border: 1px solid #3498db; border-radius: 4px;">
            <small style="color:#777;">Tải lên ảnh mới sẽ **thay thế toàn bộ** gallery hiện có.</small>
            
            <?php if (!empty($galleryImages)): ?>
                <div style="margin-top: 15px;">
                    <label>Gallery hiện tại (<?= count($galleryImages) ?> ảnh):</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <?php foreach ($galleryImages as $gImg): ?>
                        <img src="<?= htmlspecialchars($gImg['image_url'], ENT_QUOTES, 'UTF-8') ?>" style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #ddd; padding: 3px; border-radius: 4px;">
                    <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="description">Mô tả:</label>
            <textarea name="description" id="description" rows="5" 
                      style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"><?= isset($product['description']) ? htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8') : '' ?></textarea>
        </div>
        
        <div style="display: flex; gap: 20px;">
            <div style="margin-bottom: 15px; flex: 1;">
                <label for="material">Chất liệu:</label>
                <input type="text" name="material" id="material" 
                       value="<?= isset($product['material']) ? htmlspecialchars($product['material'], ENT_QUOTES, 'UTF-8') : '' ?>" 
                       style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 15px; flex: 1;">
                <label for="brand">Thương hiệu:</label>
                <input type="text" name="brand" id="brand" 
                       value="<?= isset($product['brand']) ? htmlspecialchars($product['brand'], ENT_QUOTES, 'UTF-8') : 'F.Style' ?>" 
                       style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 15px; flex: 1;">
                <label for="sku_code">Mã SKU:</label>
                <input type="text" name="sku_code" id="sku_code" 
                       value="<?= isset($product['sku_code']) ? htmlspecialchars($product['sku_code'], ENT_QUOTES, 'UTF-8') : '' ?>" 
                       style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
        </div>


        <button type="submit" class="btn-primary" style="padding: 10px 20px; font-size: 16px;">
            <?= $product ? 'Cập Nhật Sản Phẩm' : 'Thêm Sản Phẩm Mới' ?>
        </button>
        <a href="?ctrl=admin&act=listProducts" class="btn-secondary" style="padding: 10px 20px; font-size: 16px;">Hủy</a>
    </form>
</div>