<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['pre_system'] = array(
                                'function' => 'client_side_caching',
                                'filename' => 'clientsidecaching.php',
                                'filepath' => 'hooks',
                                'params'   => array(
                                                '@^/$@' => '60',
                                                '@^/badge.*@i' => '5',
                                                '@^/widget.*@i' => '5',
                                                '@.*\.css$@i' => '60',
                                                '@.*\.js$@i' => '60' );

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */
