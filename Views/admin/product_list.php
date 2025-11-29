<h1 style="color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; margin-bottom: 30px;">QUẢN LÝ SẢN PHẨM</h1>

<a href="?ctrl=admin&act=productForm" style="display: inline-block; padding: 10px 15px; background: #27ae60; color: white; text-decoration: none; border-radius: 5px; margin-bottom: 20px;">+ Thêm Sản Phẩm Mới</a>

<table style="width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border-radius: 8px;">
    <thead>
        <tr style="background-color: #34495e; color: white;">
            <th style="padding: 15px; text-align: left;">ID</th>
            <th style="padding: 15px; text-align: left;">Tên sản phẩm</th>
            <th style="padding: 15px; text-align: left;">Danh mục</th>
            <th style="padding: 15px; text-align: right;">Giá bán</th>
            <th style="padding: 15px; text-align: right;">Lượt xem</th>
            <th style="padding: 15px; text-align: center;">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $sp): ?>
        <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 15px;"><?= $sp['id'] ?></td>
            <td style="padding: 15px;">
                <img src="<?= $sp['image'] ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; vertical-align: middle; margin-right: 10px;">
                <span style="font-weight: 600;"><?= $sp['name'] ?></span>
            </td>
            <td style="padding: 15px;"><?= $sp['category_name'] ?></td>
            <td style="padding: 15px; text-align: right;">
                <?= number_format($sp['price']) ?> đ
                <?php if ($sp['price_sale'] > 0) echo ' <span style="color: red;">(Sale)</span>'; ?>
            </td>
            <td style="padding: 15px; text-align: right;"><?= $sp['views'] ?></td>
            <td style="padding: 15px; text-align: center;">
                <a href="?ctrl=admin&act=productForm&id=<?= $sp['id'] ?>" style="color: #3498db; text-decoration: none; margin-right: 10px;">Sửa</a>
                <a href="?ctrl=admin&act=productDelete&id=<?= $sp['id'] ?>" 
                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')" 
                   style="color: #e74c3c; text-decoration: none;">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>