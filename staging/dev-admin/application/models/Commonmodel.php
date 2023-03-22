<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Commonmodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	//Host total count
	function host_total_count( $startDate = '', $endDate = '' ) {
		$this->db->select('*');
	    $this->db->where( 'status !=', 4 );
	    //Date Range
	    if( ( $startDate ) && ( $endDate ) ) {
    		$this->db->where( 'created_on >=', $startDate );
    		$this->db->where( 'created_on <=', $endDate );
    	}
	    $query = $this->db->get( 'guider' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	return $rowcount;
	    } else {
	    	return 0;
	    }
	}
	function DHostTotalCount( $startDate = '', $endDate = '' ) {
		$this->db->select('*');
	    $this->db->where( 'status !=', 4 );
	    $this->db->where( 'status !=', 0 );
	    //Date Range
	    if( ( $startDate ) && ( $endDate ) ) {
    		$this->db->where( 'created_on >=', $startDate );
    		$this->db->where( 'created_on <=', $endDate );
    	}
	    $query = $this->db->get( 'guider' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	return $rowcount;
	    } else {
	    	return 0;
	    }
	}
	function DPendingHostTotalCount( $startDate = '', $endDate = '' ) {
		$this->db->select('*');
	    $this->db->where( 'status', 0 );
	    //Date Range
	    if( ( $startDate ) && ( $endDate ) ) {
    		$this->db->where( 'created_on >=', $startDate );
    		$this->db->where( 'created_on <=', $endDate );
    	}
	    $query = $this->db->get( 'guider' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	return $rowcount;
	    } else {
	    	return 0;
	    }
	}
	//Guest total count
	function guest_total_count( $startDate = '', $endDate = '' ) {
		$this->db->select('*');
	    $this->db->where( 'status !=', 4 );
	    //Date Range
	    if( ( $startDate ) && ( $endDate ) ) {
    		$this->db->where( 'created_on >=', $startDate );
    		$this->db->where( 'created_on <=', $endDate );
    	}
	    $query = $this->db->get( 'traveller' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	return $rowcount;
	    } else {
	    	return 0;
	    }
	}
	//Newsletter total count
	function newsletter_total_count() {
		$this->db->select('*');
	    $this->db->where( 'status !=', 4 );
	    $query = $this->db->get( 'newsletter' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	return $rowcount;
	    } else {
	    	return 0;
	    }
	}
	//Service total count
	function service_total_count( $startDate = '', $endDate = '' ) {
		$this->db->select('*');
	    $this->db->where( 'status !=', 4 );
	    //Date Range
	    if( ( $startDate ) && ( $endDate ) ) {
    		$this->db->where( 'service_date >=', $startDate );
    		$this->db->where( 'service_date <=', $endDate );
    	}
	    $query = $this->db->get( 'service_list' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	return $rowcount;
	    } else {
	    	return 0;
	    }
	}
	function ActiveBookingTotal_count( $startDate = '', $endDate = '' ) {
		$this->db->select('*');
		$this->db->from('journey_list');
		$this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id');
	    $this->db->where( 'jny_status', 2 );
	    //Date Range
	    if( ( $startDate ) && ( $endDate ) ) {
    		$this->db->where( 'service_date >=', $startDate );
    		$this->db->where( 'service_date <=', $endDate );
    	}
	    $query = $this->db->get();
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	return $rowcount;
	    } else {
	    	return 0;
	    }
	}
	//Service Revenue total count
	function revenue_total_count( $startDate = '', $endDate = '' ) {
		$this->db->select('SUM(guider_charged) as totalRevenue');
	    $this->db->where( 'status !=', 4 );
	    //Date Range
	    if( ( $startDate ) && ( $endDate ) ) {
    		$this->db->where( 'service_date >=', $startDate );
    		$this->db->where( 'service_date <=', $endDate );
    	}
	    $query = $this->db->get( 'service_list' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$row = $query->row();
	    	return $row->totalRevenue;
	    } else {
	    	return 0;
	    }
	}
	//Host pending total count
	function host_total_pending( $status, $startDate = '', $endDate = '' ) {
		$this->db->select('*');
	    $this->db->where( 'status', $status );
	    //Date Range
	    if( ( $startDate ) && ( $endDate ) ) {
    		$this->db->where( 'created_on >=', $startDate );
    		$this->db->where( 'created_on <=', $endDate );
    	}
	    $query = $this->db->get( 'guider' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	return $rowcount;
	    } else {
	    	return 0;
	    }
	}
	//File Upload
    function fileUpload( $folder, $allowedTypes = false ) {
    	if(!$allowedTypes){ $allowedTypes = 'gif|jpg|jpeg|png'; }
    	$upload_path_url         = $this->config->item( 'upload_path_url' );
        $json = array();
        $config['upload_path']   = './uploads/'.$folder.'/thumb/'; 
        $config['allowed_types'] = $allowedTypes; 
        $config['max_size']      = 400000; 
        $config['encrypt_name']  = true;
        $config['max_width']     = 2400;
        $config['max_height']    = 2400;
        $this->load->library('upload', $config);
        
        $this->upload->initialize($config);
        if ( ! $this->upload->do_upload('image')) {
            $json['error'] = $this->upload->display_errors();
        } else {
            $upload_data = $this->upload->data();
            $resize_conf = array(
                'upload_path'  => realpath('./uploads/'.$folder.'/thumb/'),
                'source_image' => $upload_data['full_path'], 
                'new_image'    => './uploads/'.$folder.'/'.$upload_data['file_name'],
                'width'        => 285,
                'height'       => 200);
            $this->load->library('image_lib'); 
            $this->image_lib->initialize($resize_conf);
            
            if ( ! $this->image_lib->resize()){
                $json['error'] = $this->image_lib->display_errors();
            }else{
				$json['ProfilePicture'] = $upload_data['file_name'];
                $json['ProfilePic']     = $upload_path_url.''.$folder.'/'.$upload_data['file_name'];                        
            }   
            $json['success'] = "A file has been uploaded successfully";
        }
        return $json;
    }
    //NEW FILE UPLOAD
    function newFileUpload( $filename, $folder, $rename='', $size = '', $width = '', $height = '' ) {
        if( !$_FILES[ "$filename" ][ 'name' ] ) {
            return false;
        } 
        $_FILES[ "$filename" ][ 'name' ]; 
        $config['upload_path']      = './uploads/'.$folder;
        $config['allowed_types']    = 'gif|jpg|jpeg|png';
        $config['max_size']         = 500000;
        $config['max_width']        = $width;
        $config['max_height']       = $height;

        $filetype                   = $_FILES[ "$filename" ][ 'type' ];
        $expfiletype                = explode( '.', $_FILES[ "$filename" ][ 'name' ] );  
        $expfiletypeEx              = end($expfiletype);
        $config['file_name']        = $rename.'.'.$expfiletypeEx;

        $this->upload->initialize( $config );
        $this->load->library( 'upload', $config );

        if ( ! $this->upload->do_upload( $filename ) ) {
            $res = array( 'status' => 'error','msg' => $this->upload->display_errors() );
            return $res;
        } else {
            $uploadedImage = $this->upload->data();
            $this->resizeImage($uploadedImage['file_name'], $folder);
            $res  = array( 'status' => 'success','file_name' => $config['file_name'], 'msg' => 'file upload successfully.' );
            return $res;
        }
    }
    public function resizeImage($filename, $folder)
    {
        $upload_path_url = $this->config->item( 'upload_path_url' );
        $source_path    = './uploads/'.$folder.'/'.$filename;
        $target_path    = './uploads/'.$folder.'/thumb/';
        $this->load->library('image_lib');
        $config_manip   = array(
                          'image_library' => 'gd2',
                          'source_image'  => $source_path,
                          'new_image'     => $target_path,
                          'maintain_ratio'=> TRUE,
                          'create_thumb'  => TRUE,
                          'thumb_marker'  => '',
                          'width'         => 150,
                          'height'        => 150
                        );
        $this->image_lib->initialize($config_manip);
        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }
        $this->image_lib->clear();
    }
    //END NEW FILE UPLOAD

    function payment_count( $startDate = '', $endDate = '' ) {
		$this->db->select('
							SUM(sub_total) AS payoutAmt,
                    		SUM(percentage_amount) AS percentageAmt, 
                    		SUM(transaction_amount) AS transactionAmt,
                    		SUM(paid_to_guider) AS guiderAmt,
                    		SUM(service_fees) AS serviceAmt
                    	 ');
        $this->db->from('senangpay_transaction');
        $this->db->join('journey_list', 'journey_list.jny_service_id = senangpay_transaction.serviceID');
        $this->db->where('senangpay_transaction.pay_status',1,FALSE);
	    //Date Range
	    if( ( $startDate ) && ( $endDate ) ) {
    		$this->db->where( 'DATE(senangpay_transaction.pay_updated) >=', $startDate );
    		$this->db->where( 'DATE(senangpay_transaction.pay_updated) <=', $endDate );
    	}
	    $query = $this->db->get();
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$row = $query->row();
	    	return $row;
	    } else {
	    	return 0;
	    }
	}
    public function siteInfo( $key ){
        $this->db->select( '*' );
        $this->db->where( 's_key', "$key" );
        $query  = $this->db->get( 'site_setting' );
        $row    = $query->row();
        return $row;
    }
    //Image List
    function newsletter_image() {
    	$this->db->select('*');
	    $this->db->where( 'status', 1 );
	    $query = $this->db->get( 'newsletter' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$result = $query->result();
	    	return $result;
	    } else {
	    	return false;
	    }
    }	
    function state_list($country_id=false, $status=false) {

        $this->db->select('*');
        $this->db->from('states');
        $this->db->where( 'country_id', $country_id );
        if($status){ $this->db->where( 'status', $status ); }
        $this->db->order_by("name", "asc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $stateLists = $query->result();
            return $stateLists;
        }else {
            return false; 
        }
    }
    function cityStatus( $city_id, $status ) {
        $data2      = array('status' => $status);
        $table2     = $this->db->dbprefix( 'states' );
        $this->db->where( 'id', $city_id );
        $this->db->update( $table2, $data2 );
        return true;
    }
    public function cityInfo( $city_id ){
        $this->db->select( '*' );
        $this->db->where( 'id', $city_id );
        $query  = $this->db->get( 'states' );
        $row    = $query->row();
        return $row;
    }
    function getAllActivityLists() {
        $this->db->select('guider_activity_list.*,guider.first_name AS guiderName');
        $this->db->from('guider_activity_list');
        $this->db->join('guider', 'guider.guider_id = guider_activity_list.activity_guider_id', 'left');
        $this->db->where( 'activity_status', 1 );
        $this->db->order_by("activity_id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        }
    }
    function InitiateSenangpay( $data ){
        $table = $this->db->dbprefix( 'senangpay_transaction' );
        $this->db->insert( $table, $data );
        $payment_id = $this->db->insert_id();
        return $payment_id;
    }
    function insertDonorInfo( $data ){
        $table = $this->db->dbprefix( 'qrscan_donate_users' );
        $this->db->insert( $table, $data );
    }
    function insertServiceLog( $data ){
        $table = $this->db->dbprefix( 'service_log' );
        $this->db->insert( $table, $data );
    }
}