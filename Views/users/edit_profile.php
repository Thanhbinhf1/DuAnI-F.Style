<div class="container" style="max-width: 640px; margin: 40px auto; padding: 0 16px;">
    <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; box-shadow: 0 14px 38px rgba(15,23,42,0.08); padding: 28px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 16px;">
            <div>
                <div style="font-weight: 800; font-size: 20px; color: #111827;">Chỉnh sửa hồ sơ</div>
                <p style="margin: 4px 0 0; color: #6b7280;">Cập nhật thông tin liên hệ và địa chỉ nhận hàng</p>
            </div>
            <a href="?ctrl=user&act=profile#personal" style="color:#111827; text-decoration:none; font-weight:600;">Quay lại</a>
        </div>

        <form action="?ctrl=user&act=updateProfile" method="post" style="display: grid; gap: 16px;">
            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Họ và tên</label>
                <input type="text" name="fullname" value="<?=htmlspecialchars($user['fullname'] ?? '', ENT_QUOTES)?>" required
                    style="width:100%; padding:12px; border:1px solid #e5e7eb; border-radius:10px; outline:none;">
            </div>
            <div>
                <label style="display:block; font-weight:600; margin-bottom:6px;">Email</label>
                <input type="email" name="email" value="<?=htmlspecialchars($user['email'] ?? '', ENT_QUOTES)?>" required
                    style="width:100%; padding:12px; border:1px solid #e5e7eb; border-radius:10px; outline:none;">
            </div>
            <div style="display:grid; gap:12px; grid-template-columns: repeat(auto-fit, minmax(240px,1fr));">
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Số điện thoại</label>
                    <input type="text" name="phone" value="<?=htmlspecialchars($user['phone'] ?? '', ENT_QUOTES)?>" required
                        style="width:100%; padding:12px; border:1px solid #e5e7eb; border-radius:10px; outline:none;">
                </div>
                <div>
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Địa chỉ nhận hàng</label>
                    <input type="text" name="address" value="<?=htmlspecialchars($user['address'] ?? '', ENT_QUOTES)?>" required
                        style="width:100%; padding:12px; border:1px solid #e5e7eb; border-radius:10px; outline:none;">
                </div>
            </div>

            <div style="display:flex; gap:12px; justify-content:flex-end; flex-wrap:wrap; margin-top:4px;">
                <a href="?ctrl=user&act=profile#personal" style="padding:10px 18px; border:1px solid #e5e7eb; border-radius:10px; color:#111827; text-decoration:none; font-weight:600;">Hủy</a>
                <button type="submit" style="padding:10px 18px; background:#111827; color:#fff; border:none; border-radius:10px; font-weight:700; cursor:pointer;">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
