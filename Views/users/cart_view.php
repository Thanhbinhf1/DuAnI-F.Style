<div class="container py-5">
    <div class="d-flex align-items-center mb-4">
        <h2 class="fw-bold mb-0 text-uppercase">Giỏ hàng của bạn</h2>
        <span class="badge bg-secondary ms-2 rounded-pill fs-6">
            <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?> sản phẩm
        </span>
    </div>

    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 border-0" width="45%">Sản phẩm</th>
                                    <th class="py-3 border-0 text-center">Đơn giá</th>
                                    <th class="py-3 border-0 text-center">Số lượng</th>
                                    <th class="py-3 border-0 text-center">Thành tiền</th>
                                    <th class="py-3 border-0 text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['cart'] as $key => $item): 
                                    $imgSrc = str_contains($item['image'], 'http') ? $item['image'] : "./public/img/products/" . $item['image'];
                                    $maxStock = $item['stock'];
                                ?>
                                <tr id="row-<?=$key?>" class="border-bottom">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="position-relative flex-shrink-0">
                                                <img src="<?=$imgSrc?>" alt="<?=$item['name']?>" 
                                                     class="rounded-3 border" 
                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-1 fw-bold text-dark text-truncate" style="max-width: 200px;">
                                                    <?=$item['name']?>
                                                </h6>
                                                <small class="text-muted d-block mb-1"><?=$item['info']?></small>
                                                <?php if($maxStock < 10): ?>
                                                    <small class="text-danger fw-bold" style="font-size: 0.75rem;">
                                                        <i class="fas fa-exclamation-circle"></i> Chỉ còn: <?=$maxStock?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="text-center fw-semibold text-muted">
                                        <?=number_format($item['price'])?> đ
                                    </td>

                                    <td class="text-center">
                                        <div class="input-group input-group-sm d-inline-flex w-auto border rounded-pill overflow-hidden">
                                            <button class="btn btn-light px-2 border-0" type="button" 
                                                    onclick="updateQty('<?=$key?>', -1)">
                                                <i class="fas fa-minus small"></i>
                                            </button>
                                            <input type="number" id="qty-<?=$key?>" value="<?=$item['quantity']?>" 
                                                   class="form-control border-0 text-center p-0 fw-bold bg-white" 
                                                   style="width: 40px; height: 30px;" readonly>
                                            <button class="btn btn-light px-2 border-0" type="button" 
                                                    onclick="updateQty('<?=$key?>', 1)">
                                                <i class="fas fa-plus small"></i>
                                            </button>
                                        </div>
                                        <input type="hidden" id="stock-<?=$key?>" value="<?=$maxStock?>">
                                    </td>

                                    <td class="text-center fw-bold text-primary" id="row-total-<?=$key?>">
                                        <?=number_format($item['price'] * $item['quantity'])?> đ
                                    </td>

                                    <td class="text-center">
                                        <a href="?ctrl=cart&act=delete&key=<?=$key?>" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')" 
                                           class="btn btn-link text-danger p-0 opacity-50 hover-opacity-100" 
                                           title="Xóa sản phẩm">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-3 bg-light border-top">
                        <a href="index.php" class="text-decoration-none text-muted fw-500 hover-primary">
                            <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 cart-summary-box">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Thông tin đơn hàng</h5>
                        
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Tạm tính:</span>
                            <span class="fw-bold text-dark" id="sub-total"><?=number_format($totalPrice)?> đ</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Phí vận chuyển:</span>
                            <span class="text-success">Miễn phí</span>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold fs-5">Tổng cộng:</span>
                            <span class="fw-bold fs-4 text-primary" id="cart-total"><?=number_format($totalPrice)?> đ</span>
                        </div>

                        <button onclick="window.location.href='?ctrl=order&act=checkout'" 
                                class="btn btn-dark w-100 py-3 rounded-pill fw-bold text-uppercase shadow-hover transition-all">
                            Tiến hành thanh toán
                        </button>

                        <div class="mt-3 text-center">
                            <small class="text-muted"><i class="fas fa-shield-alt me-1"></i> Bảo mật thanh toán 100%</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="text-center py-5">
            <div class="mb-4">
                <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/cart/9bdd8040b334d31946f4.png" 
                     alt="Empty Cart" style="width: 150px; opacity: 0.7;">
            </div>
            <h4 class="text-muted mb-3">Giỏ hàng của bạn đang trống</h4>
            <p class="text-muted mb-4">Hãy dạo quanh cửa hàng và chọn cho mình vài món đồ ưng ý nhé!</p>
            <a href="index.php" class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow-sm">
                MUA SẮM NGAY
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
    /* Màu chủ đạo (Lấy theo CSS cũ của bạn) */
    .text-primary { color: #ff5722 !important; }
    .btn-primary { background-color: #ff5722; border-color: #ff5722; }
    .btn-primary:hover { background-color: #e64a19; border-color: #e64a19; }
    
    .hover-opacity-100:hover { opacity: 1 !important; }
    .hover-primary:hover { color: #ff5722 !important; }
    
    .fw-500 { font-weight: 500; }

    /* Input số lượng: bỏ mũi tên tăng giảm mặc định của browser */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; margin: 0; 
    }

    /* Hiệu ứng nút thanh toán */
    .shadow-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    .transition-all { transition: all 0.3s ease; }

    /* Sticky summary: Giữ khung thanh toán đứng yên khi cuộn trang */
    @media (min-width: 992px) {
        .cart-summary-box {
            position: sticky;
            top: 100px; /* Cách header một chút */
            z-index: 10;
        }
    }
</style>

<script>
function updateQty(key, change) {
    let qtyInput = document.getElementById('qty-' + key);
    let stockInput = document.getElementById('stock-' + key);
    let currentQty = parseInt(qtyInput.value);
    let maxStock = parseInt(stockInput.value);

    let newQty = currentQty + change;

    // 1. Chặn số lượng
    if (newQty < 1) return; 
    if (newQty > maxStock) {
        alert('Kho chỉ còn ' + maxStock + ' sản phẩm!');
        return;
    }

    // 2. Cập nhật ô input ngay lập tức
    qtyInput.value = newQty;

    // 3. Gửi AJAX để lấy giá tiền mới
    let formData = new FormData();
    formData.append('key', key);
    formData.append('qty', newQty);

    fetch('?ctrl=cart&act=updateAjax', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            // ĐIỀN GIÁ MỚI VÀO HTML
            document.getElementById('row-total-' + key).innerText = data.row_total;
            document.getElementById('sub-total').innerText = data.cart_total;
            document.getElementById('cart-total').innerText = data.cart_total;
        }
    })
    .catch(error => console.error('Lỗi:', error));
}
</script>