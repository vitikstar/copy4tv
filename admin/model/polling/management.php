<?php
class ModelPollingManagement extends Model {
    public function addPolling($data) {
        if((int)$data['status']){
            $this->db->query("UPDATE " . DB_PREFIX . "polling SET status = '0'");
        }
        $this->db->query("INSERT INTO " . DB_PREFIX . "polling SET  status = '" . (int)$data['status'] . "'");

        $polling_id = $this->db->getLastId();

        if (isset($data['question'])) {
            foreach ($data['question'] as $language_id => $question) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "polling_description SET polling_id = '" . (int)$polling_id . "', language_id = '" . (int)$language_id . "', question = '" . $this->db->escape($question). "'");
            }
        }

        if (isset($data['answer'])) {
            foreach ($data['answer'] as $language_id => $value) {
                foreach ($value as $polling_possible) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "polling_possible SET polling_id = '" . (int)$polling_id . "', language_id = '" . (int)$language_id . "', title = '" .  $this->db->escape($polling_possible['polling_possible']) . "', sort_order = '" .  (int)$polling_possible['sort_order'] . "'");
                }
            }
        }

        return $polling_id;
    }

    public function editPolling($polling_id, $data) {
        if((int)$data['status']){
            $this->db->query("UPDATE " . DB_PREFIX . "polling SET status = '0'");
        }
        $this->db->query("UPDATE " . DB_PREFIX . "polling SET status = '". (int)$data['status'] ."' WHERE polling_id = '" . (int)$polling_id . "'");

        if (isset($data['question'])) {
            foreach ($data['question'] as $language_id => $question) {
                $this->db->query("UPDATE " . DB_PREFIX . "polling_description SET question = '" . $this->db->escape($question) . "' WHERE polling_id = '" . (int)$polling_id . "' AND language_id = '" . (int)$language_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "polling_possible WHERE polling_id = '" . (int)$polling_id . "'");

        if (isset($data['answer'])) {
            foreach ($data['answer'] as $language_id => $value) {
                foreach ($value as $polling_possible) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "polling_possible SET polling_id = '" . (int)$polling_id . "', language_id = '" . (int)$language_id . "', title = '" .  $this->db->escape($polling_possible['polling_possible']) . "', sort_order = '" . (int)$polling_possible['sort_order'] . "'");
                }
            }
        }
    }

    public function deletePolling($polling_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "polling WHERE polling_id = '" . (int)$polling_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "polling_description WHERE polling_id = '" . (int)$polling_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "polling_possible WHERE polling_id = '" . (int)$polling_id . "'");
    }
    public function statsPolling($polling_id) {
        $query = $this->db->query("SELECT question,pr.answer_id as answer_id, opp.title as answer,pr.polling_id as polling_id FROM  oc_polling_result pr  LEFT JOIN oc_polling_description pd ON(pd.polling_id=pr.polling_id)  LEFT JOIN oc_polling_possible opp ON(opp.polling_possible_id=pr.answer_id) LEFT JOIN oc_polling_possible pps ON(pps.polling_id=pr.polling_id) WHERE pr.polling_id='".$polling_id."' AND pd.language_id='1' AND pps.language_id='1' GROUP BY pr.answer_id");

        $stats = array();
        $answer = array();
        $query2 = $this->db->query("SELECT answer_id FROM  oc_polling_result   WHERE polling_id='". $polling_id ."'");
        foreach ($query->rows as $row){
            $stats[$row['polling_id']] = array(
                'question'=>$row['question'],
                'answer'=>$answer
            );
            $query = $this->db->query("SELECT answer_id FROM  oc_polling_result   WHERE answer_id='". $row['answer_id'] ."'");
            $answer[] =  array('title'=>$row['answer'],'count'=>$query->num_rows,'percent'=>($query->num_rows/$query2->num_rows)*100);
        }



        $stats[$polling_id]['answer'] = $answer;
        $stats[$polling_id]['percent'] = count($answer);

        return $stats;
    }



    public function getPollings($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "polling AS p LEFT JOIN " . DB_PREFIX . "polling_description AS pd ON(p.polling_id=pd.polling_id) WHERE pd.language_id=1";

        $sort_data = array(
            'status'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY p.polling_id";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
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

    public function getPolling($polling_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "polling  WHERE polling_id = '". $polling_id ."'";

        $query = $this->db->query($sql);

        return $query->row;
    }

    public function getPollingDescription($polling_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "polling p LEFT JOIN " . DB_PREFIX . "polling_description pd ON(p.polling_id=pd.polling_id) WHERE p.polling_id = '". $polling_id ."'";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getPollingPosible($polling_id) {
        $polling_possible_data = array();

        $polling_possible_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "polling_possible WHERE polling_id = '" . (int)$polling_id . "' ORDER BY sort_order ASC");

        foreach ($polling_possible_query->rows as $polling_possible) {
            $polling_possible_data[$polling_possible['language_id']][] = array(
                'title'      => $polling_possible['title'],
                'sort_order' => $polling_possible['sort_order']
            );
        }

        return $polling_possible_data;
    }

    public function getTotalPollings() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "polling");

        return $query->row['total'];
    }
}
