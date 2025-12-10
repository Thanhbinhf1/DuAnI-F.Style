<div class="container" style="max-width: 460px; margin: 60px auto 80px;">
    <div style="
        background:#fff;
        border-radius: 10px;
        padding: 30px 28px 32px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #eee;
    ">
        <h2 style="text-align:center; margin-bottom:20px; letter-spacing:1px;">
            QUÊN MẬT KHẨU
        </h2>

        <?php if (!empty($error)): ?>
            <p style="color:#e53935; text-align:center; margin-bottom:15px;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <p style="color:#27ae60; text-align:center; margin-bottom:15px; font-weight: 600;">
                <?= htmlspecialchars($success) ?>
            </p>
        <?php endif; ?>

        <p style="text-align: center; color: #666; margin-bottom: 20px;">
            Vui lòng nhập địa chỉ Email đã đăng ký để nhận **MÃ XÁC NHẬN** (6 chữ số).
        </p>

        <form action="?ctrl=user&act=sendResetLink" method="post">
            <div style="margin-bottom: 20px;">
                <label style="font-size: 14px;">Email của bạn</label>
                <input type="email" name="email" value="<?= htmlspecialchars($oldEmail ?? '') ?>" required
                       style="width:100%; padding:8px 10px; margin-top:4px;
                              border-radius:5px; border:1px solid #ccc;">
            </div>
            <button type="submit"
                    style="width:100%; padding:10px 0; border:none;
                           border-radius:6px; background:#ff5722; color:#fff;
                           font-weight:600; text-transform:uppercase;">
                Gửi Mã xác nhận
            </button>
        </form>
        

        <p style="text-align:center; margin-top:18px; font-size:14px;">
            <a href="?ctrl=user&act=login">← Quay lại Đăng nhập</a>
        </p>
    </div>
</div>