<div class="container" style="max-width: 460px; margin: 60px auto 80px;">
    <div style="
        background:#fff;
        border-radius: 10px;
        padding: 30px 28px 32px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid #eee;
    ">
        <h2 style="text-align:center; margin-bottom:20px; letter-spacing:1px;">
            ĐẶT LẠI MẬT KHẨU
        </h2>

        <?php if (!empty($error) && !isset($code)): ?>
            <p style="color:#e53935; text-align:center; margin-bottom:15px; font-weight: 600;">
                <?= htmlspecialchars($error) ?>
            </p>
            <p style="text-align:center; margin-top:18px; font-size:14px;">
                <a href="?ctrl=user&act=forgotPassword">Yêu cầu lại mã xác thực</a>
            </p>
        <?php else: ?>
            <p style="text-align: center; color: #666; margin-bottom: 20px;">
                Vui lòng nhập mật khẩu mới cho tài khoản của bạn.
            </p>

            <form action="?ctrl=user&act=updatePassword" method="post">
                <input type="hidden" name="code" value="<?= htmlspecialchars($code ?? '') ?>">

                <div style="margin-bottom: 15px;">
                    <label style="font-size: 14px;">Mật khẩu mới</label>
                    <input type="password" name="password" required minlength="6"
                           style="width:100%; padding:8px 10px; margin-top:4px;
                                  border-radius:5px; border:1px solid #ccc;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-size: 14px;">Xác nhận mật khẩu mới</label>
                    <input type="password" name="password_confirm" required minlength="6"
                           style="width:100%; padding:8px 10px; margin-top:4px;
                                  border-radius:5px; border:1px solid #ccc;">
                </div>

                <button type="submit"
                        style="width:100%; padding:10px 0; border:none;
                               border-radius:6px; background:#222; color:#fff;
                               font-weight:600; text-transform:uppercase;">
                    Lưu mật khẩu mới
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>