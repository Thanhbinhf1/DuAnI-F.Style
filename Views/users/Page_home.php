<div class="banner-wrapper" id="homeBanner">
    <?php if (!empty($banners)): ?>
    <?php foreach ($banners as $i => $b): ?>
    <div class="banner-slide <?= $i === 0 ? 'active' : '' ?>">
    </div>
    <?php endforeach; ?>
    <?php foreach ($banners as $i => $b): ?>
    <?php $imgSrc = !empty($b['image']) ? $b['image'] : './Public/Img/banner.jpg'; ?>

    <div class="banner-slide <?= $i === 0 ? 'active' : '' ?>">
        <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($b['title']) ?>"
            onerror="this.onerror=null; this.src='./Public/Img/banner.jpg';">

        <div class="banner-content">
            <h1><?= htmlspecialchars($b['title']) ?></h1>
            <?php if (!empty($b['link'])): ?>
            <a href="<?= $b['link'] ?>" class="btn-banner">Xem Ngay</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <div class="banner-dots">
        <?php foreach ($banners as $i => $b): ?>
        <div class="dot <?= $i === 0 ? 'active' : '' ?>" onclick="manualSlide(<?= $i ?>)"></div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div class="banner-slide active">
        <img src="./Public/Img/banner.jpg" alt="F.Style Banner"
            onerror="this.src='https://via.placeholder.com/1200x500/000000/FFFFFF?text=F.Style+Fashion'">
        <div class="banner-content">
            <h1>F.STYLE FASHION</h1>
            <p>Phong cách thời thượng - Dẫn đầu xu hướng</p>
            <a href="?ctrl=product&act=list" class="btn-banner">Mua Sắm Ngay</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="container" style="margin-top: 50px;">
    <section class="section-product">
        <div class="section-header">
            <h2>SẢN PHẨM HOT 🔥</h2>
            <a href="?ctrl=product&act=list&type=hot">Xem tất cả &rarr;</a>
        </div>
        <div class="product-list">
            <?php 
            if(isset($spHot) && count($spHot) > 0) {
                foreach ($spHot as $sp): 
                    $link = "?ctrl=product&act=detail&id=" . $sp['id'];
                    $img = !empty($sp['image']) ? $sp['image'] : 'https://via.placeholder.com/300';
            ?>
            <div class="product-item">
                <div class="thumb-wrapper">
                    <a href="<?=$link?>"><img src="<?=$img?>" alt="<?=$sp['name']?>"></a>
                    <span class="badge badge-hot">HOT</span>
                    <?php if (isset($sp['price_sale']) && $sp['price_sale'] > 0): ?>
                    <span class="badge badge-sale">-<?=round(100 - ($sp['price_sale']/$sp['price']*100))?>%</span>
                    <?php endif; ?>
                </div>
                <div class="product-info">
                    <h3 class="product-name"><a href="<?=$link?>" title="<?=$sp['name']?>"><?=$sp['name']?></a></h3>
                    <div class="product-meta">
                        <div class="stars">
                            <?php 
                            $rating = isset($sp['avg_rating']) ? round($sp['avg_rating']) : 5;
                            for($i=1; $i<=5; $i++) echo '<i class="fa-solid fa-star ' . ($i <= $rating ? 'gold' : 'gray') . '"></i>';
                            ?>
                        </div>
                        <span class="sold-count">Đã bán <?= number_format($sp['sold_count'] ?? 0) ?></span>
                    </div>
                    <div class="price-box">
                        <?php if(isset($sp['price_sale']) && $sp['price_sale'] > 0): ?>
                        <span class="current-price"><?=number_format($sp['price_sale'])?>đ</span>
                        <span class="old-price"><?=number_format($sp['price'])?>đ</span>
                        <?php else: ?>
                        <span class="current-price"><?=number_format($sp['price'])?>đ</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-buttons">
                        <a href="<?=$link?>" class="btn-action btn-view">Xem</a>
                        <button type="button"
                            class="btn-action btn-favorite <?= isset($sp['is_favorited']) && $sp['is_favorited'] ? 'active' : '' ?>"
                            data-product-id="<?=$sp['id']?>" onclick="toggleFavorite(this, <?=$sp['id']?>)">
                            <i class="fa-solid fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; 
            } else { echo "<p class='text-muted'>Đang cập nhật sản phẩm...</p>"; }
            ?>
        </div>
    </section>

    <section class="section-product">
        <div class="section-header">
            <h2>HÀNG MỚI VỀ 🆕</h2>
            <a href="?ctrl=product&act=list&type=new">Xem tất cả &rarr;</a>
        </div>
        <div class="new-arrival-layout" style="display: flex; gap: 20px; flex-wrap: wrap;">
            <div class="big-poster" style="flex: 1; min-width: 300px;">
                <img src="https://img.freepik.com/free-photo/portrait-handsome-smiling-stylish-young-man-model-dressed-red-checkered-shirt-fashion-man-posing_158538-4909.jpg"
                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px; min-height: 400px;">
            </div>
            <div class="product-grid-right"
                style="flex: 1.5; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
                <?php 
                if(isset($spMoi) && count($spMoi) > 0) {
                    foreach ($spMoi as $sp): 
                        $link = "?ctrl=product&act=detail&id=" . $sp['id'];
                        $img = !empty($sp['image']) ? $sp['image'] : 'https://via.placeholder.com/300';
                ?>
                <div class="product-item">
                    <div class="thumb-wrapper">
                        <a href="<?=$link?>"><img src="<?=$img?>" alt="<?=$sp['name']?>"></a>
                        <span class="badge badge-new">NEW</span>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><a href="<?=$link?>"><?=$sp['name']?></a></h3>
                        <div class="price-box">
                            <span class="current-price"><?=number_format($sp['price'])?>đ</span>
                        </div>
                    </div>
                </div>
                <?php endforeach; 
                } else { echo "<p>Chưa có sản phẩm mới.</p>"; }
                ?>
            </div>
        </div>
    </section>

    <section class="section-product">
        <div class="section-header">
            <h2>SẢN PHẨM GIÁ TỐT 🏷️</h2>
            <a href="?ctrl=product&act=list&type=sale">Xem tất cả &rarr;</a>
        </div>
        <div class="product-list">
            <?php 
            if(isset($spGiaTot) && count($spGiaTot) > 0) {
                foreach ($spGiaTot as $sp): 
                    $link = "?ctrl=product&act=detail&id=" . $sp['id'];
                    $img = !empty($sp['image']) ? $sp['image'] : 'https://via.placeholder.com/300';
            ?>
            <div class="product-item">
                <div class="thumb-wrapper">
                    <a href="<?=$link?>"><img src="<?=$img?>" alt="<?=$sp['name']?>"></a>
                    <?php if (isset($sp['price_sale']) && $sp['price_sale'] > 0): ?>
                    <span class="badge badge-sale">-<?=round(100 - ($sp['price_sale']/$sp['price']*100))?>%</span>
                    <?php endif; ?>
                </div>
                <div class="product-info">
                    <h3 class="product-name"><a href="<?=$link?>" title="<?=$sp['name']?>"><?=$sp['name']?></a></h3>
                    <div class="price-box">
                        <?php if(isset($sp['price_sale']) && $sp['price_sale'] > 0): ?>
                        <span class="current-price"><?=number_format($sp['price_sale'])?>đ</span>
                        <span class="old-price"><?=number_format($sp['price'])?>đ</span>
                        <?php else: ?>
                        <span class="current-price"><?=number_format($sp['price'])?>đ</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; 
            } else { echo "<p>Đang cập nhật...</p>"; }
            ?>
        </div>
    </section>

    <section class="section-news" style="margin-bottom: 50px;">
        <h2>TIN TỨC THỜI TRANG 📰</h2>
        <div class="news-list"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <div class="news-item">
                <img src="https://img.freepik.com/free-photo/two-young-beautiful-blond-smiling-hipster-women-trendy-summer-clothes_158538-2.jpg"
                    style="width: 100%; border-radius: 8px;">
                <h3 style="margin: 10px 0; font-size: 18px;">Cách phối đồ mùa hè năng động</h3>
                <p style="color: #666; font-size: 14px;">Mùa hè này mặc gì cho mát mẻ mà vẫn xinh? Cùng xem ngay...</p>
            </div>
            <div class="news-item">
                <img src="https://img.freepik.com/free-photo/full-length-portrait-happy-excited-girl-bright-colorful-clothes-holding-shopping-bags-while-standing-showing-peace-gesture-isolated_231208-5946.jpg"
                    style="width: 100%; border-radius: 8px;">
                <h3 style="margin: 10px 0; font-size: 18px;">Xu hướng thời trang Gen Z năm 2025</h3>
                <p style="color: #666; font-size: 14px;">Những items không thể thiếu trong tủ đồ của giới trẻ năm nay...
                </p>
            </div>
            <div class="news-item">
                <img src="https://img.freepik.com/free-photo/fashion-portrait-young-businessman-handsome-model-man-casual-cloth-suit-sunglasses-hands-pockets_158538-12.jpg"
                    style="width: 100%; border-radius: 8px;">
                <h3 style="margin: 10px 0; font-size: 18px;">Đàn ông mặc gì để lịch lãm?</h3>
                <p style="color: #666; font-size: 14px;">Gợi ý 5 set đồ công sở vừa lịch sự vừa thoải mái...</p>
            </div>
        </div>
    </section>
</div>

<script>
// Logic Javascript cho Banner Slide
document.addEventListener('DOMContentLoaded', function() {
    let currentSlide = 0;
    const slides = document.querySelectorAll('.banner-slide');
    const dots = document.querySelectorAll('.dot');
    let slideInterval;

    // Hàm chuyển slide
    function goToSlide(n) {
        if (slides.length === 0) return;

        // Xóa class active cũ
        slides[currentSlide].classList.remove('active');
        if (dots[currentSlide]) dots[currentSlide].classList.remove('active');

        // Tính toán slide tiếp theo
        currentSlide = (n + slides.length) % slides.length;

        // Thêm class active mới
        slides[currentSlide].classList.add('active');
        if (dots[currentSlide]) dots[currentSlide].classList.add('active');
    }

});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ... các biến khai báo ...

    // Hàm tự động chạy
    function startSlideShow() {
        slideInterval = setInterval(() => {
            // Tự động chuyển sang slide tiếp theo sau 3 giây
            goToSlide(currentSlide + 1);
        }, 3000);
    }

    // QUAN TRỌNG: Nó chỉ bắt đầu chạy nếu tìm thấy CÓ NHIỀU HƠN 1 slide
    if (slides.length > 1) {
        startSlideShow();
    }
});
</script>