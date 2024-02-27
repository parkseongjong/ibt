<style type="text/css">
.login_box {
	width: 750px;
	height: 950px;
	border-radius: 30px;
	background-color: #ffffff;
	text-align: center;
	margin: 41px auto 100px auto;
}
.welcome {
	padding-top: 100px;
	font-size: 30px;
	font-weight: bold;
	line-height: 1.6;
	text-align: center;
	color: #000000;
}
.com_logo2 {
	margin-top: 10px;
	margin-bottom: 50px;
}
.form-field input.input {
	width: 434px;
	height: 23px;
	font-size: 22px;
	font-weight: normal;
	line-height: 2.18;
	text-align: left;
	color: #252424;
	padding: 20px;
	margin-bottom: 23px;
	border: solid 2px #e0e0e0;
	background-color: #ffffff;
}
.form-check {
	margin-top: 30px;
	color: #585858;
	font-size: 16px;
	line-height: 2.4;
}

.form-submit {
	margin-top: 30px;
}
.form-submit input.button {
	width: 474px;
	height: 64px;
	background-color: #6738ff;
	border: 0;
	font-size: 22px;
	font-weight: bold;
	letter-spacing: normal;
	text-align: center;
	color: #ffffff;
}
.foot-lnk {
	margin-top: 110px;
	color: #686868;
	font-size: 18px;
	line-height: 2.4;
}
.foot-lnk * {
	color: #686868;
	font-size: 18px;
	line-height: 2.4;
}
</style>

<div class="container">

	<div class="login_box">
		<?php echo $this->Form->create('login');?>
		<div class="welcome">
			Welcome
		</div>

		<div class="com_logo2">
			<img src="/wb/imgs/com_logo2.jpg" />
		</div>

		<div class="form-field">
			<input id="user" name="username" required value="<?php if(!empty($username)){ echo $username; } ?>" type="text" class="input" placeholder="Please enter your ID" />
		</div>

		<div class="form-field">
			<input id="pass" name="password" required value="<?php if(!empty($password)){ echo $password; } ?>" type="password" class="input" data-type="password" placeholder="Please enter a password" />
		</div>

		<div class="form-submit">
			<input type="submit" class="button" value="Sign In" />
		</div>

		<div class="form-check">
			<input id="check" type="checkbox" class="check">
			<label for="check"> Keep me Signed in</label>
		</div>

		<div class="foot-lnk">
			Forgot ID | <a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'forgetPassword']); ?>">Forgot Password?</a> | Sign Up | Close Account
		</div>

		</form>

	</div>

</div>

<script src="<?php echo $this->request->webroot ?>assets/js/jquery.min.js"></script>

<script>
	$(document).ready(function(){
		
		$("#new_password").focus(function(){
			$("#new_password_error").hide();
		});
		$("#new_password").blur(function(){
			var getNewPass = $(this).val();
			if(getNewPass!='') {
			}
			
		});
	});
</script>