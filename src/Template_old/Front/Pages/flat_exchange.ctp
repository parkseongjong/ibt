
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
                  <h3>Flat Exchange<br>
                    <!--<small>OKEx customer support will never ask for your SMS and Google Authentication codes. Please DO NOT disclose them to anyone.</small>--></h3>
                  <div class="row">
                    
                    <div class="col-md-12">
                      <div class="panel panel-default">
                        <div class="tab-content2 tab-content">
						<div class="row">
						<div class="col-md-8">
						
                          <div id="home" class="tab-pane fade in active">
                           
							  <?= $this->Flash->render() ?>
                             <?php echo $this->Form->create('',array('id'=>'withdrawal_form','enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','method'=>'post'));?>
							<ul class="input-box">
							
							 
															   
								
							
								
                               <li class="input-item password" data-error="false">
								<?php echo $this->Form->input('flat',['id'=>'flat_cur','required'=>'required','class'=>'form-control','options'=>array(''=>'Please Select')+$flatCurrencyArr]); ?>
                               </li>
							   
							   <li class="input-item password" data-error="false">
									<?php echo $this->Form->input('amount',['class'=>'form-control','required'=>'required']); ?>
                                </li>
							   <li class="input-item password" data-error="false">
									Available Amount : <strong id="show_balance">0</strong>
								</li>
							   
							   <li class="input-item password" data-error="false">
									Current Price : <strong id="show_current_price">0</strong>
								</li>
							   
							    <li class="input-item password" data-error="false">
									NTR : <strong id="show_ntr">0</strong>
                                </li>
							   
							 
							
							 
                              <li class="input-item email-code" data-error="false">
                                  <input placeholder="Email code" type="text" autocomplete="false"  required="required" name="email_code" class="form-control input2" >
                                  <span class="send-button" id="get_code" style="padding:10px;cursor:pointer;">Get code</span>
								  <div id="show_msg"></div>
                                 </li>
							 
                              <li class="input-item btn-item">
                                <input type="submit" class="btn confirm-btn"  value="Confirm">
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
		
		
			$("#amount").keyup(function(event){
				var getAmt = $(this).val();
				if(getAmt <= 0 ){
					
				}
				var curPrice =$("#show_current_price").html(); 
				var getNtrAmt = parseFloat(curPrice)*parseFloat(getAmt);
				$("#show_ntr").html(getNtrAmt);
			});
	  	   $("#flat_cur").change(function(event){
				var getAmt = $("#amount").val();
				var getVal = $("#flat_cur").val();
				if(getVal==""){
					$("#show_balance").html(0);
					$("#show_current_price").html(0);
					return false;
				}
				$.ajax({
					beforeSend:function(){
						//$("#validate_msg").html('<img src="<?php echo $this->request->webroot; ?>ajax-loader.gif" />');
					},
					url : '<?php echo $this->Url->build(['controller'=>'pages','action'=>'getUserLocalBalance']);  ?>/'+getVal,
					type : 'post',
					//dataType : 'json',
					success : function(resp){
						$("#show_balance").html(resp);
						
					}
				});
				
				$.ajax({
					url : '<?php echo $this->Url->Build(['controller'=>'exchange','action'=>'getCurrenPrice']); ?>/'+getVal+'/2',
					type : 'get',
					dataType : 'json',
					success : function(resp){
						if($.isEmptyObject(resp.current_price)){
							$("#show_current_price").html(0);
						}
						else {
							var returnPrice = resp.current_price.get_per_price;
							returnPrice = parseFloat(returnPrice).toFixed(8);
							$("#show_current_price").html(returnPrice);
							if(getAmt > 0) {
								var getNtrAmt = parseFloat(returnPrice)*parseFloat(getAmt);
								$("#show_ntr").html(getNtrAmt);
							}
						}
						
					}
				});
				
			});	
		
});		  
  </script>