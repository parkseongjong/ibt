<style>
.table > thead > tr > th { padding: 14px 8px;}
</style>
<link  href="<?php echo $this->request->webroot?>datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
<script src="<?php echo $this->request->webroot?>datepicker/bootstrap-datepicker.min.js"></script>


<section style="margin-left:0px;">
    <section class="main-content"> 
      <!-- Live Coin Price -->
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-collapse">
              <div class="panel-body"> 
                <!-- Table Starts Here -->
                <section class="main-content">
                  <h3>My Wallet<br>
                    <!--<small>OKEx customer support will never ask for your SMS and Google Authentication codes. Please DO NOT disclose them to anyone.</small>--></h3>
                  <div class="row">
                    
                    <div class="col-md-12">
					
					<!--<div class="alert alert-danger">
						  <strong>Note :</strong> Withdrawal will start from 14th July 2018 at 11:30 AM GMT.
						</div>-->
					
                      <div class="panel panel-default">
                        <div class="tab-content2 tab-content">
                          <div id="home" class="tab-pane fade in active ">
                            <div class="table-responsive">
							  <?= $this->Flash->render() ?>
                             <table class="table table-striped">
								<thead>
								<tr>
									<th>Curreny Name</th>
									<th>Symbol</th>
									<th>Available balance</th>
									<th>Pending Deposit</th>
									<th>Reserved</th>
									<th>Total</th>
									<th>Deposit</th>
									<th>Withdrawal</th>
								</tr>
								<thead>
								<tbody>
								<?php
								
								foreach($getCoinList as $singleCoin) { 
								/* if(!in_array($currentUserId,[10003090,10003992]) && $singleCoin['id']==5){
									continue;
								} */
								$actionPage = 'withdrawal' ;
								$getPrice = $this->Custom->getBalance($singleCoin['id'],$currentUserId);
								$totalBalance = $getPrice['withdrawBalance']+$getPrice['pendingBalance']+abs($getPrice['reserveBalance']);
								?>
									<tr>
										<td><?php echo $singleCoin['name']; ?></td>
										<td><?php echo $singleCoin['short_name']; ?></td>
										<td><?php echo number_format((float)$getPrice['withdrawBalance'],8) ?></td>
										<td><?php echo number_format((float)$getPrice['pendingBalance'],8) ?></td>
										<td><?php echo number_format((float)abs($getPrice['reserveBalance']),8) ?></td>
										<td><?php echo number_format((float)$totalBalance,8) ?></td>
										<td>
										<?php
										//if($singleCoin['id']!=2) {	
										if(!in_array($singleCoin['id'],[13,14,15])){ ?>
											<button type="submit" id="show_deposit_<?php echo $singleCoin['id']; ?>" data-coin-id="<?php echo $singleCoin['id']; ?>" data-coin-name="<?php echo $singleCoin['short_name']; ?>"  class="btn btn-default deposit_btn" style="background-color:#4583bb;color:#fff!important;">Deposit</button>
										<?php } else { ?>
											<button type="submit" id="show_deposit_<?php echo $singleCoin['id']; ?>" data-coin-id="<?php echo $singleCoin['id']; ?>" data-coin-name="<?php echo $singleCoin['short_name']; ?>"  class="btn btn-default flat_deposit_btn" style="background-color:#4583bb;color:#fff!important;">Deposit</button>
										<?php } ?>
										</td>
										<td>
										<?php
										//if($singleCoin['id']!=2) {	
										if(in_array($singleCoin['id'],[1,2,3])){ ?>
											&nbsp;
										
										<?php } else { ?>
											<a class="btn btn-default" style="background-color:#6ab10c;color:#fff!important;" href="<?php echo $this->Url->build(['controller'=>'pages','action'=>$actionPage,$singleCoin['short_name']]) ?>">Withdrawal</a>
										<?php } ?>
										</td>
									</tr>
								
								<?php } ?>
							
								</tbody>
							</table>
                          </div>
                          </div>
                          
                          
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
                <!-- Table Ends Here --> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </section>

  
  
  <!-- Modal -->
<div id="myModalDeposit" class="modal fade" role="dialog" >
  <div class="modal-dialog" style='color:#000;' >

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span id="modal_coin_name"></span> Wallet Address</h4>
      </div>
      <div class="modal-body" style="text-align:center;">
		<p class="m-b-15">This is permanent wallet address. To deposit, pay it to this address.</p>
		<br>
		<p class="m-b-15">After doing payment, please wait for 1 hour to reflect in your dashboard. Transaction verification takes time. </p>
		<br>
		<img id="model_qr_code" src="/ajax-loader.gif" />
        <p id="model_wallet_addr">Processing.......</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

  <!-- Modal -->
<div id="myModalDepositFlat" class="modal fade" role="dialog" style='color;#000;'>
  <div class="modal-dialog" style='color:#000;' >

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Deposit <span id="modal_coin_name_flat"></span></h4>
      </div>
      <div class="modal-body" style="text-align:center;">
	  <form action="#" id="deposit_form" enctype="multipart/form-data">
		<input type="hidden" class="form-control" id="flat_currency" name="currency">
		<div class="form-group">
		  <label for="email">Amount:</label>
		  <input type="text" class="form-control" required placeholder="Enter Amount" name="amount">
		</div>
		<div class="form-group">
		  <label for="pwd">Transaction Id:</label>
		  <input type="text" class="form-control" required  placeholder="Enter Transaction Id" name="txid">
		</div>
		<div class="form-group">
		  <label for="pwd">Date:</label>
		  <input type="text" readonly class="form-control" id="flat_date" placeholder="Enter Date" required name="date">
		</div>
		
		<div class="form-group">
		  <label for="pwd">Attachment:</label>
		  <input type="file" class="form-control" id="pwd" required name="attachment">
		</div>

		<button type="submit" class="btn btn-default" id="btnSubmit">Submit</button>
		<img id="model_qr_code_flat" style="display:none;" src="/ajax-loader.gif" />
		 <div id="model_wallet_addr_flat" style="display:none;"></div>
		
	  </form>
		
       
      </div>
      
    </div>

  </div>
</div>

  

  <script>
	$('document').ready(function(){
		$("#flat_date").datepicker({
			format: 'yyyy-mm-dd'
		});
		$("#admc_deposit").click(function(){
			$.ajax({
				beforeSend : function(){
					$("#loadimg").show();
				},
				url:"<?php echo $this->Url->build(['controller'=>'pages','action'=>'getcoinaddress']); ?>",
				type : 'post',
				//dataType:'json',
				success : function(getresp) {
					$("#loadimg").hide();
					$("#get_admc_address").html(getresp);
					$('#myModal').modal('show'); 
				},
				error : function(){
					$("#loadimg").hide();
				}
			});
		});
		
		$(".flat_deposit_btn").click(function(){
			var getCoinName = $(this).attr('data-coin-name');
			var getCoinId = $(this).attr('data-coin-id');
			$("#modal_coin_name_flat").html(getCoinName);
			$("#myModalDepositFlat").modal('show');  
			$("#flat_currency").val(getCoinId);  
			
		});
		
		$("#deposit_form").submit(function(event){
			    //stop submit the form, we will post it manually.
			event.preventDefault();
			var form = $(this)[0];
			var getFormData = new FormData(form);
			   // disabled the submit button
			$("#btnSubmit").prop("disabled", true);
			$.ajax({
				type: "POST",
				enctype: 'multipart/form-data',
				url: '<?php echo $this->Url->build(['controller'=>'transactions','action'=>'dashboardPost']); ?>',
				data: getFormData,
				processData: false,
				contentType: false,
				cache: false,
				success: function (data) {
					if(data=="All Fields are required" || data =="File size should be maximum 500 KB"){
						$("#model_wallet_addr_flat").html(data).addClass('alert alert-danger').show();
						setTimeout(function(){ $("#model_wallet_addr_flat").html("").removeClass('alert alert-danger').hide(); },5000)
					}
					else {
						$("#model_wallet_addr_flat").html(data).addClass('alert alert-success').show();
						setTimeout(function(){ $("#model_wallet_addr_flat").html("").removeClass('alert alert-danger').hide(); $("#myModalDepositFlat").modal('hide');   },5000)
						$("#deposit_form")[0].reset();
					}
					$("#btnSubmit").prop("disabled", false);

				},
				error: function (e) {

					/* $("#output").text(e.responseText);
					console.log("ERROR : ", e); */
					$("#btnSubmit").prop("disabled", false);

				}
			});
		});
		
		$(".deposit_btn").click(function(){
			var getCoinName = $(this).attr('data-coin-name');
			var getCoinId = $(this).attr('data-coin-id');
			$("#model_qr_code").attr('src',"/ajax-loader.gif");
			$("#model_wallet_addr").html('Processing.......');
			$("#modal_coin_name").html(getCoinName);
			$('#myModalDeposit').modal('show'); 
			if(getCoinId==2){
				$("#model_qr_code").attr('src',"http://chart.googleapis.com/chart?chs=225x225&chld=L|2&cht=qr&chl=:<?php echo $intrAddress; ?>");
				$("#model_wallet_addr").html('<?php echo $intrAddress; ?>');
			}
			else {
				$.ajax({
					beforeSend : function(){
						$("#loadimg").show();
					},
					url:"<?php echo $this->Url->build(['controller'=>'transactions','action'=>'dashboardPost']); ?>",
					type : 'post',
					data:{currency:getCoinId},
					//dataType:'json',
					success : function(getresp) {
						
						$("#model_qr_code").attr('src',"http://chart.googleapis.com/chart?chs=225x225&chld=L|2&cht=qr&chl=:"+getresp);
						$("#model_wallet_addr").html(getresp);
						
					},
					error : function(){
						
					}
				});
			}
		});
	});
  
  
  </script>
