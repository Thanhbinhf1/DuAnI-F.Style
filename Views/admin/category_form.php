<?php
// Views/admin/category_form.php
$isEdit = isset($category);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.form-container {
    font-family: 'Segoe UI', sans-serif;
    color: #444;
    max-width: 600px;
    margin: 0 auto;
}

.page-header {
    border-bottom: 2px solid #1abc9c;
    padding-bottom: 15px;
    margin-bottom: 30px;
}

.page-title {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.card {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #555;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: 0.3s;
}

.form-control:focus {
    border-color: #1abc9c;
    outline: none;
    box-shadow: 0 0 5px rgba(26, 188, 156, 0.2);
}

.btn-submit {
    padding: 10px 25px;
    background: #1abc9c;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
}

.btn-submit:hover {
    background: #16a085;
}

.btn-cancel {
    margin-left: 10px;
    color: #7f8c8d;
    text-decoration: none;
    font-weight: 600;
}

.btn-cancel:hover {
    color: #2c3e50;
}

.error-msg {
    color: #e74c3c;
    font-size: 12px;
    margin-top: 5px;
    display: none;
}

.required {
    color: #e74c3c;
    margin-left: 3px;
}
</style>

<div class="form-container">
    <div class="page-header">
        <h1 class="page-title">
            <?= $isEdit ? '<i class="fas fa-edit"></i> CẬP NHẬT DANH MỤC' : '<i class="fas fa-plus-circle"></i> THÊM DANH MỤC MỚI' ?>
        </h1>
    </div>

    <div class="card">
        <form id="catForm" action="?ctrl=admin&act=categoryPost" method="post" onsubmit="return validateForm()">
            <input type="hidden" name="id"
                value="<?= isset($category['id']) ? htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8') : 0 ?>">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="form-group">
                <label class="form-label">Tên Danh Mục <span class="required">*</span></label>
                <input type="text" name="name" id="name" class="form-control"
                    value="<?= isset($category['name']) ? htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') : '' ?>"
                    placeholder="Ví dụ: Áo thun, Quần Jean...">
                <div id="error-name" class="error-msg">Vui lòng nhập tên danh mục!</div>
            </div>

            <div class="form-group">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="1" <?= (isset($category['status']) && $category['status'] == 1) ? 'selected' : '' ?>>
                        Hiển thị công khai
                    </option>
                    <option value="0" <?= (isset($category['status']) && $category['status'] == 0) ? 'selected' : '' ?>>
                        Tạm ẩn
                    </option>
                </select>
            </div>

            <div style="margin-top: 30px;">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> <?= $isEdit ? 'Lưu Thay Đổi' : 'Thêm Mới' ?>
                </button>
                <a href="?ctrl=admin&act=categoryList" class="btn-cancel">Quay lại</a>
            </div>
        </form>
    </div>
</div>

<script>
function validateForm() {
    const nameInput = document.getElementById('name');
    const errorMsg = document.getElementById('error-name');

    // Kiểm tra rỗng
    if (nameInput.value.trim() === "") {
        nameInput.style.borderColor = "#e74c3c";
        errorMsg.style.display = "block";
        nameInput.focus();
        return false;
    }

    // Reset lỗi nếu nhập đúng
    nameInput.style.borderColor = "#ddd";
    errorMsg.style.display = "none";
    return true;
}
</script>