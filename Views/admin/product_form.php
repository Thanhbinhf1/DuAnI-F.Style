<h1 style="color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; margin-bottom: 30px;">
    <?= isset($product) ? 'CẬP NHẬT SẢN PHẨM' : 'THÊM SẢN PHẨM MỚI' ?>
</h1>

<div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
    <form action="?ctrl=admin&act=productPost" method="post">
        <input type="hidden" name="id" value="<?= $product['id'] ?? 0 ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Tên Sản Phẩm:</label>
                <input type="text" name="name" value="<?= $product['name'] ?? '' ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Danh mục:</label>
                <select name="category_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" 
                            <?= (isset($product['category_id']) && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= $cat['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Giá bán (đ):</label>
                <input type="number" name="price" value="<?= $product['price'] ?? '' ?>" required min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Giá Sale (đ):</label>
                <input type="number" name="price_sale" value="<?= $product['price_sale'] ?? 0 ?>" min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <div style="margin-bottom: 15px; grid-column: 1 / span 2;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Link Ảnh Chính:</label>
                <input type="text" name="image" value="<?= $product['image'] ?? '' ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Chất liệu:</label>
                <input type="text" name="material" value="<?= $product['material'] ?? '' ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Thương hiệu:</label>
                <input type="text" name="brand" value="<?= $product['brand'] ?? '' ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <div style="margin-bottom: 15px; grid-column: 1 / span 2;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Mã SKU/Code:</label>
                <input type="text" name="sku_code" value="<?= $product['sku_code'] ?? '' ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            </div>
            
            <div style="margin-bottom: 15px; grid-column: 1 / span 2;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Mô tả chi tiết:</label>
                <textarea name="description" rows="5" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"><?= $product['description'] ?? '' ?></textarea>
            </div>
        </div>
        
        <button type="submit" style="padding: 10px 20px; background: #2c3e50; color: white; border: none; border-radius: 5px; cursor: pointer;">
            <?= isset($product) ? 'Cập Nhật Sản Phẩm' : 'Thêm Sản Phẩm' ?>
        </button>
        <a href="?ctrl=admin&act=productList" style="margin-left: 10px; color: #7f8c8d;">Hủy</a>
    </form>
</div>