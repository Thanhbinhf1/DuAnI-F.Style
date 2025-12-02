<?php
// Views/admin/product_list.php
?>
<h1 style="color: #2c3e50; border-bottom: 2px solid #27ae60; padding-bottom: 10px; margin-bottom: 30px;">QUẢN LÝ SẢN PHẨM (<?=count($products)?> sản phẩm)</h1>

<div style="margin-bottom: 20px;">
    <a href="#" style="background: #27ae60; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none;">+ Thêm Sản Phẩm Mới</a>
</div>

<table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
    <thead style="background: #27ae60; color: white;">
        <tr>
            <th width="5%" style="padding: 15px; text-align: left;">ID</th>
            <th width="10%" style="padding: 15px;">Ảnh</th>
            <th width="30%" style="padding: 15px; text-align: left;">Tên sản phẩm</th>
            <th width="15%" style="padding: 15px; text-align: left;">Danh mục</th>
            <th width="15%" style="padding: 15px; text-align: right;">Giá bán</th>
            <th width="10%" style="padding: 15px; text-align: center;">Lượt xem</th>
            <th width="15%" style="padding: 15px; text-align: center;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $categoryNames = [];
        // Tạo mảng ánh xạ Category ID -> Tên Category
        foreach ($categories as $cat) { $categoryNames[$cat['id']] = $cat['name']; }

        foreach ($products as $sp): 
            $img = !empty($sp['image']) ? $sp['image'] : 'https://via.placeholder.com/80';
        ?>
        <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 15px;"><?=$sp['id']?></td>
            <td style="padding: 15px; text-align: center;">
                <img src="<?=$img?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 3px;">
            </td>
            <td style="padding: 15px; font-weight: bold;"><?=$sp['name']?></td>
            <td style="padding: 15px;"><?=$categoryNames[$sp['category_id']] ?? 'Khác'?></td>
            <td style="padding: 15px; text-align: right; color: #e67e22;"><?=number_format($sp['price'])?> đ</td>
            <td style="padding: 15px; text-align: center;"><?=$sp['views']?></td>
            <td style="padding: 15px; text-align: center;">
                <a href="#" style="color: #2980b9; margin-right: 10px;">Sửa</a>
                <a href="#" onclick="return confirm('Xóa sản phẩm này?')" style="color: #c0392b;">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>