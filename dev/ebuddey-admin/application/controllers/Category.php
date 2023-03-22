<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper( 'admin_helper.php' );
        $this->load->model( 'Categorymodel' );
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {

        $specialization     = $this->input->post( 'specialization' );
        $specialization_id  = $this->input->post( 'specialization_id' );
        if($specialization && !$specialization_id){
            $imageType      = '';
            $file_name      = '';
            $rename         = 'category_'.time();
            if ($_FILES['category_img']['name']) {
                $file_name  = $this->fileUpload( 'category_img', 'category', $rename );
            }
            if($file_name) {
                $data[ 'category_img' ] = $file_name;
            }
            $data['specialization'] = rawurlencode( $specialization );
            $data['created_on']     = date( 'Y-m-d' );
            $this->Categorymodel->addspecialization( $data );
            redirect(base_url('category'));
        }
        if($specialization && $specialization_id){
            $imageType      = '';
            $file_name      = '';
            $rename         = 'category_'.time();
            if ($_FILES['category_img']['name']) {
                $file_name  = $this->fileUpload( 'category_img', 'category', $rename );
            }
            if($file_name) {
                $data2[ 'category_img' ] = $file_name;
            }
            $data2['specialization'] = rawurlencode( $specialization );
            $this->Categorymodel->updateSpecialization( $data2, $specialization_id );
            redirect(base_url('category'));
        }
        $script             = '';
        $specialization_lists = $this->Categorymodel->specialization_lists();
        $data1[ 'specialization_lists' ]     = $specialization_lists;
        $content    = $this->load->view( 'category/index', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Category lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Category lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Category lists';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Category lists</li>';
        $this->template( $data );
	}
    //Delete specialization
    function delete_specialization() {
        $specialization_id = $this->input->post( 'specializationID' );
        $this->db->where( 'specialization_id', $specialization_id );
        $this->db->delete( 'specialization' );
        echo 1;
        return true;
    }
    function editCategoryForm() {

        $specialization_id = $this->input->post( 'specialization_id' );
        $categoryInfo = $this->Categorymodel->specializationInfo( $specialization_id );
        if( $categoryInfo->category_img ) {
            $image = 'uploads/category/'.$categoryInfo->category_img;
        } else {
            $image = $this->config->item( 'dir_url' ).'uploads/no_image.png';
        }
        $str   = form_open_multipart( base_url(). 'category', 'id="edit_specialization_form"' );
        $str .= '<div class="row">
                    <div class="col-sm-12">  
                      <div class="form-group">
                          <label for="specialization">Category</label><b class="text-danger">*</b>
                          <input type="text" value="' . rawurldecode( $categoryInfo->specialization ) . '" class="form-control" name="specialization" id="specialization" />
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group">
                          <label for="c_image">Image</label>
                          <input class="form-control" type="file" name="category_img" id="category_img">
                          <img style="width: 60px;" src="' . $image . '" />
                      </div>
                    </div>
                    <div class="col-sm-12">  
                      <div class="clearfix"></div>
                      <input type="hidden" name="specialization_id" value="' . $categoryInfo->specialization_id . '" />
                      <input type="submit" id="update-category" value="Update Category" class="btn btn-success">
                      <span data-dismiss="modal" class="btn btn-danger">Cancel</span>
                    </div>  
                </div>';
        $str .= form_close();        
        echo $str;
    }
    function fileUpload( $filename, $folder, $rename='', $size = '', $width = '', $height = '' ) {
          if( !$_FILES[ "$filename" ][ 'name' ] ) {
            return false;
          } 
          $_FILES[ "$filename" ][ 'name' ]; 
          $config['upload_path']        = './uploads/'.$folder;
          $config['allowed_types']      = 'gif|jpg|png';
          $config['max_size']           = 500000;
          $config['max_width']          = $width;
          $config['max_height']         = $height;

          $filetype                     = $_FILES[ "$filename" ][ 'type' ];
          $expfiletype                  = explode( '.', $_FILES[ "$filename" ][ 'name' ] );  
          $config['file_name']          = $rename.'.'.$expfiletype[ 1 ];

          $this->upload->initialize( $config );
          $this->load->library( 'upload', $config );

          if ( ! $this->upload->do_upload( $filename ) ) {
              $error = array( 'error' => $this->upload->display_errors() );
              return false;
          } else {
              $data = array( 'upload_data' => $this->upload->data() );
              return $config['file_name'];
          }
    }
    

	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
