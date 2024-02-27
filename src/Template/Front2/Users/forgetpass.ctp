<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/login2.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->request->webroot ?>assets/flag/build/css/intlTelInput.css">
<style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0;
    }
</style>
<div class="container">
	<div class="login_box">
		<?php echo $this->Form->create('',['method'=>'post','id'=>'frm']);?>
		    <div class="welcome">
			    <?=__('Find Password') ?>
		    </div>
		    <div class="com_logo2">
			    <?=__('Find pass real') ?>
		    </div>
            <div id="show_msg"></div>
            <div id="return_msg"></div>
		    <div class="form-field" style="margin: 35px 18%;" id="phone_number_area">
                <input id="phone" name="phone" required value="<?php if ( !empty($phone_no) ) echo $phone_no; ?>" type="tel" class="input"  style="vertical-align: top; display:inline-block;" onkeydown="only_number(this)" />
                <input id="sendCodeBtn" name="sendCodeBtn" type="button" class="button" style="margin-top: 10px;" value="<?=__('Send code') ?>"  />
		    </div>
			
			<div class="form-field" style="margin: 35px 18%; display:none;" id="auth_code_area" >
				<input type="tel" id="auth_code" name="auth_code" value="" class="input" maxlength="6">
				<input type="button" id="auth_code_btn" class="button" value="인증하기" onclick="check_auth_code()" />
				<p class="text-pink" id="error-text" style="display:none;"></p>
		    </div>
			<div class="form-field" style="margin: 35px 18%;">
	            <div id="send_msg_resp" class="alert" style="display:none;"></div>
			</div>
		    <div class="form-submit">
		        <img src="<?= $this->request->webroot ?>ajax-loader.gif" style="display:none;" id="send_sms_check_loader">
            </div>
		    <div class="foot-lnk" style="visibility: hidden">
			    <a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'forgetid']); ?>"><?=__('Forgot ID') ?></a>
		    </div>
        <?php echo $this->Form->end();?>
	</div>
</div>
<input type="hidden" id="ipinfo_token" value="<?=$ipinfo_token;?>">
<script>
	const ipinfo_token = $('#ipinfo_token').val(); // 2021-07-22 이충현 2f11973e5039d5 => 4f91a06d11ac51 으로 수정 
	$(document).ready(function(){
		  var telInput = $("#phone"),
				errorMsg = $("#error_phone");
				telInput.intlTelInput({
				initialCountry: "auto",
				preferredCountries : ['ca','jp','us','cn'],
				geoIpLookup: function(callback) {
					$.get('https://ipinfo.io/json?token='+ipinfo_token, function() {}, "jsonp").always(function(resp) { // 6ad007f53defcc, 52d3bade00ca48
						var countryCode = (resp && resp.country) ? resp.country : "";
						callback(countryCode);
					});
				},
				utilsScript: "<?php echo $this->request->webroot ?>assets/flag/build/js/utils.js" // just for formatting/placeholders etc
			});
		
		
		$("#sendCodeBtn").click(function() {
			let countryData = $("#phone").intlTelInput("getSelectedCountryData");
			let phoneNumber = $("#phone").val();
			let countryCode = countryData.dialCode;

			$("#error_phone").html("");
			if($("#phone").val().length > 6 && $("#phone").val().length < 16){
				if ($.trim(telInput.val())) {
					if (telInput.intlTelInput("isValidNumber")) {
						$.ajax({
							url:'/front2/users/sendsmscode',
							type:'POST',
							data :{"phone":phoneNumber, "country":countryCode,"type":'forgetpass'},
							dataType:'JSON',
							beforeSend: function(xhr){
								xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
								$("#sendCodeBtn").hide();
								$('#send_sms_check_loader').show();
							},
							success:function(resp){
								$("#sendCodeBtn").show();
								$('#send_sms_check_loader').hide();
								if(resp !== undefined && resp !== null && resp !== ''){
									if(resp.success == "false"){
										$("#phone").addClass('input_error');
										$("#error_phone").html(resp.message);
										errorMsg.removeClass("input_complete");
										$("#send_msg_resp").html(resp.message).removeClass("alert-danger").removeClass("alert-success").addClass("alert-danger").show();
										return false;
									} else if (resp.success == 'dormant') {
										window.location.href = "/front2/Users/undormant";
									} else if( resp.success == 'true'){
										$("#sendCodeBtn").attr('disabled', true).css({'pointer-events': 'auto','cursor':'pointer'});
										$("#phone").removeClass('input_error');
										$("#phone").addClass('input_complete');
										$("#send_msg_resp").html(resp.message).removeClass("alert-danger").removeClass("alert-success").addClass("alert-success").show();
										//window.location.href = "/front2/Users/changepass";
										$('#auth_code_area').show();
										$('#phone_number_area').hide();
										$('#auth_code').focus();
									}
								} else {
									errorMsg.removeClass("input_complete");
								}
							}
						});
					}else{
						$("#phone").addClass('input_error');
						$("#error_phone").html("Please enter correct number");
					}
				}
			} else{
				$("#phone").addClass('input_error');
				$("#error_phone").html("The number you entered is invalid");
			}
		});


		var input = document.getElementById("phone");

		input.addEventListener("keyup", function(event) {
			// Number 13 is the "Enter" key on the keyboard
			if (event.keyCode === 13) {
				// Cancel the default action, if needed
				event.preventDefault();
				// Trigger the button element with a click
				document.getElementById("sendCodeBtn").click();
			}
		});
		
	});


	function hideMsgWindow() {
		if(document.getElementById('_outer_box')) $('#_outer_box').hide();
		$('#_hidden_frame').hide();
	}
	function check_auth_code(){
		let phone = $('#phone').val();
		let authcode = $('#auth_code').val();
		if(phone == '' || authcode == ''){
			modal_alert('경고','필수값이 누락되었습니다.');
			return;
		}
		$.ajax({
			url:'/front2/users/smsCodeCheck',
			type:'POST',
			data :{"phone":phone, "authcode":authcode},
			dataType:'JSON',
			beforeSend: function(xhr){
				xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
				$('#send_sms_check_loader').show();
				$('#auth_code_btn').hide();
			},
			success:function(resp){
				$('#send_sms_check_loader').hide();
				if(resp.success == "false"){
					$('#send_msg_resp').hide();
					$('#error-text').html(resp.message).show();
					$('#auth_code_btn').show();
					return;
				}else if( resp.success == 'true'){
					window.location.href = "/front2/Users/newChangePassword";
				}
			}
		});
	}

</script>
<script src="<?php echo $this->request->webroot ?>assets/flag/build/js/utils.js"></script>
<script src="<?php echo $this->request->webroot ?>assets/flag/build/js/intlTelInput.js"></script>