<?php
// Biến $errors, $successMessage, $old được truyền từ PageController::contact
$errors = $errors ?? [];
$successMessage = $successMessage ?? "";
$old = $old ?? ['name'=>'','email'=>'','phone'=>'','subject'=>'','message'=>''];
?>

<div class="container" style="margin-top: 30px; margin-bottom: 50px;">
    <div class="page-box contact-page">
        <h1 class="page-title">Liên hệ với F.Style</h1>
        <p class="page-subtitle">
            Nếu bạn có bất kỳ thắc mắc, góp ý hoặc cần hỗ trợ về đơn hàng, hãy để lại thông tin. 
            F.Style sẽ phản hồi trong thời gian sớm nhất.
        </p>

        <div class="contact-grid">
            <!-- Thông tin liên hệ -->
            <div class="contact-info">
                <h2>Thông tin cửa hàng</h2>
                <p><b>F.Style Store</b></p>
                <p>Địa chỉ: Nghĩa Hành, Quảng Ngãi (Ví dụ)</p>
                <p>Hotline: <a href="tel:0342266306">0342 266 306</a></p>
                <p>Email: <a href="mailto:luyenluongpro0@gmail.com">luyenluongpro0@gmail.com</a></p>

                <h3>Kênh mạng xã hội</h3>
                <ul class="social-links">
                    <li><a href="#" target="_blank">Facebook Fanpage</a></li>
                    <li><a href="#" target="_blank">Instagram</a></li>
                    <li><a href="#" target="_blank">Shopee / Lazada (nếu có)</a></li>
                </ul>

                <h3>Giờ làm việc</h3>
                <p>Thứ 2 - Chủ nhật: 8:00 - 22:00</p>
            </div>

            <!-- Form liên hệ -->
            <div class="contact-form">
                <?php if (!empty($successMessage)): ?>
                    <div class="alert-success">
                        <?=$successMessage?>
                    </div>
                <?php endif; ?>

                <form action="?ctrl=page&act=contact" method="post" class="form-contact">
                    <div class="form-group">
                        <label>Họ và tên <span>*</span></label>
                        <input type="text" name="name" value="<?=htmlspecialchars($old['name'])?>">
                        <?php if (isset($errors['name'])): ?>
                            <p class="error-msg"><?=$errors['name']?></p>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Email <span>*</span></label>
                        <input type="email" name="email" value="<?=htmlspecialchars($old['email'])?>">
                        <?php if (isset($errors['email'])): ?>
                            <p class="error-msg"><?=$errors['email']?></p>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" value="<?=htmlspecialchars($old['phone'])?>">
                    </div>

                    <div class="form-group">
                        <label>Tiêu đề</label>
                        <input type="text" name="subject" value="<?=htmlspecialchars($old['subject'])?>">
                    </div>

                    <div class="form-group">
                        <label>Nội dung <span>*</span></label>
                        <textarea name="message" rows="5"><?=htmlspecialchars($old['message'])?></textarea>
                        <?php if (isset($errors['message'])): ?>
                            <p class="error-msg"><?=$errors['message']?></p>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn-submit-contact">Gửi liên hệ</button>
                </form>
            </div>
        </div>
    </div>
</div>
