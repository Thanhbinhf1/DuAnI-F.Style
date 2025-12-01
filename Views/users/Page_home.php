<div class="banner">
    <img src="https://dps.media/wp-content/uploads/2025/07/banner-5.png" alt="Banner Thời Trang">

    <img src="./Public/Img/banner.jpg" alt="Banner Thời Trang">

    <div class="banner-content">
        <h1>THỜI TRANG & PHONG CÁCH</h1>
        <p>Bộ sưu tập mùa hè mới nhất 2025</p>
        <a href="?ctrl=product&act=list" class="btn-banner">Mua Ngay</a>
    </div>
</div>

<div class="container">

    <section class="section-product">
        <div class="section-header">
            <h2>SẢN PHẨM HOT </h2>
            <a href="?ctrl=product&act=list&type=hot">Xem tất cả &rarr;</a>
        </div>
        <div class="product-list">
            <?php 
            if(isset($spHot) && count($spHot) > 0) {
                foreach ($spHot as $sp): 
                    $link = "?ctrl=product&act=detail&id=" . $sp['id'];
                    $img = !empty($sp['image']) ? $sp['image'] : 'https://via.placeholder.com/200';
            ?>
            <div class="product-item">
                <a href="<?=$link?>"><img src="<?=$img?>" alt="<?=$sp['name']?>"></a>
                <h3><a href="<?=$link?>"><?=$sp['name']?></a></h3>
                <p><?=number_format($sp['price'])?> đ</p>
                <a href="<?=$link?>"><button>Xem chi tiết</button></a>
            </div>
            <?php endforeach; 
            } else { echo "<p>Đang cập nhật...</p>"; }
            ?>
        </div>
    </section>

    <section class="section-product">
        <div class="section-header">
            <h2>HÀNG MỚI VỀ </h2>
            <a href="?ctrl=product&act=list&type=new">Xem tất cả &rarr;</a>
        </div>

        <div class="new-arrival-layout" style="display: flex; gap: 20px;">
            <div class="big-poster" style="width: 40%;">
                <img src="https://img.freepik.com/free-photo/portrait-handsome-smiling-stylish-young-man-model-dressed-red-checkered-shirt-fashion-man-posing_158538-4909.jpg"
                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
            </div>
            <div class="product-grid-right"
                style="width: 60%; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <?php foreach ($spMoi as $sp): 
                    $link = "?ctrl=product&act=detail&id=" . $sp['id'];
                    $img = !empty($sp['image']) ? $sp['image'] : 'https://via.placeholder.com/200';

            
            <div class="product-grid-right" style="width: 60%; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <?php 
                if(isset($spMoi) && count($spMoi) > 0) {
                    foreach ($spMoi as $sp): 
                        $link = "?ctrl=product&act=detail&id=" . $sp['id'];
                        $img = !empty($sp['image']) ? $sp['image'] : 'https://via.placeholder.com/200';
                ?>
                <div class="product-item">
                    <a href="<?=$link?>"><img src="<?=$img?>" alt="<?=$sp['name']?>"></a>
                    <h3><a href="<?=$link?>"><?=$sp['name']?></a></h3>
                    <p><?=number_format($sp['price'])?> đ</p>
                </div>
                <?php endforeach; 
                } else { echo "<p>Chưa có sản phẩm mới.</p>"; }
                ?>
            </div>
        </div>
    </section>

    <section class="section-product">
        <div class="section-header">
            <h2>SẢN PHẨM GIÁ TỐT </h2>
            <a href="?ctrl=product&act=list&type=sale">Xem tất cả &rarr;</a>
        </div>
        <div class="product-list">
            <?php 
            if(isset($spGiaTot) && count($spGiaTot) > 0) {
                foreach ($spGiaTot as $sp): 
                    $link = "?ctrl=product&act=detail&id=" . $sp['id'];
                    $img = !empty($sp['image']) ? $sp['image'] : 'https://via.placeholder.com/200';
            ?>
            <div class="product-item">
                <a href="<?=$link?>"><img src="<?=$img?>" alt="<?=$sp['name']?>"></a>
                <h3><a href="<?=$link?>"><?=$sp['name']?></a></h3>
                <p><?=number_format($sp['price'])?> đ</p>
                <a href="<?=$link?>"><button>Xem chi tiết</button></a>
            </div>
            <?php endforeach; 
            } else { echo "<p>Đang cập nhật...</p>"; }
            ?>
        </div>
    </section>

    <section class="section-news" style="margin-bottom: 50px;">
        <h2>TIN TỨC THỜI TRANG 📰</h2>
        <div class="news-list" style="display: flex; gap: 20px;">
            <div class="news-item" style="flex: 1;">
                <img src="https://img.freepik.com/free-photo/two-young-beautiful-blond-smiling-hipster-women-trendy-summer-clothes_158538-2.jpg"
                    style="width: 100%; border-radius: 8px;">
                <h3 style="margin: 10px 0; font-size: 18px;">Cách phối đồ mùa hè năng động</h3>
                <p style="color: #666; font-size: 14px;">Mùa hè này mặc gì cho mát mẻ mà vẫn xinh? Cùng xem ngay...</p>
            </div>
            <div class="news-item" style="flex: 1;">
                <img src="https://img.freepik.com/free-photo/full-length-portrait-happy-excited-girl-bright-colorful-clothes-holding-shopping-bags-while-standing-showing-peace-gesture-isolated_231208-5946.jpg"
                    style="width: 100%; border-radius: 8px;">
                <h3 style="margin: 10px 0; font-size: 18px;">Xu hướng thời trang Gen Z năm 2025</h3>
                <p style="color: #666; font-size: 14px;">Những items không thể thiếu trong tủ đồ của giới trẻ năm nay...
                </p>
            </div>
            <div class="news-item" style="flex: 1;">
                <img src="https://img.freepik.com/free-photo/fashion-portrait-young-businessman-handsome-model-man-casual-cloth-suit-sunglasses-hands-pockets_158538-12.jpg"
                    style="width: 100%; border-radius: 8px;">
                <h3 style="margin: 10px 0; font-size: 18px;">Đàn ông mặc gì để lịch lãm?</h3>
                <p style="color: #666; font-size: 14px;">Gợi ý 5 set đồ công sở vừa lịch sự vừa thoải mái...</p>
            </div>
        </div>
    </section>
</div>