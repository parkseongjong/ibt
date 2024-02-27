<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Receive <small><?=$type?></small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Receive <?=$type?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">
		 <div class="tab-block mb25" style="position: relative">
			<div class="tab-content pn-h-small">
				<div id="main_wallet_panel" class="tab-pane active">
					<div style="display: flow-root;">
						<div class="fr export_border" style="float:left !important">
							<form method="post">
							  <div style="display: inline-table;">
								  
									<select id="filter_record" class="export_filter" name="filter_row">
										<option value="">Records</option>
										<option value="10">10</option>
										<option value="25">25</option>
										<option value="50">50</option>
										<option value="100">100</option>
										<option value="250">250</option>
										<option value="500">500</option>
										<option value="1000">1000</option>
									</select>
									<select id="filter_period" class="export_filter" name="filter_month">
										<option value="">Time frame</option>
										<option value="today">Today</option>
										<option value="yesterday">Yesterday</option>
										<option value="7_day">Last 7 days</option>
										<option value="this_month">This month</option>
										<option value="last_month">Last month</option>
									  
									</select>
									<select id="bitconnect_wallet_option_export" class="filter_type" name="filter_type">
										<option value="">Type</option>
										<option value="S">Sent</option>
										<option value="R">Receive</option>
										
									</select>
									<input type="hidden" name="type" value="<?=$type?>"/>
									<br>
									<span class="text-red dn" id="bitconnect_wallet_export_error">Please select an option.</span> </div>
								<button type="submit" name="export_bitconnect_wallet_data" class="btn btn-success btn-xs" style="margin-top: -4px" onclick=""><i class="fa fa-filter"></i> Filter</button>
							</form>
						</div>
				   
						
						<div class="clearfix"></div>
					<h3 class="w3_inner_tittle two">Transactions</h3>
					<div id="main_wallet_transaction_div" class="mt10 table-responsive">
						
						   
						<table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr>
                            <th>S No.</th>
                            <th>Type</th>
                            <th>Coins(<?=$type?>)</th>
                            <th>From</th>
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
                            <td><?=($data['trans_type']=='R' ? "Receive":"Referral amount")?></td>
                            <td><?=$data['amount']?> </td>
                            <td><?=$data['from_user']['name']?> </td>
                            <td><?=$data['created']->format('d M Y');?> </td>
                        </tr>
						<?php $count++; } ?>
						<?php  if(count($listing->toArray()) < 1) {
							echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
					   } ?>	
                        </tbody>
                    </table>
                       <?php $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'receive_search',$type)));
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
