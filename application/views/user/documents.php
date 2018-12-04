<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-secion">
    <div class="container creatae-post-content">
      <h2><?php echo $this->lang->line('shared_documents'); ?></h2>
		
		<?php $i=1; foreach($documents as $document): ?>
		<div style="padding:5px 0; font-size:16px;"><?=$i; ?>- <?=$document['name']; ?> - <a href="<?=$document['url']; ?>">Download</a></div>
		<?php $i++; endforeach; ?>
      
    </div>
  </div>




<!-- /.content-wrapper -->
<script type="text/javascript">
var base_url="<?=base_url();?>";
var URL=base_url+"user/search_users";


</script>
