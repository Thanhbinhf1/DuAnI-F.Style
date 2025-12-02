<?php include_once 'layout_header.php'; ?>
<h1><?= $product ? 'Sửa Sản Phẩm: ' . htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') : 'Thêm Sản Phẩm Mới' ?></h1>

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
        <label for="image_file">Tải lên ảnh mới (để trống nếu không muốn thay đổi):</label>
        <input type="file" name="image_file" id="image_file" accept="image/*"
               style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        
        <!-- Giữ lại ảnh hiện tại nếu có -->
        <input type="hidden" name="image_current" value="<?= isset($product['image']) ? htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') : '' ?>">
        
        <?php if (isset($product['image']) && $product['image']): ?>
            <div style="margin-top: 15px;">
                <label>Ảnh sản phẩm hiện tại:</label>
                <img src="<?= htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') ?>" alt="Ảnh sản phẩm hiện tại" style="max-width: 100px; display: block; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
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

<?php include_once 'layout_footer.php'; ?>