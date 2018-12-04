<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<div class="content-wrapper">
	<section class="content-header">
        <h1>
            Questionnaires
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('admin/dashboard'); ?>">
                    <i class="fa fa-dashboard"></i>
                    Home
                </a>
            </li>
            <li class="active"><?php echo $module_name; ?></li>
        </ol>
    </section>
	 <section class="content">
        <div class="row" >
            <div class="col-xs-12" >
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
            </div>
        </div>
		 <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $section_title; ?></h3>
                      
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
						<div class="survey-reesults" style="width:100%; clear:both; float:left; margin-top:30px;">
						<h2>Survey Results</h2>			
							<?php foreach($survey['questions'] as $k=>$question){ ?>
									<div class=""><h2><?=$question['question'];?></h2></div>
									<div id="chart_<?=$question['question_id'];?>"></div> 
									<hr>
							<?php } ?>	
						</div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                </tbody>
                <tfoot>

                </tfoot>
                </table>
            </div><!-- /.box -->


        </div><!-- /.col -->
	</section>
</div> 
<script type="text/javascript"> 
     
    // Load the Visualization API and the piechart package. 
    google.charts.load('current', {'packages':['corechart','bar']});       
   
    google.charts.setOnLoadCallback(drawChart); 
       
    function drawChart() {
		
			$.ajax({
                type: 'POST',
                url: "<?php echo $chart_data_path; ?>", 
                success: function (data1) {
                 var questionArr = $.parseJSON(data1);
				 console.log(questionArr);
                 for (var i = 0; i < questionArr.length; i++) {  
					var data = new google.visualization.DataTable();
					data.addColumn('string', 'Questions');
					data.addColumn('number', 'Users');
					//data.addColumn('annotation', 'Users');
					var rows=questionArr[i].rows;
					
					for(var j=0; j<rows.length;j++){
						data.addRow([rows[j].label, parseInt(rows[j].results)]);
					}
				var height='auto';	
				if(questionArr[i].qtype=='single')
					height=50;
				   var options = {					
					width: '100%',
					height: height,
					axes: {
					  x: {
						0: {side: 'top'}
					  }
					},
					bars: 'horizontal'					 
				  };
				 var chart = new google.charts.Bar(document.getElementById('chart_'+questionArr[i].question_id));
				chart.draw(data, options);
                 }
				 
               }
			});
    
    }  
    </script>  


