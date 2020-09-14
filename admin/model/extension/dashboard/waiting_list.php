<?php
class ModelExtensionDashboardWaitingList extends Model {
    public function getTotalProductsRequest(){
        $sql = "SELECT COUNT(DISTINCT report_appearance_id) AS total FROM " . DB_PREFIX . "report_appearance";

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}