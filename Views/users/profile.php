<?php
    $fullName = htmlspecialchars($user['fullname'] ?? $user['username']);
    $email    = htmlspecialchars($user['email'] ?? 'Chýa c?p nh?t');
    $phone    = htmlspecialchars($user['phone'] ?? 'Chýa c?p nh?t');
    $address  = htmlspecialchars($user['address'] ?? 'Chýa c?p nh?t');

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
    .profile-shell { background: linear-gradient(135deg, #fff7f0 0%, #ffffff 60%, #f1f5ff 100%); min-height: 100vh; }
    .profile-card { background: #fff; border: 1px solid #eef0f3; border-radius: 14px; box-shadow: 0 20px 60px rgba(15, 23, 42, 0.07); }
    .profile-sidebar .nav-link { color: #475569; font-weight: 600; border-radius: 12px; padding: 12px 14px; }
    .profile-sidebar .nav-link:hover, .profile-sidebar .nav-link.active { background: #111827; color: #fff; }
    .section-title { font-weight: 800; color: #111827; }
    .empty-state { border: 1px dashed #cbd5e1; border-radius: 12px; padding: 16px; background: #f8fafc; }
    .chip { background: #f1f5f9; border-radius: 999px; padding: 8px 14px; font-size: 13px; color: #0f172a; display: inline-flex; align-items: center; gap: 8px; }
    .profile-section { display: none; }
    .profile-section.active { display: block; }
    .status-stack .badge { display: block; margin-bottom: 4px; }
    .status-stack small { color: #475569; }
    .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-weight: 700; font-size: 12px; line-height: 1.2; }
    .bg-warning { background: #fef08a; color: #92400e; }
    .bg-info { background: #e0f2fe; color: #075985; }
    .bg-success { background: #bbf7d0; color: #065f46; }
    .bg-danger { background: #fecdd3; color: #9f1239; }
    .bg-secondary { background: #e2e8f0; color: #0f172a; }
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
                            <div class="text-muted small">Ðõn hàng</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-5 text-warning"><?=$pendingCount?></div>
                            <div class="text-muted small">Ch? xác nh?n</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-5 text-success"><?=$completedCount?></div>
                            <div class="text-muted small">Hoàn thành</div>
                        </div>
                    </div>
                    <div class="nav flex-column">
                        <a class="nav-link active" href="#personal">Thông tin cá nhân</a>
                        <a class="nav-link" href="#orders">Ðõn hàng c?a tôi</a>
                        <a class="nav-link" href="#payments">Phýõng th?c thanh toán</a>
                        <a class="nav-link" href="#reviews">Ðánh giá</a>
                        <a class="nav-link" href="#coupons">M? gi?m giá &amp; Ði?m</a>
                        <a class="nav-link" href="#security">B?o m?t</a>
                        <a class="nav-link" href="#settings">Cài ð?t</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <section class="profile-card p-4 mb-4 profile-section active" id="personal">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="section-title mb-1">Thông tin cá nhân</div>
                            <p class="text-muted mb-0">Qu?n l? h? sõ và ð?a ch? nh?n hàng</p>
                        </div>
                        <a href="?ctrl=user&amp;act=editProfile" class="btn btn-dark btn-sm">Ch?nh s?a</a>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6"><div class="chip"><i class="fa-regular fa-id-badge"></i> <?=$fullName?></div></div>
                        <div class="col-md-6"><div class="chip"><i class="fa-regular fa-envelope"></i> <?=$email?></div></div>
                        <div class="col-md-6"><div class="chip"><i class="fa-solid fa-phone"></i> <?=$phone?></div></div>
                        <div class="col-md-6"><div class="chip"><i class="fa-solid fa-location-dot"></i> <?=$address?></div></div>
                        <div class="col-md-6"><div class="chip"><i class="fa-regular fa-user"></i> M? khách hàng: #<?=$user['id']?></div></div>
                        <div class="col-md-6"><div class="chip"><i class="fa-brands fa-facebook"></i> Liên k?t MXH: Chýa k?t n?i</div></div>
                    </div>
                </section>

                <section class="profile-card p-4 mb-4 profile-section" id="orders">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="section-title mb-1">Ðõn hàng c?a tôi</div>
                            <p class="text-muted mb-0">Theo d?i tr?ng thái, h?y ho?c mua l?i ðõn hàng</p>
                        </div>
                        <a href="?ctrl=cart&amp;act=view" class="btn btn-outline-dark btn-sm">Ti?p t?c mua s?m</a>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="chip"><i class="fa-regular fa-hourglass"></i> Ch? xác nh?n: <?=$pendingCount?></span>
                        <span class="chip"><i class="fa-solid fa-truck"></i> Ðang giao: <?=$shippingCount?></span>
                        <span class="chip"><i class="fa-solid fa-circle-check"></i> Hoàn thành: <?=$completedCount?></span>
                        <span class="chip"><i class="fa-solid fa-ban"></i> Ð? h?y: <?=$cancelledCount?></span>
                    </div>
                    <?php
                        if (!function_exists('renderStatusBadge')) {
                            function renderStatusBadge($status)
                            {
                                $map = [
                                    0 => ['label' => 'Ch? xác nh?n', 'class' => 'bg-warning text-dark'],
                                    1 => ['label' => 'Ðang giao', 'class' => 'bg-info text-dark'],
                                    2 => ['label' => 'Hoàn thành', 'class' => 'bg-success'],
                                    3 => ['label' => 'Ð? h?y', 'class' => 'bg-danger'],
                                ];

                                $current = $map[$status] ?? ['label' => 'Không xác ð?nh', 'class' => 'bg-secondary'];
                                return '<span class="badge ' . $current['class'] . ' px-3 py-2">' . $current['label'] . '</span>';
                            }
                        }

                        if (!function_exists('renderPaymentBadge')) {
                            function renderPaymentBadge($orderStatus, $paymentStatus)
                            {
                                if ((int)$paymentStatus === 1) {
                                    return '<span class="badge bg-success px-3 py-2">Ð? thanh toán</span>';
                                }
                                if ((int)$paymentStatus === 2) {
                                    return '<span class="badge bg-warning text-dark px-3 py-2">Ð? hoàn ti?n</span>';
                                }
                                return '<span class="badge bg-secondary px-3 py-2">Chýa thanh toán</span>';
                            }
                        }
                    ?>
                    <?php if ($orderCount > 0): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>M? ÐH</th>
                                        <th>Ngày ð?t</th>
                                        <th class="text-end">T?ng ti?n</th>
                                        <th class="text-center">Tr?ng thái</th>
                                        
                                        <th class="text-end">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $dh): ?>
                                        <tr>
                                            <td class="fw-semibold">#<?=htmlspecialchars($dh['id'])?></td>
                                            <td><?=date('d/m/Y', strtotime($dh['created_at']))?></td>
                                            <td class="text-end text-danger fw-bold"><?=number_format($dh['total_money'])?> ð</td>
                                            <td class="text-center" >
                                                <div class="status-stack" style="
    display: flex;
    justify-content: center;
">
    <div class="mb-2">
        <small style="display:block; margin-bottom:3px; color:#666;">Ðõn hàng:</small>
        <?php 
            $stt = $dh['status'] ?? -1;
            // M?c ð?nh: Xám
            $txt = 'Không xác ð?nh';
            $bg  = '#e2e8f0'; 
            $col = '#333'; 

            if ($stt == 0) { 
                $txt = 'Ch? xác nh?n'; $bg = '#fff3cd'; $col = '#856404'; 
            } elseif ($stt == 1) { 
                $txt = 'Ðang giao';    $bg = '#cff4fc'; $col = '#055160'; 
            } elseif ($stt == 2) { 
                $txt = 'Hoàn thành';   $bg = '#d1e7dd'; $col = '#0f5132'; 
            } elseif ($stt == 3) { 
                $txt = 'Ð? h?y';       $bg = '#f8d7da'; $col = '#842029'; 
            }
        ?>
        <span style="display:inline-block; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: <?=$bg?>; color: <?=$col?>;">
            <?=$txt?>
        </span>
    </div>

    <div>
        <small style="display:block; margin-bottom:3px; color:#666;">Thanh toán:</small>
        <?php 
            $pay = $dh['payment_status'] ?? 0;
            // M?c ð?nh: Xám
            $txtPay = 'Chýa thanh toán';
            $bgPay  = '#e2e8f0'; 
            $colPay = '#333';

            if ($pay == 1) { 
                $txtPay = 'Ð? thanh toán'; $bgPay = '#d1e7dd'; $colPay = '#0f5132'; // Xanh lá
            } elseif ($pay == 2) { 
                $txtPay = 'Ð? hoàn ti?n';  $bgPay = '#fff3cd'; $colPay = '#856404'; // Vàng
            }
        ?>
        <span style="display:inline-block; padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; background-color: <?=$bgPay?>; color: <?=$colPay?>;">
            <?=$txtPay?>
        </span>
    </div>
</div>
                                            </td>
                                            
                                            <td class="text-end">
                                                <div class="d-flex gap-2 justify-content-end">
                                                    <a class="btn btn-link btn-sm text-decoration-none" href="?ctrl=order&amp;act=detail&id=<?=urlencode($dh['id'])?>">Theo d?i</a>
                                                    <a class="btn btn-dark btn-sm" href="?ctrl=order&amp;act=reorder&id=<?=urlencode($dh['id'])?>">Mua l?i</a>
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
                                <div class="fw-semibold">B?n chýa có ðõn hàng nào</div>
                                <p class="text-muted mb-0">Khám phá s?n ph?m và ð?t hàng ngay hôm nay.</p>
                            </div>
                            <a href="?ctrl=product&amp;act=list" class="btn btn-dark">Mua s?m ngay</a>
                        </div>
                    <?php endif; ?>
                </section>
<section class="profile-card p-4 mb-4 profile-section" id="payments">
                    <div class="section-title mb-3">Phýõng th?c thanh toán</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="fw-semibold">Thanh toán khi nh?n hàng (COD)</div>
                                    <span class="badge bg-success">M?c ð?nh</span>
                                </div>
                                <p class="text-muted mb-2">Ki?m tra hàng trý?c khi thanh toán.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="fw-semibold mb-2">Thanh toán online</div>
                                <p class="text-muted small mb-2">Ch?n ví / c?ng thanh toán khi ð?t hàng.</p>
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
                    <div class="section-title mb-2">Ðánh giá c?a tôi</div>
                    <p class="text-muted mb-3">Xem l?ch s? ðánh giá, s?a ho?c xóa ðánh giá ð? g?i.</p>
                    <?php if (!empty($reviews)): ?>
                        <div class="list-group">
                            <?php foreach ($reviews as $review): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-semibold"><?=htmlspecialchars($review['product'] ?? 'S?n ph?m')?></div>
                                        <div class="text-muted small mb-2"><?=htmlspecialchars($review['created_at'] ?? '')?></div>
                                        <div><?=htmlspecialchars($review['content'] ?? '')?></div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a class="btn btn-outline-dark btn-sm" href="?ctrl=review&amp;act=edit&id=<?=urlencode($review['id'] ?? '')?>">S?a</a>
                                        <a class="btn btn-outline-danger btn-sm" href="?ctrl=review&amp;act=delete&id=<?=urlencode($review['id'] ?? '')?>" onclick="return confirm('Xóa ðánh giá này?');">Xóa</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="fw-semibold">B?n chýa có ðánh giá nào</div>
                            <p class="text-muted mb-0">H?y mua s?n ph?m và chia s? tr?i nghi?m c?a b?n.</p>
                        </div>
                    <?php endif; ?>
                </section>

                <section class="profile-card p-4 mb-4 profile-section" id="coupons">
                    <div class="section-title mb-2">M? gi?m giá / Ví tích ði?m</div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted">M? gi?m giá</div>
                                <div class="fs-4 fw-bold">0</div>
                                <p class="text-muted small mb-0">Chýa có m? kh? d?ng</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted">Ði?m thý?ng</div>
                                <div class="fs-4 fw-bold text-success">0</div>
                                <p class="text-muted small mb-0">Tích ði?m qua m?i ðõn hàng</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted">L?ch s? s? d?ng</div>
                                <div class="fs-4 fw-bold">-</div>
                                <p class="text-muted small mb-0">Chýa có giao d?ch</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="profile-card p-4 mb-4 profile-section" id="security">
                    <div class="section-title mb-2">B?o m?t tài kho?n</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="fw-semibold mb-1">Ð?i m?t kh?u</div>
                                <p class="text-muted small mb-3">C?p nh?t m?t kh?u ð?nh k? ð? b?o v? tài kho?n.</p>
                                <a class="btn btn-outline-dark btn-sm" href="?ctrl=user&amp;act=edit#password">Ð?i m?t kh?u</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="fw-semibold mb-1">Phiên ðãng nh?p</div>
                                <p class="text-muted small mb-3">Ki?m tra thi?t b? g?n ðây và ðãng xu?t kh?i t?t c? thi?t b? n?u c?n.</p>
                                <button class="btn btn-outline-dark btn-sm" type="button">Ðãng xu?t t?t c? thi?t b?</button>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="profile-card p-4 profile-section" id="settings">
                    <div class="section-title mb-2">Cài ð?t khác</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ngôn ng?</label>
                            <select class="form-select">
                                <option>Ti?ng Vi?t</option>
                                <option>English</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nh?n thông báo</label>
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
                                    <label class="form-check-label">Thông báo ð?y</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">Xóa tài kho?n</div>
                                <p class="text-muted small mb-0">Hành ð?ng này không th? hoàn tác.</p>
                            </div>
                            <button class="btn btn-outline-danger">Yêu c?u xóa</button>
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

