<?php
// --- XỬ LÝ ẢNH & DỮ LIỆU ---
$mainImg = $sp['image'];
// Kiểm tra link ảnh
if (!str_contains($mainImg, 'http')) {
    $mainImg = "./Public/Uploads/Products/" . $mainImg;
}
$galleryImages = $gallery ?? [];
$avgRating  = $averageRating['avg_rating'] ?? 0;
$totalCmt   = $averageRating['total'] ?? 0;

$hasSale = isset($sp['price_sale']) && $sp['price_sale'] > 0;
$priceShow = $hasSale ? $sp['price_sale'] : $sp['price'];
?>

<style>
/* 1. TỔNG THỂ */
.product-detail-wrapper {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    padding: 30px;
    margin-top: 30px;
}

/* 2. ẢNH SẢN PHẨM */
.main-image-box {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #f0f0f0;
    margin-bottom: 15px;
}

.thumb-list {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 5px;
}

.thumb-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    object-fit: cover;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.2s;
    opacity: 0.6;
}

.thumb-image:hover {
    opacity: 1;
}

.thumb-image.active {
    border-color: #ff5722;
    opacity: 1;
    transform: translateY(-2px);
}

/* 3. THÔNG TIN SẢN PHẨM */
.product-title {
    font-size: 26px;
    font-weight: 700;
    color: #333;
    line-height: 1.3;
}

.product-meta {
    font-size: 14px;
    color: #666;
    margin: 10px 0;
    padding-bottom: 15px;
    border-bottom: 1px dashed #eee;
}

.price-box {
    background: #fafafa;
    padding: 15px;
    border-radius: 8px;
    margin: 15px 0;
}

.current-price {
    font-size: 28px;
    font-weight: bold;
    color: #ff5722;
}

.old-price {
    text-decoration: line-through;
    color: #aaa;
    margin-left: 10px;
    font-size: 16px;
}

/* 4. NÚT BIẾN THỂ (GRID LAYOUT) */
.variant-group {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 10px;
    margin-top: 8px;
}

.variant-btn {
    border: 1px solid #ddd;
    background: #fff;
    padding: 0 5px;
    text-align: center;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    font-weight: 500;
    font-size: 14px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
}

.variant-btn:hover:not(:disabled) {
    border-color: #333;
    background: #f9f9f9;
}

.variant-btn.active {
    border-color: #ff5722;
    background: #fff4f0;
    color: #ff5722;
    font-weight: bold;
    box-shadow: 0 0 0 1px #ff5722 inset;
}

.variant-btn:disabled {
    background: #f2f2f2;
    color: #bbb;
    cursor: not-allowed;
    border-color: #eee;
    text-decoration: line-through;
}

/* 5. NÚT HÀNH ĐỘNG - PHIÊN BẢN CHUẨN (ĐÃ XÓA MARGIN THỪA) */
.action-group {
    display: flex !important;
    /* Xếp ngang */
    gap: 15px;
    /* Khoảng cách giữa 2 nút */
    margin-top: 25px;
    /* Cách phần trên 25px */
    width: 100%;
    padding: 0 !important;
    /* Xóa padding của khung nếu có */
}

.btn-action {
    flex: 1 !important;
    /* Chia đều 50-50 */
    height: 55px;

    /* QUAN TRỌNG: XÓA MARGIN 10 MÀ BẠN NÓI */
    margin: 0 !important;

    display: flex;
    align-items: center;
    justify-content: center;

    border: none;
    border-radius: 8px;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 13px;
    transition: all 0.3s;
    white-space: nowrap;
}

/* Màu sắc nút */
.btn-add-cart {
    background: #333;
    color: white;
}

.btn-add-cart:hover {
    background: #555;
}

.btn-buy-now {
    background: #ff5722;
    color: white;
}

.btn-buy-now:hover {
    background: #ff784e;
}

/* Trạng thái bị khóa */
.btn-action:disabled {
    background: #e0e0e0 !important;
    color: #999 !important;
    cursor: not-allowed;
    opacity: 0.8;
}

/* Icon */
.btn-action i {
    margin-right: 8px;
    font-size: 16px;
}

.btn-add-cart {
    background: #333;
    color: white;
}

.btn-add-cart:hover {
    background: #555;
}

.btn-buy-now {
    background: #ff5722;
    color: white;
}

.btn-buy-now:hover {
    background: #ff784e;
}

.btn-action:disabled {
    background: #e0e0e0 !important;
    color: #999 !important;
    cursor: not-allowed;
    opacity: 0.7;
}

/* Icon trong nút */
.btn-action i {
    margin-right: 8px;
    font-size: 16px;
}

/* 6. INPUT SỐ LƯỢNG */
.qty-input {
    width: 60px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    height: 35px;
    font-weight: bold;
}
</style>

<div class="container mb-5">
    <div class="product-detail-wrapper">
        <div class="row">

            <div class="col-md-5">
                <div class="main-image-box">
                    <img id="main-product-image" src="<?= $mainImg ?>" alt="<?= htmlspecialchars($sp['name']) ?>"
                        style="width: 100%; aspect-ratio: 1/1; object-fit: cover;">
                </div>

                <div class="thumb-list">
                    <img src="<?= $mainImg ?>" class="thumb-image active" data-src="<?= $mainImg ?>">
                    <?php if (!empty($galleryImages)): ?>
                    <?php foreach ($galleryImages as $img):
                            $thumbUrl = str_contains($img['image_url'], 'http') ? $img['image_url'] : "./Public/Uploads/Products/" . $img['image_url'];
                        ?>
                    <img src="<?= $thumbUrl ?>" class="thumb-image" data-src="<?= $thumbUrl ?>">
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-7 ps-md-4">
                <h1 class="product-title"><?= $sp['name'] ?></h1>

                <div class="product-meta">
                    <span>Thương hiệu: <b class="text-dark"><?= $sp['brand'] ?: 'F.Style' ?></b></span>
                    <span class="mx-2">|</span>
                    <span>Mã SP: <b class="text-dark"><?= $sp['sku_code'] ?: 'N/A' ?></b></span>
                    <span class="mx-2">|</span>
                    <span class="text-warning fw-bold">⭐ <?= $avgRating ?>/5</span>
                    <span class="text-muted small">(<?= $totalCmt ?> đánh giá)</span>
                </div>

                <div class="price-box">
                    <span id="display-price" class="current-price"><?= number_format($priceShow) ?> đ</span>
                    <?php if ($hasSale): ?>
                    <span class="old-price"><?= number_format($sp['price']) ?> đ</span>
                    <span class="badge bg-danger ms-2">-<?= round(100 - $sp['price_sale']*100/$sp['price']) ?>%</span>
                    <?php endif; ?>
                </div>

                <form action="?ctrl=cart&act=add" method="post" id="product-form">
                    <input type="hidden" name="id" value="<?= $sp['id'] ?>">
                    <input type="hidden" name="variant_id" id="selected_variant_id" value="">

                    <?php if (!empty($variants)): ?>
                    <div class="mb-3">
                        <label class="fw-bold mb-1 d-block">Màu sắc:</label>
                        <div id="color-options" class="variant-group"></div>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold mb-1 d-block">Kích thước:</label>
                        <div id="size-options" class="variant-group">
                            <small class="text-muted fst-italic py-2">Vui lòng chọn màu trước...</small>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <label class="fw-bold m-0">Số lượng:</label>
                        <div class="input-group" style="width: 120px;">
                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                onclick="this.parentNode.querySelector('input[type=number]').stepDown()">-</button>
                            <input type="number" name="quantity" class="form-control text-center p-1" value="1" min="1">
                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                onclick="this.parentNode.querySelector('input[type=number]').stepUp()">+</button>
                        </div>
                        <span id="stock-info" class="text-danger small fw-bold"></span>
                    </div>

                    <div class="action-group">
                        <button type="submit" id="btn-add-cart" class="btn-action btn-add-cart" disabled>
                            <i class="fas fa-shopping-cart"></i> <span>Thêm vào giỏ</span>
                        </button>

                        <button type="submit" id="btn-buy-now" formaction="?ctrl=cart&act=buyNow"
                            class="btn-action btn-buy-now" disabled>
                            <i class="fas fa-bolt"></i> <span>Mua ngay</span>
                        </button>
                    </div>
                </form>

                <div class="mt-4 pt-3 border-top">
                    <h6 class="fw-bold">Thông tin chi tiết</h6>
                    <table class="table table-sm table-borderless text-muted small" style="max-width: 400px;">
                        <tr>
                            <td width="120">Danh mục:</td>
                            <td><?= isset($sp['category_name']) ? $sp['category_name'] : 'Chưa cập nhật' ?></td>
                        </tr>
                        <tr>
                            <td>Chất liệu:</td>
                            <td><?= $sp['material'] ?: 'Đang cập nhật' ?></td>
                        </tr>
                        <tr>
                            <td>Xuất xứ:</td>
                            <td>Việt Nam</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="bg-white p-4 rounded shadow-sm border">
                <h4 class="fw-bold border-bottom pb-2 mb-3">Mô tả sản phẩm</h4>
                <div class="text-break" style="line-height: 1.6;">
                    <?= nl2br(htmlspecialchars($sp['description'])) ?>
                </div>
            </div>

            <div class="bg-white p-4 rounded shadow-sm border mt-4">
                <h4 class="fw-bold border-bottom pb-2 mb-3">Đánh giá khách hàng</h4>

                <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $c): ?>
                <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                    <div class="flex-shrink-0">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center fw-bold text-secondary"
                            style="width: 40px; height: 40px;">
                            <?= substr($c['fullname'] ?: $c['username'], 0, 1) ?>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <strong class="text-dark"><?= htmlspecialchars($c['fullname'] ?: $c['username']) ?></strong>
                            <span class="text-warning small"><?= str_repeat('★', $c['rating']) ?></span>
                        </div>
                        <small class="text-muted d-block mb-1"><?= date('d/m/Y H:i', strtotime($c['date'])) ?></small>

                        <p class="mb-1 text-secondary"><?= nl2br(htmlspecialchars($c['content'])) ?></p>

                        <?php if (!empty($c['image'])): 
                                $cmtImg = "./Public/Uploads/Comments/" . $c['image'];
                            ?>
                        <img src="<?= $cmtImg ?>" class="rounded border mt-1"
                            style="max-width: 100px; max-height: 100px; object-fit: cover; cursor: pointer;"
                            onclick="window.open(this.src)">
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <p class="text-muted text-center py-3">Chưa có đánh giá nào.</p>
                <?php endif; ?>

                <?php if (isset($_SESSION['user'])): ?>
                <form action="?ctrl=product&act=detail&id=<?= $sp['id'] ?>" method="post" enctype="multipart/form-data"
                    class="mt-3 bg-light p-3 rounded">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                    <h6 class="fw-bold mb-2">Viết đánh giá của bạn</h6>

                    <div class="d-flex gap-3 mb-2 align-items-center">
                        <select name="rating" class="form-select form-select-sm w-auto">
                            <option value="5">5 Sao (Tuyệt vời)</option>
                            <option value="4">4 Sao (Tốt)</option>
                            <option value="3">3 Sao (Bình thường)</option>
                            <option value="2">2 Sao (Tệ)</option>
                            <option value="1">1 Sao (Rất tệ)</option>
                        </select>

                        <div class="file-upload-wrapper">
                            <label for="cmt_img_input" class="btn btn-outline-secondary btn-sm"
                                style="cursor: pointer;">
                                <i class="fas fa-camera"></i> Thêm ảnh
                            </label>
                            <input type="file" name="comment_image" id="cmt_img_input" accept="image/*"
                                style="display: none;" onchange="previewImage(this)">
                            <span id="file-name-display" class="ms-2 small text-muted"></span>
                        </div>
                    </div>

                    <textarea name="comment_content" rows="2" class="form-control mb-2"
                        placeholder="Chia sẻ cảm nhận về sản phẩm..."></textarea>

                    <div id="image-preview-container" class="mb-2" style="display: none;">
                        <img id="img-preview" src=""
                            style="max-height: 80px; border-radius: 5px; border: 1px solid #ddd;">
                        <button type="button" class="btn-close ms-2" style="vertical-align: top;"
                            onclick="removeImage()"></button>
                    </div>

                    <button type="submit" class="btn btn-dark btn-sm px-4">Gửi đánh giá</button>
                </form>

                <script>
                // Script xem trước ảnh khi chọn
                function previewImage(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('img-preview').src = e.target.result;
                            document.getElementById('image-preview-container').style.display = 'block';
                            document.getElementById('file-name-display').innerText = input.files[0].name;
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                function removeImage() {
                    document.getElementById('cmt_img_input').value = "";
                    document.getElementById('image-preview-container').style.display = 'none';
                    document.getElementById('file-name-display').innerText = "";
                }
                </script>

                <?php else: ?>
                <div class="alert alert-warning py-2 small mt-3">
                    Vui lòng <a href="?ctrl=user&act=login" class="alert-link">đăng nhập</a> để gửi đánh giá kèm hình
                    ảnh.
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="bg-white p-3 rounded shadow-sm border">
                <h5 class="fw-bold mb-3">Sản phẩm liên quan</h5>
                <?php if (!empty($spLienQuan)): ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($spLienQuan as $spc): 
                        $img = str_contains($spc['image'], 'http') ? $spc['image'] : "./Public/Uploads/Products/" . $spc['image'];
                    ?>
                    <div class="d-flex gap-3 align-items-center">
                        <a href="?ctrl=product&act=detail&id=<?= $spc['id'] ?>">
                            <img src="<?= $img ?>" class="rounded border"
                                style="width: 70px; height: 70px; object-fit: cover;">
                        </a>
                        <div>
                            <a href="?ctrl=product&act=detail&id=<?= $spc['id'] ?>"
                                class="text-decoration-none text-dark fw-bold two-lines">
                                <?= $spc['name'] ?>
                            </a>
                            <div class="text-danger fw-bold small mt-1"><?= number_format($spc['price']) ?> đ</div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. CHUYỂN ẢNH GALLERY
        const mainImg = document.getElementById('main-product-image');
        document.querySelectorAll('.thumb-image').forEach(img => {
            img.addEventListener('click', function() {
                mainImg.src = this.dataset.src;
                document.querySelectorAll('.thumb-image').forEach(i => i.classList.remove(
                    'active'));
                this.classList.add('active');
            });
        });

        // 2. XỬ LÝ BIẾN THỂ (SIZE/MÀU)
        const variants = <?= json_encode($variants ?? [], JSON_UNESCAPED_UNICODE) ?>;

        if (Array.isArray(variants) && variants.length > 0) {
            const colorContainer = document.getElementById('color-options');
            const sizeContainer = document.getElementById('size-options');
            const priceDisplay = document.getElementById('display-price');
            const stockDisplay = document.getElementById('stock-info');
            const variantInput = document.getElementById('selected_variant_id');
            const btnAdd = document.getElementById('btn-add-cart');
            const btnBuy = document.getElementById('btn-buy-now');

            const uniqueColors = [...new Set(variants.map(v => v.color))];

            uniqueColors.forEach(color => {
                const btn = document.createElement('div');
                btn.innerText = color;
                btn.className = 'variant-btn';
                btn.onclick = function() {
                    Array.from(colorContainer.children).forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    renderSizes(color);
                };
                colorContainer.appendChild(btn);
            });

            function renderSizes(color) {
                sizeContainer.innerHTML = '';
                const available = variants.filter(v => v.color === color);

                available.forEach(v => {
                    const btn = document.createElement('div');
                    btn.innerText = v.size;
                    btn.className = 'variant-btn';

                    if (parseInt(v.quantity) <= 0) {
                        btn.classList.add('disabled');
                        btn.style.pointerEvents = 'none';
                        btn.innerText += ' (Hết)';
                    } else {
                        btn.onclick = function() {
                            Array.from(sizeContainer.children).forEach(b => b.classList.remove(
                                'active'));
                            this.classList.add('active');
                            selectVariant(v);
                        };
                    }
                    sizeContainer.appendChild(btn);
                });

                resetButtons();
            }

            function selectVariant(v) {
                const price = parseInt(v.price) || <?= (int)$priceShow ?>;
                priceDisplay.innerText = new Intl.NumberFormat('vi-VN').format(price) + ' đ';
                stockDisplay.innerText = `(Còn ${v.quantity} sp)`;
                variantInput.value = v.id;

                // Mở nút mua & Thêm icon (KHÔNG thêm style inline để tránh lệch)
                btnAdd.disabled = false;
                btnAdd.innerHTML = '<i class="fas fa-shopping-cart"></i> <span>Thêm vào giỏ</span>';
                btnBuy.disabled = false;
            }

            function resetButtons() {
                variantInput.value = '';
                stockDisplay.innerText = '';

                btnAdd.disabled = true;
                btnAdd.innerHTML = '<i class="fas fa-hand-pointer"></i> <span>Chọn phân loại</span>';
                btnBuy.disabled = true;
            }

            resetButtons(); // Khóa nút khi mới vào
        }

        // Validate trước khi submit
        document.getElementById('product-form').addEventListener('submit', function(e) {
            if (variants && variants.length > 0 && !document.getElementById('selected_variant_id')
                .value) {
                e.preventDefault();
                alert('Vui lòng chọn Màu sắc và Kích thước!');
            }
        });
    });
    </script>