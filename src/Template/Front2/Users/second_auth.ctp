<?php echo $this->Form->create('',['id'=>'login_form','method'=>'POST']);?>
<input type="hidden" id="loginTokenUserId" name="loginTokenUserId" value="<?=$this->Encrypt($this->request->session()->read('loginTokenUserId'));?>">
<div class="content-container">
  <div class="content-inner">
    <!--<img src="/wb/imgs/com_logo2.png" alt="logo" class="second-login-coinibt-logo">-->
      <img src="/wb/imgs/smbit/smbit_logo.jpg" alt="logo" class="second-login-coinibt-logo">
    <input type="password" name="otp_number"  class="second-login-input-password" maxlength="6" id="otp_number" onkeydown="only_number(this)" placeholder="Please enter the OTP number in 6 digits." pattern="[0-9]*" inputmode="numeric">
	<p id="password_error_text" class="text-pink" style="display:none;">Please enter the OTP number in 6 digits</p>
	<?= $this->Flash->render(); ?>
    <button type="button" class="second-login-button" id="login_btn" onClick="onClickLoginButton()">Login</button>
	<img id="ajax_loader" src="/ajax-loader.gif" style="display:none;"/>
    <div class="login-form-bottom-area">
      <!--<div class="login-form-bottom-left">OTP가 없다면?</div>-->
        <div class="login-form-bottom-left">Without Google OTP?</div>
      <!--<a href="/front2/users/otpinfo" class="login-form-bottom-right">OTP 발급하러가기</a>-->
        <a href="/front2/users/otpinfo" class="login-form-bottom-right">I'm going to issue Google OPT</a>
    </div>
    <div class="second-login-footer">
      <!--<a href="#" class="second-login-footer-item">아이디 찾기</a>
      <span class="second-login-footer-divider">|</span>-->
      <!--<a href="<?php /*echo $this->Url->Build(['controller'=>'users','action'=>'forgetpass']); */?>" class="second-login-footer-item">비밀번호 찾기</a>-->
        <a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'forgetpass']); ?>" class="second-login-footer-item">Finding a password</a>
      <span class="second-login-footer-divider">|</span>
      <!--<a href="<?php /*echo $this->Url->Build(['controller'=>'users','action'=>'signup']); */?>" class="second-login-footer-item">회원가입</a>-->
        <a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'signup']); ?>" class="second-login-footer-item">join membership</a>
    </div>
  </div>
</div>
<?php echo $this->Form->end();?>
<script>
	const otp = document.querySelector('#otp_number');
	$(document).ready(function(){
		otp.focus();
		setTimeout(function() {
			$('#success-msg').fadeOut('fast');
		}, 3000);
		var input = document.getElementById("otp_number");
			input.addEventListener("keyup", function(event) {
				if (event.keyCode === 13) {
					event.preventDefault();
					onClickLoginButton();
				}
			});
	});
	function onClickLoginButton() {
	    const userPassword = otp.value;
	    if(userPassword == '' || userPassword.length < 6){
			$('#password_error_text').show();
			otp.focus();
			$('#ajax_loader').hide();
			$('#login_btn').show();
			return;
		}
		$('#password_error_text').hide();
		$('#login_btn').hide();
		$('#ajax_loader').show();
		$('#login_form').submit();
	}
	function only_number(obj) {
		$(obj).keyup(function(){
			$(this).val($(this).val().replace(/[^0-9]/g,""));
		});
	}
</script>

<style>
.wrapper {
  height: initial;
}
.content-container {
  width: 100%;
  height: 100%;
  color: #000;
  padding: 68px 0 100px;
  display: flex;
  justify-content: center;
}
.content-container .content-inner {
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: #fff;
  padding: 100px 120px 40px;
  width: 100%;
  max-width: 600px;
  box-sizing: border-box;
  border-radius: 30px;
}
.content-container .content-inner .second-login-coinibt-logo {
  margin-bottom: 70px;
}
.content-container .content-inner .second-login-input-password {
  font-size: 16px;
  padding: 15px;
  background-color: #fff;
  margin-bottom: 15px;
  border: 2px solid #e0e0e0;
  width: 100%;
  display: block;
  box-sizing: border-box;
}
.content-container .content-inner .second-login-button {
  font-size: 16px;
  padding: 15px;
  background-color: #fff;
  margin-bottom: 15px;
  border: 2px solid #e0e0e0;
  width: 100%;
  cursor: pointer;
  background-color: #6738ff;
  color: #fff;
  font-weight: 700;
  border: none;
  margin-bottom: 25px;
}
.content-container .content-inner .login-form-bottom-area {
  display: flex;
  justify-content: space-between;
  margin-bottom: 300px;
  width: 100%;
  font-size: 14px;
}
.content-container .content-inner .login-form-bottom-area .login-form-bottom-left {
  color: #9a9a9a;
}
.content-container .content-inner .login-form-bottom-area .login-form-bottom-right {
  color: #6738ff;
  border-bottom: 2px solid #6738ff;
  padding-bottom: 3px;
}
.content-container .content-inner .second-login-footer {
  font-size: 20px;
  color: #585858;
}
.content-container .content-inner .second-login-footer .second-login-footer-item {}
.content-container .content-inner .second-login-footer .second-login-footer-divider {}

@media (max-width: 990px) {
  .wrapper {}
  .content-container {}
  .content-container .content-inner {
    padding: 100px 15px 40px;
  }
  .content-container .content-inner .second-login-coinibt-logo {}
  .content-container .content-inner .second-login-input-password {}
  .content-container .content-inner .second-login-button {}
  .content-container .content-inner .login-form-bottom-area {
    font-size: 14px;
    margin-bottom: 170px;
  }
  .content-container .content-inner .login-form-bottom-area .login-form-bottom-left {}
  .content-container .content-inner .login-form-bottom-area .login-form-bottom-right {}
  .content-container .content-inner .second-login-footer {
    font-size: 14px;
  }
  .content-container .content-inner .second-login-footer .second-login-footer-item {}
  .content-container .content-inner .second-login-footer .second-login-footer-divider {}
}
</style>