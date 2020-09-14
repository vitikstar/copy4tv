<?php

class ModelViberSend extends Model {

    public function send($data) {

    }

    public function addHistory($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "viber_history SET `to` = '" . $this->db->escape($data['to']) . "', public_id = '" . $this->db->escape(md5($data['subject'] . time())). "', store_id = '" . (int)$data['store_id'] . "', template_id = '" . (int)$data['template_id'] . "', language_id = '" . (int)$data['language_id'] . "', subject = '" . $this->db->escape($data['subject']) . "', message = '" . $this->db->escape($data['message']) . "', text_message = '" . $this->db->escape($data['text_message']) . "', text_message_products = '" . (int)$data['text_message_products'] . "'");
        return $this->db->getLastId();
    }

    public function addHistoryQueue($newsletter_id, $data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "viber_stats SET history_id = '" . (int)$newsletter_id . "', queue = '" . (int)$data['queue'] . "', recipients = '" . (int)$data['recipients'] . "', views = '0'");
    }

    private function base64_encode_image($image) {
        if (file_exists($image)) {
            $filename = htmlentities($image);
        } else {
            return '';
        }

        $imgtype = array('jpg' => 'jpeg', 'jpeg' => 'jpeg', 'gif' => 'gif', 'png' => 'png');

        $filetype = pathinfo($filename, PATHINFO_EXTENSION);

        if (array_key_exists($filetype, $imgtype)) {
            $imgbinary = fread(fopen($filename, "r"), filesize($filename));
        } else {
            return '';
        }

        return 'data:image/' . $imgtype[$filetype] . ';base64,' . base64_encode($imgbinary);
    }

    public function getEmailsByProductsOrdered($products) {
        $implode = array();

        foreach ($products as $product_id) {
            $implode[] = "op.product_id = '" . $product_id . "'";
        }

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id) LEFT JOIN " . DB_PREFIX . "viber_language_map lm ON (o.email = lm.c_email) WHERE (" . implode(" OR ", $implode) . ") AND o.order_status_id <> '0' AND NOT EXISTS (SELECT 1 FROM " . DB_PREFIX . "viber_blacklist bl WHERE bl.email = o.email) GROUP BY o.email");

        return $query->rows;
    }

    public function getRecipientsWithRewardPoints($data = array()) {
        $sql = "SELECT c.customer_id, c.firstname, c.lastname, c.email, cr.points FROM `" . DB_PREFIX . "customer` AS c INNER JOIN (SELECT customer_id, SUM(points) AS points FROM " . DB_PREFIX . "customer_reward GROUP BY customer_id) AS cr ON cr.customer_id = c.customer_id AND cr.points > '0' LEFT JOIN " . DB_PREFIX . "viber_language_map lm ON c.email = lm.c_email WHERE NOT EXISTS (SELECT 1 FROM " . DB_PREFIX . "viber_blacklist bl WHERE bl.email = c.email)";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getSubscribedRecipientsWithRewardPoints($data = array()) {
        $sql = "SELECT c.customer_id, c.firstname, c.lastname, c.email, cr.points FROM `" . DB_PREFIX . "customer` AS c INNER JOIN (SELECT customer_id, SUM(points) AS points FROM " . DB_PREFIX . "customer_reward GROUP BY customer_id) AS cr ON cr.customer_id = c.customer_id AND cr.points > '0' LEFT JOIN " . DB_PREFIX . "viber_language_map lm ON c.email = lm.c_email WHERE c.newsletter = '1' AND NOT EXISTS (SELECT 1 FROM " . DB_PREFIX . "viber_blacklist bl WHERE bl.email = c.email)";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getCustomers($data = array()) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) LEFT JOIN " . DB_PREFIX . "viber_language_map lm ON c.email = lm.c_email WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
            $implode[] = "c.newsletter = '" . (int)$data['filter_newsletter'] . "'";
        }

        if (!empty($data['filter_customer_group_id'])) {
            $implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        $implode[] = "NOT EXISTS (SELECT 1 FROM " . DB_PREFIX . "viber_blacklist bl WHERE bl.email = c.email)";

        if ($implode) {
            $sql .= " AND " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'name',
            'c.email',
            'customer_group',
            'c.status',
            'c.approved',
            'c.ip',
            'c.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getCustomer($customer_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "viber_language_map lm ON c.email = lm.c_email WHERE c.customer_id = '" . (int)$customer_id . "' AND NOT EXISTS (SELECT 1 FROM " . DB_PREFIX . "viber_blacklist bl WHERE bl.email = c.email)");

        return $query->row;
    }

    public function getAffiliates($data = array()) {
        $sql = "SELECT *, CONCAT(a.firstname, ' ', a.lastname) AS name, (SELECT SUM(at.amount) FROM " . DB_PREFIX . "affiliate_transaction at WHERE at.affiliate_id = a.affiliate_id GROUP BY at.affiliate_id) AS balance FROM " . DB_PREFIX . "affiliate a LEFT JOIN " . DB_PREFIX . "viber_language_map lm ON a.email = lm.c_email";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(a.firstname, ' ', a.lastname) LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "LCASE(a.email) = '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
        }

        if (!empty($data['filter_code'])) {
            $implode[] = "a.code = '" . $this->db->escape($data['filter_code']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "a.status = '" . (int)$data['filter_status'] . "'";
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "a.approved = '" . (int)$data['filter_approved'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(a.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        $implode[] = "NOT EXISTS (SELECT 1 FROM " . DB_PREFIX . "viber_blacklist bl WHERE bl.email = a.email)";

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'name',
            'a.email',
            'a.code',
            'a.status',
            'a.approved',
            'a.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getAffiliate($affiliate_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "affiliate a LEFT JOIN " . DB_PREFIX . "viber_language_map lm ON a.email = lm.c_email WHERE affiliate_id = '" . (int)$affiliate_id . "' AND NOT EXISTS (SELECT 1 FROM " . DB_PREFIX . "viber_blacklist bl WHERE bl.email = a.email)");

        return $query->row;
    }

    private function write_raw($message, $file) {
        if(!file_exists(dirname($file))) {
            mkdir(dirname($file), 0777, true);
        }

        if(!file_exists(dirname($file) . '/.htaccess')) {
            file_put_contents(dirname($file) . '/.htaccess', 'deny from all');
        }

        file_put_contents($file, $message . "\n", FILE_APPEND | LOCK_EX);
    }

}