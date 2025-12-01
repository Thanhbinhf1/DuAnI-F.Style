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
        <?php foreach ($categories as $cat): ?>
        <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 15px;"><?= $cat['id'] ?></td>
            <td style="padding: 15px; font-weight: 600;"><?= $cat['name'] ?></td>
            <td style="padding: 15px; text-align: center;">
                <?= $cat['status'] == 1 ? '<span style="color: green;">Hiển thị</span>' : '<span style="color: red;">Ẩn</span>' ?>
            </td>
            <td style="padding: 15px; text-align: center;">
                <a href="?ctrl=admin&act=categoryForm&id=<?= $cat['id'] ?>" style="color: #3498db; text-decoration: none; margin-right: 10px;">Sửa</a>
                <a href="?ctrl=admin&act=categoryDelete&id=<?= $cat['id'] ?>" 
                   onclick="return confirm('Xóa danh mục này sẽ xóa tất cả sản phẩm thuộc nó. Bạn có chắc chắn?')" 
                   style="color: #e74c3c; text-decoration: none;">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>