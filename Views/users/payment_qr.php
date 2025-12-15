<div class="container" style="margin: 50px auto; text-align: center; max-width: 800px;">
    <div style="
        max-width: 520px;
        margin: 0 auto;
        padding: 30px 20px 35px;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 0 20px rgba(0,0,0,0.08);
    ">
        <h2 style="color: #ff5722; margin-bottom: 10px; text-transform: uppercase;">
            THANH TOÁN CHUYỂN KHOẢN
        </h2>
        <p>Vui lòng quét mã QR bên dưới để thanh toán cho đơn hàng <b>#<?=$order['id']?></b></p>

        <?php
            $bankId      = 'MB';                     // Mã ngân hàng
            $accountNo   = '0342266306';             // Số tài khoản
            $accountName = 'LUONG HUU LUYEN';        // Tên chủ TK (VIẾT HOA cho đẹp trên QR)
            $amount      = $order['total_money'];    // Số tiền
            $content     = 'FSTYLE ' . $order['id']; // Nội dung CK

            // Link tạo ảnh QR từ VietQR
            $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-qr_only.png?amount={$amount}&addInfo={$content}&accountName={$accountName}";
        ?>

        <!-- KHUNG QR, LUÔN GIỮ TỶ LỆ VUÔNG & CĂN GIỮA -->
        <div style="
            margin: 25px auto 30px;
            display: flex;
            justify-content: center;
        ">
            <img src="<?=$qrUrl?>" alt="Mã QR thanh toán"
                 style="
                    max-width: 320px;
                    width: 100%;
                    height: auto;              /* ✅ chỉ set height:auto để không bị méo */
                    border: 2px solid #333;
                    padding: 12px;
                    border-radius: 12px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                 ">
        </div>

        <!-- THÔNG TIN TÀI KHOẢN -->
        <div style="
            background: #f9f9f9;
            padding: 18px 20px;
            border-radius: 8px;
            text-align: left;
            display: inline-block;
        ">
            <p>🏦 <b>Ngân hàng:</b> MB Bank (Quân Đội)</p>
            <p>💳 <b>Số tài khoản:</b> <?=$accountNo?></p>
            <p>👤 <b>Chủ tài khoản:</b> <?=$accountName?></p>
            <p>💰 <b>Số tiền:</b>
                <span style="color: red; font-weight: bold; font-size: 18px;">
                    <?=number_format($amount)?> đ
                </span>
            </p>
            <p>📝 <b>Nội dung CK:</b>
                <span style="background: yellow; padding: 2px 6px; font-weight: bold;">
                    <?=$content?>
                </span>
            </p>
        </div>

        <div style="margin-top: 30px;">
            <p style="font-style: italic; color: #666; margin-bottom: 20px;">
                * Hệ thống sẽ xử lý đơn hàng sau khi nhận được thanh toán. 
            </p>
            <a href="<?= BASE_URL ?>"
               style="
                    padding: 12px 24px;
                    background: #ff5722;
                    color: #fff;
                    text-decoration: none;
                    border-radius: 6px;
                    font-weight: bold;
               ">
                ĐÃ THANH TOÁN XONG
            </a>
        </div>
    </div>
</div>
