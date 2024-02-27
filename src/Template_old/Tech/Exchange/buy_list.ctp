<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Buy List </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Buy List</li>
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
						 <input type="hidden" name="type" value="<?=$type?>"/>
						
						
						<div class="col-md-4 col-sm-3 col-xs-12">
							<?php  echo $this->Form->input('username',array('placeholder'=>'Username','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>
						</div>
						
						<div class="col-md-4 col-sm-3 col-xs-12">
							<?php  echo $this->Form->input('email',array('placeholder'=>'Email','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'email')); ?>
						</div>
						
						
						
						<div class="col-md-4 col-sm-3 col-xs-12">
							<?php  echo $this->Form->input('start_date',array('placeholder'=>'Start date  ','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>
                       
						</div>

						<div class="col-md-3 col-sm-3 col-xs-12">
							 <?php  echo $this->Form->input('end_date',array('placeholder'=>'End Date','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
						</div>
						
						<div class="col-md-3 col-sm-3 col-xs-12">
								<?php  echo $this->Form->input('spend_coin_id',array('empty'=>'Spend Coin','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>$coinList)); ?>
						</div> 
						<div class="col-md-3 col-sm-3 col-xs-12">
								<?php  echo $this->Form->input('get_coin_id',array('empty'=>'Get Coin','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>$coinList)); ?>
						</div> 
						
						<div class="col-md-3 col-sm-3 col-xs-12">
								<?php  echo $this->Form->input('status',array('empty'=>'Status','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array('pending'=>'Pending','processing'=>'Processing','completed'=>'Completed'))); ?>
						</div> 
						
						
						<div class="col-md-3 col-sm-3 col-xs-12">
								<?php  echo $this->Form->input('pagination',array('empty'=>'No of record','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100))); ?>
								<input type="hidden" name="export" id="export" />
						</div> 
						
						
						<div class="col-md-1 col-sm-1 col-xs-12">
							<button type="submit" class="btn btn-success">Filter</button>
						</div>
						
                        
                     </div>
                   
                  
				</form>
		
                                    
                                    <div class="clearfix"></div>
                                <h3 class="w3_inner_tittle two">Buy List</h3>
								<div class="dropdown">
									<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Export
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
											<th>S No.</th>
											<th>Username</th>
											<th>Total Spend Amount</th>
											<th>Spend Amount</th>
											<th>Spend Coin</th>
											<th>Total Receive Amount</th>
											<th>Receive Amount</th>
											<th>Receive Coin</th>
											<th>Rate</th>
											<th>Admin Fee</th>
											<th>Status</th>
											<th>Date</th>
										</tr>
										</thead>
										<tbody>
										<?php
										$count= 1;
											
										 foreach($listing->toArray() as $k=>$data){
											
											if($k%2==0) $class="odd";
											else $class="even";
										?>
										<tr class="<?=$class?>">
											<td> <?=$count?></td>
											<td> <?php echo $data['user']['username']; ?></td>
											<td><?php echo number_format((float)$data['total_buy_spend_amount'],8)?> </td>
											<td><?php echo number_format((float)$data['buy_spend_amount'],8)?> </td>
											<td><?php echo $data['spendcryptocoin']['short_name']?> </td>
											<td><?php echo number_format((float)$data['total_buy_get_amount'],8)?> </td>
											<td><?php echo number_format((float)$data['buy_get_amount'],8)?> </td>
											<td><?php echo $data['getcryptocoin']['short_name']?> </td>
											<td> <?php echo number_format((float)$data['per_price'],8); ?></td>
											<td> <?php echo number_format((float)$data['buy_fees'],8); ?></td>
											<td><?php echo ucfirst($data['status']);?> </td>
											<td><?=$data['created_at']->format('d M Y H:i:s');?> </td>
										</tr>
										<?php $count++; } ?>
										<?php  if(count($listing->toArray()) < 1) {
											echo "<tr class='even'><td colspan = '10'>No record found</td></tr>";
									   } ?>	
										</tbody>
									</table>
									   <?php $this->Paginator->options(array('url' => array('controller' => 'Exchange', 'action' => 'buyListSearch')));
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
		
		function export_f(v) {
            $('#export').val(v);
            $("form").submit();
            $('#export').val('');
        }
		
  </script>
