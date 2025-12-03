<?php
    $fullName = htmlspecialchars($user['fullname'] ?? $user['username']);
    $email    = htmlspecialchars($user['email'] ?? 'Chưa cập nhật');
    $phone    = htmlspecialchars($user['phone'] ?? 'Chưa cập nhật');
    $address  = htmlspecialchars($user['address'] ?? 'Chưa cập nhật');

    // Bảo vệ biến mảng tránh warning khi chưa có dữ liệu
    $orders      = $orders ?? [];
    $wishlist    = $wishlist ?? [];
    $savedCarts  = $savedCarts ?? [];
    $reviews     = $reviews ?? [];
    $invoices    = $invoices ?? [];

    $orderCount     = count($orders);
    $pendingCount   = 0;
    $shippingCount  = 0;
    $completedCount = 0;

    foreach ($orders as $item) {
        if ($item['status'] == 0) {
            $pendingCount++;
        } elseif ($item['status'] == 1) {
            $shippingCount++;
        } elseif ($item['status'] == 2) {
            $completedCount++;
        }
    }

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
    .profile-section { display: none; }
    .profile-section.active { display: block; }
</style>

<div class="profile-shell py-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="profile-card p-4 profile-sidebar">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" class="rounded-circle" alt="Avatar" style="width: 72px; height: 72px; object-fit: cover;">
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
                        <a class="nav-link" href="#wishlist">Yêu thích</a>
                        <a class="nav-link" href="#saved-cart">Giỏ hàng đã lưu</a>
                        <a class="nav-link" href="#payments">Phương thức thanh toán</a>
                        <a class="nav-link" href="#reviews">Đánh giá</a>
                        <a class="nav-link" href="#coupons">Mã giảm giá &amp; Điểm</a>
                        <a class="nav-link" href="#invoices">Hóa đơn</a>
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
                        <a href="?ctrl=user&amp;act=edit" class="btn btn-dark btn-sm">Chỉnh sửa</a>
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
                        <span class="chip"><i class="fa-regular fa-hourglass"></i> Chờ xác nhận: <?=$pendingCount?></span>
                        <span class="chip"><i class="fa-solid fa-truck"></i> Đang giao: <?=$shippingCount?></span>
                        <span class="chip"><i class="fa-solid fa-circle-check"></i> Hoàn thành: <?=$completedCount?></span>
                    </div>
                    <?php if ($orderCount > 0): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã ĐH</th>
                                        <th>Ngày đặt</th>
                                        <th>Người nhận</th>
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
                                            <td><?=htmlspecialchars($dh['fullname'])?></td>
                                            <td class="text-end text-danger fw-bold"><?=number_format($dh['total_money'])?> đ</td>
                                            <td class="text-center"><?=renderStatusBadge($dh['status'])?></td>
                                            <td class="text-end">
                                                <div class="d-flex gap-2 justify-content-end">
                                                    <a class="btn btn-link btn-sm text-decoration-none" href="?ctrl=order&amp;act=detail&id=<?=urlencode($dh['id'])?>">Theo dõi</a>
                                                    <a class="btn btn-outline-danger btn-sm <?=($dh['status'] > 0 ? 'disabled' : '')?>" href="?ctrl=order&amp;act=cancel&id=<?=urlencode($dh['id'])?>" <?=($dh['status'] > 0 ? 'aria-disabled="true"' : '')?> onclick="return confirm('Bạn muốn hủy đơn hàng này?');">Hủy</a>
                                                    <a class="btn btn-dark btn-sm" href="?ctrl=order&amp;act=reorder&id=<?=urlencode($dh['id'])?>">Mua lại</a>
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

                <section class="profile-card p-4 mb-4 profile-section" id="wishlist">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="section-title mb-1">Yêu thích</div>
                            <p class="text-muted mb-0">Lưu lại sản phẩm bạn muốn mua sau</p>
                        </div>
                        <a href="?ctrl=product&amp;act=list" class="btn btn-outline-dark btn-sm">Tiếp tục xem sản phẩm</a>
                    </div>
                    <?php if (!empty($wishlist)): ?>
                        <div class="row g-3">
                            <?php foreach ($wishlist as $item): ?>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100 d-flex gap-3">
                                        <img src="<?=htmlspecialchars($item['image'] ?? 'https://via.placeholder.com/80x80')?>" alt="<?=htmlspecialchars($item['name'] ?? 'Sản phẩm')?>" style="width:80px;height:80px;object-fit:cover;" class="rounded-3">
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold"><?=htmlspecialchars($item['name'] ?? 'Sản phẩm')?></div>
                                            <div class="text-muted small mb-2">Mã: <?=htmlspecialchars($item['sku'] ?? '#')?></div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-danger fw-bold"><?=isset($item['price']) ? number_format($item['price']).' đ' : 'Liên hệ'?></div>
                                                <div class="d-flex gap-2">
                                                    <a class="btn btn-outline-dark btn-sm" href="?ctrl=cart&amp;act=add&id=<?=urlencode($item['id'] ?? '')?>">Thêm vào giỏ</a>
                                                    <a class="btn btn-outline-danger btn-sm" href="?ctrl=wishlist&amp;act=remove&id=<?=urlencode($item['id'] ?? '')?>" onclick="return confirm('Xóa khỏi danh sách yêu thích?');">Xóa</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-semibold">Danh sách yêu thích trống</div>
                                <p class="text-muted mb-0">Thêm sản phẩm để dễ dàng mua lại.</p>
                            </div>
                            <a class="btn btn-dark" href="?ctrl=product&amp;act=list">Thêm vào giỏ</a>
                        </div>
                    <?php endif; ?>
                </section>

                <section class="profile-card p-4 mb-4 profile-section" id="saved-cart">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="section-title mb-1">Giỏ hàng đã lưu / bỏ quên</div>
                            <p class="text-muted mb-0">Khôi phục các sản phẩm bạn đã thêm nhưng chưa thanh toán</p>
                        </div>
                        <a href="?ctrl=cart&amp;act=view" class="btn btn-outline-dark btn-sm">Đi tới giỏ hàng</a>
                    </div>
                    <?php if (!empty($savedCarts)): ?>
                        <div class="list-group">
                            <?php foreach ($savedCarts as $cart): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold">Giỏ lưu ngày <?=htmlspecialchars($cart['saved_at'] ?? '')?></div>
                                        <div class="text-muted small"><?=htmlspecialchars($cart['note'] ?? 'Không có ghi chú')?></div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a class="btn btn-dark btn-sm" href="?ctrl=cart&amp;act=restore&id=<?=urlencode($cart['id'] ?? '')?>">Thanh toán ngay</a>
                                        <a class="btn btn-outline-danger btn-sm" href="?ctrl=cart&amp;act=removeSaved&id=<?=urlencode($cart['id'] ?? '')?>" onclick="return confirm('Xóa giỏ hàng đã lưu này?');">Xóa</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="fw-semibold">Chưa có sản phẩm được lưu</div>
                            <p class="text-muted mb-0">Chúng tôi sẽ nhắc bạn nếu giỏ hàng bị bỏ quên quá lâu.</p>
                        </div>
                    <?php endif; ?>
                </section>

                <section class="profile-card p-4 mb-4 profile-section" id="payments">
                    <div class="section-title mb-3">Phương thức thanh toán</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="fw-semibold">Thanh toán khi nhận hàng</div>
                                    <span class="badge bg-success">Mặc định</span>
                                </div>
                                <p class="text-muted mb-2">Phù hợp khi bạn muốn kiểm tra hàng trước.</p>
                                <button class="btn btn-outline-dark btn-sm">Cập nhật</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="fw-semibold mb-2">Ví điện tử &amp; Thẻ</div>
                                <p class="text-muted small mb-2">Hỗ trợ Momo, VNPay, ZaloPay. Thẻ được lưu dưới dạng token an toàn.</p>
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
                                        <div class="text-muted small mb-2"><?=htmlspecialchars($review['created_at'] ?? '')?></div>
                                        <div><?=htmlspecialchars($review['content'] ?? '')?></div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a class="btn btn-outline-dark btn-sm" href="?ctrl=review&amp;act=edit&id=<?=urlencode($review['id'] ?? '')?>">Sửa</a>
                                        <a class="btn btn-outline-danger btn-sm" href="?ctrl=review&amp;act=delete&id=<?=urlencode($review['id'] ?? '')?>" onclick="return confirm('Xóa đánh giá này?');">Xóa</a>
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

                <section class="profile-card p-4 mb-4 profile-section" id="invoices">
                    <div class="section-title mb-2">Hóa đơn / Invoice</div>
                    <p class="text-muted mb-3">Xuất file PDF hóa đơn và xem lịch sử giao dịch thanh toán.</p>
                    <?php if (!empty($invoices)): ?>
                        <div class="list-group">
                            <?php foreach ($invoices as $invoice): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold">Hóa đơn #<?=htmlspecialchars($invoice['code'] ?? '')?></div>
                                        <div class="text-muted small">Ngày: <?=htmlspecialchars($invoice['created_at'] ?? '')?></div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a class="btn btn-outline-dark btn-sm" href="?ctrl=invoice&amp;act=view&id=<?=urlencode($invoice['id'] ?? '')?>">Xem</a>
                                        <a class="btn btn-dark btn-sm" href="?ctrl=invoice&amp;act=download&id=<?=urlencode($invoice['id'] ?? '')?>">Tải PDF</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="fw-semibold">Chưa có hóa đơn</div>
                            <p class="text-muted mb-0">Bạn sẽ thấy hóa đơn khi hoàn tất đơn hàng.</p>
                        </div>
                    <?php endif; ?>
                </section>

                <section class="profile-card p-4 mb-4 profile-section" id="security">
                    <div class="section-title mb-2">Bảo mật tài khoản</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="fw-semibold mb-1">Đổi mật khẩu</div>
                                <p class="text-muted small mb-3">Cập nhật mật khẩu định kỳ để bảo vệ tài khoản.</p>
                                <a class="btn btn-outline-dark btn-sm" href="?ctrl=user&amp;act=edit#password">Đổi mật khẩu</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="fw-semibold mb-1">Phiên đăng nhập</div>
                                <p class="text-muted small mb-3">Kiểm tra thiết bị gần đây và đăng xuất khỏi tất cả thiết bị nếu cần.</p>
                                <button class="btn btn-outline-dark btn-sm" type="button">Đăng xuất tất cả thiết bị</button>
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
            link.addEventListener('click', function (event) {
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
