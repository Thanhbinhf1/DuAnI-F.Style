<div class="container" style="margin-top: 30px;">
    <div class="product-detail-container" style="display: flex; gap: 40px;">
        
        <div class="left-column" style="width: 40%;">
            <img src="<?=$sp['image']?>" alt="<?=$sp['name']?>" style="width: 100%; border: 1px solid #eee; border-radius: 8px;">
        </div>

        <div class="right-column" style="width: 60%;">
            <h1 style="font-size: 24px; color: #333;"><?=$sp['name']?></h1>
            <p>Thương hiệu: <b><?=$sp['brand'] ?? 'F.Style'?></b> | Mã SP: <?=$sp['sku_code'] ?? 'N/A'?></p>
            
            <p id="display-price" class="price" style="font-size: 28px; color: red; font-weight: bold; margin: 20px 0;">
                <?=number_format($sp['price'])?> đ
            </p>

            <form action="?ctrl=cart&act=add" method="post">
                <input type="hidden" name="id" value="<?=$sp['id']?>">
                <input type="hidden" name="variant_id" id="selected_variant_id" required>

                <div style="margin-bottom: 15px;">
                    <label style="font-weight: bold;">Màu sắc:</label> <br>
                    <div id="color-options" style="display: flex; gap: 10px; margin-top: 5px;">
                        </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="font-weight: bold;">Kích thước:</label> <br>
                    <div id="size-options" style="display: flex; gap: 10px; margin-top: 5px;">
                        <span style="color: #999;">Vui lòng chọn màu trước</span>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label>Số lượng:</label>
                    <input type="number" name="quantity" value="1" min="1" style="padding: 5px; width: 60px;">
                    <span id="stock-info" style="margin-left: 10px; color: #666;"></span>
                </div>

                <button type="submit" id="btn-add-cart" disabled style="background: #ccc; color: white; padding: 12px 30px; border: none; font-size: 16px; cursor: not-allowed; border-radius: 4px;">
                    VUI LÒNG CHỌN PHÂN LOẠI
                </button>
            </form>

            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
            
            <div class="description" style="color: #666; line-height: 1.6;">
                <b>Mô tả sản phẩm:</b><br>
                <?= !empty($sp['description']) ? $sp['description'] : "Đang cập nhật mô tả..." ?>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Nhận dữ liệu từ PHP sang JS
    const variants = <?= json_encode($variants) ?>; 
    const colorContainer = document.getElementById('color-options');
    const sizeContainer = document.getElementById('size-options');
    const priceDisplay = document.getElementById('display-price');
    const stockDisplay = document.getElementById('stock-info');
    const variantInput = document.getElementById('selected_variant_id');
    const btnAdd = document.getElementById('btn-add-cart');

    // 2. Lọc ra các màu duy nhất để hiển thị
    const uniqueColors = [...new Set(variants.map(v => v.color))];
    
    uniqueColors.forEach(color => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.innerText = color;
        btn.style.cssText = "padding: 5px 15px; border: 1px solid #ddd; background: white; cursor: pointer;";
        
        btn.onclick = function() {
            // Reset style các nút màu
            Array.from(colorContainer.children).forEach(b => {
                b.style.border = "1px solid #ddd"; 
                b.style.background = "white";
            });
            // Highlight nút đang chọn
            this.style.border = "2px solid #ff5722";
            
            // Hiện các size tương ứng với màu này
            showSizesForColor(color);
        };
        colorContainer.appendChild(btn);
    });

    function showSizesForColor(selectedColor) {
        sizeContainer.innerHTML = ""; // Xóa size cũ
        
        // Lọc các biến thể có màu đang chọn
        const availableVariants = variants.filter(v => v.color === selectedColor);

        availableVariants.forEach(variant => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.innerText = variant.size;
            btn.style.cssText = "padding: 5px 15px; border: 1px solid #ddd; background: white; cursor: pointer;";
            
            // Nếu hết hàng thì làm mờ
            if (variant.quantity <= 0) {
                btn.disabled = true;
                btn.style.opacity = "0.5";
                btn.style.cursor = "not-allowed";
                btn.title = "Hết hàng";
            } else {
                btn.onclick = function() {
                    // Reset style nút size
                    Array.from(sizeContainer.children).forEach(b => {
                        b.style.border = "1px solid #ddd"; 
                    });
                    this.style.border = "2px solid #ff5722";

                    // CẬP NHẬT THÔNG TIN KHI CHỌN XONG
                    updateProductInfo(variant);
                };
            }
            sizeContainer.appendChild(btn);
        });
    }

    function updateProductInfo(variant) {
        // Cập nhật giá tiền
        priceDisplay.innerText = new Intl.NumberFormat('vi-VN').format(variant.price) + ' đ';
        
        // Cập nhật tồn kho
        stockDisplay.innerText = `(Còn ${variant.quantity} sản phẩm)`;
        
        // Cập nhật ID biến thể vào form để gửi đi
        variantInput.value = variant.id;

        // Bật nút Mua hàng
        btnAdd.disabled = false;
        btnAdd.style.background = "#ff5722";
        btnAdd.style.cursor = "pointer";
        btnAdd.innerText = "🛒 THÊM VÀO GIỎ HÀNG";
    }
</script>