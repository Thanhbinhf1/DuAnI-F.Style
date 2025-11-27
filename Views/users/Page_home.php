<div class="container">
    <h2>Sản Phẩm Mới Nhất</h2>
    <div class="product-list" style="display: flex; flex-wrap: wrap; gap: 20px;">
        <?php 
        // Kiểm tra xem có dữ liệu sản phẩm không
        if(isset($dsSanPham) && count($dsSanPham) > 0) {
            foreach ($dsSanPham as $sp) {
                // Link đến trang chi tiết (cần tạo sau)
                $link = "?ctrl=product&act=detail&id=" . $sp['id']; 
                // Xử lý hình ảnh (nếu chưa có ảnh thì dùng ảnh mẫu)
                $img = !empty($sp['image']) ? $sp['image'] : 'https://via.placeholder.com/200';
        ?>
            <div class="product-item" style="border: 1px solid #ddd; padding: 10px; width: 23%;">
                <a href="<?=$link?>">
                    <img src="<?=$img?>" alt="<?=$sp['name']?>" style="width: 100%; height: auto;">
                </a>
                <h3><a href="<?=$link?>"><?=$sp['name']?></a></h3>
                <p style="color: red; font-weight: bold;"><?=number_format($sp['price'])?> VND</p>
                <button>Thêm vào giỏ</button>
            </div>
        <?php 
            }
        } else {
            echo "<p>Đang cập nhật sản phẩm...</p>";
        }
        ?>
    </div>
</div>