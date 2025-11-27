<h2 style="margin:20px;">🛍 Giỏ hàng</h2>

<?php if(!isset($_SESSION['cart']) || count($_SESSION['cart'])==0): ?>

<p style="margin:20px">Giỏ hàng trống!</p>

<?php else: ?>

<table border="1" cellpadding="10" width="80%" style="margin:20px;">
<tr>
    <th>Ảnh</th><th>Tên</th><th>Giá</th><th>Số lượng</th><th>Tổng</th><th>Xóa</th>
</tr>

<?php 
$sum=0;
foreach($_SESSION['cart'] as $id=>$item):
$total = $item['quantity'] * $item['price'];
$sum += $total;
?>

<tr>
<td><img src="Public/Images/<?= $item['image'] ?>" width="60"></td>
<td><?= $item['name'] ?></td>
<td><?= number_format($item['price']) ?>đ</td>

<td>
    <a href="index.php?ctrl=cart&act=decrease&id=<?= $id ?>">➖</a>
    <?= $item['quantity'] ?>
    <a href="index.php?ctrl=cart&act=addToCart&id=<?= $id ?>&name=<?= urlencode($item['name']) ?>&price=<?= $item['price'] ?>&img=<?= $item['image'] ?>">➕</a>
</td>

<td><?= number_format($total) ?>đ</td>
<td><a href="index.php?ctrl=cart&act=remove&id=<?= $id ?>">🗑 Xóa</a></td>
</tr>
<?php endforeach; ?>
</table>

<h3 style="margin-left:20px">Tổng tiền: <b style="color:red"><?= number_format($sum) ?>đ</b></h3>
<a style="margin-left:20px;color:red" href="index.php?ctrl=cart&act=clear">❌ Xóa tất cả</a>

<?php endif; ?>
