<div class="container" style="margin-top: 30px; margin-bottom: 50px;">
    <h2>GIỎ HÀNG CỦA BẠN </h2>

    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
        <form action="?ctrl=cart&act=update" method="post">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $key => $item): 
                        // Xử lý ảnh (nếu là ảnh online hay offline)
                        $imgSrc = $item['image'];
                        if (!str_contains($imgSrc, 'http')) $imgSrc = "./public/img/products/" . $imgSrc;
                    ?>
                    <tr>
                        <td class="product-col">
                            <img src="<?=$imgSrc?>" alt="" style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px;">
                            <div class="product-info">
                                <h4><?=$item['name']?></h4>
                                <span style="color: #666; font-size: 13px;"><?=$item['info']?></span>
                            </div>
                        </td>
                        <td style="color: #ff5722; font-weight: bold;">
                            <?=number_format($item['price'])?> đ
                        </td>
                        <td>
                            <input type="number" name="qty[<?=$key?>]" value="<?=$item['quantity']?>" min="1" style="width: 60px; text-align: center; padding: 5px; border: 1px solid #ddd;">
                        </td>
                        <td style="color: #ff5722; font-weight: bold;">
                            <?=number_format($item['price'] * $item['quantity'])?> đ
                        </td>
                        <td>
                            <a href="?ctrl=cart&act=delete&key=<?=$key?>" onclick="return confirm('Xóa món này?')" style="color: red; font-weight: bold;">✕</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-actions">
                <a href="index.php" class="btn-continue">← Tiếp tục mua sắm</a>
                <button type="submit" class="btn-update">Cập nhật giỏ hàng</button>
            </div>
        </form>

        <div class="cart-total-box">
            <div class="total-row">
                <span>Tổng tiền:</span>
                <span class="total-price"><?=number_format($totalPrice)?> đ</span>
            </div>
            <a href="?ctrl=order&act=checkout" class="btn-checkout">TIẾN HÀNH THANH TOÁN</a>
        </div>

    <?php else: ?>
        <div style="text-align: center; padding: 50px; background: #fff; border-radius: 10px;">
            <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/cart/9bdd8040b334d31946f4.png" style="width: 100px;">
            <p style="margin-top: 20px; color: #666;">Giỏ hàng của bạn còn trống</p>
            <a href="index.php" class="btn-checkout" style="display: inline-block; margin-top: 15px; width: auto; padding: 10px 30px;">MUA NGAY</a>
        </div>
    <?php endif; ?>
</div>