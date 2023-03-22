<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Experience extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Experiencemodel');
        sessionset();
        error_reporting(E_ALL);
        $this->load->library('encryption');
    }

    public function index() {

        $tplData[ 'guider_lists' ]      = $this->Guidermodel->guider_lists(null,1);
        $content = $this->load->view( 'experience/index', $tplData, true );
        $data[ 'navigation' ]           = '';
        $data[ 'header' ][ 'title' ]    = HOST_NAME.' Experience List';
        $data[ 'footer' ][ 'script' ]   = '';
        $data[ 'content' ]              = $content;
        $data[ 'breadcrumb' ]           = '<li class="active">'.HOST_NAME.' Experience list</li>';
        $this->template( $data );
    }
    public function experienceTableResponse(){
        global $permission_arr;
        $data           = array();
        $where          = array();
        $experienceLists= $this->Experiencemodel->get_datatables($where);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status         = '';
        $totalAmount    = 0;
        if($experienceLists){
            $statusbtn  = '';
            foreach ($experienceLists as $experience) {
                if( $experience->status == 1 ){
                    $status     = '<span class="label label-success">Approved</span>';
                }else if($experience->status == 2){
                    $status     = '<span class="label label-warning">Under Review</span>';
                }else if($experience->status == 3){
                    $status     = '<span class="label label-danger">Reject</span>';
                }
                if( in_array( 'experience/status', $permission_arr ) ) {
                    $approveBtn = '<a href="javascript:;" onClick="return experienceStatus('.$experience->te_id.', 1);" class="btn btn-success btn-xs" data-toggle="tooltip" data-original-title="Click to Activate">Approve</a>';
                    $rejectBtn = '<a href="javascript:;" onClick="return experienceStatus('.$experience->te_id.', 3);" class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Click to Activate">Reject</a>';
                }else{
                    $approveBtn = '';
                    $rejectBtn  = '';
                }
                if( in_array( 'experience/index', $permission_arr ) ) {
                    $viewbtn = '<a class="btn btn-primary btn-xs" href="'.$this->config->item( 'admin_url' ).'experience/view/'.$experience->te_id.'"><i class="fa fa-search"></i></a>';
                }else{
                    $viewbtn = '';
                }
                
                $row    = array();
                $row[]  = $si_no;
                $row[]  = $experience->first_name;
                $row[]  = $experience->experience_title;
                $row[]  = $experience->cityName;
                $row[]  = $experience->price_rate;
                $row[]  = $status;
                $row[]  = $approveBtn.$rejectBtn.$viewbtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Experiencemodel->count_all($where),
                        "recordsFiltered"   => $this->Experiencemodel->count_filtered($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }

    public function view()
    {
        global $permission_arr;
        if( !in_array( 'experience/index', $permission_arr ) ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $te_id    = $this->uri->segment(3);
        $talentExpInfo   = $this->Experiencemodel->experienceInfo($te_id);
        if(!$talentExpInfo){ redirect( $this->config->item( 'admin_url' ) . 'experience' ); }
        $tplData[ 'talentExpInfo' ]     = $talentExpInfo;
        $tplData[ 'te_id' ]             = $te_id;
        $content    = $this->load->view( 'experience/view', $tplData, true );
        $data[ 'navigation' ]           = 'experience';
        $data[ 'header' ][ 'title' ]    = 'View '.HOST_NAME.' Experience';
        $data[ 'content' ]              = $content;
        $data[ 'breadcrumb' ]           = '
                                          <li><a href="'.$this->config->item( 'admin_url' ).'experience">View '.HOST_NAME.' Experience</a></li>
                                          <li class="active">View '.HOST_NAME.' Experience</li>';
        $this->template( $data );
    }
    function experienceStatus(){
        if( !$this->input->is_ajax_request() ) {
            echo 'No Direct Access Denied';
            return false;
            exit();
        }
        $te_id  = $this->input->post( 'te_id' );
        $data   = array('status' => $this->input->post( 'status' ));
        $delete = $this->Experiencemodel->updateExperience( $te_id, $data );
        return true;
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
