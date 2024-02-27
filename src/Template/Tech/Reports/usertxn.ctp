<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Users <small>Listing</small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Users</li>
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
							
							
						 <div class="col-md-2 col-sm-2 col-xs-12">
                            <?php  echo $this->Form->input('pagination',array('empty'=>'No of record','class' => 'form-control col-md-7 col-xs-12','label' =>false,'type'=>'select','options'=>array(10=>10,25=>25,50=>50,100=>100,200=>200,500=>500))); ?>
                           <input type="hidden" name="export" id="export" />
                        </div>
						
                         <div class="col-md-1 col-sm-1 col-xs-12">
                          <button type="submit" class="btn btn-success">Filter</button>
                        </div>	
						 
                     </div>
                    
                  
				</form>
		<div class="clearfix"></div>
       
                    <h3 class="w3_inner_tittle two">Users</h3>
					
					<?php foreach($allTotal as $singleTotal) { ?>
					<div class="col-lg-4 col-xs-6">

						<div class="small-box bg-aqua">
						<div class="inner">
						<h3 id="eth_total_cnt"><?php echo $singleTotal['totalsum']; ?></h3>
						<p>Total <?php echo $singleTotal['cryptocoin']['short_name']; ?></p>
						</div>
						<div class="icon"> <i class="fa fa-calculator" style="color:#fff;font-size:30px"></i> </div>
						<!--<a href="/tech/reports/users" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>--> </div>
					</div>
					<?php } ?>
					<br/>
					
					<div class="clearfix"></div>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Export
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <!--<li><a href="javascript:void(0)" onclick="export_f('e')">Excel</a></li>-->
                            <li><a href="javascript:void(0)" onclick="export_f('c')">CSV</a></li>
                        </ul>
                    </div>
					<div class="clearfix"></div>
					<?=$this->Flash->render();?>
                    <div class="table-responsive">
                    <table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr> 
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Eth</th>
                            <th>Eth Reserve</th>
                            <th>Ram</th>
                            <th>Ram Reserve</th>
                            <th>Admc</th>
                            <th>Admc Reserve</th>
							<th>Usd</th>
                            <th>Usd Reserve</th> 
                          	                          
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count= 1;
						

						
						
                        foreach($users->toArray() as $k=>$data){
						
						
                        if($k%2==0) $class="odd";
                        else $class="even";
                        
						
						$ethReserve = 0; 	
						$ramReserve = 0; 	
						$admcReserve = 0; 
						$usdReserve = 0;
						
						
						$ethTotal = 0;
						if(!empty($data['ethtransactions'])){
							foreach($data['ethtransactions'] as $ethTrans){
								if(!empty($ethTrans['coin_amount'])){
									$ethTotal = $ethTotal + $ethTrans['coin_amount'];
									/* if($ethTrans['remark']=='reserve for exchange'){
										$ethReserve = $ethReserve + $ethTrans['coin_amount'];
									} */
								}
							}
						}
						
						if(!empty($data['eth_reserve'])){
							foreach($data['eth_reserve'] as $ethSpend){
								if(!empty($ethSpend['total_buy_spend_amount'])){
									//$ethReserve = $ethReserve + $ethSpend['total_buy_spend_amount'];
									$ethReserve = $ethReserve + ($ethSpend['buy_get_amount']*$ethSpend['per_price']);
								}
							}
						}
						
						$ramTotal = 0;
						if(!empty($data['ramtransactions'])){
							foreach($data['ramtransactions'] as $ramTrans){
								if(!empty($ramTrans['coin_amount'])){
									$ramTotal = $ramTotal + $ramTrans['coin_amount'];
									/* if($ramTrans['remark']=='reserve for exchange'){
										$ramReserve = $ramReserve + $ramTrans['coin_amount'];
									} */
								}
							}
						}
						
						$usdTotal = 0;
						if(!empty($data['usdtransactions'])){
							foreach($data['usdtransactions'] as $usdTrans){
								if(!empty($usdTrans['coin_amount'])){
									$usdTotal = $usdTotal + $usdTrans['coin_amount'];
									/* if($ramTrans['remark']=='reserve for exchange'){
										$ramReserve = $ramReserve + $ramTrans['coin_amount'];
									} */
								}
							}
						}
						
						
						if(!empty($data['ram_reserve'])){
							foreach($data['ram_reserve'] as $ramSpend){
								if(!empty($ramSpend['total_sell_spend_amount'])){
									$ramReserve = $ramReserve + $ramSpend['total_sell_spend_amount'];
								}
							}
						}	
						
						$admcTotal = 0;
						if(!empty($data['admctransactions'])){
							foreach($data['admctransactions'] as $admcTrans){
								if(!empty($admcTrans['coin_amount'])){
									$admcTotal = $admcTotal + $admcTrans['coin_amount'];
									/* if($admcTrans['remark']=='reserve for exchange'){
										$admcReserve = $admcReserve + $admcTrans['coin_amount'];
									} */
								}
							}
						}
						
						
						if(!empty($data['admc_reserve'])){
							foreach($data['admc_reserve'] as $admcSpend){
								if(!empty($admcSpend['total_sell_spend_amount'])){
									$admcReserve = $admcReserve + $admcSpend['total_sell_spend_amount'];
								}
							}
						}
						
						if(!empty($data['usd_reserve'])){
							foreach($data['usd_reserve'] as $usdSpend){
								if(!empty($usdSpend['total_sell_spend_amount'])){
									$admcReserve = $admcReserve + $usdSpend['total_sell_spend_amount'];
								}
							}
						}
						
						
						
                        ?>
                        <tr class="<?=$class?>" id="user_row_<?= $data['id']; ?>">
                            <td><?=$count?></td>
                            <td><?php echo $data['username']; ?></td>
                            <td><?php echo $data['email']; ?></td>
                            <td><?php echo ($ethTotal<0) ? 0 : number_format((float)$ethTotal,8); ?></td>
							<td><?php echo number_format((float)abs($ethReserve),8); ?></td>
							<td><?php echo ($ramTotal<0) ? 0 : number_format((float)$ramTotal,8); ?></td>		
							<td><?php echo number_format((float)abs($ramReserve),8); ?></td>		
							<td><?php echo ($admcTotal<0) ? 0 : number_format((float)$admcTotal,8); ?></td>		
							<td><?php echo number_format((float)abs($admcReserve),8); ?></td>
							<td><?php echo ($usdTotal<0) ? 0 : number_format((float)$usdTotal,8); ?></td>		
							<td><?php echo number_format((float)abs($usdReserve),8); ?></td>							
                            
							
							
                        </tr>
                        <?php $count++;} ?>
                        </tbody>
                    </table>
                    <?php $this->Paginator->options(array('url' => array('controller' => 'Reports', 'action' => 'usertxnSearch')));
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


