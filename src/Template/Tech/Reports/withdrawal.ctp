<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'withdrawal']);  ?>"><?= __('Withdrawals List');?></a> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a></li>
            <li class="active"> <a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'withdrawal']);  ?>"><?= __('Withdrawals List');?> </a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-tables">
                <div class="w3l-table-info agile_info_shadow">
                    <div class="clearfix"></div>
                    <form method="get" id="frm">
                        <div class="form-group">
                            <div id="selectuser" class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('user_name',array('empty'=>__('Please select user'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','id'=>"user_name")); ?>
                            </div>
                            <div id="selectcoin" class="col-md-2 col-sm-2 col-xs-12">
                                <?php  echo $this->Form->input('coin_first_id',array('id'=>'coin_first_id','class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"select","options"=>[''=>__('Please select coin')]+$coinList,'value'=>(!empty($_GET['coin_first_id']) ? $_GET['coin_first_id'] : "")));?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php  echo $this->Form->input('start_date',array('placeholder'=>__('Start Date'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>(!empty($_GET['start_date']) ? $_GET['start_date'] : ""))); ?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <?php  echo $this->Form->input('end_date',array('placeholder'=>__('End Date'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>(!empty($_GET['end_date']) ? $_GET['end_date'] : ""))); ?>
                                <input type="hidden" name="export" id="export" />
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <button type="submit" class="btn btn-success"><?= __('Search');?></button>
                            </div>
					    </div>
                    <!-- Token Button List -->
                        <div class="token-button-list-checkbox-container">
                            <label for=""><?= __('View in scroll form');?></label>
                            <input type="checkbox" id="token-button-list-checkbox" class="token-button-list-checkbox">
                        </div>
                        <div class="token-button-list-container">
                            <?php 
								foreach($coinList as $k=>$data) { ?>
 									<button type="button" onclick="form_submit('coin_first_id',<?=$k;?>)" class="btn btn-token <?php if($this->request->query('coin_first_id') == $k) {echo 'selected';}?>"><?=$data;?></button>
                            <?php } ?>
                        </div>
                    <!-- /Token Button List -->
				    </form>
				   
				    <div class="dropdown m-b-20">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?= __('Export');?>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                          <!--  <li><a href="javascript:void(0)" onclick="export_f('e')">Excel</a></li>-->
                            <li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
                        </ul>
                    </div>
                    <div class="mt10 table-responsive">
                    <table id="table-two-axis" class="two-axis table">
                        <thead style="background: #d3ccea;font-size: 16px;">
                        <tr>
                            <th style="color:#fff"><?= __('#')?></th>
                            <th style="color:#fff"><?= __('ID')?></th>
                            <th style="color:#fff"><?= __('Name')?></th>
                            <th style="color:#fff"><?= __('Phone Number')?></th>
                          	<th style="color:#fff"><?= __('Email');?></th>
							<th style="color:#fff"><?= __('Currency');?></th>
                            <th style="color:#fff"><?= __('Data');?></th>
                            <th style="color:#fff"><?= __('Status');?></th>
							<th style="color:#fff"><?= __('Date & Time');?></th>
						</tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= $serial_num;
						
                        foreach($withdrawals->toArray() as $k=>$data){
							$this->add_system_log(200, $data['user_id'], 1, '출금 목록 조회 (withdrawal)');
							
						if($data['withdrawal_send']=='N' && !empty($data['withdrawal_id'])){
							$usedStatus = "Processing";
							$txId = '';
						}
						else if($data['withdrawal_send']=='Y') {
							$usedStatus = "Completed";
							$txId = !empty($data['withdrawal_id']) ? $data['withdrawal_tx_id'] : '';
						}
						else if($data['withdrawal_send']=='N') {
							$usedStatus = "<div style='cursor:pointer;color:blue;' id='status_id_".$data['id']."' onClick='change_withdrawal_status(".$data['id'].")'>".__('Pending')."</div>";
							$txId = '';
						}
						else {
							$usedStatus = "Nil==>".$data['id'];
							$txId = '';
						}
						
						$transFee = 0.02;
						$withdrawalAmt = abs($data['coin_amount']);
						$withdrawalAmt = $withdrawalAmt-$transFee;
						
						
						
						$backgroundColr = ($data['withdrawal_amount_in_usd']==100 || $data['withdrawal_amount_in_usd']==200) ? 'background-color:red' : "";
                        if($k%2==0) $class="odd";
                        else $class="even";
                        ?>
                        <tr style="<?php echo $backgroundColr; ?>" class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?=$count?></td>
                            <td><?= $data['user_id']; ?></td>
                            <td><a href="javascript:void(0)" onclick="unmasking(this,'N',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('N',$data['user']['name']); ?></a></td>
							<td><a href="javascript:void(0)" onclick="unmasking(this,'P',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('P',$data['user']['phone_number']); ?></a></td>
							<td><a href="javascript:void(0)" onclick="unmasking(this,'E',<?= $data['user_id']; ?>)" class="text-dark"><?= $this->masking('E',$data['user']['email']); ?></a></td>
							<td><?= $data['cryptocoin']['short_name']; ?></td>
							<td>
								<strong><?= __('Amount')?></strong> - <?php echo $withdrawalAmt." ".$data['cryptocoin']['short_name']; ?>
								<br/>
								<strong><?= __('Transaction ID')?></strong> - <?php echo $txId; ?>
								<br/>
								<?php if(false && $data['cryptocoin']['id']==3) { ?>
									<br/>
									<strong><?= __('Amount in USD')?></strong> - <?php echo abs($data['withdrawal_amount_in_usd']); ?>
									<br/>
									<strong><?= __('Coin Price')?></strong> - <?php echo $data['withdrawal_coin_price']; ?>
								<?php } ?>
								
								<?php if($data['cryptocoin']['type']=="flat") { ?>
									
									<strong><?= __('User Bank Account Number')?></strong> - <?php echo $data['flat_account_no']; ?>
									<br/>
									<strong><?= __('User Bank Name')?></strong> - <?php echo $data['flat_bank_name']; ?>
									<br/>
									<strong><?= __('Username')?></strong> - <?php echo $data['flat_account_owner']; ?>
									<br/>
									<strong><?= __('User Bank Address')?></strong> - <?php echo $data['flat_bank_address']; ?>
								
								<?php } else { ?>
							
								<strong><?= __('Wallet Address')?></strong> - <?php echo $data['wallet_address']; ?>
								<?php } ?>
							</td>
							<td><?php echo __($usedStatus); ?></td>
							<td><?= $data['created']->format('Y-m-d H:i:s'); ?></td>
						</tr>
                        <?php $count++;} ?>
                        <?php  if(count($withdrawals->toArray()) < 1) {
                            echo "<tr class='even'><td colspan = '10'>".__('No record found')."</td></tr>";
                        } ?>
                        </tbody>
                    </table>
                    <?php
                    $searchArr = [];
                    foreach($this->request->query as $singleKey=>$singleVal){
                        $searchArr[$singleKey] = $singleVal;
                    }
                    $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'withdrawal')+$searchArr));
                        echo "<div class='pagination' style = 'float:right'>";

                        // the 'first' page button
                        $paginator = $this->Paginator;
                        echo $paginator->first(__('First'));

                        // 'prev' page button,
                        // we can check using the paginator hasPrev() method if there's a previous page
                        // save with the 'next' page button
                        if($paginator->hasPrev()){
                            //echo $paginator->prev(__('Prev'));
                        }

                        // the 'number' page buttons
                        echo $paginator->numbers(array('modulus' => 9));

                        // for the 'next' button
                        if($paginator->hasNext()){
                            //echo $paginator->next(__('Next'));
                        }

                        // the 'last' page button
                        echo $paginator->last(__('Last'));

                        echo "</div>";

                    ?>
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
        <h4 class="modal-title"><?= __('Withdrawal');?></h4>
      </div>
      <div class="modal-body">
        <p>
		
		<select name="withdrawal_type" required id="withdrawal_type" class="form-control">
			<option value=""><?= __('Select a payment method');?></option>
			<option value="coinpayment"><?= __('Coin Payment');?></option>
			<option value="other"><?= __('Other');?></option>
		</select>
		<br/>
		<input type="text" id="model_tx_id" style="display:none;" placeholder="Transaction id" name="model_tx_id" class="form-control" />
		
		<br/>
		<input type="text" id="model_withdrawal_date" readonly style="display:none;" placeholder="Date" name="withdrawal_date" class="form-control" />
		
		<input type="text" required id="model_withdrawal_id" style="display:none;"  name="model_withdrawal_id" class="form-control" />
		<br/>
		<textarea name="comment" required id="comment" placeholder="Comment" class="form-control" ></textarea>
		
		
		</p>
      </div>
      <div class="modal-footer">
		<input type="submit" name="Submit"  value="Submit" class="form-control" />
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Cancel');?></button>
      </div>
    </div>
	</form>
  </div>
</div>
<input type="hidden" id="user_name_search" name="" value="<?=$this->request->query('user_name');?>">
<script>
    $(document).ready(function() {
        datepicker_set('start-date');
		datepicker_set('end-date');
		user_search_select2('user_name'); /* user name search */
		username_ajax_check('user_name'); // 검색 후 selected 처리
        $("#coin_first_id").select2();
        tokenButtonList();
			
		$("#withdrawal_type").change(function(){
			var getVal = $(this).val();
			if(getVal == 'other') {
				$("#model_tx_id").show();
				$("#model_tx_id").attr("required",true);
				$("#model_withdrawal_date").show();
				$("#model_withdrawal_date").attr("required",true);
			} else {
				$("#model_tx_id").hide();
				$("#model_tx_id").attr("required",false);
				$("#model_withdrawal_date").hide();
				$("#model_withdrawal_date").attr("required",false); 
			}
		});
			
			
		$("#model_form").submit(function(){ 
			var id = $("#model_withdrawal_id").val();
			var withdrawal_type = $("#withdrawal_type").val();
			var model_tx_id = $("#model_tx_id").val();
			var model_withdrawal_date = $("#model_withdrawal_date").val();
			var comment = $("#comment").val();
			var newStatus = "Completed";
			if(withdrawal_type=="coinpayment"){
				newStatus = "Processing";
			}
			
			
			if(id!='') {
				jQuery.ajax({ 
					url: '<?php echo $this->Url->build(['controller'=>'Reports','action'=>'ethWithdrawalUpdate']); ?>',
					beforeSend: function(xhr){
						xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
					},
					data: {'id':id,
						   'status':"Y",
						   'model_tx_id':model_tx_id,
						   'model_withdrawal_date':model_withdrawal_date,
						   'withdrawal_type':withdrawal_type,
						   'comment':comment,
						   },
					type: 'POST',
					success: function(data) {
						if(data == 1){
							jQuery("#status_id_"+id).html(newStatus).css('color','#000;').attr('onClick','#');
							new PNotify({
								  title: '<?= __('Success!');?>',
								  text: '<?= __('Status changed successfully!');?>',
								  type: 'success',
								  styling: 'bootstrap3',
								  delay:1200
							  });
							
						} else {
							new PNotify({
								  title: '<?= __('Response');?>',
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
		});


    });

		
		// jQuery('.table-responsive').on('click','.pagination li a',function(event){
		// 	event.preventDefault() ;
		// 	var keyy = $('form').serialize();
		// 	var urli = jQuery(this).attr('href');
		// 	jQuery.ajax({
		// 				url: urli,
		// 				data: {key:keyy},
		// 				type: 'POST',
		// 				success: function(data) {
		// 					if(data){
		//
		// 						jQuery('.table-responsive').html(data);
		//
		// 					}
		// 				}
		// 	});
		//
		// });
        
		
	function change_withdrawal_status(id){
		
		$("#model_withdrawal_id").val(id);
		$('#myModal').modal('show'); 
		
		/* var ques= "Do you want change the status to Processing";
		
		bootbox.confirm(ques, function(result) {
			if(result == true){
				jQuery.ajax({ 
					url: '<?php echo $this->Url->build(['controller'=>'Reports','action'=>'ethWithdrawalUpdate']); ?>',
					data: {'id':id,'status':"Y"},
					type: 'POST',
					success: function(data) {
						if(data == 1){
							jQuery("#status_id_"+id).html('Processing').css('color','#000;').attr('onClick','#');
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
					
					}
				});
			}
		}); */
		
	}	
		
		
</script>


