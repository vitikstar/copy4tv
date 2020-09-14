<?php
class ModelExtensionModuleLooked extends Model
{

    public function getLookedProducts($customer_id)
    {
        $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_looked` WHERE customer_id='". $customer_id ."' GROUP BY product_id ORDER BY looked_id DESC");

        $arr = array();

        if($result->num_rows){
                foreach($result->rows as $product){
                        $arr[] = $product['product_id'];
                }
        }
        return $arr;
    }
    
    public function setLookedProducts($customer_id,$product_id)
    {
        $result1 = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_looked` WHERE customer_id='" . $customer_id . "' AND product_id='". $product_id ."'");

        if (!$result1->num_rows) {
            $result2 = $this->db->query("INSERT INTO `" . DB_PREFIX . "customer_looked` SET customer_id='" . $customer_id . "',product_id='" . $product_id . "'");
        }
    }
}
