<div class="container" style="margin-top: 50px; margin-bottom: 50px; text-align: center;">
    
    <div style="max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 40px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1);">
        <h2 style="color: #ff5722; margin-bottom: 10px;">THANH TOÁN CHUYỂN KHOẢN</h2>
        <p>Vui lòng quét mã QR bên dưới để thanh toán cho đơn hàng <b>#<?=$order['id']?></b></p>
        
        <?php
            $bankId = 'MB'; // Mã ngân hàng (MB, VCB, ACB, TPB...)
            $accountNo = '0342266306'; // Số tài khoản của bạn
            $accountName = 'Luong Huu Luyen'; // Tên chủ tài khoản
            $amount = $order['total_money']; // Số tiền cần trả
            $content = 'FSTYLE ' . $order['id']; // Nội dung chuyển khoản (Ví dụ: FSTYLE 123)
            
            // Link tạo ảnh QR tự động từ VietQR
            $qrUrl = "https://img.vietqr.io/image/$bankId-$accountNo-print.png?amount=$amount&addInfo=$content&accountName=$accountName";
        ?>

        <div style="margin: 30px 0;">
            <img src="<?=$qrUrl?>" alt="Mã QR Thanh Toán" style="width: 300px; border: 2px solid #333; padding: 10px; border-radius: 10px;">
        </div>

        <div style="background: #f9f9f9; padding: 20px; border-radius: 5px; text-align: left; display: inline-block;">
            <p>🏦 <b>Ngân hàng:</b> MB Bank (Quân Đội)</p>
            <p>💳 <b>Số tài khoản:</b> <?=$accountNo?></p>
            <p>👤 <b>Chủ tài khoản:</b> <?=$accountName?></p>
            <p>💰 <b>Số tiền:</b> <span style="color: red; font-weight: bold; font-size: 18px;"><?=number_format($amount)?> đ</span></p>
            <p>📝 <b>Nội dung CK:</b> <span style="background: yellow; padding: 2px 5px; font-weight: bold;"><?=$content?></span></p>
        </div>

        <div style="margin-top: 30px;">
            <p style="font-style: italic; color: #666; margin-bottom: 20px;">
                * Hệ thống sẽ tự động xử lý đơn hàng sau khi nhận được thanh toán.
            </p>
            <a href="?ctrl=user&act=profile" style="padding: 12px 30px; background: #333; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
                ĐÃ THANH TOÁN XONG
            </a>
        </div>
    </div>
</div>