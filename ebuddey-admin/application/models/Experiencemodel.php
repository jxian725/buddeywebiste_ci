<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Experiencemodel extends CI_Model {

	public $primaryKey      = 'te_id';
    public $column_order    = array('te_id','talent_id', 'experience_title','states.name','price_rate','talent_experience_list.status'); 
    public $column_search   = array('te_id','talent_id', 'experience_title','states.name','price_rate','talent_experience_list.status');
    public $allcolumn_search= array('te_id','talent_id', 'experience_title','states.name','price_rate','talent_experience_list.status');
    public $order           = array('te_id' => 'DESC'); // default order

	public function __construct(){
		parent::__construct();
	}

	function get_datatables($where=false){
        $this->_get_datatables_query();
        $this->db->where_in('talent_experience_list.status', [1,2,3]);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered($where=false){
        $this->_get_datatables_query();
        $this->db->where_in('talent_experience_list.status', [1,2,3]);
        $query  = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($where=false){
        $this->db->select('talent_experience_list.*');
        $this->db->from('talent_experience_list');
        $this->db->where_in('talent_experience_list.status', [1,2,3]);
        $query = $this->db->get();
        return $query->num_rows();
    }
    private function _get_datatables_query(){

        $this->db->select('talent_experience_list.*, first_name, last_name, states.name as cityName, specialization.specialization as categoryName');
        $this->db->join('guider', 'guider.guider_id = talent_experience_list.talent_id','left');
        $this->db->join('states', 'states.id = talent_experience_list.city','left');
        $this->db->join('specialization', 'specialization.specialization_id = talent_experience_list.skills_category','left');
        $this->db->from('talent_experience_list');
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
    
	public function experienceInfo($te_id){
		
		$this->db->select('talent_experience_list.*,first_name, last_name, phone_number, email, states.name as cityName, specialization.specialization as categoryName');
        $this->db->join('guider', 'guider.guider_id = talent_experience_list.talent_id','left');
        $this->db->join('states', 'states.id = talent_experience_list.city','left');
        $this->db->join('specialization', 'specialization.specialization_id = talent_experience_list.skills_category','left');
	    $this->db->where('te_id', $te_id );
	    $query = $this->db->get('talent_experience_list');
		return $query->row();
	}
	//UPDATE
    function updateExperience( $te_id, $data ) {
    	$this->db->where( 'te_id', $te_id );
		$this->db->update( 'talent_experience_list', $data );
    	return true;
    }
}