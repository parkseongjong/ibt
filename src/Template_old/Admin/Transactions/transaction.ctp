<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?php echo ucfirst($type); ?> Transaction <small><?php echo $coinNameStatic; ?></small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Transaction</li>
        </ol>
    </section>

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
						
						
						<div class="col-md-2 col-sm-2 col-xs-12">
							<?php  echo $this->Form->input('username',array('placeholder'=>'Username','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>
						</div>
						
						
						<div class="col-md-2 col-sm-2 col-xs-12">
							<?php  echo $this->Form->input('start_date',array('placeholder'=>'Start date  ','class' => 'form-control col-md-7 col-xs-12 has-feedback-left','label' =>false,'type'=>'text')); ?>
                       
						</div>

						<div class="col-md-2 col-sm-2 col-xs-12">
							 <?php  echo $this->Form->input('end_date',array('placeholder'=>'End Date','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'text')); ?>
						</div>
						
						<div class="col-md-2 col-sm-2 col-xs-12">
								<?php  echo $this->Form->input('wallet_address',array('placeholder'=>'Wallet Address','class' => 'form-control col-md-7 col-xs-12','label' =>false)); ?>
						</div> 
						
						
						<div class="col-md-2 col-sm-2 col-xs-12">
								<?php  echo $this->Form->input('pagination',array('empty'=>'No of record','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100))); ?>
						</div> 
						
						
						<div class="col-md-1 col-sm-1 col-xs-12">
							<button type="submit" class="btn btn-success">Filter</button>
						</div>
						
                        
                     </div>
                   
                  
				</form>
		
                                    
                                    <div class="clearfix"></div>
                                <h3 class="w3_inner_tittle two"><?php echo ucfirst($type); ?> Transactions</h3>
                                <div id="main_wallet_transaction_div" class="mt10 table-responsive">
                                    
                                       
                                       
									<table id="table-two-axis" class="two-axis table">
										<thead>
										<tr>
											<th>S No.</th>
											<th>Username</th>
											<th>Wallet Address</th>
											<th>Transaction Type</th>
											<th>Remark</th>
											<th>Btc Received</th>
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
											<td><?php echo $data['wallet_address'];?> </td>
											<td><?php echo $data['trans_type'];?> </td>
											<td><?php echo $data['coin_type'];?> </td>
											<td><?php echo number_format((float)abs($data['btc_coins']),8)?> </td>
											<td><?=$data['created_at']->format('d M Y H:i:s');?> </td>
										</tr>
										<?php $count++; } ?>
										<?php  if(count($listing->toArray()) < 1) {
											echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
									   } ?>	
										</tbody>
									</table>
									   <?php $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'transaction_search')));
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
