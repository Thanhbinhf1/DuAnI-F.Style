<?php
class Favorite
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Check if a user has favorited a product.
     *
     * @param int $userId
     * @param int $productId
     * @return bool
     */
    public function isFavorite($userId, $productId)
    {
        $sql = "SELECT id FROM favorites WHERE user_id = ? AND product_id = ?";
        $result = $this->db->queryOne($sql, [$userId, $productId]);
        // queryOne returns an array on success, false on no rows, and null on error.
        // We just need to know if we got a valid row back.
        return is_array($result);
    }

    /**
     * Add a product to a user's favorites.
     *
     * @param int $userId
     * @param int $productId
     * @return bool
     */
    public function add($userId, $productId)
    {
        // First, check if it's already favorited to avoid database errors
        if ($this->isFavorite($userId, $productId)) {
            return true;
        }
        $sql = "INSERT INTO favorites (user_id, product_id) VALUES (?, ?)";
        return $this->db->execute($sql, [$userId, $productId]);
    }

    /**
     * Remove a product from a user's favorites.
     *
     * @param int $userId
     * @param int $productId
     * @return bool
     */
    public function remove($userId, $productId)
    {
        $sql = "DELETE FROM favorites WHERE user_id = ? AND product_id = ?";
        return $this->db->execute($sql, [$userId, $productId]);
    }

    /**
     * Get all favorite product IDs for a user.
     *
     * @param int $userId
     * @return array
     */
    public function getFavoriteProductIds($userId)
    {
        $sql = "SELECT product_id FROM favorites WHERE user_id = ?";
        $results = $this->db->query($sql, [$userId]);
        
        $productIds = [];
        foreach ($results as $row) {
            $productIds[] = $row['product_id'];
        }
        return $productIds;
    }
}
