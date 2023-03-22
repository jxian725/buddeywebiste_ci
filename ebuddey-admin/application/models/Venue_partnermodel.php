<?php
class Venue_partnermodel extends CI_Model {  
    public $primaryKey         = 'venuepartnerId';  
    public $column_order    = array('venuepartnerId','company_name','city','email','mobile_no','business_addrss','postcode','venue_partners.status'); 
    public $column_search   = array('venuepartnerId','company_name','city','email','mobile_no','business_addrss','postcode','venue_partners.status');
    public $allcolumn_search= array('venuepartnerId','company_name','city','email','mobile_no','business_addrss','postcode','venue_partners.status');
    public $order           = array('venuepartnerId' => 'DESC'); // default order
	// New Task insert 
	public function __construct(){ 
        parent::__construct(); 
    }
    function insertRegister( $data ) { 
        $this->db->insert( 'venue_partners', $data );
        return $this->db->insert_id();
    }
    public function updateVenuePartners($venuepartnerId, $data){
    	$this->db->where( 'venuepartnerId', $venuepartnerId );
		$this->db->update( 'venue_partners', $data );
		return true; 
	}
    public function deletePartner($venuepartnerId){
        $this->db->where( 'venuepartnerId', $venuepartnerId );
        $this->db->delete( 'venue_partners' );
        return true; 
    }
    function venuePartnerInfo($venuepartnerId) { 
        $this->db->select('venue_partners.*,states.name as cityName');
        $this->db->from('venue_partners');
        $this->db->join('states', 'states.id = venue_partners.city','left');
        $this->db->where('venue_partners.venuepartnerId', $venuepartnerId );
        $query = $this->db->get();
        return $query->row();
    }   
    public function ExistsPartnerEmail($email){
        $this->db->where('email', trim($email));
        $this->db->where('status != ',4,FALSE);
        $query = $this->db->get('venue_partners');
        if( $query->num_rows() > 0 )
        { return true; }else{ return false; }
    }
    //Buskerspod tabel data
    function get_datatables($where=false){                 
        $this->_get_datatables_query();
        $this->db->where('venue_partners.status != ',2,FALSE);  
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']); 
        $query = $this->db->get();
        return $query->result(); 
    }
    function count_filtered($where=false){  
        $this->_get_datatables_query();
        $this->db->where('venue_partners.status != ',2,FALSE);   
        $query  = $this->db->get();
        return $query->num_rows(); 
    }
    public function count_all($where=false){  
        $this->db->select('venuepartnerId');
        $this->db->from('venue_partners');
        $this->db->where('venue_partners.status != ',2,FALSE);
        return $this->db->count_all_results();
    }
    private function _get_datatables_query(){

        $this->db->select('venue_partners.*, states.name as cityName');
        $this->db->from('venue_partners');
        $this->db->join('states', 'states.id = venue_partners.city','left');
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
                    $this->db->group_start(); //open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
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
    
    public function partnerList(){
        $this->db->select('partner_list.*,states.name as cityName');
        $this->db->from('partner_list');
        $this->db->join('states', 'states.id = partner_list.city_id','left');
        $this->db->where( 'partner_list.status', 1 );
        $this->db->order_by("partner_id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return false;
        }
    }
    function partner_Info( $partnerID ) {
        $this->db->where( 'partner_id', $partnerID );
        $query = $this->db->get( 'partner_list' );
        if( $query->num_rows() > 0 ) {
            return $query->row();
        } else { return false; }
    }
    function searchcityLists($search=''){
        $this->db->where( 'status', 1 );
        $this->db->where( 'country_id', 132 );
        if($search){ $this->db->like( 'name', $search ); }
        $this->db->order_by( 'name' , 'asc' );
        $query = $this->db->get( 'states' );
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else { return false; } 
    }
}
?>    