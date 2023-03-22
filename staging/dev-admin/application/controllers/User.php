<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model( 'Usermodel' );
        $this->load->model( 'Guidermodel' );
        $this->load->model( 'Settingsmodel' );
        $this->load->library('encryption');
        sessionset();
        error_reporting(E_ALL);
    }

    public function index() {
        $script     = '';
        $user_search  = $this->input->get('user_search');
        $order_by     = $this->input->get('order_by');
        $user_lists   = $this->Usermodel->user_lists( $user_search, $order_by );
        $data1[ 'user_lists' ]     = $user_lists;
        $content    = $this->load->view( 'user/index', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'User lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'User lists';
        $data[ 'header' ][ 'metadescription' ]  = 'User lists';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">User lists</li>';
        $this->template( $data );
    }

    public function add() {
        $script     = '';
        $role_lists = $this->Settingsmodel->role_lists();
        $data1      = array( 'role_lists' => $role_lists );
        $content    = $this->load->view( 'user/add_user', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Add User';
        $data[ 'header' ][ 'metakeyword' ]      = 'Add User';
        $data[ 'header' ][ 'metadescription' ]  = 'Add User';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'user">Manage User</a></li>
                                              <li class="active">Add User</li>';
        $this->template( $data );
    }

    function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }

    function add_user() {
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        
        $this->form_validation->set_rules( 'user_name', 'User name', 'required' );
        $this->form_validation->set_rules( 'mobile_number', 'Mobile', 'required' );

        $this->form_validation->set_rules( 'contact_email', 'Email', 'required' );
        $this->form_validation->set_rules( 'password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm password','required|matches[password]');
        //$this->form_validation->set_rules( 'role', 'Role', 'required' );
        $this->form_validation->set_rules( 'contact_full', 'Full name', 'required' );
        $this->form_validation->set_rules( 'role_type', 'Role Type', 'required' );
        
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
        } else {
            //Create the user profile
            $role_info = $this->Settingsmodel->roleInfo( $this->input->post( 'role_type' ) );
            $user_data[ 'full_name' ]          = $this->input->post( 'contact_full' );
            $user_data[ 'username' ]           = $this->input->post( 'user_name' );
            $user_data[ 'user_email' ]         = $this->input->post( 'contact_email' );
            $user_data[ 'contact_number' ]     = $this->input->post( 'mobile_number' );
            $user_data[ 'password' ]           = $this->encryption->encrypt($this->input->post( 'password' ));
            //$user_data[ 'password' ]           = password_hash($this->input->post( 'password' ), PASSWORD_DEFAULT);
            $user_data[ 'address' ]            = $this->input->post( 'address' );
            $user_data[ 'account_type' ]       = $this->input->post( 'role_type' );
            $user_data[ 'login_type' ]         = $role_info->role;
            $user_data[ 'createdon' ]          = date( 'Y-m-d' );
            $this->db->insert( 'user', $user_data );
            //echo $this->db->last_query();
            echo 1;
        }
        return true;
    }

    //Password Update
    function passwordConfirm() {
        $user_id  = $this->input->post( 'user_id' );
        $data1[ 'user_id' ] = $user_id;
        $data1[ 'type' ]    = $this->input->post( 'type' );
        echo $this->load->view( 'user/password_update_form', $data1, true );
        return true;
    }

    //Check Password Exists
    function verify_password_info() {
        $id     = $this->input->post( 'user_id' );
        $type   = $this->input->post( 'type' );
        $user_id    = $this->session->userdata( 'USER_ID' );
        $password   = $this->input->post( 'password' );
        $this->form_validation->set_rules( 'password', 'Password', 'required|min_length[5]|max_length[10]' );
        if( $this->form_validation->run() == FALSE ) {
            $data[ 'Jmsg' ]     = validation_errors();
            $data[ 'res' ]      = 1;
            echo json_encode( $data );
            return false;
        } else {
            if($type == 'change'){
                if( $this->Guidermodel->check_password( $user_id, $password ) ) {
                    $data[ 'Jmsg' ] = 'New password cannot be the same as the old password';
                    $data[ 'res' ]   = 5;
                    echo json_encode( $data );
                } else {
                    $data[ 'res' ]   = 4;
                    $data[ 'Jmsg' ]  = 'Password Changed successfully.';
                    $user_data[ 'password' ] = $this->encryption->encrypt($password);
                    $this->Usermodel->updateUserInfo($user_data, $id);
                    echo json_encode( $data );
                }
            }else{
                if( !$this->Guidermodel->check_password( $user_id, $password ) ) {
                    $data[ 'Jmsg' ] = 'Enter password not matched.';
                    $data[ 'res' ]  = 3;
                    echo json_encode( $data );
                } else {
                    $data[ 'res' ]  = 2;
                    echo json_encode( $data );
                }
            }
        }
    }
    function deleteUser(){
        $user_id    = $this->input->post( 'user_id' );
        $status     = $this->input->post( 'status' );
        if($user_id != $this->session->userdata( 'USER_ID' )){
            $data       = array('status' => $status);
            $this->db->where( 'user_id', $user_id );
            $this->db->update( 'user', $data );
            return true;
        }else{
            return false;
        }
    }
    //Function edit
    function edit() {
        $user_id    = $this->uri->segment(3);
        $script     = '';
        $userInfo   = $this->Usermodel->userInfo($user_id);
        $data1      = array( 'user_id' => $user_id, 'userInfo' => $userInfo );
        $content    = $this->load->view( 'user/edit_user', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Edit User';
        $data[ 'header' ][ 'metakeyword' ]      = 'Edit User';
        $data[ 'header' ][ 'metadescription' ]  = 'Edit User';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'user">Manage User</a></li>
                                              <li class="active">Edit User</li>';
        $this->template( $data );
    }


    function edit_user() {

        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }

        $this->form_validation->set_rules( 'user_name', 'User name', 'required' );
        $this->form_validation->set_rules( 'mobile_number', 'Mobile', 'required' );

        $this->form_validation->set_rules( 'contact_email', 'Email', 'required' );
        
        $this->form_validation->set_rules( 'contact_full', 'Full name', 'required' );
        
        
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
        } else {
            $role_info = $this->Settingsmodel->roleInfo( $this->input->post( 'role_type' ) );
            $user_id                           = $this->input->post( 'user_id' );
            $user_data[ 'full_name' ]          = $this->input->post( 'contact_full' );
            $user_data[ 'username' ]           = $this->input->post( 'user_name' );
            $user_data[ 'user_email' ]         = $this->input->post( 'contact_email' );
            $user_data[ 'contact_number' ]     = $this->input->post( 'mobile_number' );
            $user_data[ 'address' ]            = $this->input->post( 'address' );
            $user_data[ 'account_type' ]       = $this->input->post( 'role_type' );
            $user_data[ 'login_type' ]         = $role_info->role;
            $this->db->where( 'user_id', $user_id );            
            $this->db->update( 'user', $user_data );
            echo 1;
        }
        return true;
    }

}