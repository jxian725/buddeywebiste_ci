<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Feedbackmodel extends CI_Model {
    public $column_order     = array('support_id','fb_guider_id','subject','description','app_version','feedback_status'); 
    public $column_search    = array('support_id','fb_guider_id','subject','description','app_version','feedback_status');
    public $allcolumn_search = array('support_id','fb_guider_id','subject','description','app_version','feedback_status');
    public $column_order2    = array('support_id','fb_traveller_id','subject','description','app_version','feedback_status'); 
    public $column_search2   = array('support_id','fb_traveller_id','subject','description','app_version','feedback_status');
    public $allcolumn_search2= array('support_id','fb_traveller_id','subject','description','app_version','feedback_status');
    public $order           = array('support_id' => 'DESC'); // default order 
    public function __construct(){
        parent::__construct();
    }
    function get_guider_datatables($where=false){
        $this->_get_guider_datatables_query();
        $this->db->where('feedback_status != ',4,FALSE);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_guider_filtered($where=false){
        $this->_get_guider_datatables_query();
        $this->db->where('feedback_status != ',4,FALSE);
        $query  = $this->db->get();
        return $query->num_rows();
    }
    public function count_guider_all($where=false){
        $this->db->select('support_id');
        $this->db->from('guider_feedback');
        $this->db->join('guider', 'guider.guider_id = guider_feedback.fb_guider_id', 'left');
        $this->db->where('feedback_status != ',4,FALSE);
        //return $this->db->count_all_results();
        $query = $this->db->get();
        return $query->num_rows();
    }
    private function _get_guider_datatables_query(){

        $this->db->select('guider_feedback.*, guider.first_name AS guiderName');
        $this->db->from('guider_feedback');
        $this->db->join('guider', 'guider.guider_id = guider_feedback.fb_guider_id', 'left');
        /* Individual column filtering */
        $j = 0;
        foreach ($this->column_search as $search){
            if ( isset($_POST['columns'][$j]) && $_POST['columns'][$j]['searchable'] == "true" && $_POST['columns'][$j]['search']['value'] != '' ) {
                $this->db->where("(".$search." LIKE '%".$_POST['columns'][$j]['search']['value']."%')");
            }
            $j++;
        }
        /*Top search All column Filtring*/
        $i = 0;
        foreach ($this->allcolumn_search as $item){
            if($_POST['search']['value']){
                if($i===0){
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->allcolumn_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if(isset($_POST['order'])){
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_traveller_datatables($where=false){
        $this->_get_traveller_datatables_query();
        $this->db->where('feedback_status != ',4,FALSE);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_traveller_filtered($where=false){
        $this->_get_traveller_datatables_query();
        $this->db->where('feedback_status != ',4,FALSE);
        $query  = $this->db->get();
        return $query->num_rows();
    }
    public function count_traveller_all($where=false){
        $this->db->select('support_id');
        $this->db->from('traveller_feedback');
        $this->db->join('traveller', 'traveller.traveller_id = traveller_feedback.fb_traveller_id', 'left');
        $this->db->where('feedback_status != ',4,FALSE);
        //return $this->db->count_all_results();
        $query = $this->db->get();
        return $query->num_rows();
    }
    private function _get_traveller_datatables_query(){

        $this->db->select('traveller_feedback.*, traveller.first_name AS travellerName');
        $this->db->from('traveller_feedback');
        $this->db->join('traveller', 'traveller.traveller_id = traveller_feedback.fb_traveller_id', 'left');
        /* Individual column filtering */
        $j = 0;
        foreach ($this->column_search2 as $search){
            if ( isset($_POST['columns'][$j]) && $_POST['columns'][$j]['searchable'] == "true" && $_POST['columns'][$j]['search']['value'] != '' ) {
                $this->db->where("(".$search." LIKE '%".$_POST['columns'][$j]['search']['value']."%')");
            }
            $j++;
        }
        /*Top search All column Filtring*/
        $i = 0;
        foreach ($this->allcolumn_search2 as $item){
            if($_POST['search']['value']){
                if($i===0){
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->allcolumn_search2) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if(isset($_POST['order'])){
            $this->db->order_by($this->column_order2[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    public function travellerFeedbackInfo($support_id){
        
        $this->db->select('traveller_feedback.*, traveller.first_name AS travellerName');
        $this->db->from('traveller_feedback');
        $this->db->join('traveller', 'traveller.traveller_id = traveller_feedback.fb_traveller_id', 'left');
        $this->db->where('support_id', $support_id );
        $query = $this->db->get();
        $row = $query->row();
        return $row;
    }
    public function guiderFeedbackInfo($support_id){
        
        $this->db->select('guider_feedback.*, guider.first_name AS guiderName');
        $this->db->from('guider_feedback');
        $this->db->join('guider', 'guider.guider_id = guider_feedback.fb_guider_id', 'left');
        $this->db->where('support_id', $support_id );
        $query = $this->db->get();
        $row = $query->row();
        return $row;
    }
    function deleteGuiderFeedbackInfo($support_id) {
        $this->db->where( 'support_id', $support_id );
        $this->db->delete( 'guider_feedback' );
        return true;
    }
    function deleteTravellerFeedbackInfo($support_id) {
        $this->db->where( 'support_id', $support_id );
        $this->db->delete( 'traveller_feedback' );
        return true;
    }
}