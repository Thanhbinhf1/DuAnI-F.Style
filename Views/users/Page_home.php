<div class="container">
    <h2>Sản Phẩm Mới Nhất</h2>
    
    <div class="product-list">
        <?php 
        if(isset($dsSanPham) && count($dsSanPham) > 0) {
            foreach ($dsSanPham as $sp) {
                $link = "?ctrl=product&act=detail&id=" . $sp['id']; 
                $img = !empty($sp['image']) ? $sp['image'] : 'https://via.placeholder.com/200';
        ?>
            <div class="product-item">
                <a href="<?=$link?>">
                    <img src="<?=$img?>" alt="<?=$sp['name']?>">
                </a>
                <h3><a href="<?=$link?>"><?=$sp['name']?></a></h3>
                <p><?=number_format($sp['price'])?> đ</p>
                <a href="<?=$link?>"><button>Xem chi tiết</button></a>
            </div>
        <?php 
            }
        } else {
            echo "<p style='text-align:center; width:100%'>Đang cập nhật sản phẩm...</p>";
        }
        ?>
    </div>
</div>