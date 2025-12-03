<?php
    $fullName = htmlspecialchars($user['fullname'] ?? $user['username']);
    $email    = htmlspecialchars($user['email'] ?? 'Chưa cập nhật');
    $phone    = htmlspecialchars($user['phone'] ?? 'Chưa cập nhật');
    $address  = htmlspecialchars($user['address'] ?? 'Chưa cập nhật');

    $orderCount      = count($orders);
    $pendingCount    = 0;
    $shippingCount   = 0;
    $completedCount  = 0;

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
                        <a class="nav-link" href="#coupons">Mã giảm giá & Điểm</a>
                        <a class="nav-link" href="#invoices">Hóa đơn</a>
                        <a class="nav-link" href="#security">Bảo mật</a>
                        <a class="nav-link" href="#settings">Cài đặt</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="profile-body">
        <div class="profile-content" id="profile-content">
            <section class="profile-section" data-section="overview">
                <div class="section-header">
                    <div>
                        <p class="eyebrow">Hồ sơ</p>
                        <h2>Thông tin cá nhân</h2>
                    </div>
                </div>
                <div class="info-grid">
                    <div class="avatar-block">
                        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Avatar" class="avatar">
                        <div class="avatar-meta">
                            <h3><?= htmlspecialchars($user['fullname'] ?? 'Khách hàng') ?></h3>
                            <p><?= htmlspecialchars($user['email'] ?? 'Chưa cập nhật email') ?></p>
                        </div>
                        <a class="ghost-btn" href="?ctrl=user&act=edit">Chỉnh sửa thông tin</a>
                    </div>
                    <div class="info-columns">
                        <div class="info-box">
                            <h4>Cơ bản</h4>
                            <dl>
                                <div><dt>Họ và tên</dt><dd><?= htmlspecialchars($user['fullname'] ?? 'Chưa cập nhật') ?></dd></div>
                                <div><dt>Email</dt><dd><?= htmlspecialchars($user['email'] ?? 'Chưa cập nhật') ?></dd></div>
                                <div><dt>Số điện thoại</dt><dd><?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?></dd></div>
                                <div><dt>Ngày sinh</dt><dd><?= htmlspecialchars($user['birthday'] ?? 'Chưa cập nhật') ?></dd></div>
                                <div><dt>Giới tính</dt><dd><?= htmlspecialchars($user['gender'] ?? 'Chưa cập nhật') ?></dd></div>
                                <div><dt>Mã khách hàng</dt><dd><?= htmlspecialchars($user['username'] ?? '#') ?></dd></div>
                            </dl>
                        </div>
                        <div class="info-box">
                            <h4>Địa chỉ</h4>
                            <?php if (count($addresses) > 0): ?>
                                <ul class="address-list">
                                    <?php foreach ($addresses as $index => $address): ?>
                                        <li>
                                            <div>
                                                <p class="address-title">Địa chỉ <?= $index + 1 ?> <?= !empty($address['is_default']) ? '(Mặc định)' : '' ?></p>
                                                <p><?= htmlspecialchars($address['line1'] ?? '') ?></p>
                                                <p><?= htmlspecialchars($address['line2'] ?? '') ?></p>
                                                <p><?= htmlspecialchars($address['city'] ?? '') ?> <?= htmlspecialchars($address['district'] ?? '') ?></p>
                                                <p><?= htmlspecialchars($address['phone'] ?? '') ?></p>
                                            </div>
                                            <div class="chip-row">
                                                <?php if (!empty($address['tag'])): ?><span class="chip"><?= htmlspecialchars($address['tag']) ?></span><?php endif; ?>
                                                <?php if (!empty($address['is_default'])): ?><span class="chip chip--primary">Mặc định</span><?php endif; ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="muted">Chưa có địa chỉ nào, hãy thêm địa chỉ giao hàng mặc định.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <section class="profile-section" data-section="orders" hidden>
                <div class="section-header">
                    <div>
                        <p class="eyebrow">Lịch sử mua</p>
                        <h2>Đơn hàng của tôi</h2>
                    </div>
                    <div class="pill-group">
                        <span class="pill">Tổng: <?= count($orders) ?></span>
                    </div>
                </div>
                <?php if (count($orders) > 0): ?>
                    <div class="table-responsive">
                        <table class="flat-table">
                            <thead>
                                <tr>
                                    <th>Mã ĐH</th>
                                    <th>Ngày đặt</th>
                                    <th>Người nhận</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $dh): ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($dh['id']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($dh['created_at'])) ?></td>
                                        <td><?= htmlspecialchars($dh['fullname']) ?></td>
                                        <td class="price"><?= number_format($dh['total_money']) ?> đ</td>
                                        <td>
                                            <?php if ($dh['status'] == 0): ?>
                                                <span class="status status--warning">Chờ xử lý</span>
                                            <?php elseif ($dh['status'] == 1): ?>
                                                <span class="status status--info">Đang giao</span>
                                            <?php elseif ($dh['status'] == 2): ?>
                                                <span class="status status--success">Đã giao</span>
                                            <?php else: ?>
                                                <span class="status status--danger">Đã hủy</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="action-cell">
                                            <a class="link" href="#">Theo dõi</a>
                                            <?php if ($dh['status'] == 0): ?>
                                                <a class="link link--danger" href="#">Hủy</a>
                                            <?php endif; ?>
                                            <?php if ($dh['status'] == 2): ?>
                                                <a class="link" href="#">Mua lại</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="muted">Bạn chưa có đơn hàng nào.</p>
                <?php endif; ?>
            </section>

            <section class="profile-section" data-section="wishlist" hidden>
                <div class="section-header">
                    <div>
                        <p class="eyebrow">Yêu thích</p>
                        <h2>Danh sách yêu thích</h2>
                    </div>
                </div>
                <?php if (count($wishlist) > 0): ?>
                    <div class="card-grid">
                        <?php foreach ($wishlist as $item): ?>
                            <article class="product-card">
                                <img src="<?= htmlspecialchars($item['image'] ?? 'https://via.placeholder.com/80x80') ?>" alt="<?= htmlspecialchars($item['name'] ?? 'Sản phẩm') ?>">
                                <div>
                                    <h4><?= htmlspecialchars($item['name'] ?? 'Sản phẩm') ?></h4>
                                    <p class="muted">Mã: <?= htmlspecialchars($item['sku'] ?? '#') ?></p>
                                    <div class="price-row">
                                        <span class="price"><?= isset($item['price']) ? number_format($item['price']) . ' đ' : 'Giá liên hệ' ?></span>
                                        <div class="action-links">
                                            <a class="link" href="#">Thêm vào giỏ</a>
                                            <a class="link link--danger" href="#">Xóa</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="muted">Chưa có sản phẩm yêu thích. Hãy lưu lại để mua nhanh.</p>
                <?php endif; ?>
            </section>

            <section class="profile-section" data-section="saved-carts" hidden>
                <div class="section-header">
                    <div>
                        <p class="eyebrow">Giỏ hàng</p>
                        <h2>Giỏ đã lưu / Giỏ bỏ quên</h2>
                    </div>
                </div>
                <?php if (count($savedCarts) > 0): ?>
                    <ul class="list-block">
                        <?php foreach ($savedCarts as $cart): ?>
                            <li>
                                <div>
                                    <p class="address-title">Giỏ lưu ngày <?= htmlspecialchars($cart['saved_at'] ?? '') ?></p>
                                    <p><?= htmlspecialchars($cart['note'] ?? 'Không có ghi chú') ?></p>
                                </div>
                                <div class="action-links">
                                    <a class="link" href="#">Thanh toán ngay</a>
                                    <a class="link link--danger" href="#">Xóa</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="muted">Chưa có giỏ hàng nào được lưu.</p>
                <?php endif; ?>
            </section>

            <section class="profile-section" data-section="payments" hidden>
                <div class="section-header">
                    <div>
                        <p class="eyebrow">Thanh toán</p>
                        <h2>Phương thức thanh toán</h2>
                    </div>
                </div>
                <ul class="chip-list">
                    <li class="chip chip--primary">COD</li>
                    <li class="chip">Momo</li>
                    <li class="chip">VNPay</li>
                    <li class="chip">ZaloPay</li>
                </ul>
                <p class="muted">Có thể lưu token thẻ an toàn nếu hệ thống hỗ trợ.</p>
            </section>

            <section class="profile-section" data-section="reviews" hidden>
                <div class="section-header">
                    <div>
                        <p class="eyebrow">Đánh giá</p>
                        <h2>Đánh giá của tôi</h2>
                    </div>
                </div>
                <?php if (count($reviews) > 0): ?>
                    <ul class="list-block">
                        <?php foreach ($reviews as $review): ?>
                            <li>
                                <div>
                                    <p class="address-title"><?= htmlspecialchars($review['product'] ?? 'Sản phẩm') ?></p>
                                    <p><?= htmlspecialchars($review['content'] ?? '') ?></p>
                                </div>
                                <div class="action-links">
                                    <a class="link" href="#">Sửa</a>
                                    <a class="link link--danger" href="#">Xóa</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="muted">Bạn chưa có đánh giá nào.</p>
                <?php endif; ?>
            </section>

         <div class="col-lg-9">
                <div class="profile-card p-4 mb-4" id="personal">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="section-title mb-1">Thông tin cá nhân</div>
                            <p class="text-muted mb-0">Quản lý hồ sơ và địa chỉ nhận hàng</p>
                        </div>
                        <a href="?ctrl=user&act=edit" class="btn btn-dark btn-sm">Chỉnh sửa</a>
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
                </div>

                <div class="profile-card p-4 mb-4" id="orders">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="section-title mb-1">Đơn hàng của tôi</div>
                            <p class="text-muted mb-0">Theo dõi trạng thái, hủy hoặc mua lại đơn hàng</p>
                        </div>
                        <a href="?ctrl=cart&act=view" class="btn btn-outline-dark btn-sm">Tiếp tục mua sắm</a>
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
                                                    <button type="button" class="btn btn-link btn-sm text-decoration-none">Theo dõi</button>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" <?=($dh['status'] > 0 ? 'disabled' : '')?>>Hủy</button>
                                                    <button type="button" class="btn btn-dark btn-sm">Mua lại</button>
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
                            <a href="?ctrl=product&act=list" class="btn btn-dark">Mua sắm ngay</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="profile-card p-4 mb-4" id="wishlist">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="section-title mb-1">Yêu thích</div>
                            <p class="text-muted mb-0">Lưu lại sản phẩm bạn muốn mua sau</p>
                        </div>
                        <a href="?ctrl=product&act=list" class="btn btn-outline-dark btn-sm">Tiếp tục xem sản phẩm</a>
                    </div>
                    <div class="empty-state d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-semibold">Danh sách yêu thích trống</div>
                            <p class="text-muted mb-0">Thêm sản phẩm để dễ dàng mua lại.</p>
                        </div>
                        <button class="btn btn-dark">Thêm vào giỏ</button>
                    </div>
                </div>

                <div class="profile-card p-4 mb-4" id="saved-cart">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="section-title mb-1">Giỏ hàng đã lưu / bỏ quên</div>
                            <p class="text-muted mb-0">Khôi phục các sản phẩm bạn đã thêm nhưng chưa thanh toán</p>
                        </div>
                        <a href="?ctrl=cart&act=view" class="btn btn-outline-dark btn-sm">Đi tới giỏ hàng</a>
                    </div>
                    <div class="empty-state">
                        <div class="fw-semibold">Chưa có sản phẩm được lưu</div>
                        <p class="text-muted mb-0">Chúng tôi sẽ nhắc bạn nếu giỏ hàng bị bỏ quên quá lâu.</p>
                    </div>
                </div>

                <div class="profile-card p-4 mb-4" id="payments">
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
                                <div class="fw-semibold mb-2">Ví điện tử & Thẻ</div>
                                <p class="text-muted small mb-2">Hỗ trợ Momo, VNPay, ZaloPay. Thẻ được lưu dưới dạng token an toàn.</p>
                                <div class="d-flex gap-2 flex-wrap">
                                    <span class="chip"><i class="fa-solid fa-wallet"></i> Momo</span>
                                    <span class="chip"><i class="fa-solid fa-qrcode"></i> VNPay</span>
                                    <span class="chip"><i class="fa-regular fa-credit-card"></i> ZaloPay</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-card p-4 mb-4" id="reviews">
                    <div class="section-title mb-2">Đánh giá của tôi</div>
                    <p class="text-muted mb-3">Xem lịch sử đánh giá, sửa hoặc xóa đánh giá đã gửi.</p>
                    <div class="empty-state">
                        <div class="fw-semibold">Bạn chưa có đánh giá nào</div>
                        <p class="text-muted mb-0">Hãy mua sản phẩm và chia sẻ trải nghiệm của bạn.</p>
                    </div>
                </div>

                <div class="profile-card p-4 mb-4" id="coupons">
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
                </div>

                <div class="profile-card p-4 mb-4" id="invoices">
                    <div class="section-title mb-2">Hóa đơn / Invoice</div>
                    <p class="text-muted mb-3">Xuất file PDF hóa đơn và xem lịch sử giao dịch thanh toán.</p>
                    <div class="empty-state">
                        <div class="fw-semibold">Chưa có hóa đơn</div>
                        <p class="text-muted mb-0">Bạn sẽ thấy hóa đơn khi hoàn tất đơn hàng.</p>
                    </div>
                </div>

                <div class="profile-card p-4 mb-4" id="security">
                    <div class="section-title mb-2">Bảo mật tài khoản</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="fw-semibold mb-1">Đổi mật khẩu</div>
                                <p class="text-muted small mb-3">Cập nhật mật khẩu định kỳ để bảo vệ tài khoản.</p>
                                <a class="btn btn-outline-dark btn-sm" href="?ctrl=user&act=edit#password">Đổi mật khẩu</a>
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
                </div>

                <div class="profile-card p-4" id="settings">
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
