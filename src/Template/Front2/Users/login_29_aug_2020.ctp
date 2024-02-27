<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/login2.css" />
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
<body>


<div class="container">

	<div class="login_box">

		<?php echo $this->Form->create('login');?>

		<div class="welcome">
			<?=__('Welcome') ?>
		</div>

		<div class="com_logo2">
			<img src="/wb/imgs/com_logo3.jpg" />
		</div>

           <?= $this->Flash->render() ?>

		<div class="form-field">
			<input id="user" name="username" required value="" type="text" class="input" placeholder="<?php echo (trim($getUserCountryCode)=="KR") ? "Please enter your H.P number" : "Please enter your username or email"; ?>" />

           <!-- <input id="<?php echo (trim($getUserCountryCode)=="KR") ? "phone" : "email"; ?>" name="<?php echo (trim($getUserCountryCode)=="KR") ? "phone" : "email"; ?>" type="<?php echo (trim($getUserCountryCode)=="KR") ? "tel" : "email"; ?>" class="input" placeholder="<?php echo (trim($getUserCountryCode)=="KR") ? "000-0000-0000" : __('Email') ?>" /> -->
		</div>
        <ul class="label" style="margin-top:30px">
            <li id="msg_pass_check"></li>
		</ul>
		<div class="form-field">
			<input id="pass" name="password" required value="" type="password" class="input" data-type="password" placeholder="<?=__('Please enter a password') ?>" onKeyUp="check_password()" />
		</div>

		<div class="form-submit">
			<input id="loginBtn" name="loginBtn" type="submit" class="button" value="<?=__('Sign In') ?>" onclick="validate()" />
		</div>

		<div class="form-check">
			<input id="check" type="checkbox" class="check">
			<label for="check"> <?=__('Keep me Signed in') ?></label>
		</div>

		<!-- 임시 버튼 나중에 삭제 요망 -->
		
		<!-- 임시 버튼 나중에 삭제 요망 -->

		<div class="foot-lnk">
			<a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'forgetpass']); ?>"><?=__('Forgot Password?') ?></a> | <a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'signup']); ?>"><?=__('Sign Up') ?></a>
		</div>

		</form>

	</div>

</div>

<script src="<?php echo $this->request->webroot ?>assets/js/jquery.min.js"></script>

<script>
    
$(document).ready(function(){
                   
        $('#pass').blur(function(){
          if(!checkPass($('#pass').val())){
              check_password();
              $('#msg_pass_check').html("<?=__('Password rule') ?>");
              $('#pass').addClass('input_error');

          } else{
              $('#msg_pass_check').html("");
              $('#pass').removeClass('input_error');
          }
        });



});
</script>

<script type="text/javascript">
function check_password() {
    var len = $('#pass').val().length;
    var pass = $('#pass').val();
	if (len<8 || len>30) {
		$('#pass').addClass('input_error');
	} else {
        if(!checkPass(pass)) {
            $('#pass').addClass('input_error');
            $('#err').show();
        } else{
            $('#pass').removeClass('input_error');
        }
	}
}

function validate(){
        var pass = $('#pass').val();

        if(!checkPass(pass)){
            check_password();
            $('#pass').addClass('input_error');
            $('#err').show();
            return false;
        }
        else {
            $('#pass').removeClass('input_error');
            $('#err').hide();
            return true;
        }
}

function checkPass(str){
    var re = /^(?=.*?[a-zA-Z])(?=.*?[0-9]).{8,}$/;
    return re.test(str);
}
</script>