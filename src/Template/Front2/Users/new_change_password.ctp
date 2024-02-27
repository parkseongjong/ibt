<!DOCTYPE html>
<!-- saved from url=(0057)https://xbootstrap.com/demo/crypto/cryptoapp/index-6.html -->
<html lang="en" style="" class="js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths">
<head>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<meta name="description" content="Coin IBT is a fast and secure platform that makes it easy to buy, sell, and store cryptocurrency like Bitcoin, Ethereum, and more.">
<meta name="keywords" content="crypto, Bootstrap, bitcoins, ethereum, dogecoin, iota, ripple, siacoin, exchange, trading platform, crypto trading">
<meta name="author" content="">
<link rel="icon" href="<?php echo $this->request->webroot?>assets/images/favicon2.ico" type="image/x-icon" />
<title>COIN IBT Exchange</title>
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/font-awesome.css" />
<!-- <link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/animate+animo.css" /> -->
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/csspinner.min.css" />
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/app.css?id=<?php echo time(); ?>" />
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/new.css" />
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/new_customer.css" />
<script src="<?php echo $this->request->webroot ?>assets/html/js/jquery.js"></script>
<script src="<?php echo $this->request->webroot ?>assets/html/js/bootstrap.js"></script>
<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/login2.css?id=<?php echo time(); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->request->webroot ?>assets/flag/build/css/intlTelInput.css">
<style>
	@media (max-width: 990px) {
	  .change-pw .form-submit button.button {
		width:280px;
	  }
	}
	#myModalOTP .modal-content .form-group{padding: 0 20px 20px 20px;}
	#myModalOTP .modal-content .form-group > input {padding:15px;width:80%;}
</style>
<body>
	<?php echo $this->Form->create('',array('id'=>'password_form','class'=>'form-horizontal form-label-left','method'=>'post'));?>
	<div class="container">
		<div class="login_box">
				<div class="welcome2">
					<?=__('Password Settings') ?>
				</div>
				<div class="com_logo5">
					<?=__('Please set a new password') ?>
				</div>
				<p id="" class="text-pink" ><?=__('Password Info')?></p>
				<input type="hidden" id="type" name="type" value="<?=$type;?>">
				<div class="change-pw mt-xl" >
					<div class="form-field" <?php if($type == 'exclude_old_password') echo 'style="display:none;"';?>> <!-- 본인 인증에서 넘어왔을 경우에는 올드패스워드 불필요 -->
						<input id="password" name="old_password" class="input" type="password" maxlength="20"  placeholder="<?=__('Please enter a password');?>" onkeyup="check_password(1,this.value)"  />
						<p id="pw_error_text1" class="text-pink" style="display:none;"><?=__('Please enter a password');?></p>
					</div>
					<div class="form-field">
						<input id="new_password" name="new_password"  required value="" maxlength="20" type="password" class="input" data-type="password" placeholder="<?=__('Please set a new password');?>" onkeyup="check_password(2,this.value)"  />
						<p id="pw_error_text2" class="text-pink" style="display:none; "><?=__('Please check the password notes');?></p>
					</div>
					<div class="form-field">
						<input id="confirm_password" name="confirm_password" required value="" maxlength="20" type="password" class="input" data-type="password"  placeholder="<?=__('Please confirm your password');?>" onkeyup="check_password(3,this.value)"/>
						<p id="pw_error_text3" class="text-pink" style="display:none;"><?=__('New and confirm passwords are not same');?></p>
					</div>
					<?= $this->Flash->render() ?>
					<div class="form-submit">
						<button type="button" class="button" onclick="validate()" id="form_sumbit_btn"><?=__('Change');?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal -->
	<div id="myModalOTP" class="modal fade" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered bd-example-modal-sm">
			<!-- Modal content-->
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h5 class="modal-title">OTP 를 입력해주세요</h5>
					</div>
					<div class="modal-body">
						<div class="form-group" >
							<input type="number" maxlength="6" id="otp_number" name="otp_number" placeholder="6자리를 입력해주세요" onkeydown="only_number(this)" onkeyup="check_otp(this)"/>
							<p id="pw_error_text4" class="text-pink" style="display:none;">OTP 번호를 6자리로 입력해주세요</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" onclick="otp_submit()" id="otp_submit_btn">확인</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</form>
</body>
<script>
	$(function(){
		//$('#myModalOTP').modal('show');
	})
	let pass_same_chk = false; // 패스워드 - 패스워드 확인 같은지 체크
	let pass_chk = false; // 특수문자, 영문자, 숫자 포함 되어 있는지 체크
	let origin_pass_chk = false; // 현재 비밀번호 확인
	let otp_chk = false; // otp 체크
	const regex = new RegExp("^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,20}$");
	const pattern1 = /[0-9]/;
    const pattern2 = /[a-zA-Z]/;
    const pattern3 = /[~!@\#$%<>^&*]/;     // 원하는 특수문자 추가 제거
	const type = $('#type').val();
	/* 비밀번호 확인 */
	function check_password(check_type,value){
		if(check_type == 1){
			if(value.length < 6){
				origin_pass_chk = false;
			} else {
				origin_pass_chk = true;
			}
			return pass_error('origin_pass_chk');
		}
		if(check_type == 2){
			pass_same_chk = false;
			if(value.length < 8){
				pass_chk = false;
			} else if (value.length >= 8 && value.length < 10){
				if (!regex.test(value)) {
					pass_chk = false;
				} else {
					pass_chk = true;
				}
			} else if(value.length >= 10){
				 if( (!pattern1.test(value) && !pattern2.test(value) ) || (!pattern1.test(value) && !pattern3.test(value)) || (!pattern2.test(value) && !pattern3.test(value))) {
					pass_chk = false;
				 } else {
					pass_chk = true;
				 }
			}
			return pass_error('pass_chk');
		}
		if(check_type == 3){
			pass_same_chk = !(value != $('#new_password').val());
			return pass_error('pass_same_chk');
		}
	}
	/* 비밀번호 에러 메세지 */
	function pass_error(check_type){
		if(check_type == 'origin_pass_chk'){ // 현재 비밀번호
			if(!origin_pass_chk){
				$('#pw_error_text1').show();
			} else {
				$('#pw_error_text1').hide();
			}			 
		}
		if(check_type == 'pass_chk'){
			if(!pass_chk){ // 특수 문자 관련 메세지
				$('#pw_error_text2').show();
			} else {
				$('#pw_error_text2').hide();
			}			 
		}
		if(check_type == 'pass_same_chk'){ // 패스워드 불일치
			if(!pass_same_chk){
				$('#pw_error_text3').show();
			} else {
				$('#pw_error_text3').hide();
			}
		}
		if(check_type == 'otp_chk'){
			if(!otp_chk){
				$('#pw_error_text4').show();
			} else {
				$('#pw_error_text4').hide();
			}
		}
	}
	function check_otp(obj){
		if(obj.value.length < 6){
			otp_chk = false;
		} else {
			otp_chk = true;
			$(obj).val($(obj).val().slice(0, 6));
		}
		return pass_error('otp_chk');
	}
	function validate(){
		if(type == 'include_old_password'){
			if(!origin_pass_chk){
				pass_error('origin_pass_chk');
				return;
			}
		}
		if(!pass_chk){
			pass_error('pass_chk');
			return;
		}
		if(!pass_same_chk){
			pass_error('pass_same_chk');
			return;
		}
		if(type == 'include_old_password'){
			$('#myModalOTP').modal('show');
			$('#otp_number').focus();
			return;
		}
		$('#form_sumbit_btn').hide();
		$('#password_form').submit();
	}
	function otp_submit(){
		if(!otp_chk){
			pass_error('otp_chk');
			return;
		}
		$('#otp_submit_btn').hide();
		$('#password_form').submit();
	}
	function only_number(obj) {
		$(obj).keyup(function(){
			$(this).val($(this).val().replace(/[^0-9]/g,""));
		});
	}
</script>
</body>
</html>