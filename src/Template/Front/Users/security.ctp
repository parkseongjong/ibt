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
                  <h3>Security<br>
                    <!--<small>OKEx customer support will never ask for your SMS and Google Authentication codes. Please DO NOT disclose them to anyone.</small>--></h3>
                  <div class="row">
				  <?= $this->Flash->render() ?>
                    <div class="col-md-3">
                      <div class="panel panel-default" style="background-color: #054190;">
                        <ul class="nav intabing">
                          <li class="active"><a data-toggle="tab" href="#home">Login Password</a></li>
                          
                          <li><a data-toggle="tab" href="#menu2">Google Authenticator</a></li>
                          <li><a data-toggle="tab" href="#menu3">2FA</a></li>
                         
                        </ul>
                      </div>
                    </div>
                    <div class="col-md-9">
                      <div class="panel panel-default">
                        <div class="tab-content2 tab-content">
                          <div id="home" class="tab-pane fade in active">
                            <h4>Change login password</h4>
							  
                             <?php echo $this->Form->create($user,array('id'=>'password_form','enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','method'=>'post'));?>
							<ul class="input-box">

                              <li class="input-item password" data-error="false">
                                <input placeholder="Current password" type="password" required="required" autocomplete="false" class="form-control" name="old_password" >
                               </li>
                              <li class="input-item password" data-error="false">
                                <input placeholder="New password " type="password" id="new_password" required="required" autocomplete="false"  class="form-control new_password"  name="new_password" >
								<span id="new_password_error" style="display:none;" >Password should have atleast 1 small-case letter, 1 Capital letter, 1 digit, 1 special character and the length should be between 6-10 characters.</span>
                               </li> 
                              <li class="input-item confirm-password" data-error="false">
                                <input placeholder="Confirm new password " id="confirm_password" type="password" autocomplete="false" required="required" class="form-control confirm_password"  name="confirm_password">
								<span id="confirm_password_error" style="display:none;">Password and confirm password should be same.</span>
                               </li>
                              <li class="input-item email-code" data-error="false">
                                  <input placeholder="Email code" type="text" autocomplete="false"  required="required" name="email_code" class="form-control input2" >
                                  <span class="send-button" id="get_code" style="padding:10px;cursor:pointer;">Get code</span>
								  <div id="show_msg"></div>
                                 </li>
                         
                              <li class="input-item btn-item">
                                <input type="submit" name="submitpass" class="btn confirm-btn" value="Confirm">
                              </li>
                            </ul>
							</form>
                          </div>
                          
						  
						   <div id="menu2" class="tab-pane fade verification_box">
							  <h4>Nind Google Authenticator in 3 Steps:</h4>
							  <?= $this->Flash->render() ?>
                             <?php echo $this->Form->create($user,array('enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','method'=>'post'));?>
							<ul class="input-box">
							<?php if($googleVerify=="Y") { ?>
                             <!-- <li class="input-item password" data-error="false">
                                <ul class="list-inline customcss customcss2" style="margin-top:0px;">
								
								
									<li><span class="text-danger">Off</span></li>
									<li><label class="switch" >
									  <input type="checkbox" id="gauth_checkbox" value="Y" <?php //if($googleAuthEnable == "Y") { echo "checked"; } ?>>
									  <span class="slider round"></span>
									</label></li>
									<li><span class="text-success">On</span></li>
									<li><div id="return_msg"></div></li>
							
							</ul>-->
                               </li>
							<?php } ?>
							   
							   <li>
							      <div class="verification_hadding">
								   <span>1</span> <p>Install google Authenticator or other authentication tools in your phone</p>
								  </div>
								  <div class="verification_hadding_in a_link">
								    <div class="row">
								      <div class="col-sm-6">
								        Android:
								      </div>
									  <div class="col-sm-6">
								      <a target='_blank' href='https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2'>Download</a>
								      </div>
								    </div>
									<div class="row">
								      <div class="col-sm-6">
								    IOS:
								      </div>
									  <div class="col-sm-6">
								  <a target='_blank' href='https://apps.apple.com/us/app/google-authenticator/id388497605'>Download</a>
								      </div>
								    </div>
									
								  </div>
							   
							   </li>
							   <li class="input-item password" data-error="false">
							    <div class="verification_hadding">
								   <span>2</span><p>Please set your apps as follws</p>
								  </div>
								  
								<div class="verification_hadding_in ">
								 <div class="row">
								   <div class="col-sm-3">
                                   <img src="<?php echo $googleAuthUrl; ?>" alt="Avatar" width="150"  class="img-thumbnail  account-img-mb">
                                   </div> 
								   <div class="col-sm-9">
                                   <p>If your phone failed to scan you can enter the following private key manually</p>
								   <p>Key: <b><?php echo $secret; ?></b></p>
                                   </div> 
								</div>
							   </div>
							   </li>
							   
							 
                              <li class="input-item password" data-error="false">
							  <div class="verification_hadding">
								   <span>3</span><p>Verify Authenticator </p>
								  </div>
								  <div class="verification_hadding_in ">
                                <input placeholder="Enter Code" type="text" required="required" autocomplete="false"  class="form-control"  name="authcode" >
								</div>
                               </li>
                             
                         
                              <li class="input-item btn-item">
                                <input type="submit" name="submitauth" class="btn confirm-btn" value="Verify">
                              </li>
							  
							  
                            </ul>
							</form>
                          </div>
                          
                          
						  
						  
						   <div id="menu3" class="tab-pane fade">
                            <h4>Login 2FA</h4>
							  
                             <?php echo $this->Form->create($user,array('enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','method'=>'post'));?>
							<ul class="input-box">

                              <li class="input-item password" data-error="false">
							  
                                <input  type="radio" style="height:0px;" autocomplete="false" class="authenable" name="secondauth" value="second_verification" <?php if($secondVerification=="Y") { echo "checked"; } ?>> Email
                               </li>
							   <li class="input-item password" data-error="false">
                                <input  type="radio" style="height:0px;"  autocomplete="false" class="authenable" name="secondauth" value="g_auth_enable" <?php if($googleAuthEnable=="Y") { echo "checked"; } ?>> Google Authenticator
                               </li>
							    <li class="input-item btn-item">
                                <input style="color: #000;" type="button" onclick="uncheckAll();" class="btn " value="Clear All">
                              </li>
                             
                              <li class="input-item btn-item">
                                <input type="submit" name="submitsecondlogin" class="btn confirm-btn" value="Submit">
                              </li>
							   <li class="input-item btn-item">
                               <span >Note - Please Verify Google Authenticator before enable it.</span>
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
  	 function uncheckAll(){
		 $('.authenable').prop('checked', false);
	 }
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
	 

	 
	 $("#gauth_checkbox").change(function(){
			if($(this).is(":checked")){
				
				var boxVal = "Y";	
			}
			else {
				var boxVal = "N";
			}
			
			jQuery.ajax({ 
				url: '<?php echo $this->Url->build(['controller'=>'users' , 'action'=>'change_googleauth_verification']);  ?>',
				type : 'post',
				dataType: "json",
				data : {"verification_status":boxVal},
				success: function(data) {
					 $("#return_msg").removeClass("alert alert-success alert-danger");
                    if (data.success == 1) {
                        $("#authQrCode").attr("src", data.qr);
                        $('#myModal').modal({
                            backdrop: "static",
                            show: true
                        });
                    }

                    //if (boxVal == "Y") {
                    //   $("#return_msg").addClass("alert alert-success").html("2-Step verification enabled.<br>Check your email for 2fa verification code");
                  // } 
                   if (boxVal == "N") {
                        $("#return_msg").addClass("alert alert-danger").html("2-Step verification disabled");
                    }
                    setTimeout('$("#return_msg").fadeOut();', 5000);
				}
			});
		});
		
		$("#new_password").focus(function(){
			$("#new_password_error").hide();
		});
		$("#new_password").blur(function(){
			var getNewPass = $(this).val();
			if(getNewPass!='') {
				if(!checkPass(getNewPass)){
					$("#new_password_error").show();
				}
			}
			
		});
		
		
		$("#confirm_password").focus(function(){
			$("#confirm_password_error").hide();
		});
		$("#confirm_password").blur(function(){
			var getCnfPass = $(this).val();
			var getNewPass = $("#new_password").val();
			if(getNewPass !=getCnfPass) {
				$("#confirm_password_error").show();
			}
			
		});
		
		$("#password_form").submit(function(){
			var getNewPass = $("#new_password").val();	
			if(!checkPass(getNewPass)){
				$("#new_password_error").show();
				return false;
			}
			
			var getCnfPass = $("#confirm_password").val();
			var getNewPass = $("#new_password").val();
			if(getNewPass !=getCnfPass) {
				$("#confirm_password_error").show();
				return false;
			}
			return true;
		});
  });
  function checkPass(str){
	   var re = /(?=^.{6,10}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&amp;*()_+}{&quot;:;'?/&gt;.&lt;,])(?!.*\s).*$/;
		return re.test(str);
  }
  </script>