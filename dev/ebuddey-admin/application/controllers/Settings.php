<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper( 'admin_helper.php' );
        $this->load->model( 'Settingsmodel' );
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $script     = '';
        $role_lists = $this->Settingsmodel->role_lists();
        $data1[ 'role_lists' ]     = $role_lists;
        $content    = $this->load->view( 'role/index', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Role lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Role lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Role lists';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Role lists</li>';
        $this->template( $data );
	}
    //Delete Role
    function delete_role() {
        $role_id = $this->input->post( 'role_id' );
        $this->db->where( 'role_id', $role_id );
        $this->db->delete( 'role' );
        echo 1;
        return true;
    }
    //Validation
    function roleValidate() {
        //Validate the form
        $this->form_validation->set_rules( 'role', 'Role name', 'required' );
        $this->form_validation->set_rules( 'role_code', 'Role Code', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
            return false;
        } else {
            $role = rawurlencode( $this->input->post( 'role' ) );
            $role_code = rawurlencode( $this->input->post( 'role_code' ) );
            $data = array(
                    'role'          => $role,
                    'role_code'     => $role_code,
                    'created_on'    => date( 'Y-m-d' )
                    );
            echo $this->Settingsmodel->addrole( $data );
        }
        return true;
    }
    //Edit form
    function edit_role() {
        $role_id = $this->input->post( 'role_id' );
        $role_info = $this->Settingsmodel->roleInfo( $role_id );
        $data = array( 'role_id' => $role_id, 'role_info' => $role_info );
        echo $this->load->view( 'role/edit', $data, true );
        return true;
    }
    //Update role form
    function updatevalidaterole() {
        //Validate the form
        $this->form_validation->set_rules( 'role', 'Role name', 'required' );
        $this->form_validation->set_rules( 'role_code', 'Role Code', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
            return false;
        } else {
            $role = rawurlencode( $this->input->post( 'role' ) );
            $role_code = rawurlencode( $this->input->post( 'role_code' ) );
            $role_id   = $this->input->post( 'role_id' );
            $data = array(
                    'role'          => $role,
                    'role_code'     => $role_code
                    );
            echo $this->Settingsmodel->updaterole( $data, $role_id );
        }
        return true;
    }

    public function permission() {
        global $permission_arr;
        if( !in_array( 'role/permission', $permission_arr ) ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $role_id                                = $this->uri->segment( 3 );
        $role_info                              = $this->Settingsmodel->roleInfo( $role_id );
        $data_content['role_id']                = $role_id;
        $data_content['role_info']              = $role_info;
        $permission_list                        = $this->Settingsmodel->permission_list( $role_id );
        $data_content ['permission_list']       = $permission_list;

        $content                                = $this->load->view( 'role/add_permission_form',$data_content, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Add permission';
        $data[ 'header' ][ 'metakeyword' ]      = 'Add permission';
        $data[ 'header' ][ 'metadescription' ]  = 'Add permission';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Add permission</li>';
        $this->template( $data );
        return true;
    }

    public function add_permission() {

        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $role_id             = $this->input->post('role_id'); 
        $pendingguider       = $this->input->post('pendingguider');
        $guider              = $this->input->post('guider');
        $traveller           = $this->input->post('traveller');
        $newsletter          = $this->input->post('newsletter');
        $category            = $this->input->post('category');
        $journey             = $this->input->post('journey');
        $role                = $this->input->post('role');
        $cms                 = $this->input->post('cms');

        if(isset( $_POST['pendingguider'])){
            $pendingguider = $_POST['pendingguider'];
        }
        if(isset( $_POST['guider'])){
            $guider = $_POST['guider'];
        }
        if(isset( $_POST['traveller'])){
            $traveller = $_POST['traveller'];
        }
        if(isset( $_POST['newsletter'])){
            $newsletter = $_POST['newsletter'];
        }
        if(isset( $_POST['category'])){
            $category = $_POST['category'];
        }
        if(isset( $_POST['journey'])){
            $journey = $_POST['journey'];
        }
        if(isset( $_POST['role'])){
            $role = $_POST['role'];
        }
        if(isset( $_POST['cms'])){
            $cms = $_POST['cms'];
        }
        $modules   = '';
        $mod       = '';
        $module    = array( $pendingguider, $guider, $traveller, $newsletter, $category, $journey, $role, $cms );
        //$this->Settingsmodel->delete_permission_role( $role_id );
        $data2 = array('status' => 0);
        $this->Settingsmodel->updateAllPermission($role_id, $data2);
        foreach ( $module as $key => $value ) {
            $modules   = $value;
            if( $modules ) {
                foreach ( $modules as $key => $mod ) {
                    $created_on                      = date( 'Y-m-d H:i:s' );
                    $permission_data[ 'role_id' ]    = $role_id;
                    $permission_data[ 'module' ]     = $mod;
                    $permission_data[ 'created_on' ] = $created_on;
                    
                    if($this->Settingsmodel->rolePermissionInfo($role_id, $mod)){
                        $data3 = array('status' => 1);
                        $this->Settingsmodel->updatePermission( $role_id, $mod, $data3 );
                    }else{
                        $this->Settingsmodel->addPermission( $permission_data );
                    }
                }
            }         
        }
        echo 1;
        return true;
    }

	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
