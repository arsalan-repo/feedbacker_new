<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-secion">
    <div class="container creatae-post-content notification-page">
     <!-- <h2>Notifications</h2>-->
     <?php  if (!empty($notifications)) { ?>
	     <?php foreach($notifications as $row) {  ?>
		  <div class="notification-block <?=($row['is_unread'])? 'unread':'viewed'; ?>">
            <div class="post-img">
            <img src="<?php echo $row['photo']; ?>" alt="" />
            </div>
            <div class="notification-description"> 
                <span class="post-designation">
				<?php if($row['notification_id']==5 || $row['notification_id']==8 || $row['notification_id']==6){ ?>
					<?php 
					if($row['title_id']) {
						echo '<a href="'.site_url('encrypted/').$row['title_id'].'">'.$row['message'].'</a>';
					}  ?>
				<?php }elseif($row['notification_id']==7){ ?>
					<a href="<?=site_url('encrypted/documents/'.$row['id']); ?>"><?=$row['message']; ?></a>
				<?php }else{ ?>
					<?php 
					if($row['title_id']) {
						echo '<a href="'.site_url('post/title').'/'.$row['title_id'].'">'.$row['message'].'</a>';
					} elseif($row['feedback_id']) {
						echo '<a href="'.site_url('post/detail').'/'.$row['feedback_id'].'">'.$row['message'].'</a>';
					} ?>
				<?php } ?>
               
                </span> 
                <?php if($row['notification_id']!=7){?>
					<?php if($row['notification_id']==5): ?>
					<span class="notification-sub-title"><?php echo $this->emoji->Decode(nl2br($row['title'])); ?></span> 
					<?php else: ?>
					<span class="notification-sub-title"><?php echo $this->emoji->Decode(nl2br($row['feedback'])); ?></span> 
					<?php endif; ?>	
				<?php } ?>
                <span class="notification-time"><?php echo $row['time']; ?></span> 
            </div>
        </div>
		<?php } ?>	
	 <?php } else { ?>
  	 <?php echo $no_record_found; ?>
  	 <?php } ?>
    </div>
</div>
<!-- /.content-wrapper -->
