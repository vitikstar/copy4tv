<?php
class ModelAccountWishlist extends Model {
	public function addWishlist($product_id) {
        $wishlist = (isset($_COOKIE["wishlist"])) ? $_COOKIE["wishlist"] : '';
        $wishlist_arr = explode(',', $wishlist);
        $wishlist_arr_new = array_unique(array_merge($wishlist_arr, array(0=>$product_id)));
        $wishlist = implode(",",$wishlist_arr_new);
        setcookie('wishlist', $wishlist, time() + 60 * 60 * 24 * 30);

		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_wishlist WHERE customer_id = '" . (int)$this->customer->getId() . "' AND product_id = '" . (int)$product_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_wishlist SET customer_id = '" . (int)$this->customer->getId() . "', product_id = '" . (int)$product_id . "', date_added = NOW()");
	}

	public function deleteWishlist($product_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_wishlist WHERE customer_id = '" . (int)$this->customer->getId() . "' AND product_id = '" . (int)$product_id . "'");
        $wishlist = (isset($_COOKIE["wishlist"])) ? $_COOKIE["wishlist"] : '';
        $wishlist_arr = explode(',', $wishlist);
        $wishlist_arr_new = array_diff($wishlist_arr, array(0=>$product_id));
        $wishlist = implode(",",$wishlist_arr_new);
        setcookie('wishlist', $wishlist, time() + 60 * 60 * 24 * 30);
	}

	public function getWishlist() {
        $wishlist = array();
        if($this->customer->isLogged()){
            $query = $this->db->query("SELECT * FROM oc_customer_relation WHERE customer_id_main='" . $this->customer->getId() . "'");

            $relation_customer_id = array();
            $relation_customer_id[] = $this->customer->getId();
            foreach ($query->rows as $row) {
                foreach ($query->rows as $row) {
                    $relation_customer_id[] = $row['customer_id_child'];
                }
            }

            $in = implode(",", $relation_customer_id);

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_wishlist WHERE customer_id IN(". $in .")");

            foreach ($query->rows as $row) {
                $wishlist[] = $row['product_id'];
            }


        }else{
            if ((isset($_COOKIE["wishlist"]))) {
                foreach (explode(",", $_COOKIE["wishlist"]) as $element) {
                    if (!empty($element)) {
                        $wishlist[] = $element;
                    }
                }
            }
        }
		return $wishlist;
    }


	public function getTotalWishlist() {

        if ($this->customer->isLogged()) {
                    $query = $this->db->query("SELECT * FROM oc_customer_relation WHERE customer_id_main='" . $this->customer->getId() . "'");
                    $relation_customer_id = array();
                    $relation_customer_id[] = $this->customer->getId();
                    foreach ($query->rows as $row) {
                        foreach ($query->rows as $row) {
                            $relation_customer_id[] = $row['customer_id_child'];
                        }
                    }

                    $in = implode(",", $relation_customer_id);

                    $query = ($in) ? $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_wishlist WHERE customer_id IN(" . $in . ")") : array();

        }else{
                    if ((isset($_COOKIE["wishlist"]))) {

                        foreach (explode(",", $_COOKIE["wishlist"]) as $element) {
                            if (!empty($element)) {
                                $new_array[] = $element;
                            }
                        }
                        return count($new_array);
} else {
    return 0;
}
        }
		return (is_object($query)) ? $query->row['total'] : 0;
	}
}
