<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <div style="display: flex; gap: 40px;">
        
        <div style="width: 30%;">
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center;">
                <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" style="width: 100px; border-radius: 50%; margin-bottom: 15px;">
                <h3><?=$user['fullname']?></h3>
                <p style="color: #666;"><?=$user['email']?></p>
                <hr style="margin: 15px 0; border-top: 1px solid #ddd;">
                <div style="text-align: left;">
                    <p><strong>Tài khoản:</strong> <?=$user['username']?></p>
                    <p><strong>Điện thoại:</strong> <?=$user['phone'] ?? 'Chưa cập nhật'?></p>
                    <p><strong>Địa chỉ:</strong> <?=$user['address'] ?? 'Chưa cập nhật'?></p>
                </div>
                <a href="?ctrl=user&act=edit" style="display:block; margin-top: 15px; padding: 10px; border: 1px solid #333; background: white; color: #333; cursor: pointer; text-decoration: none;">Chỉnh sửa thông tin</a>
            </div>
        </div>

        <div style="flex: 1;">
            <h2 style="border-bottom: 2px solid #ff5722; padding-bottom: 10px; margin-bottom: 20px;">LỊCH SỬ ĐƠN HÀNG</h2>
            
            <?php if (count($orders) > 0): ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="background: #333; color: white;">
                        <th style="padding: 10px;">Mã ĐH</th>
                        <th style="padding: 10px;">Ngày đặt</th>
                        <th style="padding: 10px;">Người nhận</th>
                        <th style="padding: 10px;">Tổng tiền</th>
                        <th style="padding: 10px;">Trạng thái</th>
                        <th style="padding: 10px;">Chi tiết</th>
                    </tr>
                    <?php foreach ($orders as $dh): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px; text-align: center;">#<?=$dh['id']?></td>
                        <td style="padding: 15px; text-align: center;"><?=date('d/m/Y', strtotime($dh['created_at']))?></td>
                        <td style="padding: 15px; text-align: center;"><?=$dh['fullname']?></td>
                        <td style="padding: 15px; text-align: center; font-weight: bold; color: #ff5722;">
                            <?=number_format($dh['total_money'])?> đ
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <?php 
                                if($dh['status'] == 0) echo "<span style='color:orange'>Chờ xử lý</span>";
                                elseif($dh['status'] == 1) echo "<span style='color:blue'>Đang giao</span>";
                                elseif($dh['status'] == 2) echo "<span style='color:green'>Đã giao</span>";
                                else echo "<span style='color:red'>Đã hủy</span>";
                            ?>
                        </td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="#" style="color: #333; text-decoration: underline;">Xem</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>Bạn chưa có đơn hàng nào.</p>
            <?php endif; ?>
        </div>
    </div>
</div>