<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Journeys extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Journeysmodel');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $script     = '';
        if( $this->input->post( 'a_link' ) ) {
            $imageType              = '';
            $imageType1             = '';
            $imageType2             = '';
            $file_name  = $this->fileUpload( 'a_image', 'journey', $imageType );
            if( $file_name ) {
                $data[ 'image' ] = $file_name;
            } /*else {
                $Emsg    = 'Invalid image.';
                $this->session->set_flashdata( 'Emsg', $Emsg );
                redirect( 'journeys' );
            }*/
            //Two or More Images
            $file_name1  = $this->fileUpload( 'b_image', 'journey', $imageType1 );
            if( $file_name1 ) {
                $data[ 'image1' ] = $file_name1;
            }
            $file_name2  = $this->fileUpload( 'c_image', 'journey', $imageType2 );
            if( $file_name2 ) {
                $data[ 'image2' ] = $file_name2;
            }

            $link                   = $this->input->post( 'a_link' );
            $data[ 'name' ]         = $link;
            $data[ 'description' ]  = rawurlencode( $this->input->post( 'a_desc' ) );
            $data[ 'created_on' ]   = date( 'Y-m-d' );

            $this->Journeysmodel->addjourney($data);
            $this->session->set_flashdata( 'Smsg', 'Journey added successfully.' );
            redirect( 'journeys' );
        }
        if( $this->session->flashdata( 'Smsg' ) ) {
            $script .= "toastr.success( '" . $this->session->flashdata( 'Smsg' ) . "','Success' );";
        }

        if( $this->session->flashdata( 'Emsg' ) ) {
            $script .= "toastr.error( '" . $this->session->flashdata( 'Emsg' ) . "','Error' );";
        }
        $journeyList = $this->Journeysmodel->journeyList();
        $data1[ 'journeyList' ]     = $journeyList;
        $content    = $this->load->view( 'journeys/journeys', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Journey lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Journey lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Journey lists';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Newsletter lists</li>';
        $this->template( $data );
    }
    function journeyForm() {
        $str  = form_open_multipart( base_url(). 'journeys', 'id="journey-form"' );
        $str .= '<div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                          <label for="a_image">Image</label> 
                          <input type="file" name="a_image" id="a_image" />
                      </div>
                    </div>
                    <div class="col-sm-4">  
                      <div class="form-group">
                          <label for="b_image">Image 1</label> 
                          <input type="file" name="b_image" id="b_image" />
                      </div>
                    </div>
                    <div class="col-sm-4">  
                      <div class="form-group">
                          <label for="c_image">Image 2</label> 
                          <input type="file" name="c_image" id="c_image" />
                      </div>
                    </div>
                    <div class="col-sm-12">  
                      <div class="form-group">
                          <label for="a_link">Name</label><b class="text-danger">*</b>
                          <input type="text" class="form-control" name="a_link" id="a_link" />
                      </div>
                    </div>
                    <div class="col-sm-12">  
                      <div class="form-group">
                          <label for="a_desc">Description</label><b class="text-danger">*</b>
                          <textarea class="form-control wysihtml5" name="a_desc" id="a_desc"></textarea>
                      </div>
                    </div>  
                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                      <input type="button" id="create-journey" value="Create" onClick="return addJourney();" class="btn btn-success">
                      <span data-dismiss="modal" class="btn btn-danger">Cancel</span>
                    </div>  
                </div>    
                ';
        $str .= form_close();        
        echo $str;
        return true;
    }

    function editjourney() {
        if( $this->input->post( 'a_link' ) ) {
            $journeyID = $this->input->post( 'journeyID' );
            $imageType              = '';
            $file_name  = $this->fileUpload( 'a_image', 'journey', $imageType );
            if( $file_name ) {
                $data[ 'image' ] = $file_name;
            }
            $imageType1              = '';
            $file_name1  = $this->fileUpload( 'b_image', 'journey', $imageType1 );
            if( $file_name1 ) {
                $data[ 'image1' ] = $file_name1;
            }
            $imageType2              = '';
            $file_name2  = $this->fileUpload( 'c_image', 'journey', $imageType2 );
            if( $file_name2 ) {
                $data[ 'image2' ] = $file_name2;
            }

            $link                   = $this->input->post( 'a_link' );
            $data[ 'name' ]         = $link;
            $data[ 'description' ]  = rawurlencode( $this->input->post( 'a_desc' ) );

            $this->db->where( 'journey_id', $journeyID );
            $this->db->update( 'journeys', $data );
            $this->session->set_flashdata( 'Smsg', 'Journey updated successfully.' );
            redirect( 'journeys' );
        } else {
            redirect( 'journeys' );
        }
    }

    function editJourneyForm() {

        $journeyID = $this->input->post( 'journeyID' );
        $str   = form_open_multipart( base_url(). 'journeys/editjourney', 'id="edit-journey-form"' );
        $journeyInfo = $this->Journeysmodel->journeyInfo( $journeyID );
        if( $journeyInfo->image ) {
            $path = "http://" . $_SERVER['SERVER_NAME'] . '/ebuddey-admin/uploads/journey/' . $journeyInfo->image;
            if( $journeyInfo->image ) {
              $image = "http://" . $_SERVER['SERVER_NAME'] . '/ebuddey-admin/uploads/journey/' . $journeyInfo->image;
            } else {
              $image = $this->config->item( 'dir_url' ).'uploads/no_image.png';
            }
        } else {
            $image = $this->config->item( 'dir_url' ).'uploads/no_image.png';
        }
        //Multiple Images
        if( $journeyInfo->image1 ) {
            $path1 = "http://" . $_SERVER['SERVER_NAME'] . '/ebuddey-admin/uploads/journey/' . $journeyInfo->image1;
            if( $journeyInfo->image1 ) {
              $image1 = "http://" . $_SERVER['SERVER_NAME'] . '/ebuddey-admin/uploads/journey/' . $journeyInfo->image1;
            } else {
              $image1 = $this->config->item( 'dir_url' ).'uploads/no_image.png';
            }
        } else {
            $image1 = $this->config->item( 'dir_url' ).'uploads/no_image.png';
        }
        if( $journeyInfo->image2 ) {
            $path2 = "http://" . $_SERVER['SERVER_NAME'] . '/ebuddey-admin/uploads/journey/' . $journeyInfo->image2;
            if( $journeyInfo->image2 ) {
              $image2 = "http://" . $_SERVER['SERVER_NAME'] . '/ebuddey-admin/uploads/journey/' . $journeyInfo->image2;
            } else {
              $image2 = $this->config->item( 'dir_url' ).'uploads/no_image.png';
            }
        } else {
            $image2 = $this->config->item( 'dir_url' ).'uploads/no_image.png';
        }

        $str .= '<div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                          <label for="a_image">Image</label>
                          <input type="file" name="a_image" id="a_image" />
                          <input type="hidden" name="journeyID" value="' . $journeyID . '" />
                          <img style="width: 60px;" src="' . $image . '" />
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                          <label for="b_image">Image1</label>
                          <input type="file" name="b_image" id="b_image" />
                          <img style="width: 60px;" src="' . $image1 . '" />
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                          <label for="c_image">Image2</label>
                          <input type="file" name="c_image" id="c_image" />
                          <img style="width: 60px;" src="' . $image2 . '" />
                      </div>
                    </div>
                    <div class="col-sm-12">  
                      <div class="form-group">
                          <label for="a_link">Name</label><b class="text-danger">*</b>
                          <input type="text" value="' . $journeyInfo->name . '" class="form-control" name="a_link" id="a_link" />
                      </div>
                    </div>
                    <div class="col-sm-12">  
                      <textarea class="form-control wysihtml5" name="a_desc" id="a_desc">' . rawurldecode( $journeyInfo->description ) . '</textarea> <br />
                      <div class="clearfix"></div>
                      <input type="button" id="update-journey" value="Update journey" onClick="return addJourney( \'edit\' );" class="btn btn-success">
                      <span data-dismiss="modal" class="btn btn-danger">Cancel</span>
                    </div>  
                </div>   
                ';
        $str .= form_close();        
        echo $str;
        return true;
    }

    function deleteJourney() {
        $journeyID = $this->input->post( 'journeyID' );
        $this->db->where( 'journey_id', $journeyID );
        $this->db->delete( 'journeys' );
        echo 1;
        return true;
    }

    //File Upload
    function fileUpload( $filename, $folder, $allowedtype='', $size = '', $width = '', $height = '' ) {

          if( !$_FILES[ "$filename" ][ 'name' ] ) {
            return false;
          }
          //Create codeigniter instance
          //$CI = get_instance();
            
          $_FILES[ "$filename" ][ 'name' ]; 
          $config['upload_path']        = $_SERVER[ 'DOCUMENT_ROOT' ].'/ebuddey-admin/uploads/'.$folder;
          $config['allowed_types']      = 'gif|jpg|png';
          $config['max_size']           = 500000;
          $config['max_width']          = $width;
          $config['max_height']         = $height;

          $filetype                     = $_FILES[ "$filename" ][ 'type' ];
          $expfiletype                  = explode( '.', $_FILES[ "$filename" ][ 'name' ] );  
          $config['file_name']          = date( 'dmyhis' ) . '.' . $expfiletype[ 1 ];

          $this->upload->initialize( $config );
          $this->load->library( 'upload', $config );

          if ( ! $this->upload->do_upload( $filename ) ) {
              $error = array( 'error' => $this->upload->display_errors() );
              print_r( $error );
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
?>    