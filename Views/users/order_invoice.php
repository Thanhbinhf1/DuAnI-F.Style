<?php
// Fallback: Nếu Controller quên truyền data thì tự lấy
if (empty($orderDetails) && isset($_GET['id'])) {
    include_once 'Models/Order.php';
    $orderModel = new Order();
    // Gọi hàm lấy chi tiết có kèm hình ảnh
    if (method_exists($orderModel, 'getOrderDetailsForReview')) {
        $orderDetails = $orderModel->getOrderDetailsForReview($_GET['id']);
    } else {
        $orderDetails = $orderModel->getOrderDetails($_GET['id']);
    }
}
?>

<div class="container" style="max-width: 900px; margin: 30px auto; padding: 0 15px;">
    <div
        style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; box-shadow: 0 8px 24px rgba(0,0,0,0.05);">

        <div
            style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px;">
            <div>
                <h2 style="margin: 0; color: #333;">Hóa đơn #<?= htmlspecialchars($order['id'] ?? $_GET['id']) ?></h2>
                <p style="margin: 5px 0 0; color: #777; font-size: 14px;">
                    Ngày đặt:
                    <?= isset($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : date('d/m/Y') ?>
                </p>
            </div>
            <button onclick="window.print()" class="btn btn-dark btn-sm">
                <i class="fas fa-print"></i> In hóa đơn
            </button>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h5 style="font-size: 16px; font-weight: 600; color: #555;">Người nhận</h5>
                <p style="margin: 3px 0;">
                    <strong><?= htmlspecialchars($order['fullname'] ?? $user['fullname']) ?></strong>
                </p>
                <p style="margin: 3px 0;"><?= htmlspecialchars($order['phone'] ?? $user['phone']) ?></p>
                <p style="margin: 3px 0; color: #666;"><?= htmlspecialchars($order['address'] ?? 'Tại cửa hàng') ?></p>
            </div>
            <div class="col-md-6 text-md-end">
                <h5 style="font-size: 16px; font-weight: 600; color: #555;">Thanh toán</h5>
                <p style="margin: 3px 0;"><?= htmlspecialchars($order['payment_method'] ?? 'COD') ?></p>
                <p style="margin: 3px 0; color: green; font-weight: bold;">Thành công</p>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Sản phẩm</th>
                    <th class="text-center">SL</th>
                    <th class="text-end">Đơn giá</th>
                    <th class="text-end">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderDetails as $item): 
                     $pName = $item['product_name'] ?? $item['name'];
                     $pPrice = $item['price'] ?? 0;
                     $pTotal = $pPrice * (int)$item['quantity'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($pName) ?></td>
                    <td class="text-center"><?= (int)$item['quantity'] ?></td>
                    <td class="text-end"><?= number_format($pPrice) ?> đ</td>
                    <td class="text-end fw-bold"><?= number_format($pTotal) ?> đ</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fs-5 fw-bold text-danger">TỔNG CỘNG</td>
                    <td class="text-end fs-5 fw-bold text-danger"><?= number_format($order['total_money'] ?? 0) ?> đ
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="text-center mt-4">
            <button type="button" class="btn btn-warning px-4 py-2 fw-bold" data-bs-toggle="modal"
                data-bs-target="#reviewModal">
                <i class="fas fa-star me-2"></i> Đánh giá sản phẩm
            </button>
        </div>
    </div>
</div>

<?php if (!empty($orderDetails)): ?>
<div class="modal fade" id="reviewModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold"><i class="fas fa-comments"></i> Đánh giá đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="?ctrl=product&act=submitMultiReviews" method="POST">
                <input type="hidden" name="order_id" value="<?= $_GET['id'] ?? 0 ?>">

                <div class="modal-body" style="max-height: 60vh; overflow-y: auto; background: #f8f9fa;">
                    <p class="text-center mb-4 text-muted">Ý kiến của bạn giúp chúng tôi phục vụ tốt hơn!</p>

                    <?php foreach ($orderDetails as $item): 
                  $pId = $item['product_id'];
                  $pName = $item['product_name'] ?? $item['name'];
                  
                  // --- XỬ LÝ ẢNH CHỐNG LỖI (FALLBACK) ---
                  $imgRaw = $item['image'] ?? '';
                  $imgSrc = ''; 
                  
                  if (!empty($imgRaw)) {
                      if (strpos($imgRaw, 'http') !== false) {
                          $imgSrc = $imgRaw;
                      } else {
                          $imgSrc = "./Public/Uploads/Products/" . $imgRaw;
                      }
                  }
                  // ---------------------------------------
              ?>
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="row g-0 align-items-center p-3">
                            <div class="col-2 text-center">
                                <?php if($imgSrc): ?>
                                <img src="<?= $imgSrc ?>" class="rounded border"
                                    style="width: 60px; height: 60px; object-fit: cover;"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <?php endif; ?>

                                <div class="rounded border bg-light d-flex align-items-center justify-content-center fw-bold text-secondary"
                                    style="width: 60px; height: 60px; font-size: 20px; display: <?= $imgSrc ? 'none' : 'flex' ?>; margin: 0 auto;">
                                    <?= substr($pName, 0, 1) ?>
                                </div>
                            </div>

                            <div class="col-10 ps-3">
                                <h6 class="fw-bold text-dark text-truncate mb-2"><?= htmlspecialchars($pName) ?></h6>

                                <div class="d-flex align-items-center mb-2">
                                    <span class="me-2 text-muted small">Chất lượng:</span>
                                    <select name="reviews[<?= $pId ?>][rating]"
                                        class="form-select form-select-sm w-auto border-warning text-warning fw-bold">
                                        <option value="5">⭐⭐⭐⭐⭐ (Tuyệt vời)</option>
                                        <option value="4">⭐⭐⭐⭐ (Hài lòng)</option>
                                        <option value="3">⭐⭐⭐ (Bình thường)</option>
                                        <option value="2">⭐⭐ (Tệ)</option>
                                        <option value="1">⭐ (Rất tệ)</option>
                                    </select>
                                </div>

                                <textarea name="reviews[<?= $pId ?>][content]"
                                    class="form-control form-control-sm bg-light" rows="2"
                                    placeholder="Hãy chia sẻ cảm nhận của bạn về sản phẩm này..."></textarea>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Để sau</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold">Gửi đánh giá</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var modalEl = document.getElementById('reviewModal');
        if (modalEl) {
            var myModal = new bootstrap.Modal(modalEl);
            myModal.show();
        }
    }, 800);
});
</script>
<?php endif; ?>