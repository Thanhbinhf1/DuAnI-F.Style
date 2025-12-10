<style>
/* CSS CHO SLIDESHOW */
.banner-container {
    position: relative;
    width: 100%;
    height: 500px;
    /* Chiều cao banner */
    overflow: hidden;
    background: #000;
}

.banner-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    /* Hiệu ứng mờ dần */
    display: flex;
    align-items: center;
    justify-content: center;
    visibility: hidden;
    /* Ẩn hẳn để không che các phần tử khác */
}

.banner-slide.active {
    opacity: 1;
    z-index: 1;
    visibility: visible;
}

.banner-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.7);
    /* Làm tối ảnh chút để chữ nổi */
}

.banner-content {
    position: absolute;
    z-index: 2;
    text-align: center;
    color: white;
    text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
    padding: 0 20px;
}

.banner-content h1 {
    font-size: 3em;
    margin-bottom: 15px;
    font-weight: bold;
    text-transform: uppercase;
}

.banner-content p {
    font-size: 1.2em;
    margin-bottom: 20px;
}

.btn-banner {
    display: inline-block;
    padding: 12px 35px;
    background: #ff5722;
    color: white;
    text-decoration: none;
    font-weight: bold;
    border-radius: 30px;
    text-transform: uppercase;
    transition: all 0.3s;
    border: 2px solid #ff5722;
}

.btn-banner:hover {
    background: transparent;
    color: #ff5722;
    transform: scale(1.05);
}

/* Chấm tròn điều hướng */
.banner-dots {
    position: absolute;
    bottom: 25px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 3;
    display: flex;
    gap: 12px;
}

.dot {
    width: 12px;
    height: 12px;
    background: rgba(255, 255, 255, 0.4);
    border-radius: 50%;
    cursor: pointer;
    transition: 0.3s;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.dot.active {
    background: #ff5722;
    transform: scale(1.3);
    border-color: #ff5722;
}
</style>

<div class="banner-container" id="homeBanner">
    <?php if (!empty($banners)): ?>
    <?php foreach ($banners as $i => $b): ?>
    <div class="banner-slide <?= $i===0 ? 'active' : '' ?>">
        <img src="<?= $b['image'] ?>" alt="<?= htmlspecialchars($b['title']) ?>"
            onerror="this.src='./Public/Img/banner.jpg'">
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
        <div class="dot <?= $i===0 ? 'active' : '' ?>" onclick="setSlide(<?= $i ?>)"></div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="banner-slide active">
        <img src="./Public/Img/banner.jpg" alt="Default Banner">
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
                    $img = !empty($sp['image']) ? $sp['image'] : 'https://via.placeholder.com/200';
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
                        $img = !empty($sp['image']) ? $sp['image'] : 'https://via.placeholder.com/200';
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
                    $img = !empty($sp['image']) ? $sp['image'] : 'https://via.placeholder.com/200';
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
document.addEventListener('DOMContentLoaded', function() {
    let slideIndex = 0;
    const slides = document.querySelectorAll('.banner-slide');
    const dots = document.querySelectorAll('.dot');
    let timer;

    // Chỉ chạy nếu có từ 2 banner trở lên
    if (slides.length < 2) {
        if (slides.length === 1) slides[0].classList.add('active'); // Hiện cái duy nhất nếu có
        return;
    }

    function showSlide(n) {
        // Xử lý vòng lặp index
        if (n >= slides.length) slideIndex = 0;
        else if (n < 0) slideIndex = slides.length - 1;
        else slideIndex = n;

        // Reset
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));

        // Active mới
        slides[slideIndex].classList.add('active');
        if (dots[slideIndex]) dots[slideIndex].classList.add('active');
    }

    function nextSlide() {
        showSlide(slideIndex + 1);
    }

    // Tự động chạy 3s
    timer = setInterval(nextSlide, 30000);

    // Gắn sự kiện click cho nút Dot (Thay vì dùng onlick inline)
    window.setSlide = function(n) {
        clearInterval(timer);
        showSlide(n);
        timer = setInterval(nextSlide, 3000);
    }
});
</script>