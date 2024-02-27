<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Usd Withdrawal <small>Listing</small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Usd Withdrawal</li>
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
                            <?php  echo $this->Form->input('tx_id',array('placeholder'=>'Tx Id','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
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
       
                    <h3 class="w3_inner_tittle two">Usd withdrawal list</h3>
                   <div class="w3l-table-info agile_info_shadow">
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
						</tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= 1; 
                        foreach($withdrawals->toArray() as $k=>$data){
						
						$usedStatus = ($data['withdrawal_send']=='N') ? "<div style='cursor:pointer;color:blue;' id='status_id_".$data['id']."' onClick='change_withdrawal_status(".$data['id'].")'>Not Used</div>" : "<div style='cursor:pointer;color:blue;' id='status_id_".$data['id']."' onClick='change_withdrawal_status_used(".$data['id'].")'>Used</div>" ;
						
                        if($k%2==0) $class="odd";
                        else $class="even";
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?=$count?></td>
                            <td><?php echo $data['user']['username']; ?></td>
							<td><?php echo $data['user']['email']; ?></td>
							<td>
								<strong>Tx Id</strong> - <?php echo $data['withdrawal_tx_id']; ?>
								<br/>
								<strong>Amount</strong> - <?php echo abs($data['coin_amount'])." ".$data['cryptocoin']['short_name']; ?>
								
								<?php if($data['cryptocoin']['id']==3) { ?>
									<br/>
									<strong>Amount In Usd</strong> - <?php echo abs($data['withdrawal_amount_in_usd']); ?>
									<br/>
									<strong>Coin Price</strong> - <?php echo $data['withdrawal_coin_price']; ?>
								<?php } ?>
								<br/>
								<strong>Wallet Address</strong> - <?php echo $data['wallet_address']; ?>
							</td>
							
							<td><?php echo $usedStatus; ?></td>
							<td><?php echo date('d M Y',strtotime($data['created'])); ?></td>
						</tr>
                        <?php $count++;} ?>
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'usdWithdrawalSearch')));
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
		
		
		
	function change_withdrawal_status(id){
	
		var ques= "Do you want change the status to Used";
		
		bootbox.confirm(ques, function(result) {
			if(result == true){
				jQuery.ajax({ 
					url: '<?php echo $this->Url->build(['controller'=>'Reports','action'=>'usdWithdrawalUpdate']); ?>',
					data: {'id':id,'status':"Y"},
					type: 'POST',
					success: function(data) {
						if(data == 1){
							jQuery("#status_id_"+id).html('Used').css('color','#000;').attr('onClick','#');
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
		
		
	function change_withdrawal_status_used(id){
	
		var ques= "Do you want change the status to Not Used";
		
		bootbox.confirm(ques, function(result) {
			if(result == true){
				jQuery.ajax({ 
					url: '<?php echo $this->Url->build(['controller'=>'Reports','action'=>'usdWithdrawalUpdateNotUsed']); ?>',
					data: {'id':id,'status':"N"},
					type: 'POST',
					success: function(data) {
						if(data == 1){
							jQuery("#status_id_"+id).html('Not Used').css('color','#000;').attr('onClick','#');
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


