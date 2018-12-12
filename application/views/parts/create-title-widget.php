<style>
    .create-title{
        background: #fff;
        border-radius: 10px;
        border: 1px solid #cecaca;
        margin: 0 0 20px 0;
    }
    .panel-header{
        background: #f5f6f7;
        border: none;
        border-bottom: 1px solid #dddfe2;
        border-radius: 10px 10px 0 0;
        padding: 10px;
        font-size: 15px;
        font-weight: bold;
    }
    .panel-body{
        padding: 10px 10px 60px 10px;
    }
    .form-element input[type="text"], .form-element textarea{
        display: block;
        width: 100%;
        padding: .375rem .75rem;
        font-size: 0.9rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: .25rem;
    }
    .form-element {
        margin: 10px 0;
    }
    .form-element textarea{
        height: 70px;
    }
    .quarter-width {
        width: 25%;
        float: left;
        padding: 5px 10px;
    }
    .quarter-width input[type="file"] {
        display: none;
    }
    .custom-file-upload {
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer;
        border-radius: 5px;
        background: #f5f6f7;
        width: 135px;
    }
    label{
        width: auto;
    }
</style>
<div class="create-title">
    <div class="panel-header">
        Create Encrypted Title
    </div>
    <div class="panel-body">
        <?php
        $attributes = array('id' => 'create-post-form', 'enctype' => 'multipart/form-data');
        echo form_open_multipart('encrypted/create', $attributes);
        ?>
        <div class="form-element">
            <input type="text" name="title" id="title" placeholder="Enter Encrypted Title" required />
        </div>
        <div class="form-element">
            <label>Is it questionnaire?</label>
            <br/>
            <br/>
            <br/>
            <br/>
            <input type="checkbox" name="is_survey" id="is_survey" style="width:auto; margin:4px 5px 0 0;" /> Yes
        </div>
        <div class="form-element">
            <textarea name="feedback_cont" id="feedback_cont" placeholder="Your Feed" rows="10" required></textarea>
        </div>
        <div class="form-element">
            <img id="preview" src="" alt="" height="200" width="200" style="display:inline-block;">
            <div id="preview-wrapper" style="display:inline-block;"></div>
            <div id="pdf-preview-wrapper" style="display:inline-block;"></div>
        </div>
        <div class="form-element" style="width: 100%;margin: 20px 0 0 0;">
            <div class="quarter-width">
                <label for="file-upload" class="custom-file-upload">
                    <i class="fa fa-camera"></i> Picture/Video
                </label>
            </div>
            <div class="quarter-width">
                <label for="feedback_files" class="custom-file-upload">
                    <i class="fa fa-file-pdf-o"></i> Documents
                </label>
                <input name="feedback_files[]" id="feedback_files" multiple type="file" style="float: none"/>
            </div>
            <div class="quarter-width">
                <label for="file-upload" class="custom-file-upload linkUser">
                    <i class="fa fa-user"></i> Link Users
                </label>
            </div>
            <div class="quarter-width">
                <label for="file-upload" class="custom-file-upload linkGroup">
                    <i class="fa fa-group"></i> Link Group
                </label>
            </div>
        </div>
        <div class="form-element" style="padding: 30px 0;">
            <div class="user-wrapper" style="width:100%;">
                <div class="user-list" style="margin-bottom:20px;display: none;margin: 30px 0px 0px;">
                    <label style="padding-bottom:10px;"><?php echo $this->lang->line('link_users'); ?></label>
                    <select id="linked-users" class="js-data-example-ajax form-control" name="users[]" multiple="true" style="width:100%;" placeholder="<?php echo $this->lang->line('enter_user_name_or_email_address'); ?>"></select>
                </div>
                <div class="user-group" style="display: none;margin: 30px 0px 0px;">
                    <p style="padding-bottom:10px;"><?php echo $this->lang->line('select_from_list'); ?> <span class="pull-right" style="display:block;" id="create-user-group"><?php echo $this->lang->line('add_new_list'); ?></span> </p>
                    <select class="select2-single form-control" name="user_groups" id="user_groups" style="width:100%; padding:10px;" placeholder="Select List">
                        <option value=""><?php echo $this->lang->line('select_group'); ?></option>
                        <?php foreach($groups as $group): ?>
                            <option value="<?=$group['group_id']; ?>"><?=$group['title']; ?>(<?=$group['count']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="post-btn-block" style="clear:both; width:100;">
            <span class="post-btn"><?php echo $this->lang->line('post_enc'); ?></span>
            <!--<span class="link-user-btn">Link Users</span>-->
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<div id="dialog-add-group" title="<?php echo $this->lang->line('create_new_user_group'); ?>">
    <p class="validateTips"><?php echo $this->lang->line('all_fields_are_required'); ?></p>
    <form style="position:relative; width:100%;" action="<?=site_url('user/create_user_group'); ?>">
        <label for="name"><?php echo $this->lang->line('name'); ?></label>
        <input type="text" name="name" id="group-name" value="" class="text ui-widget-content ui-corner-all" required>
        <div class="user-list" style="position:relative; clear:both;">
            <label style="padding-bottom:10px;"><?php echo $this->lang->line('select_user'); ?></label>
            <select class="js-data-example-ajax text ui-widget-content ui-corner-all" id="group-users" name="users[]" multiple="true" style="width:100%;" placeholder="<?php echo $this->lang->line('enter_user_name_or_email'); ?>"></select>
        </div>

        <!-- Allow form submission with keyboard without duplicating the dialog button -->
        <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </form>
</div>
<script>
    $('.linkUser').click(function () {
        $('.user-list').toggle();
    })
    $('.linkGroup').click(function () {
        $('.user-group').toggle();
    })
</script>
<script type="application/javascript">
    var current_url="<?=site_url('encrypted/create');?>";
    $("#dashboard_url").val(current_url);
    var base_url="<?=base_url();?>";
    var URL=base_url+"user/search_users";
    function userlist (state) {
        if(state.id==state.text)
            return;
        if(!state.photo){
            var photo_url="https://feedbacker.me/dev/assets/images/user-avatar.png";
        }else{
            var photo_url="https://d1f8jwm5uy46l.cloudfront.net/uploads/user/thumbs/"+state.photo;
        }
        var $item = $(
            '<div class="select2-list-item-with-image user-'+state.id+'" style="clear:both; float:none; line-height:32px;"><img style="width:32px; float:left; margin-right:8px;" src="'+photo_url+'" class="img-flag" /> ' + state.text + '</div>'
        );
        return $item;
    };
    $(function() {
        $('.js-data-example-ajax').select2({
            templateResult: userlist,
            minimumInputLength: 2,
            placeholder: '<?php echo $this->lang->line('enter_user_name_or_email'); ?>',
            tags: [],
            ajax: {
                url: URL,
                dataType: 'json',
                type: "POST",
                async: false,
                quietMillis: 50,
                data: function (term) {
                    return {
                        s: term
                    };
                },
                processResults: function (data) {
                    //console.log(data);
                    return {
                        results: $.map(data.users, function(index, val) {
                            return {
                                id: index.id,
                                text: index.text,
                                photo:index.photo
                            }
                        })
                    };
                },
            }
        });
        var dialog, form;
        dialog = $(".encrypted-title-user-form").dialog({
            autoOpen: false,
            width: 680,
            modal: true,
            resizable: false,
            buttons: false,
            close: function() {
                form[0].reset();
            }
        });
        form = dialog.find( "form" );

        // jQuery Toastr
        if ($.trim($(".div-toastr-error").html()).length > 0) {
            $(".div-toastr-error p").each(function( index ) {
                toastr.error($(this).html(), 'Failure Alert', {timeOut: 5000});
            });
        }

        // Set Autocomplete Off
        $("#create-post-form").attr('autocomplete', 'off');
        dialog.find( ".search-btn" ).on( "click", function( event ) {
            event.preventDefault();
            $("#link-user-search-error").remove();
            $("#link-user-search").removeClass( "error" );

            if ($("#link-user-search").val().length == 0) {
                $("#link-user-search").addClass( "error" );
                $('<label id="link-user-search-error" class="error" for="link-user-search">Please enter username or email address</label>').insertAfter("#search-bar");

                return false;
            }

            $.ajax({
                dataType: 'json',
                type:'POST',
                url: form[0].action,
                data: new FormData(form[0]),
                processData: false,
                contentType: false
            }).done(function(data){
                //console.log(data);
            });

            //dialog.dialog( "close" );
            //return true;
        });
        $('.link-user-btn').off("click").on("click",function(e){
            //dialog.find( "#id" ).val(feedback_id);
            dialog.dialog( "open" );
        });
        var requestRunning = false;
        $(".post-btn").click(function() {
            $("#create-post-form").submit();

        });

        var reqired_description=true;
        $("#create-post-form").validate({
            // Specify the validation rules
            rules: {
                title: {
                    required: true
                },
                feedback_cont: {
                    required:{
                        depends: function(element) {
                            if(document.getElementById("feedback_files").files.length<1)
                                return true;
                            else
                                return false;

                        }
                    }
                }
            },

            // Specify the validation error messages
            messages: {
            },

            submitHandler: function(form) {
                form.submit();
            }
        });


    });
    $( function() {

        $('#is_survey').change(function() {
            if($(this).is(":checked")) {
                $("#title-elements-wrapper").hide();
            }else{
                $("#title-elements-wrapper").show();
            }
        });
        $( "#user_groups" ).change(function(d) {
            var list_id=$(this).val();
            console.log(list_id);
            $.ajax({
                dataType: 'json',
                type:'POST',
                url: base_url+"user/group_users",
                data: { id: list_id }
            }).done(function(items){
                //console.log(items);
                var selectedValues = [];
                $('#linked-users').val(null).trigger('change');
                $("#linked-users").empty().trigger('change');
                $.each( items, function( key, item ) {
                    //console.log(item);
                    selectedValues.push(item.user_id);
                    $("#linked-users").append('<option value="'+item.user_id+'" selected="selected">'+item.name+'</option>');
                });
                $("#linked-users").val(selectedValues).trigger('change');
            });
        });
        var gdialog, gform,
            name = $( "#name" ),
            allFields = $( [] ).add( name ),
            tips = $( ".validateTips" );
        function updateTips( t ) {
            tips
                .text( t )
                .addClass( "ui-state-highlight" );
            setTimeout(function() {
                tips.removeClass( "ui-state-highlight", 1500 );
            }, 500 );
        };
        gdialog = $( "#dialog-add-group" ).dialog({
            autoOpen: false,
            height: 400,
            width: 350,
            modal: true,
            buttons: {
                "<?php echo $this->lang->line('create_group'); ?>": addUser,
                "<?php echo $this->lang->line('cancel'); ?>": function() {
                    gdialog.dialog( "close" );
                }
            },
            close: function() {
                gform[ 0 ].reset();
                allFields.removeClass( "ui-state-error" );
            }
        });

        gform = gdialog.find( "form" ).on( "submit", function( event ) {
            event.preventDefault();
            addUser();

        });
        function addUser() {
            var valid = true;
            allFields.removeClass( "ui-state-error" );
            var group_name=$('#group-name').val();
            if(group_name=="")
                valid=false;
            if ( valid ) {
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url: gform[0].action,
                    data: new FormData(gform[0]),
                    processData: false,
                    contentType: false
                }).done(function(group){

                    var hasOption = $('#user_groups option[value="' + group.group_id + '"]');
                    console.log(hasOption);
                    if (hasOption.length == 0) {
                        $('#user_groups').append('<option value="'+group.group_id+'" selected="selected">'+group.title+'('+group.count+')'+'</option>');
                    }else{
                        $('#user_groups option[value="' + group.group_id + '"]').text(group.title+'('+group.count+')');
                    }
                    $('#group-users').val(null).trigger('change');
                    gform[ 0 ].reset();
                    gdialog.dialog( "close" );
                });

            } else{
                updateTips("All Fields are required.");
            }
            return valid;
        }
        $( "#create-user-group" ).on( "click", function() {
            console.log(gdialog);
            gdialog.dialog( "open" );
        });
        $("#feedback_img").change(function(){
            imagePreview(this);
        });
        $("#feedback_files").change(function(){
            pdfPreview(this);
        });
    });
    function pdfPreview(input){
        var files = input.files;
        $("#pdf-preview-wrapper").html('');
        $.each(input.files, function(i, file) {
            var span = document.createElement("div");
            span.innerHTML=file.name;
            $("#pdf-preview-wrapper").append(span);
            /*var img = document.createElement("img");
            img.id = "pdf"+(i+1);
            img.style.cssText="width:200px; height:200px; margin:5px;";
            img.src = 'https://feedbacker.me/assets/images/pdf-large-icon.png';
            //console.log(file);
            $("#pdf-preview-wrapper").append(img);*/
        });
        reqired_description=false;
    }
    function imagePreview(input) {
        var files = input.files;
        $("#preview-wrapper").html('');
        $.each(input.files, function(i, file) {
            var img = document.createElement("img");
            img.id = "image"+(i+1);
            img.style.cssText="width:200px; height:200px; margin:5px;";
            var reader = new FileReader();
            reader.onloadend = function () {
                img.src = reader.result;
            }
            reader.readAsDataURL(file);
            $("#image"+i).after(img);
            $("#preview-wrapper").append(img);

        });
    }
</script>
