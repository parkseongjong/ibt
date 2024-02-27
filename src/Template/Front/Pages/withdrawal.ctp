
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
                    
                    <div class="col-md-12">
                      <div class="panel panel-default">
                        <div class="tab-content2 tab-content">
						<div class="row">
						<div class="col-md-9 col-md-offset-3">
						
                          <div id="home" class="tab-pane fade in active">
                           
							  <?= $this->Flash->render() ?>
                             <?php echo $this->Form->create('',array('id'=>'withdrawal_form','enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','method'=>'post'));?>
							<ul class="input-box">
							
							  <li class="input-item password" data-error="false">Coin Symbol : <strong><?php echo $coinDetail['short_name']; ?> </strong>
                               
                               </li>
								<li class="input-item password" data-error="false">
								Available Amount : <strong><?php echo $userbalance; ?></strong>
                             
                               </li>							   
								
								<?php if($coinType=="flat") { ?>
									
								<li class="input-item password" data-error="false">
								Bank Account
                                <input placeholder="Bank Account(A/C)" id="bank_account" type="text" required="required" autocomplete="false" class="form-control" name="flat_account_no" >
                               </li>
							   
								<li class="input-item password" data-error="false">
								NAME OF BANK
                                <input placeholder="NAME OF BANK" id="flat_bank_name" type="text" required="required" autocomplete="false" class="form-control" name="flat_bank_name" >
                               </li>
							   
								<li class="input-item password" data-error="false">
								OWNER OF BANK ACCOUNT
                                <input placeholder="OWNER OF BANK ACCOUNT" id="flat_account_owner" type="text" required="required" autocomplete="false" class="form-control" name="flat_account_owner" >
                               </li>
							   
								<li class="input-item password" data-error="false">
								ADDRESS OF BANK
                                <input placeholder="ADDRESS OF BANK" id="flat_bank_address" type="text" required="required" autocomplete="false" class="form-control" name="flat_bank_address" >
                               </li>
							   
									
								<?php } ?>
								
                              <li class="input-item password" data-error="false">
							  Quantity
                                <input placeholder="Quantity" id="quantity" type="text" required="required" autocomplete="false" class="form-control" name="quantity" >
                               </li>
							   
							    <li class="input-item password" data-error="false">
							  Transaction Fess
                                <input placeholder="Transaction Fee" id="trans_fee" type="text" readonly value="<?php echo $transFee ?>" class="form-control" name="trans_fee" >
                               </li>
							
							  <li class="input-item password" data-error="false">
							  Total Withdrawal
                                <input placeholder="Total Withdrawal" id="total_withdrawal" type="text" required="required" readonly autocomplete="false" class="form-control" name="total_withdrawal" >
                               </li>	

							  <?php if($coinType!="flat") { ?>
                              <li class="input-item password" data-error="false">
							  Withdrawal Address
                                <input placeholder="Withdrawal Address" type="text" required="required" autocomplete="false"  class="form-control" id="withdrawal_address"  name="withdrawal_address" >
								<?php if($coinDetail['id']==2) { ?>
								<!-- <span class="send-button" id="validate_button" style="ppadding: 10px;cursor: pointer;display: block;margin: 10px 0;	width: 150px;line-height: 18px;padding:10px;cursor:pointer;">Validate Address</span>
								  <div id="validate_msg" style="width: 447px;"></div>-->
								  
								<?php } ?>
								<div id="validate_msg" style="width: 447px;"></div>
                               </li>
							
                              <li class="input-item confirm-password" data-error="false">
                                Disclaimer : Please verify your withdrawal address. We Cannot refund an incorrect withdrawal.
								
                               </li>
							      <?php } ?>
                              <li class="input-item email-code" data-error="false">
                                  <input placeholder="Google Auth code" type="text" autocomplete="false"  required="required" name="email_code" class="form-control input2" >
                                  <!--<span class="send-button" id="get_code" style="padding:10px;cursor:pointer;">Get code</span>-->
								  <div id="show_msg"></div>
                                 </li>
                         
                              <li class="input-item btn-item">
                                <input type="submit" style='color:#000;' class="btn" value="Submit">
                              </li>
                            </ul>
							</form>
                          </div>
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
  
  <script>
  $(document).ready(function(){
	  
	  	  
	  <?php if($coinDetail['id']==4) { ?>
	    $("#withdrawal_address").blur(function(event){
			var addr = $("#withdrawal_address").val();
			
			$.ajax({
				beforeSend:function(){
					$("#validate_msg").html('<img src="<?php echo $this->request->webroot; ?>ajax-loader.gif" />');
				},
				url : '<?php echo $this->Url->build(['controller'=>'pages','action'=>'validateAdmcAddress']);  ?>',
				data : { 'address' : addr },
				type : 'post',
				dataType : 'json',
				success : function(resp){
					if(resp==0){
						$("#validate_msg").html("<div class='alert alert-danger'>Invalid Address</div>");
						$("#withdrawal_address").val('');
					}else {
						$("#validate_msg").html("<div class='alert alert-success'>Valid Address</div>");
					}
					
				}
			});
			
		});	
	  <?php } ?>
	  
	  <?php if($coinDetail['id']==2) { ?>
	  $("#validate_button").click(function(){
		  var addr = $("#withdrawal_address").val();
		  var get = isAddress(addr);
		  if(get==true){
			$("#validate_msg").html("<div class='alert alert-success'>Valid Address</div>");  
		  }
		  else {
			$("#validate_msg").html("<div class='alert alert-danger'>invalid Address</div>");    
		  }
		 
	  });
	  
	  
		/* $("#withdrawal_form").submit(function(){
			var addr = $("#withdrawal_address").val();
			var get = isAddress(addr);
			if(get==false){ 
				$("#validate_msg").html("<div class='alert alert-danger'>invalid Address</div>");  
				return false;
			}
		}); */
	  <?php  } ?>
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
	 
	 
  });
  
/**
 * Checks if the given string is an address
 *
 * @method isAddress
 * @param {String} address the given HEX adress
 * @return {Boolean}
*/  
  
    var isAddress = function (address) {
		if (!/^(0x)?[0-9a-f]{40}$/i.test(address)) {
			// check if it has the basic requirements of an address
			return false;
		} else if (/^(0x)?[0-9a-f]{40}$/.test(address) || /^(0x)?[0-9A-F]{40}$/.test(address)) {
			// If it's all small caps or all all caps, return true
			return true;
		} else {
			// Otherwise check each case
			return isChecksumAddress(address);
		}
};

/**
 * Checks if the given string is a checksummed address
 *
 * @method isChecksumAddress
 * @param {String} address the given HEX adress
 * @return {Boolean}
*/
	var isChecksumAddress = function (address) {
		// Check each case
		address = address.replace('0x','');
		var addressHash = sha3(address.toLowerCase());
		for (var i = 0; i < 40; i++ ) {
			// the nth letter should be uppercase if the nth digit of casemap is 1
			if ((parseInt(addressHash[i], 16) > 7 && address[i].toUpperCase() !== address[i]) || (parseInt(addressHash[i], 16) <= 7 && address[i].toLowerCase() !== address[i])) {
				return false;
			}
		}
		return true;
	};
  </script>