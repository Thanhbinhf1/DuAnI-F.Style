<?php include_once 'Views/admin/layout_header.php'; ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold"><i class="fas fa-images me-2"></i>Quản lý Banner</h2>
        <a href="?ctrl=admin&act=bannerForm" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Thêm Banner
        </a>
    </div>

    <div class="row g-4">
        <?php if(!empty($banners)): ?>
        <?php foreach ($banners as $b): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 banner-card">
                <div class="position-relative">
                    <img src="<?=$b['image']?>" class="card-img-top" style="height: 180px; object-fit: cover;">

                    <a href="?ctrl=admin&act=bannerToggle&id=<?=$b['id']?>&status=<?=$b['status']?>"
                        class="position-absolute top-0 end-0 m-2 badge <?=$b['status']?'bg-success':'bg-secondary'?> text-decoration-none shadow"
                        title="Bấm để thay đổi trạng thái">

                        <?php if($b['status']): ?>
                        <i class="fas fa-eye"></i> Đang hiện
                        <?php else: ?>
                        <i class="fas fa-eye-slash"></i> Đã ẩn
                        <?php endif; ?>

                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title fw-bold text-truncate"><?=$b['title']?></h5>
                    <?php if(!empty($b['link'])): ?>
                    <p class="card-text text-muted small mb-2"><i class="fas fa-link me-1"></i> <?=$b['link']?></p>
                    <?php else: ?>
                    <p class="card-text text-muted small mb-2"><i>(Không có liên kết)</i></p>
                    <?php endif; ?>
                </div>

                <div class="card-footer bg-white border-0 d-flex justify-content-end gap-2 pb-3">
                    <a href="?ctrl=admin&act=bannerForm&id=<?=$b['id']?>" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-edit"></i> Sửa
                    </a>
                    <a href="?ctrl=admin&act=bannerDelete&id=<?=$b['id']?>" onclick="return confirm('Xóa banner này?')"
                        class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-trash"></i> Xóa
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="col-12 text-center py-5 text-muted">
            <i class="fas fa-folder-open fa-3x mb-3"></i>
            <p>Chưa có banner nào. Hãy thêm mới ngay!</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Hiệu ứng hover cho card */
.banner-card {
    transition: transform 0.2s;
}

.banner-card:hover {
    transform: translateY(-5px);
}
</style>

<?php include_once 'Views/admin/layout_footer.php'; ?>