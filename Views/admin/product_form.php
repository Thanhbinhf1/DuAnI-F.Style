<?php include_once 'layout_header.php'; ?>
<h1><?= $product ? 'Sửa Sản Phẩm: ' . $product['name'] : 'Thêm Sản Phẩm Mới' ?></h1>

<form action="?ctrl=admin&act=productPost" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $product['id'] ?? 0 ?>">

    <div style="margin-bottom: 15px;">
        <label for="category_id">Danh mục:</label>
        <select name="category_id" id="category_id" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" 
                    <?= ($product && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= $cat['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" name="name" id="name" required 
               value="<?= $product['name'] ?? '' ?>" 
               style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    
    <div style="display: flex; gap: 20px;">
        <div style="margin-bottom: 15px; flex: 1;">
            <label for="price">Giá gốc:</label>
            <input type="number" name="price" id="price" required 
                   value="<?= $product['price'] ?? '' ?>" 
                   style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <div style="margin-bottom: 15px; flex: 1;">
            <label for="price_sale">Giá khuyến mãi (0 nếu không có):</label>
            <input type="number" name="price_sale" id="price_sale" 
                   value="<?= $product['price_sale'] ?? 0 ?>" 
                   style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="image">Ảnh (URL hoặc tên file):</label>
        <input type="text" name="image" id="image" required 
               value="<?= $product['image'] ?? '' ?>" 
               style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        <?php if ($product && $product['image']): ?>
            <img src="<?= $product['image'] ?>" alt="Ảnh sản phẩm hiện tại" style="max-width: 100px; margin-top: 10px; display: block;">
        <?php endif; ?>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="description">Mô tả:</label>
        <textarea name="description" id="description" rows="5" 
                  style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"><?= $product['description'] ?? '' ?></textarea>
    </div>
    
    <div style="display: flex; gap: 20px;">
        <div style="margin-bottom: 15px; flex: 1;">
            <label for="material">Chất liệu:</label>
            <input type="text" name="material" id="material" 
                   value="<?= $product['material'] ?? '' ?>" 
                   style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <div style="margin-bottom: 15px; flex: 1;">
            <label for="brand">Thương hiệu:</label>
            <input type="text" name="brand" id="brand" 
                   value="<?= $product['brand'] ?? 'F.Style' ?>" 
                   style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <div style="margin-bottom: 15px; flex: 1;">
            <label for="sku_code">Mã SKU:</label>
            <input type="text" name="sku_code" id="sku_code" 
                   value="<?= $product['sku_code'] ?? '' ?>" 
                   style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
    </div>


    <button type="submit" class="btn-primary" style="padding: 10px 20px; font-size: 16px;">
        <?= $product ? 'Cập Nhật Sản Phẩm' : 'Thêm Sản Phẩm Mới' ?>
    </button>
    <a href="?ctrl=admin&act=listProducts" class="btn-secondary" style="padding: 10px 20px; font-size: 16px;">Hủy</a>
</form>

<?php include_once 'layout_footer.php'; ?>