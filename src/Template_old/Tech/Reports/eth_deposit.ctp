<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Eth Deposit <small>Listing</small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"> Eth Deposit</li>
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
                            <?php  echo $this->Form->input('username',array('placeholder'=>'Username','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                            
                        </div>
						
                         <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php  echo $this->Form->input('email',array('placeholder'=>'Email','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                        </div>
						
						 <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php  echo $this->Form->input('tx_id',array('placeholder'=>'Transaction ID','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                        </div>
						
						 <div class="col-md-3 col-sm-3 col-xs-12">
                            <?php  echo $this->Form->input('wallet_address',array('placeholder'=>'Wallet Address','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
                        </div>
                      
						 
                     </div>
                   
                       <div class="col-md-2 col-sm-2 col-xs-12">
                            <?php  echo $this->Form->input('pagination',array('empty'=>'No of record','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100))); ?>
                           <input type="hidden" name="export" id="export" />
                        </div> 
                         <div class="col-md-1 col-sm-1 col-xs-12">
                          <button type="submit" class="btn btn-success">Filter</button>
                        </div>
					 </div>
                  
				</form>
		<div class="clearfix"></div>
       
                    <h3 class="w3_inner_tittle two">Eth Deposit list</h3>
                   <div class="w3l-table-info agile_info_shadow">
				   <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Export
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                          <!--  <li><a href="javascript:void(0)" onclick="export_f('e')">Excel</a></li>-->
                            <li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
                        </ul>
                    </div>
                    <div class="table-responsive">
                    <table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr> 
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Data</th>
                            <th>Status </th>
							<th>Created </th>
							<th>Action </th>
						</tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= 1; 
                        foreach($getData->toArray() as $k=>$data){
						
						
						
                        if($k%2==0) $class="odd";
                        else $class="even";
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?=$count?></td>
                            <td><?php echo $data['user']['username']; ?></td>
							<td><?php echo $data['user']['email']; ?></td>
							<td>
								<strong>Tx Id</strong> - <?php echo $data['tx_id']; ?>
								<br/>
								<strong>Amount</strong> - <?php echo abs($data['coin_amount'])." ".$data['cryptocoin']['short_name']; ?>
							
								<br/>
								<strong>Wallet Address</strong> - <?php echo $data['wallet_address']; ?>
								<br/>
								<?php if(!empty($data['user_file'])) { ?><a target="_blank" href="<?php echo "/uploads/flat/".$data['user_file'] ?>"> Show Attachment</a> <?php } ?>
							</td>
							
							<td><?php echo ucfirst($data['status']); ?></td>
							<td><?php echo date('d M Y',strtotime($data['created'])); ?></td>
							<td><?php if($data['status']=="pending"){
								?>
								
								<div style='cursor:pointer;color:blue;' data-curr-id="<?php echo $data['id']; ?>" onClick='change_deposit_status("<?php echo $data['id']; ?>")'>Approve</div>
								<?php
								
							} else { echo "Approved"; } ?></td>
						</tr>
                        <?php $count++;} ?>
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'ethDepositSearch')));
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

                    ?>
                    </div>
					</div>
                </div>
            </div>
        </div>
    </section>
</div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
	<form id="model_form" onSubmit="return false;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Eth Withdrawal</h4>
      </div>
      <div class="modal-body">
        <p>
		
		<select name="withdrawal_type" required id="withdrawal_type" class="form-control">
			<option value="">Select Payment Method</option>
			<option value="coinpayment">Coin Payment</option>
			<option value="other">Other</option>
		</select>
		<br/>
		<input type="text" id="model_tx_id" style="display:none;" placeholder="Transaction id" name="model_tx_id" class="form-control" />
		
		<input type="text" required id="model_withdrawal_id" style="display:none;"  name="model_withdrawal_id" class="form-control" />
		<br/>
		<textarea name="comment" required id="comment" placeholder="Comment" class="form-control" ></textarea>
		
		
		</p>
      </div>
      <div class="modal-footer">
		<input type="submit" name="Submit"  value="Submit" class="form-control" />
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
	</form>
  </div>
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
			
			
			$("#withdrawal_type").change(function(){
				var getVal = $(this).val();
				if(getVal == 'other') {
					
					$("#model_tx_id").show();
					$("#model_tx_id").attr("required",true);
					
				}
				else {
					$("#model_tx_id").hide();
					$("#model_tx_id").attr("required",false); 
				}
			});
			
			
			$("#model_form").submit(function(){ 
				var id = $("#model_withdrawal_id").val();
				var withdrawal_type = $("#withdrawal_type").val();
				var model_tx_id = $("#model_tx_id").val();
				var comment = $("#comment").val();
				
				if(withdrawal_type=="coinpayment"){
					var newStatus = "Processing";
				}
				else {
					var newStatus = "Completed";
				}
				
				if(id!='') {
					jQuery.ajax({ 
						url: '<?php echo $this->Url->build(['controller'=>'Reports','action'=>'ethWithdrawalUpdate']); ?>',
						data: {'id':id,
							   'status':"Y",
							   'model_tx_id':model_tx_id,
							   'withdrawal_type':withdrawal_type,
							   'comment':comment,
							   },
						type: 'POST',
						success: function(data) {
							if(data == 1){
								jQuery("#status_id_"+id).html(newStatus).css('color','#000;').attr('onClick','#');
								new PNotify({
									  title: 'Success',
									  text: 'Status changed successfully!',
									  type: 'success',
									  styling: 'bootstrap3',
									  delay:1200
								  });
								
							}
							else {
								new PNotify({
									  title: 'Response',
									  text: data,
									  type: 'error',
									  styling: 'bootstrap3',
									  delay:1200
								  });
							}
							$("#model_withdrawal_id").val('');
							$('#myModal').modal('hide'); 
						}
					});
				}
				else {
					
				}
			});


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
		
		
		
	function change_deposit_status(id){
		
		//$("#model_withdrawal_id").val(id);
		//$('#myModal').modal('show'); 
		
		 var ques= "Do you want change the status to Complete";
		
		bootbox.confirm(ques, function(result) {
			if(result == true){
				jQuery.ajax({ 
					url: '<?php echo $this->Url->build(['controller'=>'Reports','action'=>'ethDepositUpdate']); ?>',
					data: {'id':id,'status':"completed"},
					type: 'POST',
					success: function(data) {
						if(data == 1){
							location.reload();
							/* jQuery("#status_id_"+id).html('Processing').css('color','#000;').attr('onClick','#');
							new PNotify({
								  title: 'Success',
								  text: 'Status changed successfully!',
								  type: 'success',
								  styling: 'bootstrap3',
								  delay:1200
							  }); */
							
						}
						else {
							new PNotify({
								  title: 'Response',
								  text: data,
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


