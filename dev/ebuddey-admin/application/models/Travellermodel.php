<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Travellermodel extends CI_Model {

	public $primaryKey      = 'traveller_id';
    public $column_order    = array('guiderID','first_name','email','phone_number','languages_known','about_me','status'); 
    public $column_search   = array('guiderID','first_name','email','phone_number','languages_known','about_me','status');
    public $allcolumn_search= array('guiderID','first_name','email','phone_number','languages_known','about_me','status');
    public $order           = array('traveller_id' => 'DESC'); // default order

	public function __construct(){
		parent::__construct();
	}

	function get_datatables($where=false){
        $this->_get_datatables_query();
        $this->db->where( 'traveller.status !=', 4 );
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered($where=false){
        $this->_get_datatables_query();
        $this->db->where( 'traveller.status !=', 4 );
        $query  = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($where=false){
        $this->db->select('traveller.*');
        $this->db->from('traveller');
        $this->db->where( 'traveller.status !=', 4 );
        $query = $this->db->get();
        return $query->num_rows();
    }
    private function _get_datatables_query(){

        $this->db->select('traveller.*');
        $this->db->from('traveller');
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
    
	//Requestor lists
	public function traveller_lists( $requestor_search = '', $order_by = '' ){
		$this->db->select('*');
	    $this->db->where( 'status !=', 4 );
	    //If Condition traveller search
	    if( $requestor_search ) {
	    	$this->db->where( "( first_name LIKE '%". $requestor_search ."%' OR phone_number LIKE '%". $requestor_search ."%' OR email LIKE '%". $requestor_search ."%' )" );
	    }
	    //If Condition for order by
	    if( $order_by == 1 ) {
	    	$this->db->order_by( 'first_name', 'asc' );
	    } else if( $order_by == 2 ) {
	    	$this->db->order_by( 'first_name', 'desc' );
	    } else if( $order_by == 3 ) {
	    	$this->db->order_by( 'email', 'asc' );
	    } else if( $order_by == 4 ) {
	    	$this->db->order_by( 'email', 'desc' );
	    } else {
	    	$this->db->order_by( 'traveller_id', 'desc' );
	    }
	    $query = $this->db->get( 'traveller' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$result = $query->result();
	    	return $result;
	    } else {
	    	return false;
	    }
	}
	public function travellerInfo($traveller_id){
		
		$this->db->select('*');
	    $this->db->where('traveller_id', $traveller_id );
	    $query = $this->db->get('traveller');
		$row = $query->row();
	    return $row;
	}
	//UPDATE REQUESTOR STATUS
    function travellerStatus( $traveller_id, $status ) {
    	$data 	= array( 'status' => $status );
    	$table  = $this->db->dbprefix( 'traveller' );
    	$this->db->where( 'traveller_id', $traveller_id );
		$this->db->update( $table, $data );
		//echo $this->db->last_query();
    	return true;
    }
    function deleteTraveller( $traveller_id, $data ) {
        $table  = $this->db->dbprefix( 'traveller' );
        $this->db->where( 'traveller_id', $traveller_id );
        $this->db->update( $table, $data );
        return true;
    }
    //Check password
    function check_password( $user_id, $password ) {

    	$this->db->select('*');
	    $this->db->where('user_id', $user_id );
	    $query 		= $this->db->get('user');
		$userInfo   = $query->row();
		if($userInfo){
            $oldPass     = $userInfo->password;
            $currentPass = $this->encryption->decrypt($oldPass);
            if ($currentPass == trim($password)) {
                return true;
            }else{
                return false;
            }
        }else{
           return false;
        }
    }
    public function travellerLangInfo( $lang_id ) {
        $this->db->select( '*' );
        $this->db->where( 'lang_id', $lang_id );
        $query  = $this->db->get( 'traveller_languages' );
        $row    = $query->row();
        return $row;
    }
}