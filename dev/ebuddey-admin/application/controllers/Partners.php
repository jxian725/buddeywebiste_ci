<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partners extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Partnermodel');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {

        $partner_name= $this->input->post( 'partner_name' );
        $city_id     = $this->input->post( 'city_id' );
        $address     = $this->input->post( 'address' );
        $partner_id  = $this->input->post( 'partner_id' );
        $dbkl_lic_enable = $this->input->post( 'dbkl_lic_enable' );
        if($partner_name && $city_id && !$partner_id){
            $data['partner_name'] = rawurlencode( $partner_name );
            $data['city_id']      = $city_id;
            $data['fees']         = $this->input->post( 'fees' );
            $data['address']      = $address;
            $data['dbkl_lic_enable'] = ($dbkl_lic_enable)? 1 : 0;
            $data['created_on']   = date( 'Y-m-d' );
            if ($_FILES['photo']['name']) {
                $rename1    = 'new_partner'.time();
                $uploadData = $this->fileUpload( 'photo', 'partner', $rename1 );
                if( $uploadData['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData['msg']);
                }
                if( $uploadData['status'] == 'success'){
                    $data['photo'] = $uploadData['file_name'];
                }
            }
            $this->Partnermodel->addPartner( $data );
            redirect(base_url('partners'));
        }
        if($partner_name && $city_id && $partner_id){
            $partnerInfo = $this->Partnermodel->partnerInfo( $partner_id );
            $data2['partner_name'] = rawurlencode( $partner_name );
            $data2['city_id']      = $city_id;
            $data2['fees']         = $this->input->post( 'fees' );
            $data2['address']      = $address;
            $data2['dbkl_lic_enable'] = ($dbkl_lic_enable)? 1 : 0;
            if ($_FILES['photo']['name']) {
                $rename2     = 'new_partner'.time();
                $uploadData2 = $this->fileUpload( 'photo', 'partner', $rename2 );
                if( $uploadData2['status'] == 'error'){
                    $this->session->set_flashdata('errorMSG', $uploadData2['msg']);
                }
                if( $uploadData2['status'] == 'success'){
                    $data2['photo'] = $uploadData2['file_name'];
                    if($partnerInfo->photo){
                        unlink('uploads/partner/'.$partnerInfo->photo);
                    }
                }
            }
            $this->Partnermodel->updatePartner( $partner_id, $data2 );
            redirect(base_url('partners'));
        }

        $tplData[ 'partnerList' ] = $this->Partnermodel->partnerList();
        $tplData[ 'stateList' ]   = $this->Commonmodel->state_list( $country_id=132, 1 );
        $content    = $this->load->view( 'partner/list', $tplData, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Partners Lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Partners Lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Partners Lists';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Partners Lists</li>';
        $this->template( $data );
	}
    function editPartnerForm() {
        $upload_path_url = $this->config->item( 'upload_path_url' );
        $partner_id  = $this->input->post( 'partner_id' );
        $partnerInfo = $this->Partnermodel->partnerInfo( $partner_id );
        if($partnerInfo->photo){
          $photo = '<img class="img-thumbnail" src="'.$upload_path_url.'partner/'.$partnerInfo->photo.'" style="height: auto;width: 60px;" data-src="#" />';
        }else{
          $photo = '';
        }
        if($partnerInfo->dbkl_lic_enable == 1){
            $dbkl_checked = 'checked';
        }else{
            $dbkl_checked = '';
        }
        $str   = form_open_multipart( base_url(). 'partners', 'id="edit_partner_form"' );
        $str .= '<div class="row">
                    <div class="col-sm-12">  
                      <div class="form-group">
                          <label for="partner_name">Category</label><b class="text-danger">*</b>
                          <input type="text" value="' . rawurldecode( $partnerInfo->partner_name ) . '" class="form-control" name="partner_name" id="partner_name" />
                      </div>
                    </div>';
            $stateList = $this->Commonmodel->state_list( $country_id=132, 1 );
            $str .= '<div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label" for="city_id">City <span class="text-danger">*</span></label>
                            <select class="form-control" id="city_id" name="city_id">
                                <option value="">Select</option>';
                                if( $stateList ) {
                                    foreach ( $stateList as $key => $value2 ) {
                                    $str .= '<option '.(($value2->id==$partnerInfo->city_id)? 'selected':'').' value="'.$value2->id.'">'.$value2->name.'</option>';
                                    }
                                }
                    $str .= '</select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label" for="fees">Fees (RM)</label>
                            <input type="text" placeholder="Fees" id="fees" class="form-control number" name="fees" value="' . $partnerInfo->fees . '">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label" for="photo">Photo</label>
                            <input type="file" id="photo" name="photo" class="form-control">
                            <div>' . $photo . '</div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label" for="address">Address</label>
                            <textarea type="text" rows="3" placeholder="Address" id="address" class="form-control" name="address">' . $partnerInfo->address . '</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="checkbox">
                              <label><input type="checkbox" '. $dbkl_checked .' name="dbkl_lic_enable" id="dbkl_lic_enable" value="1">&nbsp;&nbsp;DBKL license Required</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">  
                      <div class="clearfix"></div>
                      <input type="hidden" name="partner_id" value="' . $partnerInfo->partner_id . '" />
                      <input type="submit" id="update-category" value="Update Partner" class="btn btn-success">
                      <span data-dismiss="modal" class="btn btn-danger">Cancel</span>
                    </div>  
                </div>';
        $str .= form_close();        
        echo $str;
    }
    function deletePartner() {
        $partner_id = $this->input->post( 'partner_id' );
        if($partner_id){
            $this->Partnermodel->deletePartner( $partner_id );
        }
        return true;
    }
    function fileUpload( $filename, $folder, $rename='', $size = '', $width = '', $height = '' ) {
          if( !$_FILES[ "$filename" ][ 'name' ] ) {
            return false;
          } 
          $_FILES[ "$filename" ][ 'name' ]; 
          $config['upload_path']        = './uploads/'.$folder;
          $config['allowed_types']      = 'gif|jpg|jpeg|png';
          $config['max_size']           = 500000;
          $config['max_width']          = $width;
          $config['max_height']         = $height;

          $filetype                     = $_FILES[ "$filename" ][ 'type' ];
          $expfiletype                  = explode( '.', $_FILES[ "$filename" ][ 'name' ] );  
          $config['file_name']          = $rename.'.'.$expfiletype[ 1 ];

          $this->upload->initialize( $config );
          $this->load->library( 'upload', $config );

          if ( ! $this->upload->do_upload( $filename ) ) {
              $res = array( 'status' => 'error','msg' => $this->upload->display_errors() );
              return $res;
          } else {
              $data = array( 'upload_data' => $this->upload->data() );
              $res  = array( 'status' => 'success','file_name' => $config['file_name'],'msg' => 'file upload successfully.' );
              return $res;
          }
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
