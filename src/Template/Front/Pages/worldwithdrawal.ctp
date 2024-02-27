<script>
.fade { opacity: 1; transition: opacity .15s linear; }
</script>
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
                  <h3>World Connects Club<br>
                    <!--<small>OKEx customer support will never ask for your SMS and Google Authentication codes. Please DO NOT disclose them to anyone.</small>--></h3>
                  <div class="row">
                    
					 
					 
					 <div class="container">

					  <!---<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#home">USD Withdrawal</a></li>
						<!--<li><a data-toggle="tab" href="#menu1">ERC20 Withdrawal</a></li>
					  </ul>-->

					  <div class="tab-content tab-content2">
						
        <div id="home" class="tab-pane fade in active">
		   <div class="col-md-9 col-md-offset-3">
			  <?= $this->Flash->render() ?>
			 <?php echo $this->Form->create('',array('enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','method'=>'post'));?>
			<ul class="input-box">
				<?php /* echo number_format($ramCurrentPrice,8); 
				echo "<br/>";
				echo number_format($admcCurrentPrice,8);
				echo "<br/>";			 
				echo number_format($ethCurrentPrice,8);
				echo "<br/>";			 
				echo number_format($usdCurrentPrice,8); */
			?>
			  <!---<li class="input-item password" data-error="false">Coin Symbol : <strong><?php //echo $coinDetail['short_name']; ?> </strong>-->
			   
			   </li>
				<li class="input-item password" data-error="false">
				Available Token : <strong><?php echo $userbalance; ?></strong>
			   </li>							   
			
			  <li class="input-item password" data-error="false">
			  
				
				
				Type
				<select name="amount_in_usd"  id="amount_in_usd" class= "form-control" style="width:447px;">
					<option value="">Please Select</option>
					<option value="2">ETH</option>
					<option value="3">RAM</option>
					<option value="4">ADMC </option>
					<!--<option value="5">USD </option>-->
				</select>
				Amount
				<select name="amount" id="amount"  class= "form-control" style="width:447px;">
					<option value="">Please Select</option>
				</select>
				
				Tokens
				<input placeholder="Quantity" readonly id="ramtrex_quantity1" type="text" required="required" autocomplete="false" class="form-control" name="quantity" >
				
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
			if(amount_in_usd_val==''){
				var getHTml = '<option value="">Please select</option>';
			}
			else if(amount_in_usd_val==2){
				var getHTml = '<option value="">Please select</option><option value="30">30 USD</option><option value="40">40 USD</option>';
			}
			
			else if(amount_in_usd_val==3){
				var getHTml = '<option value="">Please select</option><option value="20">20 USD</option>';
			}
			
			else if(amount_in_usd_val==4){
				var getHTml = '<option value="">Please select</option><option value="20">20 USD</option>';
			}
			/* else {
			var getHTml = '<option value="">Please select</option><option value="50">50 USD</option>';
			   } */
			
			$("#amount").html(getHTml);
			
		}); 

	
	 $('#amount').on('input',function(){
		 var amount = $(this).val();
		 //alert(this.value);
	     var amount_in_usd = $('#amount_in_usd').val();
		 //alert(amount_in_usd);
		 if(amount_in_usd == "") {
			var allAmount = <?php echo 0; ?>
		 }
		 if(amount_in_usd == 2) {
			var allAmount = <?php echo number_format($ethCurrentPrice,8); ?>
		 }
		  if(amount_in_usd == 3) {
			 var allAmount = <?php echo number_format($ramCurrentPrice,8); ?>
		 }
		  if(amount_in_usd ==4) {
			var allAmount = <?php echo number_format($admcCurrentPrice,8); ?>
		 }
		  if(amount_in_usd == 5) {
			 var allAmount = <?php echo number_format($usdCurrentPrice,8); ?>
		 }
		
		 
		 // alert(amount_in_usd);
		  var totalAmount = amount/allAmount;
		  //alert(allAmount);
		  var totalAmount = parseFloat(totalAmount).toFixed(8);
		  $("#ramtrex_quantity1").val(totalAmount);
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