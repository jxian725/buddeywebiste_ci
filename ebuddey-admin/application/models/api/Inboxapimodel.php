<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Inboxapimodel extends CI_Model {
     public function __construct(){
        parent::__construct();
     }
    //Add Message
    public function insertAdminMessage( $data ) {
		
		
        $this->db->insert( 'inbox', $data );
        return $this->db->insert_id();
    }
	public function insertTalentMessage( $data ) {
		
		
        $this->db->insert( 'inbox', $data );
        return $this->db->insert_id();
    }
	//Get AdminMessage info
	public function admin_talentInboxinfo($guider_id,$pageno){
		
			$pagelimit=$pageno*10;
		
			$sql="SELECT * FROM (SELECT * FROM (
			SELECT t1.*, t2.first_name, t2.last_name, t2.phone_number FROM inbox t1,guider t2 where t1.talent_id=t2.guider_id and t1.talent_id=$guider_id and t1.is_admin_delete=0 ORDER BY id DESC LIMIT $pagelimit) sub 
			ORDER BY id ASC LIMIT 10) sub1
			ORDER BY id DESC";    
			$query = $this->db->query($sql);
			return $query->result_array();
	
    }
	//Get Message info
	public function talent_talentInboxinfo($guider_id,$pageno){
		
		$pagelimit=$pageno*10;
		
		$sql="SELECT * FROM (SELECT * FROM (
		SELECT t1.*, t2.first_name, t2.last_name, t2.phone_number FROM inbox t1,guider t2 where t1.talent_id=t2.guider_id and t1.talent_id=$guider_id and t1.is_admin_delete=0 and t1.istalent_delete=0 ORDER BY id DESC LIMIT $pagelimit) sub 
		ORDER BY id ASC LIMIT 10) sub1
		ORDER BY id DESC";    
		$query = $this->db->query($sql);
		return $query->result_array();
		
    }
	//Delete Admin Message
	 public function admin_deleteMessage($msgid,$data){
        $this->db->where('id', $msgid );
        $rlt=$this->db->update( 'inbox', $data );
		return true;
	
    }
	//Delete Admin Message
	 public function talent_deleteMessage($msgid,$data){
        $this->db->where('id', $msgid );
        $rlt=$this->db->update( 'inbox', $data );
		return true;
	
    }
	function adminInboxReadinfo($talent_id){
		
        $this->db->select('COUNT(*) AS unreadmsg');
        $this->db->from('inbox');
        $this->db->where('talent_id', $talent_id);
        $this->db->where('isadmin_readstatus', 1); 
		$this->db->where( 'istalent_message', 1);
		$query = $this->db->get();
       
        $result=$query->result_array();
		$msgcount=$result[0]['unreadmsg'];
		
        return $msgcount;
    }
	function talentInboxReadinfo($talent_id){
		
        $this->db->select('COUNT(*) AS unreadmsg');
        $this->db->from('inbox');
        $this->db->where('talent_id', $talent_id);
        $this->db->where('istalent_readstatus', 1); 
		$this->db->where( 'is_admin_message', 1);
		$query = $this->db->get();
       
        $result=$query->result_array();
		$msgcount=$result[0]['unreadmsg'];
        return $msgcount;
    }
}
?>