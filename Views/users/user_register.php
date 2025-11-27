<div class="container" style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h2 style="text-align: center;">Đăng Ký</h2>
    
    <?php if(isset($error)) { ?>
        <p style="color: red; text-align: center;"><?= $error ?></p>
    <?php } ?>

    <form action="?ctrl=user&act=registerPost" method="post">
        <div style="margin-bottom: 15px;">
            <label>Họ và tên:</label>
            <input type="text" name="fullname" required style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label>Email:</label>
            <input type="email" name="email" required style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label>Tên đăng nhập:</label>
            <input type="text" name="username" required style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label>Mật khẩu:</label>
            <input type="password" name="password" required style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>
        <button type="submit" style="width: 100%; padding: 10px; background: #28a745; color: #fff; border: none; cursor: pointer;">Đăng Ký</button>
    </form>
    <p style="text-align: center; margin-top: 15px;">
        Đã có tài khoản? <a href="?ctrl=user&act=login">Đăng nhập</a>
    </p>
</div>