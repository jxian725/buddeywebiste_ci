<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Newsletterapimodel extends CI_Model {
    public function __construct(){
        parent::__construct();
    }
    //Get Newsletter List
    function get_newsletter_list($limit, $start) {
        $data = array();

        $this->db->select('*');
        $this->db->from('newsletter');
        $this->db->where('status', 1 );
        $this->db->order_by("newsletter_id", "desc");
        if($limit || $start){ $this->db->limit($limit, $start); }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $newsletterLists   = $query->result();
            foreach ($newsletterLists as $newsletter) {
                $data[] = array(
                        "newsletter_id" => "". $newsletter->newsletter_id ."",
                        "title"         => "". rawurldecode( $newsletter->title ) ."",
                        "description"   => "". strip_tags(rawurldecode( $newsletter->description )) ."",
                        "image"         => "". $newsletter->image ."",
                        "video_url"     => "". $newsletter->video_url ."",
                        "created_on"    => "". $newsletter->created_on .""
                        );        
            }
            $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Get Newsletter list Successfully.', 'result' => 'success', 'data' => $data);
            return $result;
        }else {
            $data[] = array('error' => 1);
            $result = array('response_code' => ERROR_CODE, 'response_description' => 'No Newsletter found.', 'result' => 'error', 'data'=>$data);
            return $result; 
        }
    }
}