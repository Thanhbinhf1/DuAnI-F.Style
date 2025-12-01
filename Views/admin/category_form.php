<h1 style="color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; margin-bottom: 30px;">
    <?= isset($category) ? 'CẬP NHẬT DANH MỤC' : 'THÊM DANH MỤC MỚI' ?>
</h1>

<div style="max-width: 600px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
    <form action="?ctrl=admin&act=categoryPost" method="post">
        <input type="hidden" name="id" value="<?= $category['id'] ?? 0 ?>">

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: 600; margin-bottom: 5px;">Tên Danh Mục:</label>
            <input type="text" name="name" value="<?= $category['name'] ?? '' ?>" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: 600; margin-bottom: 5px;">Trạng thái:</label>
            <select name="status" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <option value="1" <?= (isset($category['status']) && $category['status'] == 1) ? 'selected' : '' ?>>1: Hiển thị</option>
                <option value="0" <?= (isset($category['status']) && $category['status'] == 0) ? 'selected' : '' ?>>0:  Ẩn</option>
            </select>
        </div>

        <button type="submit" style="padding: 10px 20px; background: #2c3e50; color: white; border: none; border-radius: 5px; cursor: pointer;">
            <?= isset($category) ? 'Cập Nhật' : 'Thêm Mới' ?>
        </button>
        <a href="?ctrl=admin&act=categoryList" style="margin-left: 10px; color: #7f8c8d;">Hủy</a>
    </form>
</div>