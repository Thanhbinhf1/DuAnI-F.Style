<div class="container" style="max-width: 420px; margin: 60px auto 80px;">
    <div style="
        background:#fff;
        border-radius: 10px;
        padding: 30px 28px 32px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #eee;
    ">
        <h2 style="text-align: center; margin-bottom: 20px; letter-spacing:1px;">
            ĐĂNG NHẬP
        </h2>

        <?php if (!empty($error)): ?>
            <p style="color: #e53935; text-align:center; margin-bottom:15px;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form action="?ctrl=user&act=loginPost" method="post">
            <div style="margin-bottom: 15px;">
                <label style="font-size: 14px;">Tên đăng nhập</label>
                <input type="text" name="username" required
                       style="width:100%; padding:8px 10px; margin-top:4px;
                              border-radius:5px; border:1px solid #ccc;">
            </div>
            <div style="margin-bottom: 5px;">
                <label style="font-size: 14px;">Mật khẩu</label>
                <input type="password" name="password" required
                       style="width:100%; padding:8px 10px; margin-top:4px;
                              border-radius:5px; border:1px solid #ccc;">
            </div>
            <div style="text-align: right; margin-bottom: 20px; font-size: 13px;">
                <a href="?ctrl=user&act=forgotPassword" style="color: #ff5722;">Quên mật khẩu?</a>
            </div>
            <button type="submit"
                    style="width:100%; padding:10px 0; border:none;
                           border-radius:6px; background:#222; color:#fff;
                           font-weight:600; text-transform:uppercase;">
                Đăng nhập
            </button>
        </form>

        <p style="text-align:center; margin-top:18px; font-size:14px;">
            Chưa có tài khoản?
            <a href="?ctrl=user&act=register">Đăng ký ngay</a>
        </p>
    </div>
</div>