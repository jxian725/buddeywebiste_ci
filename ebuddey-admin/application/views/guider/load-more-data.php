<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$assetUrl   = $this->config->item( 'admin_url' );
$site_name  = $this->config->item( 'site_name' );
$dirUrl     = $this->config->item( 'dir_url' );
$upload_path_url = $this->config->item( 'upload_path_url' );
$name       = $guiderInfo->first_name;
if($guiderInfo){ 
$photo = ($guiderInfo->profile_image); 
}
$arr = array();
				 
				 if($datearr){ 
					foreach ($datearr as $datearrs) { 
						$arr[] = $datearrs; 
					}
				}									
									 if($inboxinfo){
									
											foreach ($inboxinfo as $inboxinfos) { 
											$message=$inboxinfos->message;
											$istalent_message=$inboxinfos->istalent_message;
											$istalent_delete=$inboxinfos->istalent_delete;
											$is_admin_message=$inboxinfos->is_admin_message;
$msgid=$inboxinfos->id;
$phpdate = strtotime( $inboxinfos->created_at );
						$mysqldate = date( 'd/m/Y', $phpdate );
						
						if(!in_array($mysqldate,$arr)) {
														
						$arr[]=$mysqldate;
						?>
						<li><p id="msgdate"><?php echo $mysqldate;?></p></li>
						<?php }
											if($is_admin_message){
											?>
											<li class="sent msg" id="<?=$msgid;?>" onclick="deletemsg(<?=$msgid;?>)">
											<img src="<?= $dirUrl;?>img/avatar5.png" alt="" />
											<p><?= $message; ?></p>
											</li>
											<?php }if($istalent_message && $istalent_delete){?>
											<li class="replies msg" id="<?=$msgid;?>">
											<img src="<?= $dirUrl;?>uploads/g_profile/<?= $photo; ?>" alt="" />
											<p><span style="font-style:italic;color:lightgray">Deleted by Talent </span><?= $message; ?><small><sub><?php echo date('h:i A', strtotime($inboxinfos->created_at)); ?></sub></small></p>
											
											</li>
											
											<?php 
											}else if($istalent_message){?>
											<li class="replies">
											<img src="<?= $dirUrl;?>uploads/g_profile/<?= $photo; ?>" alt="" />
											<p><?= $message; ?><small><sub><?php echo date('h:i A', strtotime($inboxinfos->created_at)); ?></sub></small></p>
											</li>
											
											<?php 
											}
											}
										  }
										  ?>