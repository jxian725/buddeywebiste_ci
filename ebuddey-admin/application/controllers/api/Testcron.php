<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testcron extends CI_Controller{

    function __construct(){
        parent::__construct();
    }
    public function index() {
        $data   = array('cron_time' => date( 'Y-m-d h:i:s' ));
        $this->db->insert( 'test_cron', $data );
    }
}
?> 