<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<style>
.question-choices input{ width:auto; float:none; margin-right:10px;}
.question-item.last-item{border:none !important;}
</style>
<!-- Content Wrapper. Contains page content -->
<?php if ($this->session->flashdata('error')) { ?>  
	<div class="div-toastr-error">
		<?php echo $this->session->flashdata('error'); ?>
	</div>
<?php } ?>
<?php if ($this->session->flashdata('success')) { ?>  
	<div class="div-toastr-success">
		<?php echo $this->session->flashdata('success'); ?>
	</div>
<?php } ?>
<div class="content-secion" style="min-height:530px;">
    <div class="container">
      
      <div class="post-detail-left">
        <div class="middle-content-block" id="search-form">
			<div class="profile-listing" style="padding:20px;"> 	
					<?php if ($this->session->flashdata('success')) { ?>
					<div class="callout callout-success">
						<p><?php echo $this->session->flashdata('success'); ?></p>
					</div>
					<?php } ?>
					<?php if ($this->session->flashdata('error')) { ?>  
						<div class="callout callout-danger">
							<p><?php echo $this->session->flashdata('error'); ?></p>
						</div>
					<?php } ?>
				<h2><?=$survey['title']; ?></h2>
				<h5><?=$survey['location']; ?></h5>	
				<div class="question-list">
					<form method="post" action="">
					<h2>Questions	</h2>
					<?php $i=0; foreach($survey['questions'] as $question): $i++; ?>
						<div class="question-item <?php if($i==count($survey['questions'])) echo 'last-item'; ?>">
							<h4><?=$i; ?>- <?=$question['question']; ?></h4>
							<div class="clearfix" style="clear:both;"></div>
							<?php if($question['qtype']=='mcq'): ?>
							<div class="question-options">
								
								<?php for($f=1; $f<9; $f++){ $field_name='option'.$f; ?>
								<?php if(!empty($question[$field_name])): ?>
								<span class="question-option   "><input required type="radio" name="answers[<?=$question['question_id']; ?>]" value="<?=$f; ?>" <?php if($question['answer']==$f)echo 'checked'; ?> <?php if(!empty($question['answer'])) echo 'disabled ';  ?>><?=$question[$field_name]; ?></span>
								<?php endif; ?>
								<?php } ?>
								
							</div>
							<?php elseif($question['qtype']=='checkbox'):   $canswers=$question['answer']; if(!is_array($canswers)) $canswers=array();?>
								<div class="question-choices">
									<?php foreach($question['choices'] as $choice): ?>
										<div class="field-wrapper">
											<input type="checkbox" <?php if(in_array($choice['id'],$canswers)) echo 'checked'; ?> name="answers[<?=$question['question_id']; ?>][<?=$choice['id']; ?>]"><?=$choice['option']; ?>
										</div>
									<?php endforeach; ?>
								</div>
							<?php else: ?>
							<div class="question-answer" style="width:100%; clear:both; overflow:hidden;">
								<input type="text" name="answers[<?=$question['question_id']; ?>]" value="<?php if(!empty($question['answer'])) echo $question['answer']; ?>" style="<?php if(!empty($question['answer_label'])) echo 'width:60%;'; ?>"><?=$question['answer_label']; ?>								
							</div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
					<?php if(empty($this->session->userdata['mec_user'])): ?>
					<div class="question-item2">					
						<input type="hidden" name="email"  value="<?=$email ?>" placeholder="Your Email Address">
					</div>
					<?php endif; ?>
					<div class="post-btn-block">
						<input type="hidden" name="survey_id" value="<?=$survey['survey_id']; ?>">							
						<button type="submit" id="submit-survey" name="submit-survey" class="post-btn">Submit</button>
					</div>
					</form>
				</div>				
				</div>
			</div>        
        </div>
      </div>
      <div class="post-detail-rgt">
		 <?php if(isset($this->session->userdata['mec_user'])): ?>
		<div class="home-left-profile">
          <div class="home-left-section">
            <div class="home-left-profile-block"> 
            	<span class="home-left-profile-thumb">
					<a href="<?php echo site_url('user/profile'); ?>">
					<?php 
                    if(isset($user_info['photo'])) {
                        echo '<img src="'.S3_CDN . 'uploads/user/thumbs/' . $user_info['photo'].'" alt="" />';
                    } else {
                        echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
                    }
                    ?>
					</a>
                </span> 
                <span class="home-left-profile-name"><?php echo $user_info['name']; ?></span> 
                <span class="home-left-profile-designation">
				<?php
				$getcountry = $this->common->select_data_by_id('users', 'id', $user_info['id'], 'country', '');
				echo $this->common->getCountries($getcountry[0]['country']); ?></span> </div>
          </div>
        </div>
		<?php else: ?>
			<div class="login-page">				
			  <div class="login-form" style="width:100%;">
				<div class="login-form-block">
				
				  <div class="login-form-fields">
					<div class="logo">
						<a href="<?php echo site_url(); ?>">
							<img src="<?php echo base_url().'assets/images/logo.png'; ?>" alt="" />
						</a>
					</div>
				
					<?php
					$attributes = array('class' => '', 'id' => 'signin-form');
					// $hidden = array('username' => 'Joe', 'member_id' => '234');
					echo form_open('signin/auth', $attributes);
					// echo form_open('login/auth', '', $hidden);
					?>
					<ul>
					  <li>
						<label><?php echo $this->lang->line('email'); ?></label>
						<input type="text" autocomplete="off" placeholder="" name="email" id="email" />
					  </li>
					  <li>
						<label><?php echo $this->lang->line('password'); ?></label>
						<input type="password" autocomplete="off" name="password" placeholder="" id="password" />
					  </li>
					  <li>
						<input type="submit" name="button" id="button" value="<?php echo $this->lang->line('login'); ?>" />
					  </li>
					  <li> 
						<span class="forgot-text">
							<a href="<?php echo site_url('signin/forgot_password'); ?>"><?php echo $this->lang->line('forgot_pass'); ?></a>
						</span> 
						<span class="signup-text">
							<a href="<?php echo site_url('signup'); ?>"><?php echo $this->lang->line('are_you_new'); ?></a>
						</span>
						<div class="login-with"><span><?php echo $this->lang->line('login_with'); ?></span></div>
						<div class="login-social-icons">
							
							<?php if(!empty($authUrl)) { ?>
							<span class="facebook-icon">
								<a href="<?php echo $authUrl; ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a>
							</span>
							<?php } ?>
							<?php if(!empty($oauthURL)) { ?>
							<span class="twitter-icon">
								<a href="<?php echo $oauthURL; ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a>
							</span>
							<?php } ?>
						</div>
					  </li>
					</ul>
					<?php echo form_close(); ?> 
					</div>
				</div>
			  </div>
			</div>
	   <?php endif; ?>
      </div>
    </div>
    <div class="ajax-load text-center" style="display:none">
        <p><img src="<?php echo ASSETS_URL . 'images/loader.gif'; ?>">Loading</p>
    </div>
    
    <script type="text/javascript">
		// When the browser is ready...
		$(function() {
			// jQuery Toastr
			if ($.trim($(".div-toastr-error").html()).length > 0) {
				$(".div-toastr-error p").each(function( index ) {
					toastr.error($(this).html(), 'Failure Alert', {timeOut: 5000});
				});
			}
			
			if ($.trim($(".div-toastr-success").html()).length > 0) {
				$(".div-toastr-success p").each(function( index ) {
					toastr.success($(this).html(), 'Success Alert', {timeOut: 5000});
				});
			}
		});
		
    </script>
</div>