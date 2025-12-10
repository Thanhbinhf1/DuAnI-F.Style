<div class="container" style="max-width: 460px; margin: 60px auto 80px;">
    <div style="
        background:#fff;
        border-radius: 10px;
        padding: 30px 28px 32px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #eee;
    ">
        <h2 style="text-align:center; margin-bottom:20px; letter-spacing:1px;">
            NHẬP MÃ XÁC NHẬN
        </h2>

        <?php if (!empty($error)): ?>
            <p style="color:#e53935; text-align:center; margin-bottom:15px; font-weight: 600;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>
        
        <p style="text-align: center; color: #666; margin-bottom: 20px;">
            Mã xác nhận đã được gửi tới <b><?= htmlspecialchars($email) ?></b>. Mã có hiệu lực trong 10 phút.
        </p>

        <form action="?ctrl=user&act=verifyCodePost" method="post">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            
            <div style="margin-bottom: 20px;">
                <label style="font-size: 14px;">Mã xác nhận (6 chữ số)</label>
                <input type="text" name="code" required maxlength="6" inputmode="numeric" pattern="[0-9]{6}"
                       style="width:100%; padding:12px; margin-top:4px;
                              border-radius:8px; border:1px solid #ccc; text-align: center; font-size: 20px; letter-spacing: 5px;">
            </div>
            <button type="submit"
                    style="width:100%; padding:10px 0; border:none;
                           border-radius:6px; background:#222; color:#fff;
                           font-weight:600; text-transform:uppercase;">
                Xác nhận
            </button>
        </form>

        <p style="text-align:center; margin-top:18px; font-size:14px;">
            <a href="?ctrl=user&act=forgotPassword&email=<?= urlencode($email) ?>">← Gửi lại mã</a>
        </p>
    </div>
</div>