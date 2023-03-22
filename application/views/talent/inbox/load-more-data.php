<?php 
	defined('BASEPATH') OR exit('No direct script access allowed'); 
$assetUrl   = $this->config->item( 'admin_url' );
$adminUrl   = $this->config->item( 'admin_dir_url' );
$dirUrl     = $this->config->item( 'dir_url' );	

				if($imageLists){ 
					foreach ($imageLists as $img) { 
						$photo = ($img->profile_image); 
					}
				}
				 $arr = array();
				 
				 if($datearr){ 
					foreach ($datearr as $datearrs) { 
						$arr[] = $datearrs; 
					}
				}
				
                      if($inboxinfo){
                        foreach (array_reverse($inboxinfo) as $inboxinfos) { 
						$message=$inboxinfos->message;
						$istalent_message=$inboxinfos->istalent_message;
						$is_admin_message=$inboxinfos->is_admin_message;
					    $msgid=$inboxinfos->id;
						$phpdate = strtotime( $inboxinfos->created_at );
						$mysqldate = date( 'd/m/Y', $phpdate );
						
						if(!in_array($mysqldate,$arr)) {
														
						$arr[]=$mysqldate;
						?>
						<li><p id="msgdate"><?php echo $mysqldate;?></p></li>
						<?php }
						if($istalent_message){
						?>
						<li class="sent msg" id="<?=$msgid;?>" onclick="deletemsg(<?=$msgid;?>)">
						<img src="<?= $adminUrl;?>uploads/g_profile/<?= $photo; ?>" alt="" />
						<p><?= $message; ?><small><sub>
						<?php echo date('h:i A', strtotime($inboxinfos->created_at)); ?></sub></small></p>
						</li>
						<?php }if($is_admin_message){?>
						<li class="replies msg" id="<?=$msgid;?>">
						<img src="<?= $adminUrl;?>img/avatar5.png" alt="" />
						<p><?= $message; ?><sub><small>
						<?php echo date('h:i A', strtotime($inboxinfos->created_at)); ?></sub></small></p>
						</li>
						
						<?php 
						}
						}
					  }
					  ?>