<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <?= __('Coins'); ?> <small> <?= __(' List'); ?> </small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home'); ?></a></li>
            <li class="active"><?= __('Coins Report'); ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">

        <div class="inner_content_w3_agile_info">
                <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
					 <div class="clearfix"></div>
            <form style="padding:10px" method="post" class="form-horizontal form-label-left input_mask">

					 
                     <div class="form-group">
						 <div class="col-md-3 col-sm-3 col-xs-12">
							<?php  echo $this->Form->input('start_date',array('placeholder'=>'Start date  ','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>
						   
						  </div>

						  <div class="col-md-3 col-sm-3 col-xs-12">
							 <?php  echo $this->Form->input('end_date',array('placeholder'=>'End Date','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
						   
						  </div>
						  
						 <div class="col-md-3 col-sm-3 col-xs-12">
							 <?php  echo $this->Form->input('username',array('placeholder'=>'Username','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>$usersList)); ?>
						   
						  </div>
							
							
						 <div class="col-md-2 col-sm-2 col-xs-12">
                            <?php  echo $this->Form->input('pagination',array('empty'=>'No of record','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100,200=>200,500=>500))); ?>
                           <input type="hidden" name="export" id="export" />
                        </div>
						
                         <div class="col-md-1 col-sm-1 col-xs-12">
                          <button type="submit" class="btn btn-success"><?= __('Search') ?></button>
                        </div>	
						 
                     </div>
                    
                  
				</form>
		<div class="clearfix"></div>
       
                    <h3 class="w3_inner_tittle two">Coin Report</h3>
					
					<?php //foreach($allTotal as $singleTotal) { ?>
					<!--<div class="col-lg-4 col-xs-6">

						<div class="small-box bg-aqua">
						<div class="inner">
						<h3 id="eth_total_cnt"><?php //echo $singleTotal['totalsum']; ?></h3>
						<p>Total <?php //echo $singleTotal['cryptocoin']['short_name']; ?></p>
						</div>
						<div class="icon"> <i class="fa fa-calculator" style="color:#fff;font-size:30px"></i> </div>
						</div>
					</div>-->
					<?php //} ?>
					
					
                   <!-- <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Export
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                          <li><a href="javascript:void(0)" onclick="export_f('e')">Excel</a></li>
                            <li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
                        </ul>
                    </div>-->
                    <div class="table-responsive">
                    <table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr> 
                            <th>#</th>
                            <th>Date</th>
                            <th>Ram Deposit</th>
                            <th>Ram Withdrawal</th>
							<th>Admc Deposit</th>
                            <th>Admc Withdrawal</th>
							<th>Eth Deposit</th>
                            <th>Eth Withdrawal</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= 1;
						

						
						if(!empty($getDateTrans)) {
                        foreach($getDateTrans->toArray() as $k=>$data){
						if($k%2==0) $class="odd";
                        else $class="even";
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?=$count?></td>
                            <td><?php echo $data['showdate']; ?></td>
                            <td><?php echo number_format((float)$data['totalrampurchase'],8); ?></td>
                            <td><?php echo number_format((float)abs($data['totalramwithdrawal']),8); ?></td>
							<td><?php echo number_format((float)$data['totaladmcpurchase'],8); ?></td>
                            <td><?php echo number_format((float)abs($data['totaladmcwithdrawal']),8); ?></td>
							<td><?php echo number_format((float)$data['totalethpurchase'],8); ?></td>
                            <td><?php echo number_format((float)abs($data['totalethwithdrawal']),8); ?></td>
						</tr>
                        <?php $count++;} } else { ?>
						<tr><td colspan=8 >No Data Found</td></tr>
						<?php } ?>
                        </tbody>
                    </table>
					
                    <?php
					
					if(!empty($getDateTrans)) {
					$this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'coinReportSearch')));
                        echo "<div class='pagination' style = 'float:right'>";

                        // the 'first' page button
                        $paginator = $this->Paginator;
                        echo $paginator->first("First");

                        // 'prev' page button,
                        // we can check using the paginator hasPrev() method if there's a previous page
                        // save with the 'next' page button
                        if($paginator->hasPrev()){
                            echo $paginator->prev("Prev");
                        }

                        // the 'number' page buttons
                        echo $paginator->numbers(array('modulus' => 2));

                        // for the 'next' button
                        if($paginator->hasNext()){
                            echo $paginator->next("Next");
                        }

                        // the 'last' page button
                        echo $paginator->last("Last");

                        echo "</div>";
					}
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
			
			
			$('#start-date').datepicker({
				
				format: 'yyyy-mm-dd',
				maxDate: '0'

			});
			$('#end-date').datepicker({
				
				format: 'yyyy-mm-dd',
				maxDate: '0'

			});
		$("#username").select2();

      });

		
		jQuery('.table-responsive').on('click','.pagination li a',function(event){
			event.preventDefault() ;
			var keyy = $('form').serialize();
			var urli = jQuery(this).attr('href');
			jQuery.ajax({ 
						url: urli,
						data: {key:keyy},
						type: 'POST',
						success: function(data) {
							if(data){
								
								jQuery('.table-responsive').html(data);
								
							}
						}
			});
			
		});
        
		function export_f(v) {
            $('#export').val(v);
            $("form").submit();
            $('#export').val('');
        }
		
		
	function delete_section(id){
		bootbox.confirm("Are you sure?", function(result) {
			if(result == true){
				jQuery.ajax({ 
					//url: 'delete',
					url: '<?php echo $this->Url->build(['controller'=>'users','action'=>'deleteProgram']); ?>',
					data: {'id':id},
					type: 'POST',
					success: function(data) {
						if(data == 1){
							jQuery("#user_row_"+id).remove();
							new PNotify({
								  title: 'Success',
								  text: 'Record Delete successfully!',
								  type: 'success',
								  styling: 'bootstrap3',
								  delay:1200
							  });
							
						}if(data == 'forbidden'){
							
							new PNotify({
								  title: '403 Error',
								  text: 'You donot have permission to access this action.',
								  type: 'error',
								  styling: 'bootstrap3',
								  delay:1200
							  });
							
						}
					},
					error: function (request) {
						new PNotify({
								  title: 'Error',
								  text: 'This record is being referenced in other place. You cannot delete it.',
								  type: 'error',
								  styling: 'bootstrap3',
								  delay:1200
							  });
						
					},
				});
			}
		});
		
	}
		
		
		
	function change_user_status(id){
		var status = $("#user_status_"+id).val();
		if(status == 'Y'){
			var ques= "Do you want change the status to DEACTIVE";	
			var status = "N";
			var change = '<button type="button" class="btn btn-danger btn-xs">Deactive</button>'
		}else{
			var ques= "Do you want change the status to ACTIVE";
			var status = "Y";
			var change = '<button type="button" class="btn btn-success btn-xs">Active</button>';
		}
		
		
		bootbox.confirm(ques, function(result) {
			if(result == true){
				jQuery.ajax({ 
					url: '<?php echo $this->Url->build(['controller'=>'users','action'=>'status']); ?>',
					data: {'id':id,'status':status},
					type: 'POST',
					success: function(data) {
						if(data == 1){
							jQuery("#status_id_"+id).html(change);
							jQuery("#user_status_"+id).val(status);
							new PNotify({
								  title: 'Success',
								  text: 'Status changed successfully!',
								  type: 'success',
								  styling: 'bootstrap3',
								  delay:1200
							  });
							
						}
						if(data == 'forbidden'){
							
							new PNotify({
								  title: '403 Error',
								  text: 'You donot have permission to access this action.',
								  type: 'error',
								  styling: 'bootstrap3',
								  delay:1200
							  });
							
						}
					}
				});
			}
		});

		
	}	
		
		
	</script>


