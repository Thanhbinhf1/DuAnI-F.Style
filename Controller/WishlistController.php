<?php
class WishlistController {
    function __construct() {}

    /**
     * Wishlist feature has been removed. Redirect any direct access back home.
     */
    private function redirectDisabled(): void
    {
        echo "<script>alert('Tính năng yêu thích đã được gỡ bỏ.'); window.location='index.php';</script>";
    }

    // Thêm sản phẩm vào wishlist (đã tắt)
    function add() {
        $this->redirectDisabled();
    }

    // Xóa sản phẩm khỏi wishlist (đã tắt)
    function remove() {
        $this->redirectDisabled();
    }
}
?>
