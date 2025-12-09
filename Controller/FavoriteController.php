<?php
require_once 'Models/Favorite.php';

class FavoriteController {

    /**
     * Handles AJAX request to add or remove a product from user's favorites.
     */
    public function toggle() {
        // Set content type to JSON for AJAX response
        header('Content-Type: application/json');

        // User must be logged in to favorite an item
        if (!isset($_SESSION['user']['id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để sử dụng chức năng này.'
            ]);
            exit;
        }

        $userId = (int)$_SESSION['user']['id'];
        $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($productId <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Sản phẩm không hợp lệ.'
            ]);
            exit;
        }

        try {
            $favoriteModel = new Favorite();
            $isFavorited = $favoriteModel->isFavorite($userId, $productId);

            if ($isFavorited) {
                // Remove from favorites
                $favoriteModel->remove($userId, $productId);
                echo json_encode([
                    'success' => true,
                    'status' => 'removed',
                    'message' => 'Đã xoá khỏi danh sách yêu thích.'
                ]);
                exit;
            } else {
                // Add to favorites
                $favoriteModel->add($userId, $productId);
                echo json_encode([
                    'success' => true,
                    'status' => 'added',
                    'message' => 'Đã thêm vào danh sách yêu thích.'
                ]);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra. Vui lòng thử lại.'
            ]);
            exit;
        }
    }
}
