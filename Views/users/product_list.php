<?php
// Các biến được truyền từ ProductController::list()
$cat        = isset($cat) ? $cat : (isset($_GET['cat']) ? (int)$_GET['cat'] : 0);
$type       = isset($type) ? $type : (isset($_GET['type']) ? $_GET['type'] : null);
$keyword    = isset($keyword) ? $keyword : (isset($_GET['keyword']) ? $_GET['keyword'] : '');
$page       = isset($page) ? $page : (isset($_GET['page']) ? (int)$_GET['page'] : 1);
$totalPages = isset($totalPages) ? $totalPages : 1;
?>
<div class="container" style="margin-top: 30px; margin-bottom: 50px;">
    
    <div class="category-title-bar">
        <span class="main-title"><?=$titleMain?></span>
        <span class="divider">/</span>
        <span class="sub-title"><?=$titleSub?></span>
    </div>

    <!-- Thanh filter theo loại sản phẩm -->
    <div class="product-filter-bar">
        <a href="?ctrl=product&act=list"
           class="filter-pill <?=($cat == 0 && !$type && $keyword == '') ? 'active' : ''?>">
            Tất cả
        </a>
        <a href="?ctrl=product&act=list&cat=1"
           class="filter-pill <?=($cat == 1) ? 'active' : ''?>">
            Đồ Nam
        </a>
        <a href="?ctrl=product&act=list&cat=2"
           class="filter-pill <?=($cat == 2) ? 'active' : ''?>">
            Đồ Nữ
        </a>
        <a href="?ctrl=product&act=list&cat=3"
           class="filter-pill <?=($cat == 3) ? 'active' : ''?>">
            Quần Jean
        </a>
        <a href="?ctrl=product&act=list&cat=4"
           class="filter-pill <?=($cat == 4) ? 'active' : ''?>">
            Phụ kiện
        </a>
        <a href="?ctrl=product&act=list&type=new"
           class="filter-pill <?=($type === 'new') ? 'active' : ''?>">
            Hàng mới về
        </a>
        <a href="?ctrl=product&act=list&type=hot"
           class="filter-pill <?=($type === 'hot') ? 'active' : ''?>">
            Sản phẩm hot
        </a>
        <a href="?ctrl=product&act=list&type=sale"
           class="filter-pill <?=($type === 'sale') ? 'active' : ''?>">
            Giá tốt
        </a>
    </div>

    <div class="product-list">

        <?php 
        if(isset($products) && is_array($products) && count($products) > 0) {
            foreach ($products as $sp): 
                $link = "?ctrl=product&act=detail&id=" . $sp['id'];
                $img  = !empty($sp['image']) ? $sp['image'] : 'https://via.placeholder.com/300';

                // NEW = tạo trong 30 ngày gần đây
                $isNew = !empty($sp['created_at']) && (strtotime($sp['created_at']) >= strtotime('-30 days'));
                // HOT = views >= 20
                $isHot = isset($sp['views']) && $sp['views'] >= 20;
        ?>
            <div class="product-item">
                <div class="thumb-wrapper">
                    <a href="<?=$link?>">
                        <img src="<?=$img?>" alt="<?=$sp['name']?>">
                    </a>
                    <?php if ($isNew): ?>
                        <span class="badge badge-new">NEW</span>
                    <?php endif; ?>
                    <?php if ($isHot): ?>
                        <span class="badge badge-hot">HOT</span>
                    <?php endif; ?>
                </div>
                <div class="product-info">
                    <h3><a href="<?=$link?>"><?=$sp['name']?></a></h3>
                    <p class="price"><?=number_format($sp['price'])?> đ</p>
                    <div class="product-buttons">
                        <a href="<?=$link?>" class="btn-view-detail">Xem chi tiết</a>
                        <button 
                            type="button" 
                            class="btn-favorite <?= isset($sp['is_favorited']) && $sp['is_favorited'] ? 'is-favorited' : '' ?>" 
                            data-product-id="<?=$sp['id']?>"
                            title="Yêu thích sản phẩm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                                <path d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        <?php 
            endforeach; 
        } else { 
            echo "<p style='grid-column: 1/-1; text-align: center; padding: 30px 0; color:#777; background: #f7f7f7; border-radius:8px;'>Không tìm thấy sản phẩm nào trong mục này.</p>"; 
        }
        ?>
    </div>

    <!-- Phân trang -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination-bar">
            <?php
            $baseParams = $_GET;
            unset($baseParams['page']);
            for ($i = 1; $i <= $totalPages; $i++):
                $baseParams['page'] = $i;
                $url = 'index.php?' . http_build_query($baseParams);
            ?>
                <a href="<?=$url?>" class="page-link <?=$i == $page ? 'active' : ''?>"><?=$i?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
