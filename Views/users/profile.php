<?php
    $fullName = htmlspecialchars($user['fullname'] ?? $user['username']);
    $email    = htmlspecialchars($user['email'] ?? 'Chưa cập nhật');
    $phone    = htmlspecialchars($user['phone'] ?? 'Chưa cập nhật');
    $address  = htmlspecialchars($user['address'] ?? 'Chưa cập nhật');

    $orders     = $orders ?? [];
    $reviews    = $reviews ?? [];
    $invoices   = $invoices ?? [];

    $orderCount     = count($orders);
    $pendingCount   = 0;
    $shippingCount  = 0;
    $completedCount = 0;
    $cancelledCount = 0;

    foreach ($orders as $item) {
        if ($item['status'] == 0) {
            $pendingCount++;
        } elseif ($item['status'] == 1) {
            $shippingCount++;
        } elseif ($item['status'] == 2) {
            $completedCount++;
        } elseif ($item['status'] == 3) {
            $cancelledCount++;
        }
    }
?>

<style>
.profile-shell {
    background: linear-gradient(135deg, #fff7f0 0%, #ffffff 60%, #f1f5ff 100%);
    min-height: 100vh;
}

.profile-card {
    background: #fff;
    border: 1px solid #eef0f3;
    border-radius: 14px;
    box-shadow: 0 20px 60px rgba(15, 23, 42, 0.07);
}

.profile-sidebar .nav-link {
    color: #475569;
    font-weight: 600;
    border-radius: 12px;
    padding: 12px 14px;
}

.profile-sidebar .nav-link:hover,
.profile-sidebar .nav-link.active {
    background: #111827;
    color: #fff;
}

.section-title {
    font-weight: 800;
    color: #111827;
}

.empty-state {
    border: 1px dashed #cbd5e1;
    border-radius: 12px;
    padding: 16px;
    background: #f8fafc;
}

.chip {
    background: #f1f5f9;
    border-radius: 999px;
    padding: 8px 14px;
    font-size: 13px;
    color: #0f172a;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.profile-section {
    display: none;
}

.profile-section.active {
    display: block;
}

.status-stack .badge {
    display: block;
    margin-bottom: 4px;
}

.status-stack small {
    color: #475569;
}

.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 12px;
    line-height: 1.2;
}

.bg-warning {
    background: #fef08a;
    color: #92400e;
}

.bg-info {
    background: #e0f2fe;
    color: #075985;
}

.bg-success {
    background: #bbf7d0;
    color: #065f46;
}

.bg-danger {
    background: #fecdd3;
    color: #9f1239;
}

.bg-secondary {
    background: #e2e8f0;
    color: #0f172a;
}
</style>

<div class="profile-shell py-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="profile-card p-4 profile-sidebar">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" class="rounded-circle"
                            alt="Avatar" style="width: 72px; height: 72px; object-fit: cover;">
                        <div>
                            <div class="fw-bold fs-5"><?=$fullName?></div>
                            <div class="text-muted small"><?=$email?></div>
                        </div>
                    </div>
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <div class="fw-bold fs-5"><?=$orderCount?></div>
                            <div class="text-muted small">Đơn hàng</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-5 text-warning"><?=$pendingCount?></div>
                            <div class="text-muted small">Chờ xác nhận</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-5 text-success"><?=$completedCount?></div>
                            <div class="text-muted small">Hoàn thành</div>
                        </div>
                    </div>
                    <div class="nav flex-column">
                        <a class="nav-link active" href="#personal">Thông tin cá nhân</a>
                        <a class="nav-link" href="#orders">Đơn hàng của tôi</a>
                        <a class="nav-link" href="#payments">Phương thức thanh toán</a>
                        <a class="nav-link" href="#reviews">Đánh giá</a>
                        <a class="nav-link" href="#coupons">Mã giảm giá &amp; Điểm</a>
                        <a class="nav-link" href="#security">Bảo mật</a>
                        <a class="nav-link" href="#settings">Cài đặt</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <section class="profile-card p-4 mb-4 profile-section active" id="personal">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="section-title mb-1">Thông tin cá nhân</div>
                            <p class="text-muted mb-0">Quản lý hồ sơ và địa chỉ nhận hàng</p>
                        </div>
                        <a href="?ctrl=user&amp;act=editProfile" class="btn btn-dark btn-sm">Chỉnh sửa</a>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="chip"><i class="fa-regular fa-id-badge"></i> <?=$fullName?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="chip"><i class="fa-regular fa-envelope"></i> <?=$email?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="chip"><i class="fa-solid fa-phone"></i> <?=$phone?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="chip"><i class="fa-solid fa-location-dot"></i> <?=$address?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="chip"><i class="fa-regular fa-user"></i> Mã khách hàng: #<?=$user['id']?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="chip"><i class="fa-brands fa-facebook"></i> Liên kết MXH: Chưa kết nối</div>
                        </div>
                    </div>
                </section>

                <section class="profile-card p-4 mb-4 profile-section" id="orders">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="section-title mb-1">Đơn hàng của tôi</div>
                            <p class="text-muted mb-0">Theo dõi trạng thái, hủy hoặc mua lại đơn hàng</p>
                        </div>
                        <a href="?ctrl=cart&amp;act=view" class="btn btn-outline-dark btn-sm">Tiếp tục mua sắm</a>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="chip"><i class="fa-regular fa-hourglass"></i> Chờ xác nhận:
                            <?=$pendingCount?></span>
                        <span class="chip"><i class="fa-solid fa-truck"></i> Đang giao: <?=$shippingCount?></span>
                        <span class="chip"><i class="fa-solid fa-circle-check"></i> Hoàn thành:
                            <?=$completedCount?></span>
                        <span class="chip"><i class="fa-solid fa-ban"></i> Đã hủy: <?=$cancelledCount?></span>
                    </div>
                    <?php
                        if (!function_exists('renderStatusBadge')) {
                            function renderStatusBadge($status)
                            {
                                $map = [
                                    0 => ['label' => 'Chờ xác nhận', 'class' => 'bg-warning text-dark'],
                                    1 => ['label' => 'Đang giao', 'class' => 'bg-info text-dark'],
                                    2 => ['label' => 'Hoàn thành', 'class' => 'bg-success'],
                                    3 => ['label' => 'Đã hủy', 'class' => 'bg-danger'],
                                ];

                                $current = $map[$status] ?? ['label' => 'Không xác định', 'class' => 'bg-secondary'];
                                return '<span class="badge ' . $current['class'] . ' px-3 py-2">' . $current['label'] . '</span>';
                            }
                        }

                        if (!function_exists('renderPaymentBadge')) {
                            function renderPaymentBadge($orderStatus, $paymentStatus)
                            {
                                if ((int)$paymentStatus === 1) {
                                    return '<span class="badge bg-success px-3 py-2">Đã thanh toán</span>';
                                }
                                if ((int)$paymentStatus === 2) {
                                    return '<span class="badge bg-warning text-dark px-3 py-2">Đã hoàn tiền</span>';
                                }
                                return '<span class="badge bg-secondary px-3 py-2">Chưa thanh toán</span>';
                            }
                        }
                    ?>
                    <?php if ($orderCount > 0): ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã ĐH</th>
                                    <th>Ngày đặt</th>
                                    <th class="text-end">Tổng tiền</th>
                                    <th class="text-center">Trạng thái</th>

                                    <th class="text-end">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $dh): ?>
                                <tr>
                                    <td class="fw-semibold">#<?=htmlspecialchars($dh['id'])?></td>
                                    <td><?=date('d/m/Y', strtotime($dh['created_at']))?></td>
                                    <td class="text-end text-danger fw-bold"><?=number_format($dh['total_money'])?> đ
                                    </td>
                                    <td class="text-center">
                                        <div class="status-stack" style="
    display: flex;
    justify-content: center;
">
                                            <div class="mb-2">
                                                <small style="display:block; margin-bottom:3px; color:#666;">Đơn
                                                    hàng:</small>
                                                <?php 
            $stt = $dh['status'] ?? -1;
            // Mặc định: Xám
            $txt = 'Không xác định';
            $bg  = '#e2e8f0'; 
            $col = '#333'; 

            if ($stt == 0) { 
                $txt = 'Chờ xác nhận'; $bg = '#fff3cd'; $col = '#856404'; 
            } elseif ($stt == 1) { 
                $txt = 'Đang giao';    $bg = '#cff4fc'; $col = '#055160'; 
            } elseif ($stt == 2) { 
                $txt = 'Hoàn thành';   $bg = '#d1e7dd'; $col = '#0f5132'; 
            } elseif ($stt == 3) { 
                $txt = 'Đã hủy';       $bg = '#f8d7da'; $col = '#842029'; 
            }
        ?>
                                                <span
                                                    style="display:inline-block; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: <?=$bg?>; color: <?=$col?>;">
                                                    <?=$txt?>
                                                </span>
                                            </div>

                                            <div>
                                                <small style="display:block; margin-bottom:3px; color:#666;">Thanh
                                                    toán:</small>
                                                <?php 
            $pay = $dh['payment_status'] ?? 0;
            // Mặc định: Xám
            $txtPay = 'Chưa thanh toán';
            $bgPay  = '#e2e8f0'; 
            $colPay = '#333';

            if ($pay == 1) { 
                $txtPay = 'Đã thanh toán'; $bgPay = '#d1e7dd'; $colPay = '#0f5132'; // Xanh lá
            } elseif ($pay == 2) { 
                $txtPay = 'Đã hoàn tiền';  $bgPay = '#fff3cd'; $colPay = '#856404'; // Vàng
            }
        ?>
                                                <span
                                                    style="display:inline-block; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: <?=$bgPay?>; color: <?=$colPay?>;">
                                                    <?=$txtPay?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a class="btn btn-link btn-sm text-decoration-none"
                                                href="?ctrl=order&amp;act=detail&id=<?=urlencode($dh['id'])?>">Theo
                                                dõi</a>
                                            <a class="btn btn-dark btn-sm"
                                                href="?ctrl=order&amp;act=reorder&id=<?=urlencode($dh['id'])?>">Mua
                                                lại</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="empty-state d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-semibold">Bạn chưa có đơn hàng nào</div>
                            <p class="text-muted mb-0">Khám phá sản phẩm và đặt hàng ngay hôm nay.</p>
                        </div>
                        <a href="?ctrl=product&amp;act=list" class="btn btn-dark">Mua sắm ngay</a>
                    </div>
                    <?php endif; ?>
                </section>
                <section class="profile-card p-4 mb-4 profile-section" id="payments">
                    <div class="section-title mb-3">Phương thức thanh toán</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="fw-semibold">Thanh toán khi nhận hàng (COD)</div>
                                    <span class="badge bg-success">Mặc định</span>
                                </div>
                                <p class="text-muted mb-2">Kiểm tra hàng trước khi thanh toán.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="fw-semibold mb-2">Thanh toán online</div>
                                <p class="text-muted small mb-2">Chọn ví / cổng thanh toán khi đặt hàng.</p>
                                <div class="d-flex gap-2 flex-wrap">
                                    <span class="chip"><i class="fa-solid fa-wallet"></i> Momo</span>
                                    <span class="chip"><i class="fa-solid fa-qrcode"></i> VNPay</span>
                                    <span class="chip"><i class="fa-regular fa-credit-card"></i> ZaloPay</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="profile-card p-4 mb-4 profile-section" id="reviews">
                    <div class="section-title mb-2">Đánh giá của tôi</div>
                    <p class="text-muted mb-3">Xem lịch sử đánh giá, sửa hoặc xóa đánh giá đã gửi.</p>
                    <?php if (!empty($reviews)): ?>
                    <div class="list-group">
                        <?php foreach ($reviews as $review): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold"><?=htmlspecialchars($review['product'] ?? 'Sản phẩm')?></div>
                                <div class="text-muted small mb-2"><?=htmlspecialchars($review['created_at'] ?? '')?>
                                </div>
                                <div><?=htmlspecialchars($review['content'] ?? '')?></div>
                            </div>
                            <div class="d-flex gap-2">
                                <a class="btn btn-outline-dark btn-sm"
                                    href="?ctrl=review&amp;act=edit&id=<?=urlencode($review['id'] ?? '')?>">Sửa</a>
                                <a class="btn btn-outline-danger btn-sm"
                                    href="?ctrl=review&amp;act=delete&id=<?=urlencode($review['id'] ?? '')?>"
                                    onclick="return confirm('Xóa đánh giá này?');">Xóa</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="empty-state">
                        <div class="fw-semibold">Bạn chưa có đánh giá nào</div>
                        <p class="text-muted mb-0">Hãy mua sản phẩm và chia sẻ trải nghiệm của bạn.</p>
                    </div>
                    <?php endif; ?>
                </section>

                <section class="profile-card p-4 mb-4 profile-section" id="coupons">
                    <div class="section-title mb-2">Mã giảm giá / Ví tích điểm</div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted">Mã giảm giá</div>
                                <div class="fs-4 fw-bold">0</div>
                                <p class="text-muted small mb-0">Chưa có mã khả dụng</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted">Điểm thưởng</div>
                                <div class="fs-4 fw-bold text-success">0</div>
                                <p class="text-muted small mb-0">Tích điểm qua mỗi đơn hàng</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted">Lịch sử sử dụng</div>
                                <div class="fs-4 fw-bold">-</div>
                                <p class="text-muted small mb-0">Chưa có giao dịch</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="profile-card p-4 mb-4 profile-section" id="security">
                    <div class="section-title mb-2">Bảo mật tài khoản</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="fw-semibold mb-1">Đổi mật khẩu</div>
                                <p class="text-muted small mb-3">Cập nhật mật khẩu định kỳ để bảo vệ tài khoản.</p>
                                <a class="btn btn-outline-dark btn-sm" href="?ctrl=user&amp;act=edit#password">Đổi mật
                                    khẩu</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="fw-semibold mb-1">Phiên đăng nhập</div>
                                <p class="text-muted small mb-3">Kiểm tra thiết bị gần đây và đăng xuất khỏi tất cả
                                    thiết bị nếu cần.</p>
                                <button class="btn btn-outline-dark btn-sm" type="button">Đăng xuất tất cả thiết
                                    bị</button>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="profile-card p-4 profile-section" id="settings">
                    <div class="section-title mb-2">Cài đặt khác</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ngôn ngữ</label>
                            <select class="form-select">
                                <option>Tiếng Việt</option>
                                <option>English</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nhận thông báo</label>
                            <div class="d-flex gap-3 flex-wrap">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">Email</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">SMS</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                    <label class="form-check-label">Thông báo đẩy</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">Xóa tài khoản</div>
                                <p class="text-muted small mb-0">Hành động này không thể hoàn tác.</p>
                            </div>
                            <button class="btn btn-outline-danger">Yêu cầu xóa</button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<div class="card mt-4 mb-5 shadow-sm">
    <div class="card-header bg-white fw-bold">
        <i class="fas fa-map-marker-alt text-danger"></i> Địa chỉ nhận hàng mặc định
    </div>
    <div class="card-body">
        <form action="?ctrl=user&act=saveAddress" method="post" id="address-form">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Tỉnh/Thành phố</label>
                    <select id="province" name="province_id" class="form-select" required>
                        <option value="">-- Chọn --</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Quận/Huyện</label>
                    <select id="district" name="district_id" class="form-select" required>
                        <option value="">-- Chọn --</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Phường/Xã</label>
                    <select id="ward" name="ward_id" class="form-select" required>
                        <option value="">-- Chọn --</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label small text-muted">Địa chỉ cụ thể (Số nhà, tên đường)</label>
                    <input type="text" id="street_name" name="street_address" class="form-control"
                        value="<?= $_SESSION['user']['street_address'] ?? '' ?>" required>
                </div>

                <input type="hidden" name="full_address_str" id="full_address_input">

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary px-4">Lưu địa chỉ</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // Lấy dữ liệu đã lưu trong Session (nếu có)
    const savedProv = "<?= $_SESSION['user']['province_id'] ?? '' ?>";
    const savedDist = "<?= $_SESSION['user']['district_id'] ?? '' ?>";
    const savedWard = "<?= $_SESSION['user']['ward_id'] ?? '' ?>";

    // 1. Load Tỉnh
    $.getJSON('https://esgoo.net/api-tinhthanh/1/0.htm', function(data) {
        if (data.error == 0) {
            $.each(data.data, function(k, v) {
                $("#province").append('<option value="' + v.id + '" data-name="' + v.full_name +
                    '">' + v.full_name + '</option>');
            });

            // Nếu đã có địa chỉ lưu -> Tự động chọn Tỉnh
            if (savedProv) {
                $("#province").val(savedProv).trigger('change');
            }
        }
    });

    // 2. Sự kiện chọn Tỉnh -> Load Huyện
    $("#province").change(function() {
        let id = $(this).val();
        $.getJSON('https://esgoo.net/api-tinhthanh/2/' + id + '.htm', function(data) {
            $("#district").html('<option value="">-- Chọn --</option>');
            $("#ward").html('<option value="">-- Chọn --</option>');
            if (data.error == 0) {
                $.each(data.data, function(k, v) {
                    $("#district").append('<option value="' + v.id + '" data-name="' + v
                        .full_name + '">' + v.full_name + '</option>');
                });

                // Nếu đang load lại dữ liệu cũ -> Chọn Huyện
                if (savedDist && $("#district option[value='" + savedDist + "']").length == 0) {
                    // Đợi 1 chút để DOM cập nhật (hoặc dùng logic check)
                    // Ở đây API chạy nhanh nên thường sẽ set được ngay trong callback này nếu logic flow đúng
                }
                if (savedDist) $("#district").val(savedDist).trigger('change');
            }
        });
        updateFullStr();
    });

    // 3. Sự kiện chọn Huyện -> Load Xã
    $("#district").change(function() {
        let id = $(this).val();
        $.getJSON('https://esgoo.net/api-tinhthanh/3/' + id + '.htm', function(data) {
            $("#ward").html('<option value="">-- Chọn --</option>');
            if (data.error == 0) {
                $.each(data.data, function(k, v) {
                    $("#ward").append('<option value="' + v.id + '" data-name="' + v
                        .full_name + '">' + v.full_name + '</option>');
                });
                if (savedWard) $("#ward").val(savedWard);
            }
        });
        updateFullStr();
    });

    $("#ward, #street_name").change(updateFullStr);

    function updateFullStr() {
        let t = $("#province option:selected").data('name') || '';
        let q = $("#district option:selected").data('name') || '';
        let p = $("#ward option:selected").data('name') || '';
        let s = $("#street_name").val();
        $("#full_address_input").val([s, p, q, t].filter(Boolean).join(', '));
    }
});
</script>

<script>
(function() {
    const navLinks = document.querySelectorAll('.profile-sidebar .nav-link');
    const sections = document.querySelectorAll('.profile-section');

    function activateSection(targetId) {
        sections.forEach((section) => {
            section.classList.toggle('active', section.id === targetId);
        });

        navLinks.forEach((link) => {
            const linkTarget = link.getAttribute('href').replace('#', '');
            link.classList.toggle('active', linkTarget === targetId);
        });
    }

    navLinks.forEach((link) => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const targetId = this.getAttribute('href').replace('#', '');
            activateSection(targetId);
        });
    });

    const initialTarget = window.location.hash ? window.location.hash.replace('#', '') : 'personal';
    const hasTarget = Array.from(sections).some((section) => section.id === initialTarget);
    activateSection(hasTarget ? initialTarget : 'personal');
})();
</script>