<div class="container" style="margin-top: 30px;">
    <div class="product-detail-container" style="display: flex; gap: 40px;">
        
        <div class="left-column" style="width: 40%;">
            <img src="<?=$sp['image']?>" alt="<?=$sp['name']?>" style="width: 100%; border: 1px solid #eee; border-radius: 8px;">
        </div>

        <div class="right-column" style="width: 60%;">
            <h1 style="font-size: 24px; color: #333;"><?=$sp['name']?></h1>
            
            <p class="price" style="font-size: 28px; color: red; font-weight: bold; margin: 20px 0;">
                <?=number_format($sp['price'])?> đ
            </p>

            <div class="description" style="margin-bottom: 30px; color: #666; line-height: 1.6;">
                <b>Mô tả sản phẩm:</b><br>
                <?= !empty($sp['description']) ? $sp['description'] : "Đang cập nhật mô tả..." ?>
            </div>

            <form action="?ctrl=cart&act=add" method="post">
                <input type="hidden" name="id" value="<?=$sp['id']?>">
                
                <div style="margin-bottom: 20px;">
                    <label>Số lượng:</label>
                    <input type="number" name="quantity" value="1" min="1" style="padding: 5px; width: 60px;">
                </div>

                <button type="submit" style="background: #ff5722; color: white; padding: 12px 30px; border: none; font-size: 16px; cursor: pointer; border-radius: 4px;">
                    🛒 THÊM VÀO GIỎ HÀNG
                </button>
            </form>
        </div>
    </div>

    <div class="related-products" style="margin-top: 50px; border-top: 1px solid #eee; padding-top: 20px;">
        <h3>Sản phẩm cùng loại</h3>
        <div style="display: flex; gap: 20px;">
            <?php foreach($spLienQuan as $item): ?>
                <div style="width: 23%; border: 1px solid #f0f0f0; padding: 10px;">
                    <a href="?ctrl=product&act=detail&id=<?=$item['id']?>" style="text-decoration: none; color: #333;">
                        <img src="<?=$item['image']?>" style="width: 100%; height: 200px; object-fit: cover;">
                        <p style="margin-top: 10px; font-weight: bold;"><?=$item['name']?></p>
                        <span style="color: red;"><?=number_format($item['price'])?> đ</span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>