<div class="container" style="margin-top: 30px;">
    <div class="product-detail-container" style="display: flex; gap: 40px;">
        
        <div class="left-column" style="width: 40%;">
            <img src="<?=$sp['image']?>" alt="<?=$sp['name']?>" style="width: 100%; border: 1px solid #eee; border-radius: 8px;">
        </div>

        <div class="right-column" style="width: 60%;">
            <h1 style="font-size: 24px; color: #333;"><?=$sp['name']?></h1>
            <p style="color: #666; font-size: 14px; margin-top: 5px;">
                Thương hiệu: <b><?=$sp['brand'] ?? 'F.Style'?></b> <span style="margin: 0 10px;">|</span> 
                Mã SP: <b><?=$sp['sku_code'] ?? 'Chưa cập nhật'?></b>
            </p>
            
            <p id="display-price" class="price" style="font-size: 28px; color: red; font-weight: bold; margin: 20px 0;">
                <?=number_format($sp['price'])?> đ
            </p>

            <form action="?ctrl=cart&act=add" method="post">
                <input type="hidden" name="id" value="<?=$sp['id']?>">
                <input type="hidden" name="variant_id" id="selected_variant_id" value="">

                <?php if (!empty($variants)): ?>
                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: bold;">Màu sắc:</label>
                        <div id="color-options" class="variant-group"></div>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="font-weight: bold;">Kích thước:</label>
                        <div id="size-options" class="variant-group">
                            <span style="color: #999; font-style: italic;">(Vui lòng chọn màu trước)</span>
                        </div>
                    </div>
                <?php endif; ?>

                <div style="margin-bottom: 20px;">
                    <label>Số lượng:</label>
                    <input type="number" name="quantity" value="1" min="1" style="padding: 8px; width: 60px; border: 1px solid #ddd; border-radius: 4px; text-align: center;">
                    <span id="stock-info" style="margin-left: 10px; color: #666; font-size: 14px;"></span>
                </div>

                <button type="submit" id="btn-add-cart" 
                    <?= !empty($variants) ? 'disabled' : '' ?> 
                    style="background: <?= !empty($variants) ? '#ccc' : '#ff5722' ?>; 
                           color: white; padding: 15px 40px; border: none; font-size: 16px; font-weight: bold;
                           cursor: <?= !empty($variants) ? 'not-allowed' : 'pointer' ?>; 
                           border-radius: 5px; text-transform: uppercase; transition: 0.3s;">
                    <?= !empty($variants) ? 'Vui lòng chọn phân loại' : 'THÊM VÀO GIỎ HÀNG' ?>
                </button>
            </form>

            <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">
            
            <div class="description">
                <h3 style="font-size: 18px; margin-bottom: 10px;">Mô tả sản phẩm</h3>
                <div style="color: #555; line-height: 1.8;">
                    <?= !empty($sp['description']) ? nl2br($sp['description']) : "Thông tin sản phẩm đang được cập nhật..." ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const variants = <?= json_encode($variants) ?>; 
    
    if (variants.length > 0) {
        const colorContainer = document.getElementById('color-options');
        const sizeContainer = document.getElementById('size-options');
        const priceDisplay = document.getElementById('display-price');
        const stockDisplay = document.getElementById('stock-info');
        const variantInput = document.getElementById('selected_variant_id');
        const btnAdd = document.getElementById('btn-add-cart');

        const uniqueColors = [...new Set(variants.map(v => v.color))];
        
        // Tạo nút chọn Màu
        uniqueColors.forEach(color => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.innerText = color;
            btn.className = 'variant-btn'; // Dùng class CSS mới
            
            btn.onclick = function() {
                // Xóa active cũ
                Array.from(colorContainer.children).forEach(b => b.classList.remove('active'));
                // Active nút hiện tại
                this.classList.add('active');
                
                showSizesForColor(color);
            };
            colorContainer.appendChild(btn);
        });

        function showSizesForColor(selectedColor) {
            sizeContainer.innerHTML = ""; 
            const availableVariants = variants.filter(v => v.color === selectedColor);

            availableVariants.forEach(variant => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.innerText = variant.size;
                btn.className = 'variant-btn'; // Dùng class CSS mới
                
                if (variant.quantity <= 0) {
                    btn.disabled = true;
                    btn.title = "Hết hàng";
                } else {
                    btn.onclick = function() {
                        Array.from(sizeContainer.children).forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        updateProductInfo(variant);
                    };
                }
                sizeContainer.appendChild(btn);
            });
        }

        function updateProductInfo(variant) {
            priceDisplay.innerText = new Intl.NumberFormat('vi-VN').format(variant.price) + ' đ';
            stockDisplay.innerText = `(Còn ${variant.quantity} sản phẩm)`;
            variantInput.value = variant.id;
            
            btnAdd.disabled = false;
            btnAdd.style.background = "#ff5722";
            btnAdd.style.cursor = "pointer";
            btnAdd.innerText = "THÊM VÀO GIỎ HÀNG";
        }
    }
</script>