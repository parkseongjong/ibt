<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Transaction <small>Detail</small> </h1>
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
                <div class="col-lg-12 col-xl-10 center-block ">
                    <h4>Transaction Detail</h4>
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
                               
                                    
                                   <!--
                                    <div class="fr export_border">
                                        <form action="https://bitconnect.co/user/transaction/export_bitconnect_wallet_data" method="post" accept-charset="utf-8" id="export_bitconnect_wallet_data_form" class="">
                                          <div style="display: inline-table;">
                                                <select id="export_year_id" class="export_filter" name="export_year" onchange="if($(this).val().length!=0){$('#bitconnect_wallet_export_error').hide();}">
                                                    <option value="">Year</option>
                                                    <option value="2017" selected="">2017</option>
                                                    <option value="2016">2016</option>
                                                </select>
                                                <select id="export_month_id" class="export_filter" name="export_month">
                                                    <option value="">Month</option>
                                                    <option value="12">12</option>
                                                    <option value="11">11</option>
                                                    <option value="10">10</option>
                                                    <option value="9">09</option>
                                                    <option value="8">08</option>
                                                    <option value="7">07</option>
                                                    <option value="6">06</option>
                                                    <option value="5">05</option>
                                                    <option value="4">04</option>
                                                    <option value="3">03</option>
                                                    <option value="2">02</option>
                                                    <option value="1">01</option>
                                                </select>
                                                <select id="bitconnect_wallet_option_export" class="export_filter" name="export_type">
                                                    <option value="" selected="">Select</option>
                                                    <option value="S">Sent</option>
                                                    <option value="R">Receive</option>
                                                    
                                                </select>
                                                <br>
                                                <span class="text-red dn" id="bitconnect_wallet_export_error">Please select an option.</span> </div>
                                            <button type="submit" name="export_bitconnect_wallet_data" class="btn btn-success btn-xs" style="margin-top: -4px" onclick=""><i class="fa fa-download"></i> Export</button>
                                        </form>
                                    </div>
                               
                                </div>
                                -->
                              <div class="clearfix"></div>
                                <h3 class="w3_inner_tittle two">Transactions</h3>
                                <div id="main_wallet_transaction_div" class="mt10 table-responsive">
                                    
                                       
                                       
									<table id="table-two-axis" class="two-axis table">
										<thead>
										<tr>
											<th>S No.</th>
											<th>Action</th>
											<th>Coins</th>
											<th>From / To</th>
											<th>Date</th>
										</tr>
										</thead>
										<tbody>
										<?php
										$count= 1;
											
										 foreach($listing->toArray() as $k=>$data){
											// pr($data);die;
											if($k%2==0) $class="odd";
											else $class="even";
										?>
										<tr class="<?=$class?>">
											<td> <?=$count?></td>
											<td><?=($data['trans_type']=='S' ? "Sent":"Receive")?></td>
											
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
