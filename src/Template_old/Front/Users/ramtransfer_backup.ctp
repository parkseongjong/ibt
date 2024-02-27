<?php echo $this->element('Front/profile_sidebar'); ?>

<section>
    <section class="main-content"> 
      <!-- Live Coin Price -->
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-collapse">
              <div class="panel-body"> 
                <!-- Table Starts Here -->
                <section class="main-content">
                  <h3>Ram Transfer<br>
                    <!--<small>OKEx customer support will never ask for your SMS and Google Authentication codes. Please DO NOT disclose them to anyone.</small>--></h3>
                  <div class="row">
                    
                    <div class="col-md-12">
                      <div class="panel panel-default">
					  
                        <div class="tab-content2 tab-content">
                          <div id="home" class="tab-pane fade in active">
                           Available RAM Token : <?php echo $getUserBalance; ?><br>
						   
						   Current RAM Price (USD) : <?php echo $getRamCurrentPrice['currentprice_usd']; ?><br>
						   <hr/>
							  <?= $this->Flash->render() ?>
                             <?php echo $this->Form->create($user,array('enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','method'=>'post'));?>
							<ul class="input-box">
								 <li class="input-item password" data-error="false">
                                <input placeholder="Unique Address" type="text" required="required" autocomplete="off"  class="form-control"  name="unique_address" >
                               </li>
							   
							   <li class="input-item password" data-error="false">
                                <input placeholder="Ram Amount (USD)" id="ram_amount" type="text" required="required" autocomplete="off" class="form-control" name="ram_amount" >
                               </li>
							   
                              <li class="input-item password" data-error="false">
                                <input placeholder="Ram Token" id="ram_token" type="text" required="required" autocomplete="off" class="form-control" name="amount" >
                               </li>
                             
                              <li class="input-item email-code" data-error="false">
                                  <input placeholder="Email code" type="text" autocomplete="off"  required="required" name="email_code" class="form-control input2" >
                                  <span class="send-button" id="get_code" style="padding:10px;cursor:pointer;">Get code</span>
								  <div id="show_msg"></div>
                                 </li>
                         
                              <li class="input-item btn-item">
                                <input type="submit" id="submit" class="btn" value="Send">
                              </li>
                            </ul>
							</form>
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
	 
	 $('#ram_amount').on('input', function () { 
		 var getVal = $(this).val();
		 if(!isNaN(getVal)) {
			 var calculateRamToken = getVal/<?php echo $getRamCurrentPrice['currentprice_usd']; ?>;
			 var calculateRamToken = calculateRamToken.toFixed(8);
			 $("#ram_token").val(calculateRamToken);
		 }
	 });
	 
	 $('#ram_token').on('input', function () { 
	 	 var getVal = $(this).val();
		 if(!isNaN(getVal)) {
			 if(getVal!='') {
				 var calculateRamAmount = getVal*<?php echo $getRamCurrentPrice['currentprice_usd']; ?>;
				 var calculateRamAmount = calculateRamAmount.toFixed(8);
				 $("#ram_amount").val(calculateRamAmount);
				
			 }
			 else {
				 $("#ram_amount").val('');
			 }
		 }
	 });
	 
	 $('form').submit(function(){
		 $('form [type=submit]').remove();
	})
  });
  </script>