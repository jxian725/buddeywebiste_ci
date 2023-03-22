<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission {

    //Check Permission for the user
	public function check_permission() {
        global $user_privilages;
        $CI             = get_instance(); 
        $c_name         = $CI->router->fetch_class();
        $m_name         = $CI->router->fetch_method();
        $event_id  = $CI->uri->segment( 1 );
        
        /*-------------------------------LOGGED USER PERMISSION SET UP--------------------------------------*/ 
        //If user logged in accessing the login page. Redirect them to the student list page 
        /*if( $c_name == 'login' && $m_name == 'index' && $CI->session->has_userdata( 'user_id' ) ) {
            redirect( site_url( 'dashboard' ) );
            return true;
            exit();
        } else if( $c_name == 'login' && $m_name != 'index' && $CI->session->has_userdata( 'user_id' ) ) {
        } else if( $c_name != 'login' && !$CI->session->has_userdata( 'user_id' ) && $c_name != 'api' ) {
            //If user Not logged in accessing the other page. Redirect them to the login page
            redirect( site_url() );
            return true;
            exit();
        }*/
        /*----------------------------------------PAGE BASED PERMISSION SET UP--------------------------------------*/ 
        $ROLE_ID             = $CI->session->userdata('USER_ROLE_ID');
        $permission_list     = $CI->Usermodel->permission_list( $ROLE_ID );
        $permission_arr      = array();
        if( isset( $permission_list ) && !empty( $permission_list )  ) {
            foreach ( $permission_list as $key => $value ) {
                $permission_arr[ $value->module ] = $value->module;
            }
        }
        $GLOBALS[ 'permission_arr' ] = $permission_arr;
    }
}
