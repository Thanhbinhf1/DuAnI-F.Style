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
}

.review-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #ddd;
    cursor: pointer;
}

.product-thumb {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
    margin-right: 10px;
}
</style>

<div class="main-content">
    <h2 class="mb-4">Quản lý Đánh giá & Bình luận</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Khách hàng</th>
                            <th width="120">Đánh giá</th>
                            <th width="35%">Nội dung</th>
                            <th>Ngày gửi</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($comments)): ?>
                        <?php foreach ($comments as $c): 
    // Xử lý ảnh sản phẩm
    $pImgRaw = $c['product_image'];
    if (!empty($pImgRaw)) {
        // Nếu trong DB đã có sẵn chữ 'Public' hoặc 'http' thì giữ nguyên, ngược lại thêm đường dẫn
        if (strpos($pImgRaw, 'Public') === false && strpos($pImgRaw, 'http') === false) {
            $pImg = "./Public/Uploads/Products/" . $pImgRaw;
        } else {
            $pImg = $pImgRaw;
        }
    } else {
        $pImg = "https://via.placeholder.com/50"; // Ảnh mặc định nếu không có
    }

    // Xử lý ảnh đánh giá (tương tự)
    $cImgRaw = $c['image'];
    $cImg = "";
    if (!empty($cImgRaw)) {
        if (strpos($cImgRaw, 'Public') === false && strpos($cImgRaw, 'http') === false) {
            $cImg = "./Public/Uploads/Comments/" . $cImgRaw;
        } else {
            $cImg = $cImgRaw;
        }
    }
?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?=$pImg?>" class="product-thumb">
                                    <span class="fw-bold small text-truncate"
                                        style="max-width: 150px;"><?=$c['product_name']?></span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold"><?=$c['fullname']?></div>
                            </td>
                            <td>
                                <div class="star-rating">
                                    <?php for($i=1; $i<=5; $i++) echo ($i <= $c['rating']) ? '★' : '☆'; ?>
                                </div>
                            </td>
                            <td>
                                <div class="comment-content mb-2">
                                    "<?= htmlspecialchars($c['content']) ?>"
                                </div>
                                <?php if($cImg): ?>
                                <img src="<?=$cImg?>" class="review-img" onclick="window.open(this.src)">
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($c['date'])) ?></td>
                            <td>
                                <a href="?ctrl=admin&act=commentDelete&id=<?=$c['id']?>"
                                    onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')"
                                    class="btn btn-outline-danger btn-sm" title="Xóa">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">Chưa có đánh giá nào.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once 'Views/admin/layout_footer.php'; ?>