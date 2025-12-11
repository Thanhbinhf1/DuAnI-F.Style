<style>
.banner-container {
    position: relative;
    width: 100%;
    height: 500px;
    /* Chiều cao banner */
    overflow: hidden;
    background: #000;
    margin-bottom: 30px;
}

.banner-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    display: flex;
    align-items: center;
    justify-content: center;
    visibility: hidden;
}

.banner-slide.active {
    opacity: 1;
    visibility: visible;
    z-index: 1;
}

.banner-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.8);
}

.banner-content {
    position: absolute;
    z-index: 2;
    text-align: center;
    color: white;
    text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
}

.banner-content h1 {
    font-size: 3em;
    margin-bottom: 10px;
    text-transform: uppercase;
    font-weight: bold;
}

.btn-banner {
    padding: 10px 30px;
    background: #ff5722;
    color: white;
    text-decoration: none;
    font-weight: bold;
    border-radius: 30px;
    display: inline-block;
    margin-top: 15px;
}

.banner-dots {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 3;
    display: flex;
    gap: 10px;
}

.dot {
    width: 12px;
    height: 12px;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 50%;
    cursor: pointer;
}

.dot.active {
    background: #ff5722;
    transform: scale(1.2);
}

/* Responsive cho mobile */
@media (max-width: 768px) {
    .banner-container {
        height: 300px;
    }

    .banner-content h1 {
        font-size: 1.8em;
    }

    .btn-banner {
        padding: 8px 20px;
        font-size: 13px;
    }
}
</style>

<?php
// Xử lý dữ liệu: Đảm bảo luôn có ít nhất 2 banner để chạy slide
$displayBanners = isset($banners) ? $banners : [];

if (count($displayBanners) < 2) {
    // Nếu chưa có banner nào, tạo banner mẫu 1
    if (empty($displayBanners)) {
        $displayBanners[] = [
            'image' => './Public/Img/banner.jpg',
            'title' => 'F.STYLE FASHION',
            'link'  => '?ctrl=product&act=list'
        ];
    }
    // Tạo banner mẫu 2 để đủ cặp chạy luân phiên
    $displayBanners[] = [
        'image' => 'https://img.freepik.com/free-photo/fashion-portrait-young-businessman-handsome-model-man-casual-cloth-suit-sunglasses-hands-pockets_158538-12.jpg',
        'title' => 'BỘ SƯU TẬP MỚI 2025',
        'link'  => '?ctrl=product&act=list&type=new'
    ];
}
?>

<div class="banner-container" id="homeBanner">
    <?php foreach ($displayBanners as $i => $b): ?>
    <div class="banner-slide <?= $i===0 ? 'active' : '' ?>">
        <img src="<?= !empty($b['image']) ? $b['image'] : './Public/Img/banner.jpg' ?>"
            alt="<?= htmlspecialchars($b['title']) ?>" onerror="this.src='./Public/Img/banner.jpg'">
        <div class="banner-content">
            <h1><?= htmlspecialchars($b['title']) ?></h1>
            <?php if (!empty($b['link'])): ?>
            <a href="<?= $b['link'] ?>" class="btn-banner">Xem Ngay</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <div class="banner-dots">
        <?php foreach ($displayBanners as $i => $b): ?>
        <div class="dot <?= $i===0 ? 'active' : '' ?>" onclick="manualSwitchSlide(<?= $i ?>)"></div>
        <?php endforeach; ?>
    </div>
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
document.addEventListener('DOMContentLoaded', function() {
    let currentSlide = 0;
    const slides = document.querySelectorAll('.banner-slide');
    const dots = document.querySelectorAll('.dot');
    let slideTimer;

    // --- CẤU HÌNH THỜI GIAN: 3000ms = 3 giây ---
    const TIME_PER_SLIDE = 3000;

    function showSlide(n) {
        if (slides.length === 0) return;

        // Xử lý vòng lặp index
        if (n >= slides.length) currentSlide = 0;
        else if (n < 0) currentSlide = slides.length - 1;
        else currentSlide = n;

        // Xóa class active cũ
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));

        // Thêm class active mới
        slides[currentSlide].classList.add('active');
        if (dots[currentSlide]) dots[currentSlide].classList.add('active');
    }

    function startAutoSlide() {
        slideTimer = setInterval(() => {
            showSlide(currentSlide + 1);
        }, TIME_PER_SLIDE);
    }

    // Hàm gọi khi bấm nút chấm tròn
    window.manualSwitchSlide = function(n) {
        clearInterval(slideTimer); // Dừng tự động
        showSlide(n); // Chuyển slide ngay lập tức
        startAutoSlide(); // Bắt đầu đếm lại 3 giây
    };

    // Bắt đầu chạy nếu có nhiều hơn 1 slide
    if (slides.length > 1) {
        startAutoSlide();
    } else if (slides.length === 1) {
        // Nếu chỉ có 1 slide thì hiện nó lên luôn
        slides[0].classList.add('active');
    }
});
</script>