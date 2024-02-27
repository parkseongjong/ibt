<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?= __('Buy List'); ?> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home'); ?></a></li>
            <li class="active"><?= __('Buy List'); ?></li>
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
                                    <form style="padding:10px" method="get" class="form-horizontal form-label-left input_mask">

					 
                     <div class="form-group">
						 <input type="hidden" name="type" value="<?=$type?>"/>
						
						
						<div class="col-md-4 col-sm-3 col-xs-12">
							<?php  echo $this->Form->input('username',array('placeholder'=>__('Username'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>(!empty($_GET['username']) ? $_GET['username'] : "" ))); ?>
						</div>
						
						<div class="col-md-4 col-sm-3 col-xs-12">
							<?php  echo $this->Form->input('email',array('placeholder'=>__('Email'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'email','value'=>(!empty($_GET['left']) ? $_GET['left'] : ""))); ?>
						</div>
						
						
						
						<div class="col-md-4 col-sm-3 col-xs-12">
							<?php  echo $this->Form->input('start_date',array('placeholder'=>__('Start Date'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','value'=>(!empty($_GET['start_date']) ? $_GET['start_date'] : ""))); ?>
                       
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12">
							 <?php  echo $this->Form->input('end_date',array('placeholder'=>__('End Date'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text','value'=>(!empty($_GET['end_date']) ? $_GET['end_date'] : ""))); ?>
						</div>
						
						<div class="col-md-3 col-sm-3 col-xs-12">
								<?php  echo $this->Form->input('spend_coin_id',array('empty'=>__('Coin Spent'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>$coinList,'value'=>(!empty($_GET['spend_coin_id']) ? $_GET['spend_coin_id'] : ""))); ?>
						</div> 
						<div class="col-md-3 col-sm-3 col-xs-12">
								<?php  echo $this->Form->input('get_coin_id',array('empty'=>__('Coin Received'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>$coinList,'value'=>(!empty($_GET['get_coin_id']) ? $_GET['get_coin_id'] : ""))); ?>
						</div> 
						
						<div class="col-md-3 col-sm-3 col-xs-12">
								<?php  echo $this->Form->input('status',array('empty'=>__('Status'),'class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array('pending'=>'Pending','processing'=>'Processing','completed'=>'Completed'),'value'=>(!empty($_GET['status']) ? $_GET['status'] : ""))); ?>
						</div> 
						
						
						<div class="col-md-3 col-sm-3 col-xs-12">
								<?php  echo $this->Form->input('pagination',array('empty'=>'No of records','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100),'value'=>(!empty($_GET['pagination']) ? $_GET['pagination'] : ""))); ?>
								<input type="hidden" name="export" id="export" />
						</div> 
						
						
						<div class="col-md-1 col-sm-1 col-xs-12">
							<button type="submit" class="btn btn-success"><?= __('Search'); ?></button>
						</div>
						
                        
                     </div>

                                        <div class="col-md-4 col-sm-3 col-xs-12">
                                            <?php  echo $this->Form->input('total_amount',array('placeholder'=>__('Total Amount'),'class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text','readonly'=>'readonly','value'=>(!empty($totalBuyAmount) ? $totalBuyAmount : "" ))); ?>
                                        </div>
				</form>
		
                                    
                                    <div class="clearfix"></div>
                                <h3 class="w3_inner_tittle two"><?= __('Buy List'); ?></h3>
								<div class="dropdown">
									<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?= __('Export'); ?>
										<span class="caret"></span></button>
									<ul class="dropdown-menu">
										<!--<li><a href="javascript:void(0)" onclick="export_f('e')">Excel</a></li>-->
										<li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
									</ul>
								</div>
                                <div id="main_wallet_transaction_div" class="mt10 table-responsive">
                                    
                                       
                                       
									<table border=1 id="table-two-axis" class="two-axis table">
										<thead>
										<tr>
											<th>#</th>
											<th><?= __('Username'); ?></th>
											<th><?= __('Total Amount Spent'); ?></th>
											<th><?= __('Amount Spent'); ?></th>
											<th><?= __('Coins Spent'); ?></th>
											<th><?= __('Total Amount Received'); ?></th>
											<th><?= __('Amount Received'); ?></th>
											<th><?= __('Coins Received'); ?></th>
											<th><?= __('Rate'); ?></th>
											<th><?= __('Admin Fees'); ?></th>
											<th><?= __('Status'); ?></th>
											<th><?= __('Date & Time'); ?></th>
										</tr>
										</thead>
										<tbody>
										<?php
										$count= $serial_num;
											
										 foreach($listing->toArray() as $k=>$data){
											
											if($k%2==0) $class="odd";
											else $class="even";
										?>
										<tr class="<?=$class?>">
											<td> <?=$count?></td>
											<td> <?php echo $data['user']['username']; ?></td>
											<td><?php echo number_format((float)$data['total_buy_spend_amount'],4)?> </td>
											<td><?php echo number_format((float)$data['buy_spend_amount'],4)?> </td>
											<td><?php echo $data['spendcryptocoin']['short_name']?> </td>
											<td><?php echo number_format((float)$data['total_buy_get_amount'],4)?> </td>
											<td><?php echo number_format((float)$data['buy_get_amount'],4)?> </td>
											<td><?php echo $data['getcryptocoin']['short_name']?> </td>
											<td> <?php echo number_format((float)$data['per_price'],4); ?></td>
											<td> <?php echo number_format((float)$data['buy_fees'],4); ?></td>
											<td><?php echo __(ucfirst($data['status']));?> </td>
											<td><?=$data['created_at']->format('d M Y H:i:s');?> </td>
										</tr>
										<?php $count++; } ?>
										<?php  if(count($listing->toArray()) < 1) {
											echo "<tr class='even'><td colspan = '10'>".__('No record found')."</td></tr>";
									   } ?>	
										</tbody>
									</table>
									   <?php


									$searchArr = [];
									foreach($this->request->query as $singleKey=>$singleVal){
										$searchArr[$singleKey] = $singleVal;
									}
									

									   $this->Paginator->options(array('url' => array('controller' => 'Exchange', 'action' => 'buyList')+$searchArr));
										echo "<div class='pagination' style = 'float:right'>";
					 
										// the 'first' page button
										$paginator = $this->Paginator;
										echo $paginator->first(__('First'));

										// 'prev' page button, 
										// we can check using the paginator hasPrev() method if there's a previous page
										// save with the 'next' page button
										if($paginator->hasPrev()){
										echo $paginator->prev(__('Prev'));
										}

										// the 'number' page buttons
										echo $paginator->numbers(array('modulus' => 2));

										// for the 'next' button
										if($paginator->hasNext()){
										echo $paginator->next(__('Next'));
										}

										// the 'last' page button
										echo $paginator->last(__('Last'));

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
 /*  jQuery('.table-responsive').on('click','.pagination li a',function(event){
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
			
		}); */
		
		function export_f(v) {
            $('#export').val(v);
            $("form").submit();
            $('#export').val('');
        }
		
  </script>
