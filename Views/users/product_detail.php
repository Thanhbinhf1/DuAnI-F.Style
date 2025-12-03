<?php
// Xử lý đường dẫn ảnh chính
$mainImg = $sp['image'];
// Giả định ảnh chính được lưu trong Public/Uploads/Products/
if (!str_contains($mainImg, 'http')) {
    $mainImg = "./Public/Uploads/Products/" . $mainImg;
}

// Ảnh gallery (nếu có)
$galleryImages = $gallery ?? [];

// Rating
$avgRating  = $averageRating['avg_rating'] ?? 0;
$totalCmt   = $averageRating['total'] ?? 0;
?>
<div class="container" style="margin-top: 30px; margin-bottom: 50px;">

    <div class="product-detail-container">
        <div class="left-column" style="flex: 0 0 40%;">
            <div class="mb-3">
                <img id="main-product-image"
                     src="<?=$mainImg?>"
                     alt="<?=htmlspecialchars($sp['name'])?>"
                     style="width: 100%; border-radius: 10px; border: 1px solid #eee;">
            </div>

            <?php if (!empty($galleryImages)): ?>
                <div class="d-flex flex-wrap gap-2">
                    <img src="<?=$mainImg?>" 
                         class="thumb-image active"
                         data-src="<?=$mainImg?>"
                         style="width: 70px; height: 70px; object-fit: cover; border-radius: 5px; cursor: pointer; border: 1px solid #eee;">

                    <?php foreach ($galleryImages as $img): 
                        $thumb = $img['image_url'];
                        // Giả định ảnh gallery được lưu trong Public/Uploads/Products/
                        if (!str_contains($thumb, 'http')) {
                            $thumb = "./Public/Uploads/Products/" . $thumb;
                        }
                    ?>
                        <img src="<?=$thumb?>" 
                             class="thumb-image"
                             data-src="<?=$thumb?>"
                             style="width: 70px; height: 70px; object-fit: cover; border-radius: 5px; cursor: pointer; border: 1px solid #eee;">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="right-column" style="flex: 1;">
            <h1><?=$sp['name']?></h1>

            <div style="display: flex; align-items: center; gap: 10px; margin: 8px 0 15px;">
                <span style="font-size: 14px; color: #777;">
                    Thương hiệu: <b><?=$sp['brand'] ?: 'F.Style'?></b> 
                    &nbsp;|&nbsp; Mã SP: <b><?=$sp['sku_code'] ?: 'Chưa cập nhật'?></b>
                </span>
            </div>

            <div style="margin-bottom: 10px;">
                <?php if ($totalCmt > 0): ?>
                    <span style="color: #f59e0b; font-weight: 600;">
                        ⭐ <?=$avgRating?>/5
                    </span>
                    <span style="color: #777; font-size: 14px;">
                        (<?=$totalCmt?> đánh giá)
                    </span>
                <?php else: ?>
                    <span style="color: #777; font-size: 14px;">Chưa có đánh giá</span>
                <?php endif; ?>
            </div>

            <?php
                $hasSale = isset($sp['price_sale']) && $sp['price_sale'] > 0;
                $priceShow = $hasSale ? $sp['price_sale'] : $sp['price'];
            ?>
            <div style="margin: 20px 0;">
                <span id="display-price" class="price">
                    <?=number_format($priceShow)?> đ
                </span>
                <?php if ($hasSale): ?>
                    <span style="text-decoration: line-through; color:#999; margin-left: 10px;">
                        <?=number_format($sp['price'])?> đ
                    </span>
                    <span style="margin-left: 8px; color:#d0011b; font-weight:600;">-<?=round(100 - $sp['price_sale']*100/$sp['price'])?>%</span>
                <?php endif; ?>
            </div>

            <form action="?ctrl=cart&act=add" method="post">
                <input type="hidden" name="id" value="<?=$sp['id']?>">
                <input type="hidden" name="variant_id" id="selected_variant_id" value="">

                <?php if (!empty($variants)): ?>
                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: bold;">Màu sắc:</label>
                        <div id="color-options" class="variant-group"></div>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: bold;">Kích thước:</label>
                        <div id="size-options" class="variant-group">
                            <span style="color: #999; font-style: italic;">(Vui lòng chọn màu trước)</span>
                        </div>
                    </div>
                <?php 
                endif; ?>

                <div style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <label style="margin: 0;">Số lượng:</label>
                    <input type="number" name="quantity" value="1" min="1"
                           style="padding: 8px; width: 70px; border: 1px solid #ddd; border-radius: 4px; text-align: center;">
                    <span id="stock-info" style="color: #666; font-size: 14px;"></span>
                </div>

                <div class="btn-actions">
    <button type="submit"
            id="btn-add-cart"
            class="product-action-btn primary"
            <?= !empty($variants) ? 'disabled' : '' ?>>
        <?= !empty($variants) ? 'Vui lòng chọn phân loại' : 'Thêm vào giỏ hàng' ?>
    </button>

    <button type="submit"
            formaction="?ctrl=cart&act=buyNow"
            class="product-action-btn secondary">
        Mua ngay
    </button>
</div>

            </form>

            <div style="margin-top: 30px;">
                <h5>Thông tin sản phẩm</h5>
                <table class="table table-sm" style="max-width: 500px;">
                    <tr>
                        <td style="width: 160px; color:#777;">Danh mục</td>
                        <td><?=$sp['category_name'] ?? 'Chưa xác định'?></td>
                    </tr>
                    <tr>
                        <td style="color:#777;">Thương hiệu</td>
                        <td><?=$sp['brand'] ?: 'F.Style'?></td>
                    </tr>
                    <tr>
                        <td style="color:#777;">Chất liệu</td>
                        <td><?=$sp['material'] ?: 'Đang cập nhật'?></td>
                    </tr>
                    <tr>
                        <td style="color:#777;">Mã sản phẩm</td>
                        <td><?=$sp['sku_code'] ?: 'Đang cập nhật'?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="description">
        <h4>Mô tả chi tiết</h4>
        <p><?=nl2br(htmlspecialchars($sp['description']))?></p>
    </div>

    <div class="section-product">
        <div class="section-header">
            <h2>Sản phẩm liên quan</h2>
        </div>
        <div class="product-list">
            <?php if (!empty($spLienQuan)): ?>
                <?php foreach ($spLienQuan as $spc): 
                    $img = $spc['image'];
                    if (!str_contains($img, 'http')) {
                        $img = "./Public/Uploads/Products/" . $img;
                    }
                ?>
                    <div class="product-item">
                        <a href="?ctrl=product&act=detail&id=<?=$spc['id']?>">
                            <img src="<?=$img?>" alt="<?=$spc['name']?>">
                            <h3><?=$spc['name']?></h3>
                        </a>
                        <p><?=number_format($spc['price'])?> đ</p>
                        <a href="?ctrl=product&act=detail&id=<?=$spc['id']?>" class="btn-buy-now-hover">
                            Xem chi tiết
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Chưa có sản phẩm liên quan.</p>
            <?php endif; ?>
        </div>
    </div>

    <div style="margin-top: 40px;">
        <h4>Đánh giá & Bình luận</h4>

        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $c): 
                $name = $c['fullname'] ?: $c['username'];
            ?>
                <div style="border-bottom: 1px solid #eee; padding: 10px 0;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <strong><?=htmlspecialchars($name)?></strong>
                        <small style="color:#999;"><?=date('d/m/Y H:i', strtotime($c['date']))?></small>
                    </div>
                    <div style="color:#f59e0b; font-size: 13px; margin: 2px 0 5px;">
                        <?php for ($i=1;$i<=5;$i++): ?>
                            <i class="fa<?= $i <= $c['rating'] ? 's' : 'r' ?> fa-star"></i>
                        <?php endfor; ?>
                    </div>
                    <p style="margin:0;"><?=nl2br(htmlspecialchars($c['content']))?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color:#777;">Chưa có bình luận cho sản phẩm này.</p>
        <?php endif; ?>

        <div style="margin-top: 20px;">
            <?php if (isset($_SESSION['user'])): ?>
                <form action="?ctrl=product&act=detail&id=<?=$sp['id']?>" method="post">
                    <input type="hidden" name="csrf_token"
                           value="<?=htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8')?>">

                    <div class="mb-2">
                        <label style="font-weight:600; font-size:14px;">Chấm sao:</label>
                        <select name="rating" class="form-select form-select-sm" style="max-width: 150px;">
                            <?php for ($i=5;$i>=1;$i--): ?>
                                <option value="<?=$i?>"><?=$i?> sao</option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label style="font-weight:600; font-size:14px;">Nội dung bình luận:</label>
                        <textarea name="comment_content" rows="3" class="form-control" required
                                  placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-dark btn-sm">Gửi bình luận</button>
                </form>
            <?php else: ?>
                <p style="color:#777;">
                    Bạn cần <a href="?ctrl=user&act=login">đăng nhập</a> để bình luận.
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Đổi ảnh chính theo thumbnail
    document.querySelectorAll('.thumb-image').forEach(function (img) {
        img.addEventListener('click', function () {
            const main = document.getElementById('main-product-image');
            main.src = this.dataset.src;
        });
    });

    const variants = <?= json_encode($variants ?? [], JSON_UNESCAPED_UNICODE) ?>;

    if (Array.isArray(variants) && variants.length > 0) {
        const colorContainer = document.getElementById('color-options');
        const sizeContainer  = document.getElementById('size-options');
        const priceDisplay   = document.getElementById('display-price');
        const stockDisplay   = document.getElementById('stock-info');
        const variantInput   = document.getElementById('selected_variant_id');
        const btnAdd         = document.getElementById('btn-add-cart');

        const uniqueColors = [...new Set(variants.map(v => v.color))];

        // Tạo nút màu
        uniqueColors.forEach(color => {
            const btn = document.createElement('button');
            btn.type  = 'button';
            btn.innerText = color;
            btn.className = 'variant-btn';

            btn.addEventListener('click', function () {
                Array.from(colorContainer.children).forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                showSizesForColor(color);
            });

            colorContainer.appendChild(btn);
        });

        function showSizesForColor(selectedColor) {
            sizeContainer.innerHTML = "";
            const available = variants.filter(v => v.color === selectedColor);

            available.forEach(variant => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.innerText = variant.size;
                btn.className = 'variant-btn';

                if (parseInt(variant.quantity) <= 0) {
                    btn.disabled = true;
                    btn.innerText += " (Hết hàng)";
                }

                btn.addEventListener('click', function () {
                    Array.from(sizeContainer.children).forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    updateProductInfo(variant);
                });

                sizeContainer.appendChild(btn);
            });

            // Reset
            variantInput.value = "";
            btnAdd.disabled = true;
            btnAdd.style.background = "#ccc";
            btnAdd.style.cursor = "not-allowed";
            btnAdd.innerText = "Vui lòng chọn phân loại";
            stockDisplay.innerText = "";
        }

        function updateProductInfo(variant) {
            const price = parseInt(variant.price) || <?= (int)$priceShow ?>;
            priceDisplay.innerText = new Intl.NumberFormat('vi-VN').format(price) + ' đ';
            stockDisplay.innerText = `(Còn ${variant.quantity} sản phẩm)`;
            variantInput.value = variant.id;

            btnAdd.disabled = false;
            btnAdd.style.background = "#333";
            btnAdd.style.cursor = "pointer";
            btnAdd.innerText = "THÊM VÀO GIỎ HÀNG";
            btnAdd.disabled = false;
// đổi màu basic
            
        }
    }
</script>