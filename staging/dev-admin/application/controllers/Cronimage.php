<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronimage extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('Commonmodel');
        error_reporting(E_ALL);
    }
	public function index() {
        $script     = '';
        $newsletter_image = $this->Commonmodel->newsletter_image();
        //If Condition
        if( $newsletter_image ) {
            foreach ( $newsletter_image as $key => $value ) {
                # code...
                if( $value->image_src ) { 
                    $path = IMAGE_ROOT . 'newsletter/' . $value->image_src;
                    //$str  = preg_replace('#^https?://#', '', $path);
                    if( file_exists( $path ) ) {
                    } else {
                        unlink( IMAGE_ROOT . "newsletter/".$value->image_src );
                    }
                } else {
                    //unlink( IMAGE_ROOT . "newsletter/".$value->image; );
                    $fileName  = IMAGE_ROOT . "newsletter/";
                    $filethumb = IMAGE_ROOT . "newsletter/thumb/";
                    $tmp = dirname(__FILE__);
                    if (strpos($tmp, '/', 0)!==false) {
                      define('WINDOWS_SERVER', false);
                    } else {
                      define('WINDOWS_SERVER', true);
                    }
                    $deleteError = 0;
                    if (!WINDOWS_SERVER) {
                        if (!unlink($fileName)) {
                          $deleteError = 1;
                        }
                    } else {
                        $lines = array();
                        exec("DEL /F/Q \"$fileName\"", $lines, $deleteError);
                    }
                }
            }
        } else {
            // define if we under Windows
            $fileName  = IMAGE_ROOT . "newsletter/";
            $filethumb = IMAGE_ROOT . "newsletter/thumb/";
            $tmp = dirname(__FILE__);
            if (strpos($tmp, '/', 0)!==false) {
              define('WINDOWS_SERVER', false);
            } else {
              define('WINDOWS_SERVER', true);
            }
            $deleteError = 0;
            if (!WINDOWS_SERVER) {
                if (!unlink($fileName)) {
                  $deleteError = 1;
                }
            } else {
                $lines = array();
                exec("DEL /F/Q \"$fileName\"", $lines, $deleteError);
            }
            //Thumb Files
            $tmp = dirname(__FILE__);
            if (strpos($tmp, '/', 0)!==false) {
              define('WINDOWS_SERVER', false);
            } else {
              define('WINDOWS_SERVER', true);
            }
            $deleteError = 0;
            if (!WINDOWS_SERVER) {
                if (!unlink($filethumb)) {
                  $deleteError = 1;
                }
            } else {
                $lines = array();
                exec("DEL /F/Q \"$filethumb\"", $lines, $deleteError);
            }
        }
	}
}
