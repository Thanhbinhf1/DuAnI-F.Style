<div class="container" style="max-width: 500px; margin: 50px auto;">
    <h2 style="text-align: center; margin-bottom: 20px;">CHỈNH SỬA HỒ SƠ</h2>
    
    <form action="?ctrl=user&act=updatePost" method="post" style="border: 1px solid #ddd; padding: 30px; border-radius: 8px;">
        <div style="margin-bottom: 15px;">
            <label>Họ và tên:</label>
            <input type="text" name="fullname" value="<?=$user['fullname']?>" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc;">
        </div>
        <div style="margin-bottom: 15px;">
            <label>Email:</label>
            <input type="email" name="email" value="<?=$user['email']?>" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc;">
        </div>
        <div style="margin-bottom: 15px;">
            <label>Số điện thoại:</label>
            <input type="text" name="phone" value="<?=$user['phone']?>" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc;">
        </div>
        <div style="margin-bottom: 15px;">
            <label>Địa chỉ nhận hàng:</label>
            <input type="text" name="address" value="<?=$user['address']?>" required style="width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc;">
        </div>
        
        <div style="display: flex; gap: 10px;">
            <a href="?ctrl=user&act=profile" style="padding: 10px 20px; border: 1px solid #ddd; color: #333; text-decoration: none;">Hủy</a>
            <button type="submit" style="padding: 10px 20px; background: #333; color: white; border: none; cursor: pointer; flex: 1;">LƯU THAY ĐỔI</button>
        </div>
    </form>
</div>