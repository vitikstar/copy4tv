<?php

use LisDev\Delivery\NovaPoshtaApi2;

class ModelAccountDelivery extends Model
{
    public function getAddress($customer_id)
    {
        $np = new NovaPoshtaApi2(
            API_NOVA_POSHTA
        );
        if ($customer_id) {
            $result = array();
            $customer_id_arr = array();
            $customer_id_arr[$customer_id] = $customer_id;
            $sql = "SELECT * FROM `" . DB_PREFIX . "customer_relation`   WHERE `customer_id_main`='" . $customer_id . "'";
            $query = $this->db->query($sql);

                if ($query->rows){
                    foreach($query->rows as $row){
                            $customer_id_arr[$row['customer_id_child']] = $row['customer_id_child'];
                    }
                }
                $customer_id_in = implode(',',$customer_id_arr);

                $customer_id_in = trim($customer_id_in,',');

            $sql = "SELECT *,c.name as region_name,z.name as zone_name,td.name as type_delivery_name, lc.address as address_shop_name FROM `" . DB_PREFIX . "customer_address_delivery` cad LEFT JOIN `" . DB_PREFIX . "type_delivery` td ON(cad.type_delivery_id=td.type_delivery_id) LEFT JOIN `" . DB_PREFIX . "service_delivery` sd ON(cad.service_delivery_id=sd.service_delivery_id) LEFT JOIN `" . DB_PREFIX . "country` c ON(cad.region_id=c.country_id) LEFT JOIN `" . DB_PREFIX . "location` lc ON(lc.location_id=cad.address_shop_id) LEFT JOIN `" . DB_PREFIX . "zone` z ON(cad.zone_id=z.zone_id) WHERE `customer_id` IN (" . $customer_id_in . ")";
            $query = $this->db->query($sql);
            if ($query->rows) {
                $this->load->model('localisation/zone');
                foreach ($query->rows as $k => $row) {

                    // $city_data = $this->model_localisation_zone->getZone($row['zone_id']);
                    //  if($city_data and isset($city_data['name'])){
                    //    $branch_new_post = $np->getWarehouseName($city_data['name'],$row['SiteKey']);
                    //}else{
                    $branch_new_post = $row['branch_new_post'];
                    // }

                    $result[$k]['service_delivery_name'] = $row['service_delivery_name'];
                    $result[$k]['default_address'] = $row['default_address'];
                    $result[$k]['customer_address_delivery_id'] = $row['customer_address_delivery_id'];
                    $result[$k]['shape_delivery_id'] = $row['shape_delivery_id'];
                    $result[$k]['SiteKey'] = $row['SiteKey'];
                    $result[$k]['address_shop_name'] = $row['address_shop_name'];
                    $result[$k]['branch_new_post'] = $branch_new_post;
                    $result[$k]['branch_ukr_post'] = $row['branch_ukr_post'];
                    $result[$k]['shipping_address_1'] = $row['shipping_address_1'];
                }
            }
            return $result;

        } else {
            return array();
        }

    }
    public function getAddressForCheckoutPage($customer_id)
    {
        $np = new NovaPoshtaApi2(
            API_NOVA_POSHTA
        );

        if ($customer_id) {
            $result = array();
            $sql = "SELECT *,c.name as region_name,z.name as zone_name,sd.name as service_delivery_name,td.name as type_delivery_name, lc.address as address_shop_name FROM `" . DB_PREFIX . "customer_address_delivery` cad LEFT JOIN `" . DB_PREFIX . "type_delivery` td ON(cad.type_delivery_id=td.type_delivery_id) LEFT JOIN `" . DB_PREFIX . "service_delivery` sd ON(cad.service_delivery_id=sd.service_delivery_id) LEFT JOIN `" . DB_PREFIX . "country` c ON(cad.region_id=c.country_id) LEFT JOIN `" . DB_PREFIX . "location` lc ON(lc.location_id=cad.address_shop_id) LEFT JOIN `" . DB_PREFIX . "zone` z ON(cad.zone_id=z.zone_id) WHERE `customer_id` = '" . (int)$customer_id . "'";

            $query = $this->db->query($sql);

            if ($query->rows) {
                $this->load->model('localisation/zone');
                foreach ($query->rows as $k => $row) {

                    $city_data = $this->model_localisation_zone->getZone($row['zone_id']);
                    if ($city_data and isset($city_data['name'])) {
                        $branch_new_post = $np->getWarehouseName($city_data['name'], $row['SiteKey']);
                    } else {
                        $branch_new_post = $row['branch_new_post'];
                    }
                    $result[$k]['default_address'] = $row['default_address'];
                    $result[$k]['customer_address_delivery_id'] = $row['customer_address_delivery_id'];

                    if ($row['shape_delivery_id'] == 2) {
                        $result[$k]['address'] = $row['address_shop_name'];
                    } else {
                        $result[$k]['address'] = $row['service_delivery_name'] . ", " . $branch_new_post;
                    }
                }
            }
            return $result;
        } else {
            return array();
        }

    }
    public function getOneAddress($customer_id, $customer_address_delivery_id)
    {
        if ($customer_id) {
            $sql = "SELECT *,c.name as region_name,z.name as zone_name,sd.name as service_delivery_name,td.name as type_delivery_name,td.type_delivery_id as shape_delivery_id FROM `" . DB_PREFIX . "customer_address_delivery` cad LEFT JOIN `" . DB_PREFIX . "type_delivery` td ON(cad.type_delivery_id=td.type_delivery_id) LEFT JOIN `" . DB_PREFIX . "service_delivery` sd ON(cad.service_delivery_id=sd.service_delivery_id) LEFT JOIN `" . DB_PREFIX . "country` c ON(cad.region_id=c.country_id) LEFT JOIN `" . DB_PREFIX . "zone` z ON(cad.zone_id=z.zone_id) WHERE `customer_id` = '" . (int)$customer_id . "' AND cad.customer_address_delivery_id='" . $customer_address_delivery_id . "'";

            $query = $this->db->query($sql);
            return $query->row;
        } else {
            return array();
        }

    }
    public function getTypeDeliveryAll()
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "type_delivery`";

        $query = $this->db->query($sql);
        return $query->rows;
    }

}
