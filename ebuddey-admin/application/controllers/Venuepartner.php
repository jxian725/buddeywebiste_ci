 <?php
defined('BASEPATH') OR exit('No direct script access allowed');    

class Venuepartner extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model( 'Venue_partnermodel' );
        sessionset();
    }
    public function index(){
        
        $tplData['partnerList']  = $this->Venue_partnermodel->partnerList();
        $content    = $this->load->view( 'venue_partner/list', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Venue Partner Lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Venue Partner Lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Venue Partner Lists';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Venue Partner Lists</li>';
        $this->template( $data );
    }
    public function partnerTableResponse(){
        global $permission_arr;
        
        $data           = array();
        $results        = array();
        $where          = array();
        $partnerLists   = $this->Venue_partnermodel->get_datatables($where);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $data = array();
        if($partnerLists){
            $timeInterval   = '';
            foreach ($partnerLists as $partner) {
                $password   = $this->encryption->decrypt($partner->empPassword);
                if ($partner->status == 1) {
                    $status = '<span class="label label-sm label-success">Activate</span>';
                    $statusbtn   = '<a href="javascript:;" data-toggle="tooltip" title="Inactive" onClick="return updatePartnerStatus('.$partner->venuepartnerId.',0);" class="btn btn-success btn-xs"><i class="fa fa-toggle-on"></i></a>';
                }elseif ($partenr->status == 0) {
                    $status = '<span class="label label-sm label-danger">Inactive</span>';
                    $statusbtn   = '<a href="javascript:;" data-toggle="tooltip" title="Activate" onClick="return updatePartnerStatus('.$partner->venuepartnerId.',1);" class="btn btn-warning btn-xs"><i class="fa fa-toggle-off"></i></a>';
                }
                // Search button
                $searchbtn ='<a class="btn btn-info btn-xs" href="'.base_url().'Venuepartner/partner_profile/'.$partner->venuepartnerId.'" data-toggle="tooltip" title="search"><i class="fa fa-search"></i></a>';
                // Add Partner button
                $addbtn ='<a class="btn btn-primary btn-xs" onClick="return addPartner('.$partner->venuepartnerId.',0);" data-toggle="tooltip" title="Add Partner"><i class="fa fa-plus"></i></a>';
                // Delete button
                $deletebtn ='<a class="btn btn-danger btn-xs" onClick="return deletePartner('.$partner->venuepartnerId.');" data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i></a>'; 
               
                $row    = array();
                $row[]  = $si_no;
                $row[]  = $partner->company_name;
                $row[]  = $partner->cityName;
                $row[]  = $partner->email;
                $row[]  = $partner->mobile_no;
                $row[]  = $partner->business_address;
                $row[]  = $partner->postcode;
                $row[]  = $status;
                $row[]  = $statusbtn.$searchbtn.$addbtn.$deletebtn;
                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Venue_partnermodel->count_all($where),
                        "recordsFiltered"   => $this->Venue_partnermodel->count_filtered($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }

    public function add()
    {
        $tplData    = array();
        $tplData[ 'cityLists' ] = $this->Venue_partnermodel->searchCityLists();
        $content    = $this->load->view( 'venue_partner/add', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Add Venuepartner';
        $data[ 'header' ][ 'metakeyword' ]      = 'Add Venuepartner';
        $data[ 'header' ][ 'metadescription' ]  = 'Add Venuepartner';
        $data[ 'content' ]                      = $content;
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'breadcrumb' ]                   = '
                                                   <li><a href="'.$this->config->item( 'admin_url' ).'venuepartner">Manage Venuepartner</a></li>
                                                   <li class="active">Add Venuepartner</li>';
        $this->template( $data );
    }

    function  insertVenuePartner(){

        $email = trim($this->input->post( 'email' ));
        $this->form_validation->set_rules( 'company_name', 'Company Name', 'required' );
        $this->form_validation->set_rules( 'password', 'Password', 'required|min_length[8]' );
        $this->form_validation->set_rules( 'mobile_no', 'Mobile Number', 'required|min_length[6]' );
        $this->form_validation->set_rules( 'email', 'Email', 'trim|required' );
        $this->form_validation->set_rules( 'city', 'City', 'required' );
        $this->form_validation->set_rules( 'postcode', 'Post Code', 'required' );
        $this->form_validation->set_rules( 'business_address', 'Business Address', 'required' );
        $this->form_validation->set_rules( 'contact_person', 'Contact Person', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            $result = array('res_status' => 'error', 'message' => validation_errors(), 'res_data'=>array('error' => 1));
        }elseif($this->Venue_partnermodel->ExistsPartnerEmail( $email ) && $email){
            $result = array('res_status' => 'error', 'message' => 'Email already exists .', 'res_data'=>array('error' => 2));   
        } else {
            $password   = $this->encryption->encrypt($this->input->post( 'password' ));
            $mobile_no  = ltrim(trim($this->input->post( 'mobile_no' )), '0');
            $data   = array(
                        'company_name'     => trim($this->input->post( 'company_name' )),
                        'mobile_no'        => $mobile_no,
                        'email'            => $email,
                        'city'             => trim($this->input->post( 'city' )),
                        'business_address' => trim($this->input->post( 'business_address' )),
                        'postcode'         => trim($this->input->post( 'postcode' )),
                        'contact_person'   => trim($this->input->post( 'contact_person' )),
                        'bank_name'        => trim($this->input->post( 'bank_name' )),
                        'account_name'     => trim($this->input->post( 'account_name' )),
                        'account_number'   => trim($this->input->post( 'account_number' )),
                        'status'           => 0,
                        'is_read'          => 1,
                        'password'         => $password,
                        'createdon'        => date("Y-m-d H:i:s")
                    );
            $id = $this->Venue_partnermodel->insertRegister( $data );
            $result = array('res_status' => 'success', 'message' => 'Add Venue partner successfully.', 'res_data'=>array('id' => strtoupper($id)));
        }
        echo json_encode($result);
    }

    public function partner_profile() {

        $script      = '';
        $venuepartnerId     = $this->uri->segment(3);
        $data_db[ 'partnerInfo' ] = $this->Venue_partnermodel->venuePartnerInfo($venuepartnerId);
        $content     = $this->load->view( 'venue_partner/partner_profile', $data_db, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Venue Partner Profile';
        $data[ 'header' ][ 'metakeyword' ]      = 'Partner Profile';
        $data[ 'header' ][ 'metadescription' ]  = 'Partner Profile';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
       $data[ 'breadcrumb' ]                    = '<li class="active">Venue Partner Profile</li>';
        $this->template( $data );
        return true;
    }
    function updatePartnerForm() {
        $venuepartnerId  = $this->input->post( 'venuepartnerId' );
        $data1[ 'venuepartnerId' ]  = $venuepartnerId;
        $data1[ 'field' ]      = $this->input->post( 'field' );
        $data1[ 'partnerInfo' ] = $this->Venue_partnermodel->venuePartnerInfo( $venuepartnerId );
        $data1[ 'cityLists' ] = $this->Venue_partnermodel->searchCityLists();
        echo $this->load->view( 'venue_partner/update_info_form', $data1, true );
    }
    function updatePartnerField() {
        $venuepartnerId  = $this->input->post( 'venuepartnerId' );
        $field      = $this->input->post( 'field' );
        $data       = array();
        if($field == 'business_address'){
            $data['business_address'] = $this->input->post( 'business_address' );
        }elseif ($field == 'postcode') {
            $data['postcode'] = $this->input->post( 'postcode' );
        }elseif ($field == 'email') {
            if($this->Venue_partnermodel->ExistsPartnerEmail($this->input->post( 'email' ) )){
                $data[ 'message' ] = 'Email already registred. please try new Email.';
                echo json_encode( $data ); 
                return true;
            }else{
               $data['email'] = $this->input->post( 'email' );
            }
            
        }elseif ($field == 'contact_person') {
            $data['contact_person'] = $this->input->post( 'contact_person' );
        }elseif ($field == 'company_name') {
            $data['company_name'] = $this->input->post( 'company_name' );    
        }elseif ($field == 'city') {
            $data['city'] = $this->input->post( 'city' );
        }elseif ($field == 'mobile_no') {
            $data['mobile_no'] = $this->input->post( 'mobile_no' );
        }elseif ($field == 'bank_name') {
            $data['bank_name'] = $this->input->post( 'bank_name' );
        }elseif ($field == 'account_name') {
            $data['account_name'] = $this->input->post( 'account_name' );
        }elseif ($field == 'account_number') {
            $data['account_number'] = $this->input->post( 'account_number' );
        }elseif ($field == 'password') {
            $password = $this->encryption->encrypt($this->input->post( 'password' ));
            $data['password'] = $password;

        }
        if(count($data) > 0){
            $this->Venue_partnermodel->updateVenuePartners( $venuepartnerId, $data );
        }
        $data[ 'status' ]  = 1;
        echo json_encode( $data );
        return true;
    }
    function updatePartnerStatus(){ 
        $venuepartnerId   = $this->input->post( 'venuepartnerId' );
        $status       = $this->input->post( 'status' );
        $partnerInfo  = $this->Venue_partnermodel->venuePartnerInfo($venuepartnerId);
        if($partnerInfo){
            $data   = array('status' => $status);
            $this->Venue_partnermodel->updateVenuePartners($venuepartnerId, $data);
            if($status==1){ $moduleAction = 'ACTIVE'; }else{ $moduleAction = 'DEACTIVE'; }
            $this->session->set_flashdata('successMSG', 'Status updated successfully.');
            $data[ 'status' ]  = 1;
            echo json_encode( $data );
        }else{
            $this->session->set_flashdata('errorMSG', 'Some error occurred. Please try later'); 
        }
    }
    function deletePartner(){
        $venuepartnerId   = $this->input->post( 'venuepartnerId' );
        $partnerInfo  = $this->Venue_partnermodel->venuePartnerInfo($venuepartnerId);
        if($partnerInfo){
            $this->Venue_partnermodel->deletePartner($venuepartnerId);
            $this->session->set_flashdata('successMSG', 'Delete Partner successfully.');
            $data[ 'status' ]  = 1;
            echo json_encode( $data );
        }else{
            $this->session->set_flashdata('errorMSG', 'Some error occurred. Please try later');
        }
    }
    public function addPartner() {

        $data['venuepartnerId'] = $this->input->post( 'venuepartnerId' );
        $data['field']          = $this->input->post('field');
        $venuepartnerId         = $this->input->post( 'venuepartnerId' );
        $data['partnerInfo']    = $this->Venue_partnermodel->venuePartnerInfo($venuepartnerId);
        $data['partnerList']    = $this->Venue_partnermodel->partnerList(); 
        echo  $this->load->view( 'venue_partner/add_partner', $data, true ); 
    }
    function updatePartner(){
        $venuepartnerId   = $this->input->post( 'venuepartnerId' );
        $partner_id = implode(',',$this->input->post( 'partner_id[]' ));

        $partnerInfo  = $this->Venue_partnermodel->venuePartnerInfo($venuepartnerId);
        if($partnerInfo){
            $data   = array('partner_id' => $partner_id);
            $this->Venue_partnermodel->updateVenuePartners($venuepartnerId, $data);
            $this->session->set_flashdata('successMSG', 'Partner Update successfully.');
            $data[ 'status' ]  = 1;
            echo json_encode( $data );
        }else{
            $this->session->set_flashdata('errorMSG', 'Some error occurred. Please try later');
        }
    }
    
    function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}    