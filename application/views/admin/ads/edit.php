<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php echo $module_name; ?>
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('dashboard'); ?>">
                    <i class="fa fa-dashboard"></i>
                    Home
                </a>
            </li>
            <li class="active"><?php echo $module_name; ?></li>
        </ol>
    </section>
    <section class="content-header">
        <?php if ($this->session->flashdata('success')) { ?>
            <div class="callout callout-success">
                <p><?php echo $this->session->flashdata('success'); ?></p>
            </div>
        <?php } ?>
        <?php if ($this->session->flashdata('error')) { ?>  
            <div class="callout callout-danger" >
                <p><?php echo $this->session->flashdata('error'); ?></p>
            </div>
        <?php } ?>

    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $section_title; ?></h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php
                    $form_attr = array('id' => 'edit_banner_frm', 'enctype' => 'multipart/form-data');
                    echo form_open_multipart('admin/ads/edit', $form_attr);
                    ?>
                    <input type="hidden" name="ads_id" value="<?php echo $ads_detail[0]['ads_id'] ?>">
                    <div class="box-body">
                    	<div class="form-group col-sm-10">
                            <label for="usr_name" id="page_title">User Name*</label>
                            <input type="text" class="form-control" name="usr_name" id="usr_name" value="<?php echo $ads_detail[0]['usr_name'] ?>">
                        </div>
                        <!-- User image start -->
                        <div class="form-group col-sm-10">
                            <label for="usr_img" id="page_title">User Photo</label>
                            <input type="file" class="form-control" name="usr_img" id="usr_img" value="" style="border: none;">
                        </div>
                        <?php if($ads_detail[0]['usr_img']) { ?>
                        <div class="form-group col-sm-10">
                            <label for="usr_img" id="page_title"></label>
                            <img src="<?php echo S3_CDN . 'uploads/user/thumbs/' . $ads_detail[0]['usr_img']; ?>" alt="<?php echo $ads_detail[0]['usr_img'] ?>" width="90">
                            <?php /*?><a href="<?php echo base_url('admin/ads/remove_photo/' . $ads_detail[0]['ads_id']) ?>" title="Remove Photo" style="margin: 0 auto; width: 120px;" >Remove Photo</a><?php */?>
                        </div>
                        <?php } ?>
                        <!-- User image end -->
                        <div class="form-group col-sm-10">
                            <label for="ads_cont" id="page_title">Ads Content</label>
                            <textarea rows="10" class="form-control" name="ads_cont" id="ads_cont"><?php echo $ads_detail[0]['ads_cont'] ?></textarea>
                        </div>
                        <!-- banner image start -->
                        <div class="form-group col-sm-10">
                            <label for="ads_image" id="page_title">Choose Banner</label>
                            <input type="file" class="form-control" name="ads_image" id="ads_image" value="" style="border: none;">
                        </div>
                        <?php if($ads_detail[0]['ads_img']) { ?>
                        <div class="form-group col-sm-10">
                            <label for="ads_thumb" id="page_title"></label>
                            <a href="<?php echo S3_CDN . 'uploads/feedback/main/' . $ads_detail[0]['ads_img'] ?>" target="_blank">
                                <img src="<?php echo S3_CDN . 'uploads/feedback/main/' . $ads_detail[0]['ads_img'] ?>" alt="<?php echo $ads_detail[0]['ads_img'] ?>" width="180">
                            </a>    
                            <input type="hidden" name="old_image" value="<?php echo $ads_detail[0]['ads_thumb']; ?>" />
                            <?php /*?><a href="<?php echo base_url('admin/ads/remove_banner/' . $ads_detail[0]['ads_id']) ?>" title="Remove Banner" style="margin: 0 auto; width: 120px;" >Remove Banner</a><?php */?>
                        </div>
                        <?php } ?>
                        <div class="form-group col-sm-10">
                            <label for="country" id="page_title">Country*</label>
                            <select name="country" id="country" class="form-control select2" style="width: 100%;">
                                <option value="" disabled="disabled">Select Country</option>
                                <?php
                                foreach ($country_list as $country) {
                                    ?>
                                    <option value="<?php echo $country['country_code']; ?>" <?php if($ads_detail[0]['country'] == $country['country_code']) echo "selected"; ?>><?php echo $country['country_name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-5">
                            <label for="show_on" id="page_title">Show On</label>
                            <select name="show_on" id="show_on" class="form-control ">
                            	<option value="home" <?php if($ads_detail[0]['show_on'] == 'home') echo "selected"; ?>>Home Page</option>
                                <option value="search" <?php if($ads_detail[0]['show_on'] == 'search') echo "selected"; ?>>Search Results</option>
                                <option value="title" <?php if($ads_detail[0]['show_on'] == 'title') echo "selected"; ?>>Title Listing</option>
								<option value="encrypted" <?php if($ads_detail[0]['show_on'] == 'encrypted') echo "selected"; ?>>Encrypted Title Listing</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-5 col-title_id">
                            <label for="title_id" id="page_title">Title</label>
                            <select name="title_id" id="title_id" class="form-control select2">
                                <option value="" selected="selected" disabled="disabled">Select Title</option>
                                <?php
                                foreach ($title_list as $title) {
                                    ?>
                                    <option value="<?php echo $title['title_id']; ?>" <?php if($ads_detail[0]['title_id'] == $title['title_id']) echo "selected"; ?>><?php echo $title['title']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
						<div class="form-group col-sm-5 col-encrypte_title_id">
                            <label for="encrypted_title_id" id="encrypted_title_id">Encrypted Title</label>
                            <select name="title_id" id="encrypted_title_id" class="form-control select2">
                                <option value="" selected="selected" disabled="disabled">Select Encrypted</option>
                                <?php
                                foreach ($encrypted_title_list as $title) {
                                    ?>
                                    <option value="<?php echo $title['title_id']; ?>" <?php if($ads_detail[0]['title_id'] == $title['title_id']) echo "selected"; ?>><?php echo $title['title']; ?>(<?=$title['feedbacks_count']; ?>) By <?=$title['name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-5">
                            <label for="show_after" id="page_title">Show After*</label>
                            <input type="text" class="form-control" name="show_after" id="show_after" value="<?php echo $ads_detail[0]['show_after'] ?>">
                        </div>
                        <div class="form-group col-sm-5">
                            <label for="repeat_for" id="page_title">Repeat For</label>
                            <select name="repeat_for" id="repeat_for" class="form-control select2">
                                <option value="1" <?php if($ads_detail[0]['repeat_for'] == 0) echo "selected"; ?>>No Repeat</option>
                                <?php
                                for ($i=2; $i<=50; $i++) {
                                    ?>
                                    <option value="<?php echo $i; ?>" <?php if($ads_detail[0]['repeat_for'] == $i) echo "selected"; ?>><?php echo $i; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-5">
                            <label for="ads_url" id="page_title">Ads URL</label>
                            <input type="text" class="form-control" name="ads_url" id="ads_url" value="<?php echo $ads_detail[0]['ads_url'] ?>">
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <?php
                        $save_attr = array('id' => 'btn_save', 'name' => 'btn_save', 'value' => 'Save', 'class' => 'btn btn-primary');
                        echo form_submit($save_attr);
                        ?>    
                        <button type="button" onclick="window.history.back();" class="btn btn-default">Back</button>
                        <!--<button type="submit" class="btn btn-info pull-right">Sign in</button>-->
                    </div><!-- /.box-footer -->
                    </form>
                </div><!-- /.box -->


            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    //validation for edit email formate form
    $(document).ready(function () {

        $("#edit_banner_frm").validate({
            rules: {
                usr_name: {
                    required: true,
                    //nospecialchar: true,
                    minlength: 3,
                    maxlength: 50
                },
				/*ads_cont: {
                    required: true,
                    minlength: 20
                },*/
				country: {
                    required: true,
				},
				show_after: {
					required: true,	
					digits: true
				},
				title_id: {
					required: {
						depends: function(element) {
							return $('#show_on').val() == 'title';
						}	
					}	
				},
				encrypted_title_id: {
					required: {
						depends: function(element) {
							return $('#show_on').val() == 'encrypted';
						}	
					}	
				},
				ads_url: {
					url: true	
				}
            },
            messages:
            {
                usr_name: {
                    required: "Please enter user name",
                },
                /*ads_cont: {
                    required: "Please enter ads content",
                },*/
				country: {
                    required: "Please select country",
                },
				show_after: {
					required: "Please enter after how many feedbacks this ad will be shown",	
					digits: "Please enter a valid number"
				},
				title_id: {
                    required: "Please select title",
                },
				encrypted_title_id: {
                    required: "Please select title",
                },
				ads_url: {
					url: "Please enter a valid URL"	
				}
            },
        });
    });
</script>
<script language="javascript" type="text/javascript">
    $(document).ready(function () {
		$('.col-title_id').hide();
		$('.col-encrypte_title_id').hide();
		
		if ($('#show_on').val() == 'title') {
		   $('.col-title_id').show();
		} else if ($('#show_on').val() == 'encrypted') {
		   $('.col-encrypte_title_id').show();
		} else {
		   $('.col-title_id').hide();
		}
		
        $('.callout-danger').delay(3000).hide('700');
        $('.callout-success').delay(3000).hide('700');
    });
	
	$('#show_on').change(function(){
		if ($(this).val() == 'title') {
		   $('.col-title_id').show();
		   $('.col-encrypte_title_id').hide();
		}
		else if ($(this).val() == 'encrypted') {
		   $('.col-encrypte_title_id').show();
		    $('.col-title_id').hide();
		} else {
		   $('.col-title_id').hide();
		   $('.col-encrypte_title_id').hide();
		}
	});
</script>
