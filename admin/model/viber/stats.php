<?php

class ModelViberStats extends Model {

    public function setStats($telephone,$message_id,$text_message){
        $sql = "INSERT INTO `" . DB_PREFIX . "viber_history` SET telephone='". $telephone ."',message_id='". $message_id ."', datetime=NOW(), text_message='". $text_message ."' ";
        $this->db->query($sql);
    }

    public function getTotal($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "viber_history` WHERE 1=1";

//        if (isset($data['filter_date']) && !is_null($data['filter_date'])) {
//            $sql .= " AND DATE(datetime) = DATE('" . $this->db->escape($data['filter_date']) . "')";
//        }
//
//        if (isset($data['filter_subject']) && !is_null($data['filter_subject'])) {
//            $sql .= " AND LCASE(subject) LIKE '" . $this->db->escape(mb_strtolower($data['filter_subject'], 'UTF-8')) . "%'";
//        }
//
//        if (isset($data['filter_store']) && !is_null($data['filter_store'])) {
//            $sql .= " AND `store_id` = '" . (int)$data['filter_store'] . "'";
//        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getListByComa($data = array()) {
            $sql = "SELECT message_id FROM " . DB_PREFIX . "viber_history WHERE 1=1";

            if (isset($data['filter_date']) && !is_null($data['filter_date'])) {
                $sql .= " AND DATE(datetime) = DATE('" . $this->db->escape($data['filter_date']) . "')";
            }
//
//            if (isset($data['filter_subject']) && !is_null($data['filter_subject'])) {
//                $sql .= " AND LCASE(subject) LIKE '" . $this->db->escape(mb_strtolower($data['filter_subject'], 'UTF-8')) . "%'";
//            }
//
//            if (isset($data['filter_store']) && !is_null($data['filter_store'])) {
//                $sql .= " AND `" . DB_PREFIX . "viber_history`.`store_id` = '" . (int)$data['filter_store'] . "'";
//            }



            $sort_data = array(
                'history_id',
                'subject',
                'datetime',
                'telephone',
                'text_message'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY datetime";
            }

            if (isset($data['order']) && ($data['order'] == 'ASC')) {
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

    public function getList($data_param){
        $messages_id = array();
        $result = array();

        foreach ($this->getListByComa($data_param) as $item){
            $messages_id[] = $item['message_id'];
        }

        $obj = $this->turbosms->status($messages_id);

        foreach ($obj->response_result as $k=>$item){
            $sql = "SELECT *, CONCAT(firstname, ' ', lastname) AS name FROM oc_customer WHERE telephone='". $item->recipient ."'";
            $query1 = $this->db->query($sql);
            $sql = "SELECT * FROM oc_viber_history WHERE message_id='". $item->message_id ."'";
            $query2 = $this->db->query($sql);
            $main = (array)$item;
            $main['name'] = $query1->row['name'];
            $main['date_send'] = $query2->row['datetime'];
            $main['text_message'] = $query2->row['text_message'];

            $result[$k] = $main;
        }

        return $result;
    }

    public function detail($stats_id) {
        $query = $this->db->query("SELECT n.history_id as history_id, `subject`, `to`, `datetime`, `store_id`, `queue`, `recipients`, `views`, `stats_id` FROM " . DB_PREFIX . "viber_stats as s INNER JOIN " . DB_PREFIX . "viber_history as n ON (s.history_id = n.history_id) WHERE stats_id = '" . (int)$stats_id . "'");
        $data = $query->row;

        $query = $this->db->query("SELECT `url`, COUNT(`url`) as `clicks` FROM " . DB_PREFIX . "viber_clicks as c, " . DB_PREFIX . "viber_stats_personal as s WHERE c.stats_personal_id = s.stats_personal_id AND s.history_id = '" . (int)$data['history_id'] . "' GROUP BY `url` ORDER BY `clicks` DESC");
        $data['tracks'] = $query->rows;

        $query = $this->db->query("SELECT COUNT(`url`) as `clicks` FROM " . DB_PREFIX . "viber_clicks as c, " . DB_PREFIX . "viber_stats_personal as s WHERE c.stats_personal_id = s.stats_personal_id AND s.history_id = '" . (int)$data['history_id'] . "' AND `kind` = 'unsubscribe' GROUP BY `url`");
        $clicks = 0;
        foreach ($query->rows as $row) {
            $clicks += $row['clicks'];
        };
        $data['unsubscribe_clicks'] = $clicks;

        $query = $this->db->query("SELECT COUNT(*) as `read` FROM " . DB_PREFIX . "viber_stats_personal WHERE views > 0 AND history_id = '" . (int)$data['history_id'] . "'");
        $data['read'] = $query->row['read'];

        return $data;
    }

    public function timeline($stats_id) {
        $query = $this->db->query("SELECT n.history_id as history_id, `subject`, `to`, `datetime`, `store_id`, `queue`, `recipients`, `views`, `stats_id` FROM " . DB_PREFIX . "viber_stats as s INNER JOIN " . DB_PREFIX . "viber_history as n ON (s.history_id = n.history_id) WHERE stats_id = '" . (int)$stats_id . "'");

        $data = array();

        $query = $this->db->query("SELECT COUNT(*) as `read`, DATE_FORMAT(pv.`datetime`, '%Y-%m-%d') as `date` FROM " . DB_PREFIX . "viber_stats_personal as p RIGHT JOIN " . DB_PREFIX . "viber_stats_personal_views as pv ON (p.`stats_personal_id` = pv.`stats_personal_id`) WHERE history_id = '" . (int)$query->row['history_id'] . "' GROUP BY DATE_FORMAT(pv.`datetime`, '%Y-%m-%d') ORDER BY pv.`datetime` ASC");
        foreach ($query->rows as $row) {
            if (isset($data['timeline'][$row['date']])) {
                $data['timeline'][$row['date']] += $row['read'];
            } else {
                $data['timeline'][$row['date']] = $row['read'];
            }
        }

        return $data;
    }

    public function getFailedTotal($history_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "viber_stats_personal WHERE history_id = '" . (int)$history_id . "' AND success = '0'");

        return $query->row['total'];
    }

    public function getRecipientsTotal($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "viber_stats_personal s INNER JOIN " . DB_PREFIX . "viber_history h ON (h.history_id = s.history_id) INNER JOIN " . DB_PREFIX . "customer c ON (s.email = c.email AND h.store_id = c.store_id)";

        $implode = array();

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'";
        }

        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $implode[] = "c.email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_success']) && !is_null($data['filter_success'])) {
            $implode[] = "success = '" . (int)$data['filter_success'] . "'";
        }

        $implode[] = "s.history_id = '" . $this->db->escape($data['history_id']) . "'";

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getRecipientsList($data = array()) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "viber_clicks WHERE " . DB_PREFIX . "viber_clicks.`stats_personal_id` = s.`stats_personal_id`) as `clicks` FROM " . DB_PREFIX . "viber_stats_personal s INNER JOIN " . DB_PREFIX . "viber_history h ON (h.history_id = s.history_id) INNER JOIN " . DB_PREFIX . "customer c ON (s.email = c.email AND h.store_id = c.store_id)";

        $implode = array();

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'";
        }

        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $implode[] = "c.email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_success']) && !is_null($data['filter_success'])) {
            $implode[] = "success = '" . (int)$data['filter_success'] . "'";
        }

        $implode[] = "s.history_id = '" . $this->db->escape($data['history_id']) . "'";

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'name',
            'c.email',
            's.views',
            'clicks',
            'success'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY s.views";
        }

        if (isset($data['order']) && ($data['order'] == 'ASC')) {
            $sql .= " ASC";
        } else {
            $sql .= " DESC";
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

    public function getRecipientsMarketingTotal($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "viber_stats_personal s INNER JOIN " . DB_PREFIX . "viber_history h ON (h.history_id = s.history_id) INNER JOIN " . DB_PREFIX . "viber_marketing c ON (s.email = c.email AND h.store_id = c.store_id)";

        $implode = array();

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'";
        }

        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $implode[] = "c.email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_success']) && !is_null($data['filter_success'])) {
            $implode[] = "success = '" . (int)$data['filter_success'] . "'";
        }

        $implode[] = "s.history_id = '" . $this->db->escape($data['history_id']) . "'";

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getRecipientsMarketingList($data = array()) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "viber_clicks WHERE " . DB_PREFIX . "viber_clicks.`stats_personal_id` = s.`stats_personal_id`) as `clicks` FROM " . DB_PREFIX . "viber_stats_personal s INNER JOIN " . DB_PREFIX . "viber_history h ON (h.history_id = s.history_id) INNER JOIN " . DB_PREFIX . "viber_marketing c ON (s.email = c.email AND h.store_id = c.store_id)";

        $implode = array();

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'";
        }

        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $implode[] = "c.email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_success']) && !is_null($data['filter_success'])) {
            $implode[] = "success = '" . (int)$data['filter_success'] . "'";
        }

        $implode[] = "s.history_id = '" . $this->db->escape($data['history_id']) . "'";

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'name',
            'c.email',
            's.views',
            'clicks',
            'success'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY views";
        }

        if (isset($data['order']) && ($data['order'] == 'ASC')) {
            $sql .= " ASC";
        } else {
            $sql .= " DESC";
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

    public function getRecipientsAllList($data = array()) {
        $sql = "SELECT c.email, stats_personal_id, success, views, CONCAT(c.firstname, ' ', c.lastname) AS name, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "viber_clicks WHERE " . DB_PREFIX . "viber_clicks.`stats_personal_id` = s.`stats_personal_id`) as `clicks` FROM " . DB_PREFIX . "viber_stats_personal s INNER JOIN " . DB_PREFIX . "viber_history h ON (h.history_id = s.history_id) INNER JOIN " . DB_PREFIX . "viber_marketing c ON (s.email = c.email AND h.store_id = c.store_id)";

        $implode = array();

        $sort_data = array(
            'name',
            'c.email',
            's.views',
            'clicks',
            'success'
        );

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'";
        }

        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $implode[] = "c.email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_success']) && !is_null($data['filter_success'])) {
            $implode[] = "success = '" . (int)$data['filter_success'] . "'";
        }

        $implode[] = "s.history_id = '" . $this->db->escape($data['history_id']) . "'";

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY views";
        }

        if (isset($data['order']) && ($data['order'] == 'ASC')) {
            $sql .= " ASC";
        } else {
            $sql .= " DESC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)($data['start'] / 2) . "," . max((int)($data['limit'] / 2), 1);
        }

        $main_sql = '(' . $sql . ') UNION ALL (';

        $sql = "SELECT c.email, stats_personal_id, success, views, CONCAT(c.firstname, ' ', c.lastname) AS name, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "viber_clicks WHERE " . DB_PREFIX . "viber_clicks.`stats_personal_id` = s.`stats_personal_id`) as `clicks` FROM " . DB_PREFIX . "viber_stats_personal s INNER JOIN " . DB_PREFIX . "viber_history h ON (h.history_id = s.history_id) INNER JOIN " . DB_PREFIX . "customer c ON (s.email = c.email AND h.store_id = c.store_id)";

        $implode = array();

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'";
        }

        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $implode[] = "c.email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_success']) && !is_null($data['filter_success'])) {
            $implode[] = "success = '" . (int)$data['filter_success'] . "'";
        }

        $implode[] = "s.history_id = '" . $this->db->escape($data['history_id']) . "'";

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY s.views";
        }

        if (isset($data['order']) && ($data['order'] == 'ASC')) {
            $sql .= " ASC";
        } else {
            $sql .= " DESC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)($data['start'] / 2) . "," . max((int)($data['limit'] / 2), 1);
        }

        $main_sql .= $sql . ')';

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'c.email') {
                $data['sort'] = 'email';
            }
            if ($data['sort'] == 's.views') {
                $data['sort'] = 'views';
            }
            $main_sql .= " ORDER BY " . $data['sort'];
        } else {
            $main_sql .= " ORDER BY views";
        }

        if (isset($data['order']) && ($data['order'] == 'ASC')) {
            $main_sql .= " ASC";
        } else {
            $main_sql .= " DESC";
        }

        if (isset($data['limit'])) {
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }
            $main_sql .= " LIMIT " . (int)$data['limit'];
        }

        $query = $this->db->query($main_sql);

        return $query->rows;
    }

    public function getAttachments($history_id) {
        $path = dirname(DIR_DOWNLOAD) . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . (int)$history_id;
        if (file_exists($path) && is_dir($path)) {
            $attachments = $this->attachments($path);
        } else {
            $attachments = array();
        }
        return $attachments;
    }

    public function delete($stats_id) {
        $query = $this->db->query("SELECT history_id FROM " . DB_PREFIX . "viber_stats WHERE stats_id = '" . (int)$stats_id . "'");

        $history_id = $query->row['history_id'];

        $query = $this->db->query("SELECT `store_id` FROM " . DB_PREFIX . "viber_history WHERE history_id = '" . (int)$history_id . "'");
        if ($query->row) {
            $raw_log = DIR_LOGS . 'ne/raw.' . (int)$query->row['store_id'] . '.' . (int)$history_id . '.log';
            if (file_exists($raw_log)) {
                unlink($raw_log);
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "viber_history WHERE history_id = '" . (int)$history_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "viber_stats WHERE history_id = '" . (int)$history_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "viber_queue WHERE history_id = '" . (int)$history_id . "'");

        $query = $this->db->query("SELECT stats_personal_id FROM " . DB_PREFIX . "viber_stats_personal WHERE history_id = '" . (int)$history_id . "'");
        if ($query->row) {
            $stats_personal_id = $query->row['stats_personal_id'];
            $this->db->query("DELETE FROM " . DB_PREFIX . "viber_clicks WHERE stats_personal_id = '" . (int)$stats_personal_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "viber_stats_personal_views WHERE stats_personal_id = '" . (int)$stats_personal_id . "'");
        }
        $this->db->query("DELETE FROM " . DB_PREFIX . "viber_stats_personal WHERE history_id = '" . (int)$history_id . "'");

        $path = dirname(DIR_DOWNLOAD) . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $history_id;
        if (file_exists($path) && is_dir($path)) {
            $this->rrmdir($path);
        }
    }

    public function getClicks($stats_personal_id) {
        $query = $this->db->query("SELECT `url`, COUNT(`url`) as `clicks`, `datetime` FROM " . DB_PREFIX . "viber_clicks WHERE `stats_personal_id` = '" . (int)$stats_personal_id . "' GROUP BY `url` ORDER BY `clicks` DESC");
        return $query->rows;
    }

    private function rrmdir($dir) {
        foreach(glob($dir . '/*') as $file) {
            if(is_dir($file)) {
                $this->rrmdir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dir);
    }

    private function attachments($dir) {
        $attachments = array();

        $files = (array) glob($dir . '/*');
        if (!empty($files))
            foreach ($files as $file) {
                if (is_dir($file))
                    continue;

                $attachments[] = array(
                    'filename' => basename($file),
                    'path'     => str_replace(dirname(DIR_DOWNLOAD) . DIRECTORY_SEPARATOR, '', $file)
                );
            }

        return $attachments;
    }

    public function cleanup() {
        $query = $this->db->query("SELECT `history_id` FROM `" . DB_PREFIX . "viber_history` WHERE `history_id` NOT IN(SELECT `history_id` FROM `" . DB_PREFIX . "viber_stats`)");
        foreach ($query->rows as $row) {
            $history_id = $row['history_id'];

            $this->db->query("DELETE FROM " . DB_PREFIX . "viber_history WHERE history_id = '" . (int)$history_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "viber_stats WHERE history_id = '" . (int)$history_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "viber_queue WHERE history_id = '" . (int)$history_id . "'");

            $query = $this->db->query("SELECT stats_personal_id FROM " . DB_PREFIX . "viber_stats_personal WHERE history_id = '" . (int)$history_id . "'");
            if ($query->row) {
                $stats_personal_id = $query->row['stats_personal_id'];
                $this->db->query("DELETE FROM " . DB_PREFIX . "viber_clicks WHERE stats_personal_id = '" . (int)$stats_personal_id . "'");
                $this->db->query("DELETE FROM " . DB_PREFIX . "viber_stats_personal_views WHERE stats_personal_id = '" . (int)$stats_personal_id . "'");
            }
            $this->db->query("DELETE FROM " . DB_PREFIX . "viber_stats_personal WHERE history_id = '" . (int)$history_id . "'");

            $path = dirname(DIR_DOWNLOAD) . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $history_id;
            if (file_exists($path) && is_dir($path)) {
                $this->rrmdir($path);
            }
        }
    }
}