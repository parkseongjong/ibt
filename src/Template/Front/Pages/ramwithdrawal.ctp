<?php 
$displayResult = "block";
if($coindId=3) { $displayResult = "none"; }  ?>
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
                  <h3>Withdrawal<br>
                    <!--<small>OKEx customer support will never ask for your SMS and Google Authentication codes. Please DO NOT disclose them to anyone.</small>--></h3>
                  <div class="row">
                    
					 
					 
					 <div class="container">

					  <ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#home">RAMTREX Withdrawal</a></li>
						<!--<li><a data-toggle="tab" href="#menu1">ERC20 Withdrawal</a></li>-->
					  </ul>

					  <div class="tab-content tab-content2">
					  
						<div id="home" class="tab-pane fade in active">
											   <div class="col-md-9 col-md-offset-3">
												  <?= $this->Flash->render() ?>
												 <?php echo $this->Form->create('',array('enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','method'=>'post'));?>
												<ul class="input-box">
												
												  <li class="input-item password" data-error="false">Coin Symbol : <strong><?php echo $coinDetail['short_name']; ?> </strong>
												   
												   </li>
													<li class="input-item password" data-error="false">
													Available Token : <strong><?php echo $userbalance; ?></strong>
												 
												   </li>							   
												
												  <li class="input-item password" data-error="false">
												  
													
													
													Amount In Usd
													<select name="amount_in_usd" id="amount_in_usd" class="form-control" style="width:447px;">
														<option value="">Select Amount</option>
														<option value="30">30</option>
														<!--<option value="100">100</option>
														<option value="200">200</option>
														<option value="manual">Manual Transfer</option>-->
													</select>
													
													Tokens
													<input placeholder="Quantity" readonly id="ramtrex_quantity" type="text" required="required" autocomplete="false" class="form-control" name="quantity" >
													
												   </li>
												   
												   
													
												
												  
												 
												  <li class="input-item confirm-password" data-error="false">
													Disclaimer : Please verify your withdrawal address. We Cannot refund an incorrect withdrawal.
													
												   </li>
												  <li class="input-item email-code" data-error="false">
													  <input placeholder="Email code" type="text" autocomplete="false"  required="required" name="email_code" class="form-control input2" >
													  <span class="send-button" id="get_code" style="padding:10px;cursor:pointer;">Get code</span>
													  <div id="show_msg"></div>
													 </li>
											 
												  <li class="input-item btn-item">
													<input type="submit" class="btn" value="Confirm">
												  </li>
												</ul>
												</form>
											  </div>
											  </div>
											  
											  
											 <!-- <div id="menu1" class="tab-pane fade">
											  <div class="col-md-9 col-md-offset-3">
												
												 <?php //echo $this->Form->create('',array('url'=>'front/pages/ercwithdrawal/'.$coinDetail['short_name'],'enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','method'=>'post'));
												 
												 
												 ?> 
												 
												<ul class="input-box">
												
												  <li class="input-item password" data-error="false">Coin Symbol : <strong><?php //echo $coinDetail['short_name']; ?> </strong>
												   
												   </li>
													<li class="input-item password" data-error="false">
													Available Amount : <strong><?php echo $userbalance; ?></strong>
												 
												   </li>							   
													
												  <li class="input-item password" data-error="false">
												  Quantity
													<input placeholder="Quantity" id="quantity" type="text" required="required" autocomplete="false" class="form-control" name="quantity" >
												   </li>
												   
													<li class="input-item password" data-error="false">
												  Transaction Fess
													<input placeholder="Transaction Fee" id="trans_fee" type="text" readonly value="<?php //echo $transFee ?>" class="form-control" name="trans_fee" >
												   </li>
												
												  <li class="input-item password" data-error="false">
												  Total Withdrawal
													<input placeholder="Total Withdrawal" id="total_withdrawal" type="text" required="required" readonly autocomplete="false" class="form-control" name="total_withdrawal" >
												   </li>	

												  
												  <li class="input-item password" data-error="false">
												  Withdrawal Address
													<input placeholder="Withdrawal Address" type="text" required="required" autocomplete="false"  class="form-control"  name="withdrawal_address" >
												   </li>
												  <li class="input-item confirm-password" data-error="false">
													Disclaimer : Please verify your withdrawal address. We Cannot refund an incorrect withdrawal.
													
												   </li>
												  <li class="input-item email-code" data-error="false">
													  <input placeholder="Email code" type="text" autocomplete="false"  required="required" name="email_code" class="form-control input2" >
													  <span class="send-button" id="get_code_erc" style="padding:10px;cursor:pointer;">Get code</span>
													  <div id="show_msg_erc"></div>
													 </li>
											 
												  <li class="input-item btn-item">
													
												  </li>
												</ul>
												</form>
											  </div>
											  </div>-->
						
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
  
  <script>
  $(document).ready(function(){

		$('#amount_in_usd').on('change',function(){
			var amount_in_usd_val = $(this).val();
			if(amount_in_usd_val!='') {
				if(amount_in_usd_val == 'manual') {
					window.location = '/front/users/ramtransfer';
				}
				else {
					 var quantity = amount_in_usd_val/<?php echo $currentPrice; ?>;
					 quantity = parseFloat(quantity).toFixed(8);
					 $("#ramtrex_quantity").val(quantity);
				}
			}else {
				 $("#ramtrex_quantity").val('');
			}
		}); 

	
	 $('#quantity').on('input',function(){
		 var quantity = $(this).val();
		 var fee = <?php echo $transFee; ?>;
		 var total_withdrawal = parseFloat(quantity)-parseFloat(fee);
		 $("#total_withdrawal").val(total_withdrawal);
	 });
	  
	 $("#get_code").click(function(){
		$.ajax({
			beforeSend:function(){
				$("#show_msg").html('<img src="<?php echo $this->request->webroot; ?>ajax-loader.gif" />');
			},
			url : '<?php echo $this->Url->build(['controller'=>'users','action'=>'sendEmailCode']);  ?>',
			type : 'post',
			dataType : 'json',
			success : function(resp){
				$("#show_msg").html('<div class="alert alert-success">Verification code send to your email id.</div>');
				setTimeout(function(){ $("#show_msg").hide(); }, 10000);
			}
		}) 
	 }); 
	 
	 
	 $("#get_code_erc").click(function(){
		$.ajax({
			beforeSend:function(){
				$("#show_msg").html('<img src="<?php echo $this->request->webroot; ?>ajax-loader.gif" />');
			},
			url : '<?php echo $this->Url->build(['controller'=>'users','action'=>'sendEmailCode']);  ?>',
			type : 'post',
			dataType : 'json',
			success : function(resp){
				$("#show_msg_erc").html('<div class="alert alert-success">Verification code send to your email id.</div>');
				setTimeout(function(){ $("#show_msg").hide(); }, 10000);
			}
		}) 
	 });
	 
	 
  });
  </script>