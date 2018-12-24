<style>
    .pac-container.pac-logo, #ui-id-5 {
        z-index: 999999;
    }

    .feedback_status {
        display: block;
        width: 100%;
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        margin-bottom: 10px;
    }

    #feedback_img {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
    }

    .camera-icon-block {
        padding: 22px 50px !important;
    }

    .camera-icon-block span {
        top: 10px !important;
    }

    .update {
        float: right;
        margin: -50px 0;
        padding: 15px 40px;
        border: none;
        border-radius: 5px;
        color: #000;
        background: #f5f6f7;
    }
</style>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
foreach ($feedbacks as $row) { ?>
    <div class="post-profile-block" id="post<?= $row['id']; ?>">
        <div class="post-right-arrow">
            <?php if (!empty($this->session->userdata['mec_user']['id']) && $row['user_id'] == $this->session->userdata['mec_user']['id']): ?>
                <!--                <span class="post-options-wrapper"><a href="#" class="post-options-btn" rel="toggle"-->
                <!--                                                      data-feedback="--><? //= $row['id']; ?><!--"-->
                <!--                                                      data-title="--><? //= $row['title_id']; ?><!--"></a></span>-->
                <div class="actions-bar" id="actions-bar-<?= $row['id']; ?>" style="display:none;">
                    <ul>
                        <li><a href="#" data-action="delete" data-feedback="<?= $row['id']; ?>"
                               class="option-item delete-option"><i class="fa fa-remove" aria-hidden="true"></i> Delete</a>
                        </li>
                        <!--                        <li><a href="-->
                        <? //= site_url('post/edit/' . $row['id']); ?><!--" data-action="edit"-->
                        <!--                               class="edit-option"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></li>-->
                    </ul>
                </div>
            <?php endif; ?>

            <i class="fa fa-caret-down dropdown" aria-hidden="true" style="float: right; cursor: pointer"></i>
            <ul class="dropdown_options" style="display: none">
                <?php if (!empty($this->session->userdata['mec_user']['id']) && $row['user_id'] == $this->session->userdata['mec_user']['id']) { ?>
                    <li><a href="#" class="hide_feedback_post"><img src="<?= base_url() ?>/assets/icons/new_icons/1.png" /> Hide this Title</a></li>
                    <hr/>
                    <li><a href="#" data-action="edit" class="edit_feedback" data-feedbackid="<?= $row['id']; ?>"><img src="<?= base_url() ?>/assets/icons/new_icons/5.png" /> Edit Feedback</a></li>
                    <li><a href="#" class="delete_feedback_post"><img src="<?= base_url() ?>/assets/icons/new_icons/6.png" /> Delete Feedback</a></li>
                <?php } else { ?>
                    <li><a href="#" class="hide_title" data-titleid="<?= $row['title_id']; ?>"><img src="<?= base_url() ?>/assets/icons/new_icons/1.png" /> Hide This Title</a></li>
                    <li><a href="#" class="hide_all_user_feedbacks"><img src="<?= base_url() ?>/assets/icons/new_icons/3.png" /> Hide <?= lcfirst($row['name']); ?> Feedbacks</a></li>
                    <li><a href="#" class="report_feedback" data-feedbackid="<?= $row['id']; ?>" data-titleid="<?= $row['title_id']; ?>"><img src="<?= base_url() ?>/assets/icons/new_icons/4.png" /> Report this Feedback</a></li>
                <?php } ?>
            </ul>

            <?php if (isset($row['ads'])) { ?>
                <span class="post-profile-time-text">Promoted</span>
            <?php } else { ?>
                <span class="post-followers-text">
	<?php
    if (isset($user_info['language']) && $user_info['language'] == 'ar') {
        echo $this->lang->line('followers') . " " . $row['followers'];
    } else {
        echo $row['followers'] . " " . $this->lang->line('followers');
    } ?>
	</span>
                <span class="post-profile-time-text"><?php echo $row['time']; ?></span>
            <?php } ?>
        </div>
        <div class="post-img">
            <?php
            if (isset($row['ads'])) {
                echo '<a href="' . $row['ads_url'] . '" target="_blank">';
            } else {
                echo '<a href="' . site_url('post/detail') . '/' . $row['id'] . '">';
            }
            ?>
            <?php
            if (isset($row['user_avatar'])) {
                echo '<img src="' . $row['user_avatar'] . '" alt="" />';
            } else {
                echo '<img src="' . ASSETS_URL . 'images/user-avatar.png" alt="" />';
            }
            ?>
            </a>
        </div>
        <div class="post-profile-content">
        <span class="post-designation">
            <a href="<?php echo site_url('post/title') . '/' . $row['title_id']; ?>"><?php echo $row['title']; ?></a>
        </span>
            <span class="post-name">
                <?php echo $row['name']; ?>
                <?php if (!empty($row['tagged_friends'])) { ?>
                    tagged
                    <?php foreach ($row['tagged_friends'] as $k => $v) { ?>
                        <a href="<?= site_url('UserProfile/profile/') . $k ?>" style="color: #000"
                           data-friendid="<?= $k ?>" data-friendname="<?= $v ?>"><?= ucwords($v) ?></a>
                    <?php }
                } ?>
            </span>
            <span class="post-address"
                  data-address="<?php echo $row['location']; ?>"><?php echo $row['location']; ?></span>
            <p id="feedback_content"
               data-feedbackcontent="<?php echo str_replace("\\n", "<br>", str_replace("\\r\\n", "<br>", $this->emoji->Decode(nl2br($row['feedback'])))); ?>">
                <span class="more"><?php echo str_replace("\\n", "<br>", str_replace("\\r\\n", "<br>", $this->emoji->Decode(nl2br($row['feedback'])))); ?></span>
            </p>
            <?php if (isset($row['feedback_images']) && count($row['feedback_images']) > 1) { ?>
                <div class="flexslider">
                    <ul class="slides">
                        <?php foreach ($row['feedback_images'] as $img): ?>
                            <li>
                                <a href="<?= $img['feedback_img']; ?>">
                                    <img src="<?= $img['feedback_img']; ?>"
                                         data-feedbackimg="<?= $img['feedback_img']; ?>"/>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php } else if (!empty($row['feedback_thumb'])) { ?>
                <div class="post-large-img">
                    <?php
                    if (isset($row['ads'])) { ?>
                        <a href="<?php echo $row['ads_url']; ?>" target="_blank">
                            <img src="<?php echo $row['feedback_thumb']; ?>" alt=""/>
                        </a>
                        <?php
                    } elseif (!empty($row['feedback_video'])) {
                        echo '<video width="500" height="375" controls poster="' . $row['feedback_thumb'] . '">
					<source src="' . $row['feedback_video'] . '" type="video/mp4">
					Your browser does not support the video tag.
					</video>';
                    } else {
                        echo '<img src="' . $row['feedback_thumb'] . '" alt="" />';
                    }
                    ?>
                </div>
            <?php } ?>
            <?php if (!isset($row['ads'])) { ?>
                <div class="post-follow-block">
                    <?php if (!empty($this->session->userdata['mec_user'])): ?>
                        <span class="post-follow-back-arrow post-comment-button"
                              id="reply-btn-<?php echo $row['id']; ?>">
                <img src="<?php echo ASSETS_URL . 'images/reply-arrow.png'; ?>" alt=""
                     title="<?php echo $this->lang->line('reply'); ?>"/>
            </span>
                        <span class="follow-btn-default follow-button <?php if ($row['is_followed']) echo 'unfollow-btn'; ?> follow-btn-<?php echo $row['title_id']; ?>">
                <?php if ($row['is_followed']) { ?>
                    <?php echo $this->lang->line('unfollow'); ?>
                <?php } else { ?>
                    <?php echo $this->lang->line('follow'); ?> <i class="fa fa-plus" aria-hidden="true"></i>
                <?php } ?>
            </span>
                        <span class="share">
                <a class="share_post" href="#">
                    Share <i class="fa fa-share"></i>
                </a>
            </span>
                        <span class="post-wishlist post-like-button" id="post-wishlist-<?php echo $row['id']; ?>">
				<?php if ($row['is_liked']) { ?>
                    <i class="fa fa-heart" aria-hidden="true" title="<?php echo $this->lang->line('unlike'); ?>"></i>
                <?php } else { ?>
                    <i class="fa fa-heart-o" aria-hidden="true" title="<?php echo $this->lang->line('like'); ?>"></i>
                <?php } ?>
                            <?php echo $row['likes']; ?>
            </span>

                    <?php else: ?>
                        <a class="post-follow-back-arrow" href="<?= site_url('post/detail/' . $row['id']); ?>"
                           id="reply-btn-<?php echo $row['id']; ?>">
                            <img src="<?php echo ASSETS_URL . 'images/reply-arrow.png'; ?>" alt=""
                                 title="<?php echo $this->lang->line('reply'); ?>"/>
                        </a>
                        <a href="<?= site_url('post/detail/' . $row['id']); ?>"
                           class="follow-btn-default <?php if ($row['is_followed']) echo 'unfollow-btn'; ?> follow-btn-<?php echo $row['title_id']; ?>">
                            <?php if ($row['is_followed']) { ?>
                                <?php echo $this->lang->line('unfollow'); ?>
                            <?php } else { ?>
                                <?php echo $this->lang->line('follow'); ?> <i class="fa fa-plus" aria-hidden="true"></i>
                            <?php } ?>
                        </a>
                        <a href="<?= site_url('post/detail/' . $row['id']); ?>" class="post-wishlist"
                           id="post-wishlist-<?php echo $row['id']; ?>">
                            <?php if ($row['is_liked']) { ?>
                                <i class="fa fa-heart" aria-hidden="true"
                                   title="<?php echo $this->lang->line('unlike'); ?>"></i>
                            <?php } else { ?>
                                <i class="fa fa-heart-o" aria-hidden="true"
                                   title="<?php echo $this->lang->line('like'); ?>"></i>
                            <?php } ?>
                            <?php echo $row['likes']; ?>
                        </a>
                    <?php endif; ?>
                    <span class="social-sharing">
				<a href="#">
					<div class="social-icons-container">
						<div class="sharetastic" data-url="<?= site_url('post/detail/' . $row['id']); ?>"
                             data-title="<?= $row['title']; ?>" data-description=" "></div>
                        <?php //echo strip_tags(str_replace("\\r\\n","\r\n",$this->emoji->Decode($row['feedback']))); ?>
					</div>
					<img src="<?= base_url('assets/images/share.png'); ?>"></a>
			</span>
                    <?php if (!empty($row['feedback_pdf'])): ?>
                        <span class="post-pdf-icon" id="reply-pdf-<?php echo $row['id']; ?>">
                <a href="<?= $row['feedback_pdf']; ?>"><img src="<?php echo ASSETS_URL . 'images/pdf-icon.png'; ?>"
                                                            alt="" title="PDF File"/></a>
            </span>
                    <?php endif; ?>
                    <input type="hidden" id="feedback_id" value="<?php echo $row['id']; ?>"/>
                    <input type="hidden" id="totl_likes" value="<?php echo $row['likes']; ?>"/>
                    <input type="hidden" id="title_id" value="<?php echo $row['title_id']; ?>"/>
                    <input type="hidden" id="user_id" value="<?= $row['user_id'] ?>"/>
                    <input type="hidden" id="session_id" value="<?= $this->session->userdata['mec_user']['id'] ?>"/>
                    <!--                    <input type="hidden" id="is_hidden" value="-->
                    <? //= $row['is_hidden'] ?><!--"/>-->
                    <input type="hidden" id="is_hidden" value="0"/>
                    <input type="hidden" name="latitude" id="latitude" value="<?= $row['latitude'] ?>"/>
                    <input type="hidden" name="longitude" id="longitude" value="<?= $row['longitude'] ?>"/>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>
<div class="post-detail-comment-form" title="<?php echo $this->lang->line('write_comment'); ?>">
    <?php
    $attributes = array('id' => 'reply-post-form', 'enctype' => 'multipart/form-data');
    echo form_open_multipart('post/reply', $attributes);
    ?>
    <label><?php echo $this->lang->line('comment'); ?></label>
    <textarea name="feedback_cont" id="feedback_cont" placeholder="<?php echo $this->lang->line('comment_here'); ?>"
              rows="10"></textarea>
    <input type="text" name="location" id="location" placeholder="<?php echo $this->lang->line('location'); ?>"
           readonly=""/>

    <div class="post-btn-block">
        <div class="camera-map-icon">
            <div class="camera-icon-block">
                <span>Choose File</span>
                <input name="feedback_img[]" multiple id="feedback_img" type="file"
                       style="position: absolute;left: 0;top: 0;opacity: 0;"/>
            </div>
            <img src="<?php echo base_url() . 'assets/images/map-icon.png'; ?>" class="geo-map" alt=""/>
        </div>
        <span class="post-btn"><?php echo $this->lang->line('post'); ?></span>
    </div>
    <input type="hidden" name="id" id="id" value=""/>
    <input type="hidden" name="latitude" id="latitude" value=""/>
    <input type="hidden" name="longitude" id="longitude" value=""/>
    <?php echo form_close(); ?>
    <img id="preview" src="" alt="" height="200" width="200"/>
    <div id="preview-wrapper"></div>
</div>
<div class="modal myModal">
    <div class="modal-header">
        <span class="close">&times;</span>
        <p>
            Report Feedback
        </p>
    </div>
    <div class="modal-content">
        <form>
            <label>Reason:</label>
            <textarea id="report_content"></textarea>
            <input type="hidden" id="report-feedback-id" value="">
            <input type="hidden" id="report-title-id" value="">
            <button class="submit">Submit</button>
        </form>
    </div>
</div>

<!--Edit Post-->
<div class="modal edit">
    <div class="modal-header" style="margin-top: 1%;width: 50%">
        <span class="close-edit-feedback"
              style="cursor: pointer;color: #aaa;float: right;font-size: 28px;font-weight: bold;">&times;</span>
        <p style="font-size: 20px; font-weight: bold">
            Edit Feedback
        </p>
    </div>
    <div class="modal-content" style="width: 50%">
        <form>
            <input type="hidden" name="feedback_id" id="feedback_id" value="">
            <label>Location</label>
            <input type="text" name="location" id="loc" placeholder=""/>
            <input type="hidden" name="latitude" id="lat" value=""/>
            <input type="hidden" name="longitude" id="long" value=""/>
            <label>Your Feedback</label>
            <textarea name="feedback_cont" id="feedback_cont" placeholder="" rows="3" required value=""></textarea>
            <label>Post Status</label>
            <select name="feedback_status">
                <option value="public">Public</option>
                <option value="friends">Friends</option>
                <option value="me">Only Me</option>
            </select>
            <br/>
            <br/>
            <div class="tagging">
                <label>
                    Tag Friends
                </label>
                <br/>
                <br/>
                <ul class="select-friend-tag">

                </ul>
                <input type="text" placeHolder="Enter name" name="q" id="Autocomplete" value="">
                <br/>
                <br/>
            </div>
            <div class="post-btn-block">
                <div class="camera-map-icon">
                    <div class="camera-icon-block">
                        <span>Choose File</span>
<!--                        <input name="feedback_img[]" multiple id="feedback_img" type="file" onchange="readURL(this);"/>-->
                        <input name="feedback_img[]" multiple id="feedback_img" type="file"/>
                    </div>
                    <img src="<?php echo base_url() . 'assets/images/map-icon.png'; ?>" class="geo-map" alt=""/>
                </div>
                <div class="tag-friends">
                    <a href="#" class="tag-friends"><i class="fa fa-tags"></i> Tag Friends</a>
                </div>
            </div>
            <div class="feedback-images" style="clear:both; overflow:hidden;">
                <div class="form-group" style="width:16%; float:left; margin:4px;" id="image">
                    <a href="" target="_blank">
                        <img src="" alt="" width="180">
                    </a>
<!--                    <a>-->
<!--                        <img id="blah" src="#" alt="your image" />-->
<!--                    </a>-->
                    <a class="ajax_delete" data-image_id="" data-feedback_id="" href="" title="Remove Photo"
                       style="margin: 0 auto; width: 120px;">Remove Photo</a>
                </div>
            </div>
            <button class="update">Update</button>
        </form>
    </div>
</div>
<script type="application/javascript">
    $(function () {
        baguetteBox.run('.slides');
        // Show More
        var showChar = 100;  // How many characters are shown by default
        var ellipsestext = "...";
        var moretext = "Read More";
        var lesstext = "Read Less";
        $('.post-options-btn').on('click', function (e) {
            e.preventDefault();
            var fid = $(this).data('feedback');
            $("#actions-bar-" + fid).toggle();
        });
        $('.actions-bar .option-item').on('click', function (e) {
            e.preventDefault();
            var fid = $(this).data('feedback');
            $.ajax({
                type: 'POST',
                url: "<?php echo site_url('ajax/delete_feedback'); ?>",
                data: {fid: fid},
            }).done(function (data) {

                $("#post" + fid).remove();
                toastr.success(data.message, 'Success Alert', {timeOut: 5000});
            }).fail(function (data) {
                console.log(data);
            }).always(function (data) {

            });

        });

        $('.more').each(function () {
            var content = $(this).html();

            if (content.length > showChar) {

                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);

                var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

                $(this).html(html);
            }

        });

        $(".morelink").off("click").on("click", function (e) {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });

        // Reply Dialog
        var dialog, form;

        if ($(".post-detail-comment-form").hasClass('ui-dialog-content')) {
            $(".ui-dialog-content").remove();
        }

        dialog = $(".post-detail-comment-form").dialog({
            autoOpen: false,
            width: 680,
            modal: true,
            resizable: false,
            buttons: false,
            close: function () {
                form[0].reset();

                $("#feedback_cont-error").remove();
                $("#feedback_cont").removeClass("error");
            }
        });

        form = dialog.find("form");

        dialog.find(".post-btn").on("click", function (event) {
            event.preventDefault();

            $("#feedback_cont-error").remove();
            $("#feedback_cont").removeClass("error");

            if ($("#feedback_cont").val().length == 0) {
                $("#feedback_cont").addClass("error");
                $('<label id="feedback_cont-error" class="error" for="feedback_cont">Please enter a feedback</label>').insertAfter("#feedback_cont");

                return false;
            }
            console.log(form[0].action);
            console.log(new FormData(form[0]));
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: form[0].action,
                data: new FormData(form[0]),
                processData: false,
                contentType: false
            }).done(function (data) {
                if (data.status == 1) {
                    dialog.dialog("close");
                    toastr.success(data.message, 'Success Alert', {timeOut: 5000});
                } else {
                    toastr.warning(data.error_message, 'Alert', {timeOut: 5000});
                }
            }).fail(function (data) {
                toastr.warning(data.error_message, 'Alert', {timeOut: 5000});
            });

            return false;
        });

        $('.post-comment-button').off("click").on("click", function (e) {
            var feedback_id = $(this).parent().find('#feedback_id').val();
            dialog.find("#id").val(feedback_id);
            dialog.dialog("open");
        });

        $("#feedback_img").change(function () {
            imagePreview(this);
        });

        $(".geo-map").click(function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showLocation);
            } else {
                toastr.error('Geolocation is not supported by this browser', 'Failure Alert', {timeOut: 5000});
            }
        });

        $('.flexslider').flexslider({
            animation: "slide"
        });

    });

    function showLocation(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('post/get_location'); ?>',
            data: 'latitude=' + latitude + '&longitude=' + longitude,
            success: function (response) {
                if (response) {
                    var objJSON = JSON.parse(response);
                    $('#location').val(objJSON.location);

                    $("#latitude").val(latitude);
                    $("#longitude").val(longitude);
                } else {
                    toastr.error('Error getting location. Try later!', 'Failure Alert', {timeOut: 5000});
                }
            }
        });
    }

    function imagePreview(input) {
        var files = input.files;
        $("#preview-wrapper").html('');
        $.each(input.files, function (i, file) {
            var img = document.createElement("img");
            img.id = "image" + (i + 1);
            img.style.cssText = "width:200px; height:200px; margin:5px;";
            var reader = new FileReader();
            reader.onloadend = function () {
                img.src = reader.result;
            }
            reader.readAsDataURL(file);
            $("#image" + i).after(img);
            $("#preview-wrapper").append(img);

        });
    }

    $('.follow-button').off("click").on("click", function (e) {
        e.preventDefault();

        var title_id = $(this).parent().find('#title_id').val();

        $.ajax({
            dataType: 'json',
            type: 'POST',
            url: '<?php echo site_url('title/follow'); ?>',
            data: {title_id: title_id}
        }).done(function (data) {
            // console.log(data);
            if (data.is_followed == 1) {
                $('.follow-btn-' + title_id).each(function () {
                    $(this).addClass('unfollow-btn');
                    $(this).html('<?php echo $this->lang->line('unfollow'); ?>');
                });
                toastr.success(data.message, 'Success Alert', {timeOut: 5000});
            }
            else {
                $('.follow-btn-' + title_id).each(function () {
                    $(this).removeClass('unfollow-btn');
                    $(this).html('<?php echo $this->lang->line('follow'); ?> <i class="fa fa-plus" aria-hidden="true"></i>');
                });
                toastr.warning(data.message, 'Success Alert', {timeOut: 5000});
            }
        });
    });

    $('.post-like-button').off("click").on("click", function (e) {
        e.preventDefault();

        var element = $(this).attr('id');
        var totl_likes = $(this).parent().find('#totl_likes').val();
        var feedback_id = $(this).parent().find('#feedback_id').val();

        $.ajax({
            dataType: 'json',
            type: 'POST',
            url: '<?php echo site_url('post/like'); ?>',
            data: {feedback_id: feedback_id, totl_likes: totl_likes}
        }).done(function (data) {
            // console.log(data);
            if (data.is_liked == 1) {
                var totl = parseInt(data.likes) + 1;
                $('#' + element).parent().find('#totl_likes').val(totl);

                $('#' + element).html('<i class="fa fa-heart" aria-hidden="true"></i><span class="total-likes"> ' + totl + '</span>');
                toastr.success(data.message, 'Success Alert', {timeOut: 5000});
            }
            else {
                var totl = parseInt(data.likes) - 1;
                $('#' + element).parent().find('#totl_likes').val(totl);

                $('#' + element).html('<i class="fa fa-heart-o" aria-hidden="true"></i><span class="total-likes"> ' + totl + '</span>');
                toastr.warning(data.message, 'Success Alert', {timeOut: 5000});
            }
        });
    });

    $('.share_post').click(function () {

        var feedback_id = $(this).parents('.post-follow-block').find('#feedback_id').val();
        var title_id = $(this).parents('.post-follow-block').find('#title_id').val();

        $.ajax({
            url: '<?= site_url('post/share_post/') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {feedback_id: feedback_id, title_id: title_id},
        }).done(function (data) {
            if (data.status == 1) {
                toastr.success(data.message, 'Success Alert', {timeOut: 5000});
            } else {
                toastr.warning(data.error_message, 'Success Alert', {timeOut: 5000});
            }
        })
    });

    jQuery(function () {
        $('.dropdown').click(function () {
            $(this).parent('.post-right-arrow').find('.dropdown_options').toggle();
        })
    });

    $('.delete_feedback_post').click(function () {
        var post_div = $(this).parents('.post-profile-block');
        var feedback_id = post_div.find('#feedback_id').val();
        $.ajax({
            url: '<?= site_url('post/delete_feedback_post') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {feedback_id: feedback_id},
        }).done(function (data) {
            if (data.status == 1) {
                toastr.success(data.message, 'Success Alert', {timeOut: 5000});
                post_div.remove();
            } else {
                toastr.warning(data.error_message, 'Error', {timeOut: 5000});
            }
        })
    });

    $('.hide_feedback_post').click(function () {
        var post_div = $(this).parents('.post-profile-block');
        var feedback_id = post_div.find('#feedback_id').val();
        // var is_hidden = post_div.find('#is_hidden').val();
        var is_hidden = 1;

        // if (is_hidden == 0) {
        //     $(this).html("Unhide");
        //     post_div.find('#is_hidden').val(1);
        //     is_hidden = 1;
        // } else {
        //     $(this).html("Hide");
        //     post_div.find('#is_hidden').val(0);
        //     is_hidden = 0;
        // }

        $.ajax({
            url: '<?= site_url('post/hide_feedback_post') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {feedback_id: feedback_id, is_hidden: is_hidden},
        }).done(function (data) {
            if (data.status == 1) {
                toastr.success(data.message, 'Feedback hidden', {timeOut: 5000});
                post_div.remove();
            } else {
                toastr.success(data.error_message, 'An error occurred', {timeOut: 5000});
            }
        })

    });


</script>
<script>
    $(document).ready(function () {
        $('.sharetastic').sharetastic();
        $(document).mouseup(function (e) {
            var container = $(".social-icons-container");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.hide();
            }
        });
        $('.social-sharing a').click(function (e) {
            e.preventDefault();
            var $iconwraper = $(this).find('.social-icons-container');
            $iconwraper.hide();
            //$iconwraper.css({'top':e.pageY-50,'left':e.pageX-260, 'position':'absolute', 'padding':'5px'});
            $iconwraper.css({'bottom': '10px', 'right': '0', 'position': 'absolute', 'padding': '5px'});
            $iconwraper.show();
        });
    });
</script>
<script>
    jQuery(function ($) {

    })
</script>
<script>
    jQuery(function ($) {
        $(document).on('click', '.report_feedback', function (e) {
            e.preventDefault();
            $('.myModal').fadeIn();
            var feedback_id = $(this).attr('data-feedbackid');
            $('#report-feedback-id').val(feedback_id);
            var title_id = $(this).attr('data-titleid');
            $('#report-title-id').val(title_id);
        });

        $(document).on('click', '.submit', function (e) {
            e.preventDefault();
            var report_content = $('#report_content').val();
            var report_feedback_id = $('#report-feedback-id').val();
            var report_title_id = $('#report-title-id').val();
            $.ajax({
                url: '<?= site_url('post/report_feedback') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    report_feedback_id: report_feedback_id,
                    report_title_id: report_title_id,
                    report_content: report_content
                },
            }).done(function (data) {
                if (data.status == 1) {
                    toastr.success(data.message, 'Success Alert', {timeOut: 5000});
                    $('#report_content').val('');
                    $('.myModal').css({"display": "none"});

                } else {
                    toastr.warning(data.error_message, 'Error', {timeOut: 5000});
                }
            })
        })


        $(document).on('click', '.close', function () {
            $('.myModal').css({"display": "none"});
        });
    });
</script>
<script>
    $('.hide_title').click(function (e) {
        e.preventDefault();
        var title_id = $(this).parents('.post-profile-block').find('#title_id').val();
        var session_id = <?= $this->session->userdata['mec_user']['id'] ?>;
        $.ajax({
            url: '<?= site_url('title/hide_title') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {title_id: title_id, session_id: session_id}
        }).done(function (data) {
            if (data.status == 1) {
                toastr.success(data.message, 'Success Alert', {timeOut: 5000});
            } else {
                toastr.warning(data.error_message, 'Error', {timeOut: 5000});
            }
        })
    })
</script>
<script>
    $('.hide_all_user_feedbacks').click(function (e) {
        e.preventDefault();
        var user_id = $(this).parents('.post-profile-block').find('#user_id').val();
        var session_id = <?= $this->session->userdata['mec_user']['id'] ?>;
        $.ajax({
            url: '<?= site_url('post/hide_all_user_feedbacks') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {user_id: user_id, session_id: session_id}
        }).done(function (data) {
            if (data.status == 1) {
                toastr.success(data.message, 'Success Alert', {timeOut: 5000});
            } else {
                toastr.warning(data.error_message, 'Error', {timeOut: 5000});
            }
        })
    })
</script>

<!--Edit feedback-->

<script>
    jQuery(function () {
        $(".geo-map").click(function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showLocation);
            } else {
                toastr.error('Geolocation is not supported by this browser', 'Failure Alert', {timeOut: 5000});
            }
        });
        var location_field_id = null;

        function showCurrentLocationField(lat, lng) {
            var $old_location = $(location_field_id != null ? location_field_id : "#loc");
            var rand = Math.floor(Math.random() * 99999999);
            var rand_id = 'location-' + rand;
            var $location = $('<input type="text" name="location" id="' + rand_id + '" placeholder="Enter your location"/>');

            location_field_id = "#" + rand_id;

            $location.insertAfter($old_location);
            $old_location.remove();

            var location_picker_opt1 = {
                location: {
                    latitude: lat,
                    longitude: lng
                },
                inputBinding: {
                    locationNameInput: jQuery('#' + rand_id),
                    latitudeInput: jQuery('#lat'),
                    longitudeInput: jQuery('#long'),
                },
                enableAutocomplete: true,
            };

            console.log(location_picker_opt1);
            $("#" + rand_id).locationpicker(location_picker_opt1);
        };

        function showLocation(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            showCurrentLocationField(latitude, longitude);

            $.ajax({
                type: 'POST',
                url: '<?php echo site_url('post/get_location'); ?>',
                data: 'latitude=' + latitude + '&longitude=' + longitude,
                success: function (response) {
                    if (response) {
                        var objJSON = JSON.parse(response);
                        $('#location').val(objJSON.location);

                        $("#latitude").val(latitude);
                        $("#longitude").val(longitude);

                        $("#latitude").trigger('change');
                        $("#longitude").trigger('change');
                    } else {
                        toastr.error('Error getting location. Try later!', 'Failure Alert', {timeOut: 5000});
                    }
                }
            });
        };
        var tagged_friends = [];
        $('.edit_feedback').click(function (e) {
            e.preventDefault();
            $('.edit').find('.select-friend-tag li').remove();
            $('.edit').fadeIn();
            var feedback_id = $(this).parents('.post-profile-block').find('#feedback_id').val();
            var feedback_content = $(this).parents('.post-profile-block').find('#feedback_content').attr('data-feedbackcontent');
            var latitude = $(this).parents('.post-profile-block').find('#latitude').val();
            var longitude = $(this).parents('.post-profile-block').find('#longitude').val();
            var image = '';
            if ($(this).parents('.post-profile-block').find('.flexslider img').length > 0)
                image = $(this).parents('.post-profile-block').find('.flexslider img').attr('data-feedbackimg');
            $('.edit').find('#feedback_id').val(feedback_id);
            $('.edit').find('#feedback_cont').val(feedback_content);
            $('.edit').find('#lat').val(latitude);
            $('.edit').find('#long').val(longitude);
            if(image != ''){
                $('.edit').find('.feedback-images .ajax_delete').show();
                $('.edit').find('.feedback-images a').attr('href', image);
            }else{
                $('.edit').find('.feedback-images .ajax_delete').hide();
            }
            $('.edit').find('.feedback-images img').attr('src', image);
            showCurrentLocationField(latitude, longitude);
            // Tagged Friends
            $(this).parents('.post-profile-block').find('.post-name > a').each(function () {
                $('.edit').find('.select-friend-tag').show();
                var friend_id = $(this).attr('data-friendid');
                var friend_name = $(this).attr('data-friendname');
                tagged_friends.push(friend_id);
                // console.log(tagged_friends);
                $('.edit').find('.select-friend-tag').append('<li data-name="' + friend_name + '"><i class="fa fa-remove remove-friends"></i><span>' + friend_name + '</span><input type="hidden" name="tagged_friends[]" value="' + friend_id + '"></li>')
            });
        });
        $('.close-edit-feedback').click(function () {
            $('.edit').css({"display": "none"});
            tagged_friends = [];
        });

        $("#Autocomplete").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $.getJSON("<?php echo site_url('user/find_friends'); ?>", {
                    term: request.term
                }, response);

            },
            focus: function (event, ui) {
                return false;
            },
            select: function (event, ui) {

                if (jQuery.inArray(ui.item.user_id, tagged_friends) <= -1) {
                    console.log(ui.item);
                    var $tags = $('.edit .select-friend-tag');
                    $tags.show();
                    var $li = $('<li data-id="'+ui.item.user_id+'" data-name="' + ui.item.name + '"><i class="fa fa-remove remove-friends"></i>' + ui.item.name + '<input type="hidden" name="tagged_friends[]" value="' + ui.item.user_id + '" /></li>');
                    $tags.append($li);
                    tagged_friends.push(ui.item.user_id);
                    setTimeout(function () {
                        $('#Autocomplete').val('');
                    }, 100);
                } else {
                    alert('Already exists');
                }

                this.value = ui.item.name;
                var img = 'https://feedbacker.me/dev/assets/images/user-avatar.png';
                if (ui.item.photo) {
                    img = 'https://d1f8jwm5uy46l.cloudfront.net/uploads/user/thumbs/' + ui.item.photo;
                }
                $("<div/>").html('<div class="post-profile-block friend-request" id="user-id-' + ui.item.user_id + '"><div class="post-img"><img src="' + img + '" alt=""></div><div class="post-profile-content"> <span class="post-designation"><a href="#">' + ui.item.name + '</a>	</span>			<span class="post-name">Jordan</span> 		</div>		<div class="post-buttons">			<button type="button" data-user="' + ui.item.user_id + '" id="reqbtn-' + ui.item.user_id + '" class="btn btn-blue send_friend_request" onclick="sendFriendRequest(' + ui.item.user_id + ')">Send Friend Request</button></div></div>').prependTo("#user-search-list");

                return false;
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            var img = 'https://feedbacker.me/dev/assets/images/user-avatar.png';
            if (item.photo) {
                img = 'https://d1f8jwm5uy46l.cloudfront.net/uploads/user/thumbs/' + item.photo;
            }
            return $("<li>").append("<div><img style='width:40px; border-radius:50%; display:inline-block; float:left; margin-right:10px;' src='" + img + "'><span style='display:inline-block; line-height:40px; '>" + item.name + "</span></div>").appendTo(ul);
        };
        $(document).on('click', '.remove-friends', function (e) {
            e.preventDefault();
            var $liname = $(this).parent();
            tagged_friends.splice(jQuery.inArray($liname.data('id'), tagged_friends), 1);
            $liname.remove();
            console.log(tagged_friends);
        });
        $('.update').click(function (e) {
            e.preventDefault();
            var feedback_id = $('.edit').find('#feedback_id').val();
            var feedback_content = $('.edit').find('#feedback_cont').val();
            var latitude = $('.edit').find('#lat').val();
            var longitude = $('.edit').find('#long').val();
            var location = $('.edit').find('input[name=location]').val();
            console.log(feedback_id, feedback_content, latitude, longitude, location, tagged_friends);
            $.ajax({
                url: '<?= site_url('post/edit_feedback'); ?>',
                type : 'POST',
                dataType : 'JSON',
                data : {feedback_id: feedback_id, feedback_content: feedback_content, latitude: latitude, longitude: longitude, location: location, tagged_friends: tagged_friends}
            }).done(function (data) {
                if(data.status == 1){
                    toastr.success(data.message, 'Success Alert', {timeOut: 5000});
                    $('.edit').hide();
                    window.location.reload();
                }else{
                    toastr.warning(data.message, 'An error occurred', {timeOut: 5000});
                }
            })
        })
    });


</script>
<script>
    function readURL(input)
    {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
