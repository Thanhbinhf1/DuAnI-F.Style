<div class="container" style="margin-top: 30px; margin-bottom: 50px;">
    <h2>GIỎ HÀNG CỦA BẠN </h2>

    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
        
        <div class="cart-layout">
            
            <div class="cart-left">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th width="45%">Sản phẩm</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $key => $item): 
                            $imgSrc = str_contains($item['image'], 'http') ? $item['image'] : "./public/img/products/" . $item['image'];
                            $maxStock = $item['stock'];
                        ?>
                        <tr id="row-<?=$key?>">
                            <td class="product-col">
                                <img src="<?=$imgSrc?>" alt="" style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px;">
                                <div class="product-info">
                                    <h4><?=$item['name']?></h4>
                                    <span style="color: #666; font-size: 13px;"><?=$item['info']?></span>
                                    <?php if($maxStock < 10) echo "<br><small style='color:red'>Kho còn: $maxStock</small>"; ?>
                                </div>
                            </td>
                            <td style="color: #555;">
                                <?=number_format($item['price'])?> đ
                            </td>
                            <td>
                                <div class="qty-control">
                                    <button type="button" onclick="updateQty('<?=$key?>', -1)">-</button>
                                    <input type="number" id="qty-<?=$key?>" value="<?=$item['quantity']?>" readonly>
                                    <button type="button" onclick="updateQty('<?=$key?>', 1)">+</button>
                                    <input type="hidden" id="stock-<?=$key?>" value="<?=$maxStock?>">
                                </div>
                            </td>
                            <td style="color: #ff5722; font-weight: bold;" id="row-total-<?=$key?>">
                                <?=number_format($item['price'] * $item['quantity'])?> đ
                            </td>
                            <td>
                                <a href="?ctrl=cart&act=delete&key=<?=$key?>" onclick="return confirm('Xóa món này?')" style="color: #999; font-size: 20px; text-decoration: none;">&times;</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="index.php" style="color: #333; font-weight: 500;">← Tiếp tục mua sắm</a>
            </div>

            <div class="cart-right">
                <div class="cart-summary">
                    <div class="summary-title">Thông tin đơn hàng</div>
                    <div class="summary-row">
                        <span>Tạm tính:</span>
                        <span id="sub-total"><?=number_format($totalPrice)?> đ</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển:</span>
                        <span>Miễn phí</span>
                    </div>
                    <div class="summary-total">
                        <span>Tổng cộng:</span>
                        <span class="total-price" id="cart-total"><?=number_format($totalPrice)?> đ</span>
                    </div>
                    <a href="?ctrl=order&act=checkout" class="btn-checkout">THANH TOÁN</a>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div style="text-align: center; padding: 50px; background: #fff; border-radius: 10px;">
            <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/cart/9bdd8040b334d31946f4.png" style="width: 100px;">
            <p style="margin-top: 20px; color: #666;">Giỏ hàng của bạn còn trống</p>
            <a href="index.php" class="btn-checkout" style="display: inline-block; width: auto; padding: 10px 30px; margin-top: 15px;">MUA NGAY</a>
        </div>
    <?php endif; ?>
</div>

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