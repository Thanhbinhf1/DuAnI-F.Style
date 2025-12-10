<?php include_once 'Views/admin/layout_header.php'; ?>
<div class="main-content">
    <h2><?= isset($banner) ? 'Sửa Banner' : 'Thêm Banner' ?></h2>

    <form action="?ctrl=admin&act=bannerPost" method="post" enctype="multipart/form-data"
        class="bg-white p-4 rounded shadow-sm">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="id" value="<?= $banner['id'] ?? 0 ?>">
        <input type="hidden" name="image_current" value="<?= $banner['image'] ?? '' ?>">

        <div class="mb-3">
            <label class="form-label">Tiêu đề:</label>
            <input type="text" name="title" class="form-control" value="<?= $banner['title'] ?? '' ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Link liên kết (Tùy chọn):</label>
            <input type="text" name="link" class="form-control" value="<?= $banner['link'] ?? '' ?>"
                placeholder="VD: ?ctrl=product&act=detail&id=1">
        </div>

        <div class="mb-3">
            <label class="form-label">Hình ảnh:</label>
            <input type="file" name="image_file" class="form-control mb-2">
            <?php if (!empty($banner['image'])): ?>
            <img src="<?= $banner['image'] ?>" style="height: 100px;">
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Trạng thái:</label>
            <select name="status" class="form-select">
                <option value="1" <?= (isset($banner) && $banner['status'] == 1) ? 'selected' : '' ?>>Hiển thị</option>
                <option value="0" <?= (isset($banner) && $banner['status'] == 0) ? 'selected' : '' ?>>Ẩn</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Lưu Banner</button>
        <a href="?ctrl=admin&act=bannerList" class="btn btn-secondary">Hủy</a>
    </form>
</div>
<?php include_once 'Views/admin/layout_footer.php'; ?>