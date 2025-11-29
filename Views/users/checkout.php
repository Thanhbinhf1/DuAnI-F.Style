<div class="container" style="margin-top: 30px; margin-bottom: 50px;">
    <h2 style="text-align: center; margin-bottom: 30px;">THANH TOÁN ĐƠN HÀNG</h2>
    
    <div style="display: flex; gap: 40px;">
        <div style="flex: 1; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
            <h3 style="margin-bottom: 20px;">Thông tin nhận hàng</h3>
            <form action="?ctrl=order&act=saveOrder" method="post">
                <div style="margin-bottom: 15px;">
                    <label>Họ và tên:</label>
                    <input type="text" name="fullname" value="<?=$user['fullname']?>" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Số điện thoại:</label>
                    <input type="text" name="phone" value="<?=$user['phone'] ?? ''?>" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Địa chỉ giao hàng:</label>
                    <input type="text" name="address" value="<?=$user['address'] ?? ''?>" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <button type="submit" style="width: 100%; background: #ff5722; color: white; padding: 12px; border: none; font-weight: bold; border-radius: 4px; cursor: pointer; margin-top: 10px;">XÁC NHẬN ĐẶT HÀNG</button>
            </form>
        </div>

        <div style="width: 40%; background: #f9f9f9; padding: 20px; border-radius: 8px; height: fit-content;">
            <h3 style="margin-bottom: 20px;">Đơn hàng của bạn</h3>
            <div style="max-height: 300px; overflow-y: auto;">
                <?php foreach ($_SESSION['cart'] as $item): ?>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    <div>
                        <strong><?=$item['name']?></strong><br>
                        <small>x <?=$item['quantity']?> (<?=$item['info']?>)</small>
                    </div>
                    <span style="font-weight: bold;"><?=number_format($item['price'] * $item['quantity'])?> đ</span>
                </div>
                <?php endforeach; ?>
            </div>
            <div style="margin-top: 20px; padding-top: 10px; border-top: 2px solid #ccc; display: flex; justify-content: space-between; font-size: 18px; font-weight: bold;">
                <span>Tổng cộng:</span>
                <span style="color: #ff5722;"><?=number_format($totalPrice)?> đ</span>
            </div>
        </div>
    </div>
</div>