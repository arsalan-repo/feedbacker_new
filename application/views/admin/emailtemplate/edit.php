<?php
echo $header;
echo $leftmenu;
?>

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

                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert fade in alert-success">
                                <i class="icon-remove close" data-dismiss="alert"></i>
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('error')) { ?>  
                            <div class="alert fade in alert-danger" >
                                <i class="icon-remove close" data-dismiss="alert"></i>
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php } ?>


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
                        $form_attr = array('id' => 'add_emailtemplate_frm','enctype' => 'multipart/form-data');
                        echo form_open_multipart('emailtemplate/edit', $form_attr);
                        ?>
                    <input type="hidden" name="templateid" id="id" value="<?php echo $emailtemplate_detail[0]['templateid']; ?>" />
                        <div class="box-body">
                           
                            
                            <div class="form-group col-sm-10">
                                <label for="inputEmail3" class="col-sm-2 control-label">Template Title</label>
                                <div class="col-sm-6">
                                    <?php echo $emailtemplate_detail['0']['template_title'] ?>
                                </div>
                            </div>
                            
                            <div class="form-group col-sm-10">
                                <label for="inputEmail3"  class="col-sm-2 control-label">Subject*</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="subject" id="subject" value="<?php echo $emailtemplate_detail['0']['subject'] ?>">
                                </div>
                            </div>
                            
                            <div class="form-group col-sm-10">
                                <label for="inputEmail3" class="col-sm-2 control-label">Variable </label>
                                <div class="col-sm-6">
                                    <?php $variables=  explode('<br>', $emailtemplate_detail[0]['variables']) ?>
                                    <?php
                                    foreach($variables as $variable){
                                        echo $variable;
                                        echo "<br>";
                                        
                                    }
                                    
                                    
                                    ?>
                                    <!--<textarea id="metatag_keywords" class="form-control"  cols="20" rows="2" name="metatag_keywords"><?php print_r($variables); ?></textarea>-->
                                </div>
                            </div>
                            
                            <div class="form-group col-sm-10">
                                <label for="inputEmail3" class="col-sm-2 control-label">Description *</label>
                                <div class="col-sm-6">
                                    <?php echo form_textarea(array('name' =>'emailformat','id'=>'emailformat','class'=>"ckeditor",'value'=>$emailtemplate_detail[0]['emailformat'])); ?><br>
                                </div>
                            </div>
                          
                        
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <?php
                            $save_attr = array('id' => 'btn_save', 'name' => 'btn_save', 'value' => 'Save', 'class' => 'btn btn-primary');
                            echo form_submit($save_attr);
                            ?>    
                            <button type="button" onclick="window.history.back();" class="btn btn-default">Back</button>
                        </div><!-- /.box-footer -->
                    </form>
                </div><!-- /.box -->
              
              
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php echo $footer; ?>

<script type="text/javascript">
    //validation for edit email formate form
    $(document).ready(function () {


        $("#add_emailtemplate_frm").validate({
            
            rules: {
                subject: {
                    required: true,
                }
            },
            messages:
                    {
                    subject: {
                        required: "Subject is required",
                    }
            },
        });

    });
    
    
  var roxyFileman = '<?php echo base_url().'../uploads/upload.php'; ?>' ; 

   CKEDITOR.replace( 'page_description',{
                                filebrowserBrowseUrl : roxyFileman,
                                filebrowserUploadUrl : roxyFileman,
                                filebrowserImageBrowseUrl : roxyFileman+'?type=image',
                                filebrowserImageUploadUrl : roxyFileman,
                                extraAllowedContent:  'img[alt,border,width,height,align,vspace,hspace,!src];' ,
                                removeDialogTabs: 'link:upload;image:upload'}); 

CKEDITOR.config.allowedContent = true;

CKEDITOR.on('instanceReady', function(ev) {

    // Ends self closing tags the HTML4 way, like <br>.
    ev.editor.dataProcessor.htmlFilter.addRules({
        elements: {
            $: function(element) {
                // Output dimensions of images as width and height
                if (element.name == 'img') {
                    var style = element.attributes.style;

                    if (style) {
                        // Get the width from the style.
                        var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec(style),
                            width = match && match[1];

                        // Get the height from the style.
                        match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec(style);
                        var height = match && match[1];

                        // Get the float from the style.
                        match = /(?:^|\s)float\s*:\s*(\w+)/i.exec(style);
                        var float = match && match[1];

                        if (width) {
                            element.attributes.style = element.attributes.style.replace(/(?:^|\s)width\s*:\s*(\d+)px;?/i, '');
                            element.attributes.width = width;
                        }

                        if (height) {
                            element.attributes.style = element.attributes.style.replace(/(?:^|\s)height\s*:\s*(\d+)px;?/i, '');
                            element.attributes.height = height;
                        }
                        if (float) {
                            element.attributes.style = element.attributes.style.replace(/(?:^|\s)float\s*:\s*(\w+)/i, '');
                            element.attributes.align = float;
                        }

                    }
                }

                if (!element.attributes.style) delete element.attributes.style;

                return element;
            }
        }
    });
});  
    
    
</script>