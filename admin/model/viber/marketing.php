<?php

class ModelViberMarketing extends Model {

    private $_name = 'viber';

    public function getTotal($data = array(), $for_send = false) {

        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newsletter_module nd LEFT JOIN " . DB_PREFIX . "customer c ON(nd.customer_id=c.customer_id) LEFT JOIN " . DB_PREFIX . "newsletter_type_description ntd ON(nd.type_alias=ntd.alias) ";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
        }
        if (!empty($data['filter_telephone'])) {
            $implode[] = "c.telephone LIKE '%" . $this->db->escape($data['filter_telephone']) . "%'";
        }

        if (isset($data['filter_subscribed']) && !is_null($data['filter_subscribed'])) {
            $implode[] = "ntd.alias = '" . $data['filter_subscribed'] . "'";
        }


        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        } else {
            $sql .= " WHERE 1";
        }
        $sql .=" AND  nd.form_alias='viber' AND ntd.language_id='1'";
        $sort_data = array(
            'name',
            'telephone',
            'ntd.name'
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

        $query = $this->db->query($sql);


        return $query->row['total'];
    }

    public function getList($data = array()) {

        $sql = "SELECT *, CONCAT(firstname, ' ', lastname) AS name,ntd.name as subscribed_name FROM " . DB_PREFIX . "newsletter_module nd LEFT JOIN " . DB_PREFIX . "customer c ON(nd.customer_id=c.customer_id) LEFT JOIN " . DB_PREFIX . "newsletter_type_description ntd ON(nd.type_alias=ntd.alias) ";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
        }
        if (!empty($data['filter_telephone'])) {
            $implode[] = "c.telephone LIKE '%" . $this->db->escape($data['filter_telephone']) . "%'";
        }

        if (isset($data['filter_subscribed']) && !is_null($data['filter_subscribed'])) {
            $implode[] = "ntd.alias = '" . $data['filter_subscribed'] . "'";
        }


        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        } else {
            $sql .= " WHERE 1";
        }
        $sql .=" AND  nd.form_alias='viber' AND ntd.language_id='1'";
        $sort_data = array(
            'name',
            'telephone',
            'ntd.name'
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

//        echo $sql;
//        exit;

        $query = $this->db->query($sql);


        return $query->rows;
    }

    public function getListSubscribeAll() {
            $sql = "SELECT * FROM " . DB_PREFIX . "newsletter_type_description WHERE language_id = '1'";
            $rows = $this->db->query($sql);
            return $rows->rows;
    }
    public function subscribe($id = 0) {
        $id = (int)$id;
        if ($id > 0) {
            $sql = "UPDATE " . DB_PREFIX . "ne_marketing SET subscribed = 1 WHERE marketing_id = " . $id;
            $this->db->query($sql);
        }
    }

    public function unsubscribe($id = 0) {
        $id = (int)$id;
        if ($id > 0) {
            $sql = "UPDATE " . DB_PREFIX . "ne_marketing SET subscribed = 0 WHERE marketing_id = " . $id;
            $this->db->query($sql);
        }
    }

    public function add($data, $salt = '') {

        $emails = $data['emails'];
        $list = isset($data['list']) ? $data['list'] : array();

        $i = 0;
        if ($emails) {
            $emails = preg_replace("/\n|\r/", ',', $emails);
            $emails = explode(',', $emails);

            $emails = array_filter($emails, array($this, 'filter_email'));

            foreach ($emails as $key => $item) {
                $temp = explode('|', $item);
                if (count($temp) == 1) {
                    $email = $temp[0];
                    $name = '';
                    $lastname = '';
                } elseif (count($temp) == 2) {
                    $name = $temp[0];
                    $email = $temp[1];
                    $lastname = '';
                } elseif (count($temp) == 3) {
                    $name = $temp[0];
                    $lastname = $temp[1];
                    $email = $temp[2];
                }

                $email = trim(preg_replace("/\s+/", ' ', $email));

                if ($name) {
                    $name = trim(preg_replace("/\s+/", ' ', $name));
                }

                if ($lastname) {
                    $lastname = trim(preg_replace("/\s+/", ' ', $lastname));
                }

                $this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "ne_marketing SET email = '" . $this->db->escape($email) . "', firstname = '" . $this->db->escape($name) . "', lastname = '" . $this->db->escape($lastname) . "', code = '" . $this->db->escape(md5($salt . $email)) . "', subscribed = 1, store_id = '" . (int)$data['store_id'] . "'");
                if ($this->db->countAffected() > 0) {
                    $i++;

                    if (isset($list[$data['store_id']]) && $list[$data['store_id']]) {
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ne_marketing WHERE email = '" . $this->db->escape($email) . "' AND store_id = '" . (int)$data['store_id'] . "'");
                        $row = $query->row;
                        $this->db->query("DELETE FROM " . DB_PREFIX . "ne_marketing_to_list WHERE marketing_id = '" . (int)$row['marketing_id'] . "'");
                        foreach ($list[$data['store_id']] as $id) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "ne_marketing_to_list SET marketing_id = '" . (int)$row['marketing_id'] . "', marketing_list_id = '" . (int)$id . "'");
                        }
                    } else {
                        $list = $this->config->get('ne_marketing_list');
                        if (isset($list[$data['store_id']]) && $list[$data['store_id']]) {
                            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ne_marketing WHERE email = '" . $this->db->escape($email) . "' AND store_id = '" . (int)$data['store_id'] . "'");
                            $row = $query->row;
                            $this->db->query("DELETE FROM " . DB_PREFIX . "ne_marketing_to_list WHERE marketing_id = '" . (int)$row['marketing_id'] . "'");
                            foreach ($list[$data['store_id']] as $id => $val) {
                                $this->db->query("INSERT INTO " . DB_PREFIX . "ne_marketing_to_list SET marketing_id = '" . (int)$row['marketing_id'] . "', marketing_list_id = '" . (int)$id . "'");
                            }
                        }
                        unset($list);
                    }
                }
            }
        }
        return $i;
    }

    public function delete($id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "ne_marketing WHERE marketing_id = '" . (int)$id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "ne_marketing_to_list WHERE marketing_id = '" . (int)$id . "'");
    }

    private function filter_email($email) {
        $temp = explode('|', $email);
        if (count($temp)) {
            return $temp[count($temp) - 1] && filter_var(htmlspecialchars(trim($temp[count($temp) - 1])), FILTER_VALIDATE_EMAIL);
        } else {
            return false;
        }
    }


}