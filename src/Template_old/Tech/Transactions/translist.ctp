<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>BTC/ETH Transaction <small><?php echo $coinNameStatic; ?></small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Transaction</li>
        </ol>
    </section>
<br/>
    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="tray tray-center" style="height: 726px;">
            <style>
                .export_border{
                    border: 1px dotted #b5b5b5;
                    padding: 1px;
                }
            </style>
            <div class="">
                <div class="col-lg-12 col-xl-12 center-block ">
                   
                    <div class="tab-block mb25" style="position: relative">
                        <div class="tab-content pn-h-small">
                            <div id="main_wallet_panel" class="tab-pane active">
                                <div style="display: flow-root;">
                                    <form style="padding:10px" method="post" class="form-horizontal form-label-left input_mask">

					 
                    <div class="form-group">
						 <input type="hidden" name="type" value="<?//=$type?>"/>
						
					 <!--	
						<div class="col-md-4 col-sm-3 col-xs-12">
							<?php  //echo $this->Form->input('username',array('placeholder'=>'Username','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>
						</div>
						
						
						<div class="col-md-4 col-sm-3 col-xs-12">
							<?php  //echo $this->Form->input('start_date',array('placeholder'=>'Start date  ','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>
                       
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12">
							 <?php  //echo $this->Form->input('end_date',array('placeholder'=>'End Date','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
						</div>
						
						<div class="col-md-3 col-sm-3 col-xs-12">
								<?php  //echo $this->Form->input('coin_type',array('empty'=>'Coin Type','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(1=>'BTC',2=>'ETH'))); ?>
						</div> 
						
						<div class="col-md-3 col-sm-3 col-xs-12">
								<?php  //echo $this->Form->input('status',array('empty'=>'Status','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array('pending'=>'Pending','completed'=>'Completed'))); ?>
						</div> 
						
						-->
						<div class="col-md-3 col-sm-3 col-xs-12">
								<?php  echo $this->Form->input('pagination',array('empty'=>'No of record','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100))); ?>
						</div> 
						
						
						<div class="col-md-1 col-sm-1 col-xs-12">
							<button type="submit" class="btn btn-success">Filter</button>
						</div>
						
                        
                     </div>
                   
                  
				</form>
		
                                    
                                    <div class="clearfix"></div>
                                <h3 class="w3_inner_tittle two"><?php  if(!empty($listing->toArray())){ echo $listing->toArray()[0]['user']['username']; } ?> <sub>Transactions</sub></h3>
								
								Withdrawable ETH  = <?php echo ($ethtotal<0) ? 0 : number_format((float)$ethtotal,8);  ?>
                                <div id="main_wallet_transaction_div" class="mt10 table-responsive">
                                    
                                       
                                       
									<table id="table-two-axis" class="two-axis table">
										<thead>
										<tr>
											<th>S No.</th>
											<th>Amount(ETH)</th>
											<th>Type</th>
											<th>Exchange Token</th>
											<th>Status</th>
											<th>Remark</th>
											<th>Date</th>
										</tr>
										</thead>
										<tbody>
										<?php
										$count= 1;
											$coinArr = [2=>'ETH',3=>'RAM',4=>'ADMC'];
										 foreach($listing->toArray() as $k=>$data){
											
											if($k%2==0) $class="odd";
											else $class="even";
											
											$showTxType = ($data['tx_type']=="purchase") ? "deposit" : $data['tx_type'];
											$showStatus = ($data['tx_type']=='withdrawal' && $data['withdrawal_send']=='N') ? "pending" :  $data['status'];
											
											
										?>
										<tr class="<?=$class?>">
											<td> <?=$count?></td>
											<td><?php echo number_format((float)$data['coin_amount'],8)?> </td>
											<td> <?php echo ucfirst(str_replace("_"," ",$showTxType)); ?></td>
											<td> 
											<?php if(!empty($data[$data['tx_type']])  && $data['remark']!='adminFees') {
													if($data['tx_type']=='sell_exchange' && $data['coin_amount']>0){  
														echo $data[$data['tx_type']]['total_sell_spend_amount']." ".$coinArr[$data[$data['tx_type']]['sell_spend_coin_id']];
													
													} 
													else if($data['tx_type']=='sell_exchange' && $data['coin_amount']<0){  
														echo $data[$data['tx_type']]['total_sell_get_amount']." ".$coinArr[$data[$data['tx_type']]['sell_get_coin_id']];
													
													} 
													else if($data['tx_type']=='buy_exchange' && $data['coin_amount']>0){ 
													
														echo $data[$data['tx_type']]['total_buy_spend_amount']." ".$coinArr[$data[$data['tx_type']]['buy_spend_coin_id']];
													
													} 
													else if($data['tx_type']=='buy_exchange' && $data['coin_amount']<0){ 
													
														echo $data[$data['tx_type']]['total_buy_get_amount']." ".$coinArr[$data[$data['tx_type']]['buy_get_coin_id']];
													
													} 
												}
												?>
											</td>
											<td><?php echo ucfirst($showStatus);?> </td>
											<td><?php echo ucfirst(str_replace("_"," ",$data['remark']));?> </td>
											<td><?=$data['created']->format('d M Y H:i:s');?> </td>
										</tr>
										<?php $count++; } ?>
										<?php  if(count($listing->toArray()) < 1) {
											echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
									   } ?>	
										</tbody>
									</table>
									   <?php $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'translist_search',$userId)));
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
		
  </script>
