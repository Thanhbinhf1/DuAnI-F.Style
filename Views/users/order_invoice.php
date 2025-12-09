<?php if (!empty($order_details)): ?>
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="reviewModalLabel">Đánh giá đơn hàng vừa mua</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <form action="?ctrl=product&act=submitMultiReviews" method="POST">
                <input type="hidden" name="order_id" value="<?= $_GET['id'] ?? 0 ?>">

                <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                    <p class="text-muted text-center mb-4">Hãy chia sẻ cảm nhận của bạn để nhận ưu đãi cho lần mua tới
                        nhé!</p>

                    <?php foreach ($order_details as $item): 
                  $img = $item['image'];
                  if (!str_contains($img, 'http')) $img = "./Public/Uploads/Products/" . $img;
              ?>
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="row g-0 align-items-center p-2">
                            <div class="col-md-2 text-center">
                                <img src="<?= $img ?>" class="img-fluid rounded" style="max-height: 80px;"
                                    alt="<?= htmlspecialchars($item['name']) ?>">
                            </div>
                            <div class="col-md-10">
                                <div class="card-body py-2">
                                    <h6 class="card-title mb-1"><?= htmlspecialchars($item['name']) ?></h6>

                                    <div class="rating-group mb-2">
                                        <label class="me-2 text-small">Chấm điểm:</label>
                                        <select name="reviews[<?= $item['product_id'] ?>][rating]"
                                            class="form-select form-select-sm d-inline-block w-auto">
                                            <option value="5">⭐⭐⭐⭐⭐ (Tuyệt vời)</option>
                                            <option value="4">⭐⭐⭐⭐ (Tốt)</option>
                                            <option value="3">⭐⭐⭐ (Bình thường)</option>
                                            <option value="2">⭐⭐ (Tệ)</option>
                                            <option value="1">⭐ (Rất tệ)</option>
                                        </select>
                                    </div>

                                    <textarea name="reviews[<?= $item['product_id'] ?>][content]"
                                        class="form-control form-control-sm" rows="2"
                                        placeholder="Sản phẩm thế nào? Chất lượng ra sao?"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Để sau</button>
                    <button type="submit" class="btn btn-primary" style="background-color: #f59e0b; border:none;">Gửi
                        đánh giá</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra xem Modal có tồn tại không
    var reviewModalEl = document.getElementById('reviewModal');
    if (reviewModalEl) {
        var myModal = new bootstrap.Modal(reviewModalEl);
        myModal.show();
    }
});
</script>
<?php endif; ?>