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
		<div class="welcome2">
			<?=__('Password Settings') ?>
		</div>
		<div class="com_logo5">
			<?=__('Please set a new password') ?>
		</div>
        <?php echo $this->Form->create('changepass',array('id'=>'password_form','enctype'=>'multipart/form-data','class'=>'form-horizontal form-label-left','method'=>'post'));?>
			<ul class="label" style="margin-top:30px">
				<li id="msg_pass_check"></li>
			</ul>
			<p id="" class="text-pink" >비밀번호는 영문자 / 숫자 / 특수문자 모두 혼용할 경우 8자리 이상, 두가지만 혼용할 경우 10자리 이상으로 설정해주세요</p>
			<div class="change-pw">
				<div class="form-field">
					<input id="auth_key" name="auth_key" required value="" class="input" type="number" placeholder="<?=__('Enter authentication code') ?>" maxlength="6" onkeyup="check_password(1,this.value)" onkeydown="only_number(this)"/>
					<p id="pw_error_text1" class="text-pink" style="display:none; "></p>
				</div>
				<div class="form-field">
					<input id="new_password" name="new_password"  required value="" type="password" class="input" data-type="password" data-type="password"  placeholder="<?=__('Enter a new password') ?> " onkeyup="check_password(2,this.value)" />
					<p id="pw_error_text2" class="text-pink" style="display:none; "></p>
				</div>
				<div class="form-field">
					<input id="confirm_password" name="confirm_password"  required value="" type="password" class="input" data-type="password" placeholder="<?=__('Confirm password') ?>" onkeyup="check_password(3,this.value)"/>
					<p id="pw_error_text3" class="text-pink" style="display:none;"></p>
				</div>
				<?= $this->Flash->render() ?>
				<div class="form-submit">
					<button type="button" id="submitpass" name="submitpass" class="button" onclick="validate()"><?=__('Submit') ?> </button>
				</div>
			</div>
		</form>
    </div>
</div>
<script>
	let pass_same_chk = false; // 패스워드 - 패스워드 확인 같은지 체크
	let pass_chk = false; // 특수문자, 영문자, 숫자 포함 되어 있는지 체크
	let origin_pass_chk = false; // 인증코드
	const regex = new RegExp("^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,20}$");
	const pattern1 = /[0-9]/;
    const pattern2 = /[a-zA-Z]/;
    const pattern3 = /[~!@\#$%<>^&*]/;     // 원하는 특수문자 추가 제거
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
		if(check_type == 'origin_pass_chk'){ // 인증코드
			if(!origin_pass_chk){
				$('#pw_error_text1').html('인증코드 6자리 이상 입력해주세요').show();
			} else {
				$('#pw_error_text1').html('').hide();
			}			 
		}
		if(check_type == 'pass_chk'){
			if(!pass_chk){ // 특수 문자 관련 메세지
				$('#pw_error_text2').html('비밀번호 유의사항을 확인해주세요').show();
			} else {
				$('#pw_error_text2').html('').hide();
			}			 
		}
		if(check_type == 'pass_same_chk'){ // 패스워드 불일치
			if(!pass_same_chk){
				$('#pw_error_text3').html('비밀번호가 일치하지 않습니다').show();
			} else {
				$('#pw_error_text3').html('').hide();
			}
		}
	}
	function validate(){
		if(!pass_chk){
			pass_error('pass_chk');
			return;
		}
		if(!pass_same_chk){
			pass_error('pass_same_chk');
			return;
		}
		$('#password_form').submit();
	}
	function only_number(obj) {
		$(obj).keyup(function(){
			$(this).val($(this).val().replace(/[^0-9]/g,""));
		});
	}
</script>