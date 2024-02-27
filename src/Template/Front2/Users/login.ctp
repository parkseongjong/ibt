<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/login2.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/noppa/text-security/master/dist/text-security.css">
<style>
.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}
.alert {
    position: relative;
    padding: .75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: .25rem;
}
.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}
</style>
<style>

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0;
    }

    body{    font-size: 15px;}

    .login-wrap{    color: #000;overflow: hidden;
        border-radius: 8px;
        width:100%;
        margin: 20px auto;
        height: 10%;
        max-width:450px;
        position:relative;
        box-shadow:0 12px 15px 0 rgba(0,0,0,.24),0 17px 50px 0 rgba(0,0,0,.19);
    }



    .alert .close { color: #0a0a0a; opacity: 1;}
    .foot-lnk{
        text-align:center;
    }
    .alert {    border: none;
        background-color: none;
        color: white;margin-bottom: 0;
    }

    .closebtn {
        margin: 13px;
        color: #0d324f;
        font-weight: bold;
        float: right;
        font-size: 22px;
        line-height: 20px;
        cursor: pointer;
        transition: 0.3s;
    }
    .alert2{    position: fixed;
        z-index: 9;
        width: 70%;
        font-size: 20px;
        left: 15%;
        top: 5%;
        border-radius: 10px;
        overflow: hidden;
    }
    .alert img{ width:100%;}

    .alert .nav-tabs>li.active>a, .alert .nav-tabs>li a:hover{ color:#fff; background:#200034;}
    .alert .nav-tabs>li a{ background:#d2cece; color:#000;    text-decoration: none;}
    .alert .nav-tabs>li{margin: -1px;}
    @media screen and (max-width: 600px) {
        .alert2{ width: 98%; left:2%;top: 10%;}
        .login-wrap {max-width: 100%;    border-radius: 0;    margin: 0;    padding: 10px;}
        .login-html {  padding: 30px 20px;   }
        .login-html .tab-content li { font-size: 20px;}
    }
</style>
<div class="container">
	<div class="login_box">
		<?php echo $this->Form->create('login',array('id'=>'login_frm', 'name'=>'login_frm'));?>
        <input type="hidden" name="dev_id" id="dev_id" value="" />
        <input type="hidden" name="dev_id2" id="dev_id2" value="" />
        <input type="hidden" name="dev_id3" id="dev_id3" value="" />
        <input type="hidden" name="dev_use" id="dev_use" value="" />
        <input type="hidden" name="onesignal_id" id="onesignal_id" value="" />
        <input type="hidden" name="onesignal_id2" id="onesignal_id2" value="" />
		<div class="welcome">
			<?=__('Welcome') ?>
		</div>
		<div class="com_logo2">
			<!--<img src="/wb/imgs/com_logo3.jpg" />-->
            <img src="/wb/imgs/smbit/smbit_logo.jpg">
		</div>
        <div id="success-msg" style="width: 50%; text-align: center; alignment: center; position: center; margin-left: 25%; margin-bottom: 2%">  <?= $this->Flash->render() ?> </div>
		<div class="form-field">
            <!-- 정규식 다시 작업  -->
			<input id="user" name="username" required value="<?=$this->request->data('username')?>" type="number" class="input" placeholder="<?php echo (trim($getUserCountryCode)=="KR") ? __("Please enter your H.P number") : __('Please enter your username or email'); ?>" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>

            <input type="hidden" name="deviceID"  id="deviceID"/>
           <!-- <input id="<?php echo (trim($getUserCountryCode)=="KR") ? "phone" : "email"; ?>" name="<?php echo (trim($getUserCountryCode)=="KR") ? "phone" : "email"; ?>" type="<?php echo (trim($getUserCountryCode)=="KR") ? "tel" : "email"; ?>" class="input" placeholder="<?php echo (trim($getUserCountryCode)=="KR") ? "000-0000-0000" : __('Email') ?>" /> -->
		</div>
        <ul class="label" style="margin-top:20px">
            <li id="msg_pass_check"></li>
		</ul>
		<div class="form-field">
			<input id="pass" name="password" required value="" type="password" class="input" data-type="password"  placeholder="<?=__('Please enter a password') ?>" />
		</div>

		<div class="form-submit">
		<?php 
			$onclick_function = 'login_chk()';
			if($ServerCheckValue == 'Y' && $this->check_ip() == 'fail') {
				$onclick_function = "openWin('modal_alert')";
			}
		?>
		<button id="loginBtn" type="button" class="button" onclick="<?=$onclick_function;?>" ><?=__('Sign In')?></button>
		<img id="ajax_loader_login" src="/webroot/ajax-loader.gif" style="display:none;"  />
		</div>
		<div class="foot-lnk">
 			<a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'forgetpass']); ?>"><?=__('Forgot Password?') ?></a> |
            <?php
            if($_SERVER["REMOTE_ADDR"] == '112.171.120.140' || $_SERVER["REMOTE_ADDR"] == '62.122.142.18'){
            ?>
            <a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'signup']); ?>"><?=__('Sign Up') ?></a>
            <?php } else {?>
            <a href="#" onclick="retrun_page()">Sign Up</a>
            <?php } ?>
		</div>
		</form>
	</div>
</div>
<script>
    function retrun_page(){
        alert('특정금융거래정보법에 의거하여 잠시 서비스를 중지합니다.');
        return;
    }
</script>
<input type="hidden" name="user_status" id="user_status" value="<?=$user_status;?>">
<script>
	$(document).ready(function(){
		user_status_check();
	});
	function user_status_check(){
		const user_status = $('#user_status').val();
		if(user_status != 'A'){
			error_msg(user_status);
		}
	}
	function error_msg(type){
		if(type == 'B'){
			modal_alert("<?=__('Alert')?>","<?=__('Login Block Message')?>");
		}
	}
	function login_chk(){
		//if(confirm_ip()){
			$('#loginBtn').hide();
			$('#ajax_loader_login').show();
			$('#login_frm').submit();
		//}
	}

	$(document).ready(function(){
		setTimeout(function() {
			$('#success-msg').fadeOut('fast');
		}, 3000);
		var input = document.getElementById("pass");
		var inputs = document.getElementById("user");
			input.addEventListener("keyup", function(event) {
				// Number 13 is the "Enter" key on the keyboard
				if (event.keyCode === 13) {
					// Cancel the default action, if needed
					event.preventDefault();
					// Trigger the button element with a click
					document.getElementById("loginBtn").click();
				}
			});
			inputs.addEventListener("keyup", function(event) {
				// Number 13 is the "Enter" key on the keyboard
				if (event.keyCode === 13) {
					// Cancel the default action, if needed
					event.preventDefault();
					// Trigger the button element with a click
					document.getElementById("loginBtn").click();
				}
			});
	});
</script>