<div class="container" style="max-width: 420px; margin: 60px auto;">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="h4 text-center mb-4">Đăng nhập</h2>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger py-2"><?=$error?></div>
            <?php endif; ?>

            <form action="?ctrl=user&act=loginPost" method="post">
                <input type="hidden" name="csrf_token" value="<?=htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8')?>">

                <div class="mb-3">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-dark w-100">Đăng nhập</button>
            </form>

            <p class="text-center mt-3 mb-0">
                Chưa có tài khoản?
                <a href="?ctrl=user&act=register">Đăng ký ngay</a>
            </p>
        </div>
    </div>
</div>
