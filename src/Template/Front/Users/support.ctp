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
                <div class="main-content">
                  <h3>Support </h3>
                  <div class="row">
                    <div class="col-md-12">
					<?= $this->Flash->render() ?>
                      <div class="feedback-form in_form">
					    <?php echo $this->Form->create($user,array('id'=>'formsupport','enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','method'=>'post'));?>
                          
                        <ul>
                          <li class="item form-group">
                          <label  class="col-md-3 control-label">
                           <span>Issue Type</span></span><span class="need">&nbsp;*</span>
                           </label>
                           <div  class="col-md-6">
                            <select name="issue_type" id="issue_type" required class="form-control">
                             <option value="">Choose here</option>
                             <option value="login_issue">Login Issue</option>
                          <!--   <option value="ram_deposit_issue">Ram Deposit Issue</option>
							  <option value="ram_withdrawal">RAM Withdrawal</option>
                             <option value="admc_deposit_issue">ADMC Deposit Issue</option>
                             <option value="Admc_withdrawal_issue">ADMC Withdrawal Issue</option> -->
                             <option value="eth_deposit">ETH Deposit</option>
                             <option value="eth_withdrawal">ETH Withdrawal</option>
							
                             <option value="other">Other</option>
                            </select>
                            </div>
                            
                          </li>
						  
						  
						    
						   <li class="item form-group" id="tx_id_li" style="display:none;">
                          <label  class="col-md-3 control-label">
                           <span>Transaction Id</span></span><span class="need">&nbsp;</span>
                           </label>
                           <div  class="col-md-6">
                            <input type="text" name="tx_id"  class="form-control" />
                            </div>
                            
                          </li>
						  
						  
						   <li class="item form-group">
                          <label  class="col-md-3 control-label">
                           <span>Issue</span></span><span class="need">&nbsp;*</span>
                           </label>
                           <div  class="col-md-6">
                            <textarea name="issue" required class="form-control"></textarea>
                            </div>
                            
                          </li>
						  
						  
                          <li class="item form-group">
                          <label  class="col-md-3 control-label">
                           <span>Upload</span>
                           </label>
                            <div  class="col-md-9">
							  <div class="up_dashed">
							  <i class="fa fa-cloud-upload iconfont" aria-hidden="true"></i>
							  <input type="file" name="issue_file" id="fileInput" class="img-upload-input" accept="image/jpeg,image/jpg,image/png">
							  
                             
                             </div> <span>and each file size should not exceed 2MB. Supported formats: jpg / jpeg / png </span>
							  <span id="file_error"></span>
                             </div>
							
							 </li>
                            
                          <li class="item form-group">
                          <label  class="col-md-3 control-label">
                          
                           </label>
                           <div  class="col-md-6">
                            <button class="confirm-btn btn " >Submit</button>
                            </div>
                            
                          </li>
                          
                        </ul>
						</form>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Table Ends Here --> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </section>
  
    <script>
  $('document').ready(function(){
	  
	  $("#issue_type").change(function(){
		  var getVal = $(this).val();
		
		  if(getVal == 'ram_deposit_issue' ||  getVal == 'admc_deposit_issue' ||  getVal == 'Admc_withdrawal_issue' ||  getVal == 'eth_deposit' ||  getVal == 'eth_withdrawal'){
			 $("#tx_id_li").show();
		  }
		  else {
			  $("#tx_id_li").hide();
		  }
	  });
	 
		$("#fileInput").change(function(){
			$("#file_error").html(""); 
			$(".img-upload-input").css("border-color","#F0F0F0");
			var file_size = $('#fileInput')[0].files[0].size;
			
			if(file_size>2097152) {
				$("#file_error").html("<p style='color:#FF0000'>File size is greater than 2 MB</p>");
				//$(".img-upload-input").val("");
				return false;
			}
			var ext = $('#fileInput').val().split('.').pop().toLowerCase();
			if ($.inArray(ext, ['png','jpg','jpeg']) == -1){
				$("#file_error").html("<p style='color:#FF0000'>Invalid Image Format! Image Format Must Be JPG, JPEG or PNG.</p>");
				//$(".img-upload-input").val("");
				return false;
			}
			return true;
		});
		
		$("#formsupport").submit(function(){
			$(".confirm-btn").attr("disabled",true);
			if($('#fileInput').val() != "" ){
				$("#file_error").html(""); 
				$(".img-upload-input").css("border-color","#F0F0F0");
				var file_size = $('#fileInput')[0].files[0].size;
				
				if(file_size>2097152) {
					$("#file_error").html("<p style='color:#FF0000'>File size is greater than 2 MB</p>");
					//$(".img-upload-input").val("");
					$(".confirm-btn").attr("disabled",false);
					return false;
				}
				var ext = $('#fileInput').val().split('.').pop().toLowerCase();
				if ($.inArray(ext, ['png','jpg','jpeg']) == -1){
					$("#file_error").html("<p style='color:#FF0000'>Invalid Image Format! Image Format Must Be JPG, JPEG or PNG.</p>");
					//$(".img-upload-input").val("");
					$(".confirm-btn").attr("disabled",false);
					return false;
				}
			}
			
			return true;
		});
  });
  </script>