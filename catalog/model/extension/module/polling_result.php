<?php
class ModelExtensionModulePollingResult extends Model {
	public function getAnswerOptions($polling_id) {
	    $polling_possible = array();
	    if($this->config->get('module_polling_possible_status')){
            $query = $this->db->query("SELECT * FROM  oc_polling p  LEFT JOIN oc_polling_description pd ON(pd.polling_id=p.polling_id) LEFT JOIN oc_polling_possible pps ON(pps.polling_id=p.polling_id) WHERE p.polling_id='".$polling_id."' AND pd.language_id='". (int)$this->config->get('config_language_id') ."' AND pps.language_id='". (int)$this->config->get('config_language_id') ."'");
            if($query->num_rows) {

                foreach ($query->rows as $row) {
                    $polling_possible[$row['polling_id']] = array(
                        'question' => $row['question'],
                        'polling_possible' => array(),
                    );
                }
                $answer = array();
                foreach ($query->rows as $row) {

                    //  $answer =  array_merge($answer,array($row['polling_possible_id']=>$row['title']));
                    $answer =  $answer + array($row['polling_possible_id']=>$row['title']);
                }
            }
            $polling_possible[$polling_id]['polling_possible'] = $answer;
        }
		return $polling_possible;
	}
	public function addAnswer($data) {
        $this->db->query("INSERT INTO oc_polling_result SET  customer_id='".$this->customer->getId()."', answer_id='".$data['answer_id']."', polling_id='".$data['polling_id']."', date_polling=NOW()");
	}
	public function getMainPlollingId() {
        $query = $this->db->query("SELECT polling_id FROM  oc_polling WHERE status = 1");
        return $query->row['polling_id'];
	}
}