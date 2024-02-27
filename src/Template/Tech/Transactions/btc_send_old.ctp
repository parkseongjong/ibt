<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Send <small>BTC</small> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Send BTC</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="inner_content_w3_agile_info">
            <div class="agile-validation agile_info_shadow">
                <div class="validation-grids widget-shadow  " data-example-id="basic-forms">
                    <div class="input-info">
                        <h3 class="w3_inner_tittle two">Send BTC :</h3>
                        <?= $this->Flash->render(); ?>
                    </div>
                    <div class="form-body form-body-info" style="display: inline-block;width: 100%;">
                        <?php echo $this->Form->create($this->Url->build(['controller'=>'transactions','action'=>'btcSend']),array('method'=>'post'));?>
						<input type="hidden" name="request_type" required="" id="request_type" value="admin_trans" class=" form-control">
                        <input type="hidden" name="search_keyword" id="search_keyword" value="" class=" form-control">
                        <div class="col-md-4 form-group valid-form">
                            Enter Btc Transaction Id :
                            <input id="btc_transaction_id" placeholder="Btc Transaction Id"  class="form-control required" name="btc_transaction_id" required  style="border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="text">

                        </div>
						<div class="col-md-4 form-group valid-form">
                            Enter Transaction Id :
                            <input id="trans_id" placeholder="Transaction Id"  class="form-control required" name="trans_id" required  style="border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="text">

                        </div>

						<div class="col-md-4 form-group valid-form">
                            Date :
                            <input id="payment_date"  class="form-control datepicker required" name="payment_date" required  style="border-bottom:#FF9900 1px solid;border-left:#FF9900 1px solid;border-right:#FF9900 1px solid;border-top:#FF9900 1px solid; border-radius:5px" type="text">


                        </div>
                      
                        <div class="clearfix"></div>
                        <div class="form-group col-md-6">
                            <input id="mySubmit" class="btn btn-primary" value="Submit" type="submit">
                        </div>
                        </form>
                    </div>
                </div>
            </div>
			<style>
			.odd{ background-color:cadetblue; }
			</style>
            <div class="clearfix"></div>
		
                   <h3 class="w3_inner_tittle two">Transactions</h3>
                   <div class="pull-right">
                <label style="color: cadetblue;">Search:</label>
                <input type="search" class="search_transaction" placeholder="" id="search_transaction">

            </div>
            <div class="clearfix"></div>
                    <div class="table-responsive">
                    <table id="table-two-axis" class="two-axis table">
                        <thead>
                        <tr>
                            <th>S&nbsp;No.</th>
							<th>Btc Transaction Id</th>
							<th>Username</th>
							<th>Total Btc Amount</th>
							<th>Btc Amount</th>
							<th>Wallet Address</th>
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
							
							$userBTC = $this->Conversion->getTotalBtc($data['user']['id']);
							$showStatus = "Completed";
							if($data['admin_withdrawl_transfer']=="no"){
								$showStatus = "Pending";
							}
							
							
                            ?>
                            <tr class="<?=$class?>">
                                <td><?=$count?></td>
								<td><?php echo $data['id']; ?></td>
								<td><?php echo $data['user']['username']; ?></td>
								<td><?php echo $userBTC; ?></td>
								<td><?=number_format((float)abs($data['btc_coins']),8);?></td>
								<td><?php echo $data['wallet_address']; ?></td>
								<td><?php echo $showStatus; ?></td>
								<td><?=$data['created_at']->format('d M Y H:i:s');?></td>
                            </tr>
                            <?php $count++; } ?>
                        <?php  if(count($listing->toArray()) < 1) {
                            echo "<tr class='even'><td colspan = '6'>No record found</td></tr>";
                        }  ?>
                        </tbody>
                    </table>
                    <?php  $this->Paginator->options(array('url' => array('controller' => 'Transactions', 'action' => 'btcSendSearch')));
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

            $(document).on("keyup", "#search_transaction", function (e) {
            e.preventDefault();
            var str = $("#search_transaction").val();
            $("#search_keyword").val(str);
        });

        $(document).on("keyup", "#search_transaction", function (e) {
            e.preventDefault();
            var key_code = e.which;
            if (key_code == 13) {
                var keyy = $('form').serialize();
                var urli = '<?php echo $this->Url->build(['controller' => 'Transactions', 'action' => 'btc-send-search']); ?>';

                jQuery.ajax({
                    url: urli,
                    data: {key: keyy},
                    type: 'POST',
                    success: function (data) {
                        if (data) {

                            jQuery('.table-responsive').html(data);

                        }
                    }
                });
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
		
  </script>

