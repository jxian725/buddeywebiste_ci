<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('cms/Pagesmodel');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {

        $data1[ 'page_lists' ] = $this->Pagesmodel->page_lists();
        $content    = $this->load->view( 'cms/pages', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Page lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Page lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Page lists';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Page lists</li>';
        $this->template( $data );
	}
    public function edit() {
        $page_id    = $this->uri->segment(4);

        if($this->input->post( 'pageID' )){
            $pageID = $this->input->post( 'pageID' );
            $data   = array(
                            'page_content'  => rawurlencode( $this->input->post( 'page_content' ) ),
                            'updatedby'     => $this->session->userdata( 'USER_ID' )
                            );
            $this->Pagesmodel->updatePage($pageID, $data);
        }

        $data1[ 'pageInfo' ] = $this->Pagesmodel->pageInfo($page_id);
        if(!$data1[ 'pageInfo' ]){ redirect( $this->config->item( 'admin_url' ) . 'cms/pages' ); }
        $content    = $this->load->view( 'cms/edit_page', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Update Page - '.$data1[ 'pageInfo' ]->title;
        $data[ 'header' ][ 'metakeyword' ]      = 'Update Page';
        $data[ 'header' ][ 'metadescription' ]  = 'Update Page';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Update Page Content</li>';
        $this->template( $data );
    }
    
    public function venue_partner(){
        $title     = $this->input->post( 'title' );
        $is_submit = $this->input->post( 'is_submit' );
        $cvp_id    = $this->input->post( 'cvp_id' );
        if($is_submit && !$cvp_id){
            $imageType      = '';
            $file_name      = '';
            $rename         = 'venue_partner_'.time();
            if ($_FILES['venue_partner_img']['name']) {
                $file_name  = $this->fileUpload( 'venue_partner_img', 'cms_venue_partner', $rename );
            }
            if($file_name) {
                $data[ 'venue_partner_img' ] = $file_name;
            }
            $data['title'] = rawurlencode( $title );
            $data['created_on']     = date( 'Y-m-d' );
            $this->Pagesmodel->addVenuePartnerImg( $data );
            redirect(base_url('cms/pages/venue_partner'));
        }
        if($is_submit && $cvp_id){
            $imageType      = '';
            $file_name      = '';
            $rename         = 'venue_partner_'.time();
            if ($_FILES['venue_partner_img']['name']) {
                $file_name  = $this->fileUpload( 'venue_partner_img', 'cms_venue_partner', $rename );
            }
            if($file_name) {
                $data2[ 'venue_partner_img' ] = $file_name;
            }
            $data2['title'] = rawurlencode( $title );
            $this->Pagesmodel->updateVenuePartnerImg( $data2, $cvp_id );
            redirect(base_url('cms/pages/venue_partner'));
        }
        $venuePartnerImgLists = $this->Pagesmodel->venuePartnerImgLists();
        $tplData[ 'venuePartnerImgLists' ]      = $venuePartnerImgLists;
        $content    = $this->load->view( 'cms/venue_partner', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Venue Partner images lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Venue Partner images lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Venue Partner images lists';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Venue Partner images lists</li>';
        $this->template( $data );
    }

    //Delete VenuePartner
    function deleteVenuePartner() {
        $cvp_id = $this->input->post( 'cvp_id' );
        $vpartnerInfo = $this->Pagesmodel->venuePartnerImgInfo( $cvp_id );
        if( $vpartnerInfo && $vpartnerInfo->venue_partner_img ) {
            unlink('uploads/cms_venue_partner/'.$vpartnerInfo->venue_partner_img);

            $this->db->where( 'cvp_id', $cvp_id );
            $this->db->delete( 'cms_venue_partner_images' );
        }
        echo 1;
    }

    function editVenuePartnerForm() {

        $cvp_id = $this->input->post( 'cvp_id' );
        $vpartnerInfo = $this->Pagesmodel->venuePartnerImgInfo( $cvp_id );
        if( $vpartnerInfo->venue_partner_img ) {
            $image = base_url().'uploads/cms_venue_partner/'.$vpartnerInfo->venue_partner_img;
        } else {
            $image = $this->config->item( 'dir_url' ).'uploads/no_image.png';
        }
        $str   = form_open_multipart( base_url(). 'cms/pages/venue_partner', 'id="edit_venue_partner_form"' );
        $str .= '<div class="row">
                    <div class="col-sm-12">  
                      <div class="form-group">
                          <label for="title">Title</label><b class="text-danger">*</b>
                          <input type="text" value="' . rawurldecode( $vpartnerInfo->title ) . '" class="form-control" name="title" id="title" />
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group">
                          <label for="c_image">Image</label>
                          <input class="form-control" type="file" name="venue_partner_img" id="venue_partner_img">
                          <img style="width: 60px;" src="' . $image . '" />
                      </div>
                    </div>
                    <div class="col-sm-12">  
                      <div class="clearfix"></div>
                      <input type="hidden" name="cvp_id" value="' . $vpartnerInfo->cvp_id . '" />
                      <input type="hidden" value="1" name="is_submit" />
                      <input type="submit" id="update-venue-partner" value="Update Image" class="btn btn-success">
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
