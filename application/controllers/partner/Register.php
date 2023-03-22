<?php
defined('BASEPATH') OR exit('No direct script access allowed');  

class Register extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model( 'partner/Partnermodel' );
        $this->load->library('encryption');
    }
    public function index() {

        $dataData[ 'cityLists' ] = $this->Partnermodel->searchCityLists();
        $dataData['partnerList']  = $this->Partnermodel->partnerList();
        $content     = $this->load->view( 'partner/common/register', $dataData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Buddey Partner | Sign Up';
        $data[ 'header' ][ 'metakeyword' ]      = 'Sign Up';
        $data[ 'header' ][ 'metadescription' ]  = 'Sign Up';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $this->template( $data );
        return true;
    }

    function  addPartner(){

        $email = trim($this->input->post( 'email' ));
        $this->form_validation->set_rules( 'company_name', 'Company Name', 'required' );
        $this->form_validation->set_rules( 'password', 'Password', 'required|min_length[8]' );
        $this->form_validation->set_rules( 'mobile_no', 'Mobile Number', 'required|min_length[6]' );
        $this->form_validation->set_rules( 'email', 'Email', 'trim|required' );
        $this->form_validation->set_rules( 'city', 'City', 'required' );
        $this->form_validation->set_rules( 'postcode', 'Post Code', 'required' );
        $this->form_validation->set_rules( 'business_address', 'Business Address', 'required' );
        $this->form_validation->set_rules( 'contact_person', 'Contact Person', 'required' );
        $this->form_validation->set_rules( 'bank_name', 'Bank Name', 'required' );
        $this->form_validation->set_rules( 'account_number', 'Account Number', 'required' );
        $this->form_validation->set_rules( 'account_name', 'Account Name', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            $result = array('res_status' => 'error', 'message' => validation_errors(), 'res_data'=>array('error' => 1));
        }elseif($this->Partnermodel->EmailExists( $email ) && $email){
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
            $id = $this->Partnermodel->insertRegister( $data );
            //$this->session->set_flashdata('successMSG', 'Gigs Created successfully.');
            $result = array('res_status' => 'success', 'message' => 'Register successfully.', 'res_data'=>array('id' => strtoupper($id)));
        }
        echo json_encode($result);
    }
    
    function template( $data ){
        $this->load->view( 'partner/common/homecontent', $data );
        return true;
    }
}    
   