<?php
class ModelAccountReview extends Model {
    public $review = array(1,2,3,4,5);

    public function getReviews($start = 0, $limit = 20,$customer_id = 0) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 20;
        }


       // $sql = "SELECT prl.like_review,prl.dislike_review, r.review_id, r.author, r.rating, r.text, p.product_id, pd.name, p.price, p.image, r.date_added,r.customer_id FROM " . DB_PREFIX . "review r WHERE  p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND r.customer_id='".$customer_id."' GROUP BY p.product_id ORDER BY r.date_added DESC  LIMIT " . (int)$start . "," . (int)$limit;
        $sql = "SELECT * FROM " . DB_PREFIX . "review r WHERE r.status = '1' AND r.customer_id='".$customer_id."' GROUP BY r.product_id ORDER BY r.date_added DESC  LIMIT " . (int)$start . "," . (int)$limit;
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getItems($product_id,$customer_id){
        $sql = "SELECT prl.like_review,prl.dislike_review, r.review_id, r.author, r.rating, r.text, p.product_id, pd.name, p.price, p.image, r.date_added,r.customer_id FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "p_review_like_or_dislike prl ON (r.review_id = prl.p_review_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE  p.date_available <= NOW() AND r.product_id='".$product_id."'  AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";


        $query = $this->db->query($sql);

        return $query->rows;
    }
    public function getTotalReviews($customer_id = 0) {

        $query = $this->db->query("SELECT review_id  FROM " . DB_PREFIX . "review WHERE status = '1' AND customer_id='".$customer_id."' GROUP BY product_id");


        return count($query->rows);
    }
}