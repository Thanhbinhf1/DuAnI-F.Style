<?php include_once 'Views/admin/layout_header.php'; ?>

<style>
.star-rating {
    color: #f59e0b;
    font-size: 14px;
}

.comment-content {
    font-style: italic;
    color: #555;
    background: #f9f9f9;
    padding: 8px;
    border-radius: 4px;
    border-left: 3px solid #ddd;
}

.review-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #ddd;
    cursor: pointer;
    margin-top: 5px;
    transition: transform 0.2s;
}

.review-img:hover {
    transform: scale(1.5);
    z-index: 10;
    position: relative;
}

.product-thumb {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
    margin-right: 10px;
    border: 1px solid #eee;
}
</style>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary"><i class="fas fa-comments"></i> Quản lý Đánh giá & Bình luận</h2>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Sản phẩm</th>
                            <th>Khách hàng</th>
                            <th width="120">Đánh giá</th>
                            <th width="40%">Nội dung</th>
                            <th>Ngày gửi</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $c): 
                                // 1. XỬ LÝ ẢNH SẢN PHẨM (Fix lỗi ảnh gãy)
                                $pImg = $c['product_image'];
                                if (!empty($pImg)) {
                                    // Nếu ảnh chưa có đường dẫn đầy đủ -> Thêm vào
                                    if (strpos($pImg, 'http') === false && strpos($pImg, 'Public') === false) {
                                        $pImg = "./Public/Uploads/Products/" . $pImg;
                                    }
                                } else {
                                    $pImg = "https://via.placeholder.com/50x50?text=No+Img"; // Ảnh mặc định
                                }

                                // 2. XỬ LÝ ẢNH BÌNH LUẬN (Nếu khách có up ảnh)
                                $cImg = $c['image'];
                                if (!empty($cImg)) {
                                    if (strpos($cImg, 'http') === false && strpos($cImg, 'Public') === false) {
                                        $cImg = "./Public/Uploads/Comments/" . $cImg;
                                    }
                                }
                            ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="<?= $pImg ?>" class="product-thumb" alt="Product">
                                    <span class="fw-bold small text-truncate text-dark" style="max-width: 180px;"
                                        title="<?= $c['product_name'] ?>">
                                        <?= $c['product_name'] ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-primary"><?= htmlspecialchars($c['fullname']) ?></div>
                            </td>
                            <td>
                                <div class="star-rating" title="<?= $c['rating'] ?> sao">
                                    <?php for($i=1; $i<=5; $i++) echo ($i <= $c['rating']) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star text-muted"></i>'; ?>
                                </div>
                            </td>
                            <td>
                                <?php if(!empty($c['content'])): ?>
                                <div class="comment-content mb-1">
                                    "<?= htmlspecialchars($c['content']) ?>"
                                </div>
                                <?php endif; ?>

                                <?php if(!empty($cImg)): ?>
                                <img src="<?= $cImg ?>" class="review-img" onclick="window.open(this.src)"
                                    title="Xem ảnh lớn">
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small">
                                <i class="far fa-clock me-1"></i><?= date('d/m/Y H:i', strtotime($c['date'])) ?>
                            </td>
                            <td class="text-center">
                                <a href="?ctrl=admin&act=commentDelete&id=<?= $c['id'] ?>"
                                    onclick="return confirm('Bạn có chắc muốn xóa đánh giá này không? Hành động này không thể hoàn tác!')"
                                    class="btn btn-outline-danger btn-sm" title="Xóa đánh giá">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="far fa-comment-dots fa-3x mb-3"></i>
                                <p>Hiện chưa có đánh giá nào.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once 'Views/admin/layout_footer.php'; ?>