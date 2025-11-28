<div class="container" style="margin-top: 30px; margin-bottom: 50px;">
    
    <div class="category-title-bar">
        <span class="main-title"><?=$titleMain?></span>
        <span class="divider">/</span>
        <span class="sub-title"><?=$titleSub?></span>
    </div>

    <div class="product-list" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px;">

        <?php 
        if(isset($products) && count($products) > 0) {
            foreach ($products as $sp): 
                $link = "?ctrl=product&act=detail&id=" . $sp['id'];
                $img = !empty($sp['image']) ? $sp['image'] : 'https://via.placeholder.com/200';
        ?>
            <div class="product-item">
                <a href="<?=$link?>"><img src="<?=$img?>" alt="<?=$sp['name']?>"></a>
                <h3><a href="<?=$link?>"><?=$sp['name']?></a></h3>
                <p><?=number_format($sp['price'])?> đ</p>
                <a href="<?=$link?>"><button>Xem chi tiết</button></a>
            </div>
        <?php endforeach; 
        } else { 
            echo "<p style='grid-column: 1/-1; text-align: center; padding: 50px; background: #eee;'>Không tìm thấy sản phẩm nào trong mục này.</p>"; 
        }
        ?>
    </div>
</div>
