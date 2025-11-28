<h1>QUẢN LÝ SẢN PHẨM</h1>

<a href="?ctrl=AdminProduct&act=addProduct" class="btn btn-primary" style="margin-bottom: 15px;">+ Thêm sản phẩm mới</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Ảnh</th>
            <th>Danh mục</th>
            <th>Lượt xem</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        // $products được truyền từ Controller
        if (isset($products) && is_array($products)): 
            foreach ($products as $p): 
        ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= $p['name'] ?></td>
                <td><?= number_format($p['price']) ?> VNĐ</td>
                <td><img src="Public/Img/products/<?= $p['image'] ?>" alt="" style="width: 50px; height: 50px; object-fit: cover;"></td>
                <td><?= $p['category_id'] ?></td> <td><?= $p['views'] ?></td>
                <td>
                    <a href="?ctrl=AdminProduct&act=editProduct&id=<?= $p['id'] ?>" class="action-link">Sửa</a> | 
                    <a href="?ctrl=AdminProduct&act=deleteProduct&id=<?= $p['id'] ?>" class="action-link" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                </td>
            </tr>
        <?php 
            endforeach; 
        else:
        ?>
            <tr><td colspan="7">Không có sản phẩm nào.</td></tr>
        <?php endif; ?>
    </tbody>
</table>