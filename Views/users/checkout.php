<div class="container" style="margin-top: 30px; margin-bottom: 50px;">
    <h2 style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 10px;">THANH TOÁN & ĐẶT HÀNG</h2>
    
    <form action="?ctrl=order&act=saveOrder" method="post" style="display: flex; gap: 40px;">
        
        <div style="flex: 1;">
            <h3 style="margin-bottom: 20px;">1. Địa chỉ nhận hàng</h3>
            <div style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
                <div style="margin-bottom: 15px;">
                    <label>Họ và tên người nhận:</label>
                    <input type="text" name="fullname" value="<?=$user['fullname']?>" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Số điện thoại:</label>
                    <input type="text" name="phone" value="<?=$user['phone'] ?? ''?>" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Địa chỉ chi tiết:</label>
                    <input type="text" name="address" value="<?=$user['address'] ?? ''?>" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" placeholder="Số nhà, tên đường, phường/xã...">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Ghi chú đơn hàng (Tùy chọn):</label>
                    <textarea name="note" rows="3" style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;" placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao..."></textarea>
                </div>
            </div>

            <h3 style="margin: 20px 0;">2. Phương thức thanh toán</h3>
            <div style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
                <label style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px; cursor: pointer;">
                    <input type="radio" name="payment_method" value="COD" checked>
                    <img src="https://cdn-icons-png.flaticon.com/512/2331/2331941.png" width="30">
                    <span><b>Thanh toán khi nhận hàng (COD)</b> <br> <small style="color: #666;">Bạn chỉ phải thanh toán khi đã nhận được hàng.</small></span>
                </label>
                <hr style="border-top: 1px solid #eee; margin: 10px 0;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="radio" name="payment_method" value="BANK">
                    <img src="https://cdn-icons-png.flaticon.com/512/2169/2169862.png" width="30">
                    <span><b>Chuyển khoản ngân hàng</b> <br> <small style="color: #666;">Quét mã QR để hoàn tất thanh toán.</small></span>
                </label>

                
            </div>
        </div>

        <div style="width: 35%;">
            <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ddd; position: sticky; top: 20px;">
                <h3 style="margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">Đơn hàng (<?=count($_SESSION['cart'])?> sản phẩm)</h3>
                
                <div style="max-height: 300px; overflow-y: auto; margin-bottom: 20px;">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                        <div>
                            <strong><?=$item['name']?></strong><br>
                            <small style="color: #666;">x <?=$item['quantity']?> (<?=$item['info']?>)</small>
                        </div>
                        <span style="font-weight: 600;"><?=number_format($item['price'] * $item['quantity'])?> đ</span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div style="border-top: 2px solid #ccc; padding-top: 15px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>Tạm tính:</span>
                        <span><?=number_format($totalPrice)?> đ</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>Phí vận chuyển:</span>
                        <span style="color: green;">Miễn phí</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 20px; font-weight: bold; color: #ff5722; margin-top: 10px;">
                        <span>TỔNG CỘNG:</span>
                        <span><?=number_format($totalPrice)?> đ</span>
                    </div>
                </div>

                <button type="submit" style="width: 100%; background: #ff5722; color: white; padding: 15px; border: none; font-weight: bold; font-size: 16px; border-radius: 4px; cursor: pointer; margin-top: 20px; text-transform: uppercase;">ĐẶT HÀNG NGAY</button>
            </div>
        </div>

    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
        const qrInfoDiv = document.getElementById('qr-payment-info');

        const toggleQRInfo = () => {
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
            if (selectedMethod && selectedMethod.value === 'BANK') {
                qrInfoDiv.style.display = 'block';
            } else {
                qrInfoDiv.style.display = 'none';
            }
        };

        paymentRadios.forEach(radio => radio.addEventListener('change', toggleQRInfo));

        // Initial check in case the page loads with BANK pre-selected
        toggleQRInfo();
    });
</script>