
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Untitled Document</title>
<style>

body{    font-size: 15px;}

.login-wrap{    color: #000;overflow: hidden;
    border-radius: 8px;
	width:100%;
	margin: 50px auto;
	max-width:500px;
	position:relative;
	box-shadow:0 12px 15px 0 rgba(0,0,0,.24),0 17px 50px 0 rgba(0,0,0,.19);
}
.login-html{
	width:100%;
	background:#fff;
	padding:30px 40px;}
.login-html .tab-content{ padding:0; }
.login-html .tab-content li{float: left;
    margin-right: 10px;
    font-size: 25px;
   }
	
.login-html .tab-content li a{ color:#000;text-decoration: none;}
.login-html .tab-content li	{padding-bottom: 5px;    margin: 0 30px 10px 0;    list-style: none;}
.login-html .tab-content li.active{ 
    
    border-bottom: 2px solid #000;
}
ul.tab-content {  height: 50px;}
.login-form .group .button{
	text-transform:uppercase;
}
.login-html .tab{
	font-size:22px;
	
	padding-bottom:0px;
	margin:0;
	display:inline-block;
	border-bottom:2px solid transparent;
}

.login-form{    margin-top: 20px;

}
.login-form .group{
	margin-bottom:15px;
}
.login-form .group .label,
.login-form .group .input,
.login-form .group .button{
	width:100%;
	color:#191919;
	display:block;
}
.login-form .group .input{border: 1px solid #ccc;
	padding:10px 20px;
	border-radius:5px;
	background:#fff;}
.login-form .group .button{
	border:none;
	padding:10px 20px;
	border-radius:5px;
	background:#fff;
}
.login-form .group input[data-type="password"]{
	text-security:circle;
	-webkit-text-security:circle;
}
.login-form .group .label{    text-align: left;

	font-size: 13px;
    margin-bottom: 6px;
}
.login-form .group .button{
	    background: #0d68dd;
    cursor: pointer;
    color: #fff;
}
.login-form .group label .icon{
	width:15px;
	height:15px;
	border-radius:2px;
	position:relative;
	display:inline-block;
	background:rgba(255,255,255,.1);
}

.login-form .group label .icon:before{
	left:3px;
	width:5px;
	bottom:6px;
	transform:scale(0) rotate(0);
}

.login-form .group .check:checked + label{
	color:#191919;
}
.login-form .group .check:checked + label .icon{
	background:#1161ee;
}



.alert .close { color: #fff; opacity: 1;}
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
	.login-html .sign-in, .login-html .sign-up, .login-form .group .check {
    display: none;
}
.alert img{ width:100%;}
.alert .nav-tabs{ background:#fff;}
.alert .nav-tabs>li.active>a, .alert .nav-tabs>li a:hover{ color:#fff; background:#200034;}
.alert .nav-tabs>li a{ background:#d2cece; color:#000;    text-decoration: none;}
.alert .nav-tabs>li{margin: -1px;}
@media screen and (max-width: 600px) {
.alert2{ width: 98%; left:2%;top: 10%;}
.login-wrap {max-width: 100%;    border-radius: 0;    margin: 0;    padding: 10px;}
.login-html {  padding: 30px 20px;   }	
.login-html .tab-content li { font-size: 20px;}
}
</style></head>
<body>

<div class="login-wrap">
<?=$this->Flash->render();?>
	<div class="login-html">
	
		<input id="tab-2" type="radio" name="tab" class="sign-up" checked><label for="tab-2" class="tab">Sign Up</label>
		
		<div class="login-form">
			
			  <?php  echo $this->Form->create($user,array('url'=>'front/register','class'=>'form-horizontal form-label-left','method'=>'post','id'=>'register_form'));?>
			<div class="sign-up-htm">
				<div class="group">
					<label for="user" class="label">Referral Code</label>
					<input id="user" name="referral_code_get" value="<?php echo $getReferralCodeUrl; ?>"  type="text" class="input referral_code_get" <?php echo $referralBoxReadOnly; ?>>
				</div>
				<div class="group">
					<label for="user" class="label">Username</label>
					<input id="user" name="username" required type="text" class="input">
				</div>
				<div class="group">
					<label for="pass" class="label">Password</label>
					<input id="new_password" required name="password" type="password"   class="input" data-type="password">
					<span id="new_password_error" style="display:none;color:#FF0000;" >Password should have atleast 1 small-case letter, 1 Capital letter, 1 digit, 1 special character and the length should be between 6-10 characters.</span>
				</div>
				<div class="group">
					<label for="pass" class="label">Confirm Password</label>
					<input id="confirm_password" required name="password" type="password"  class="input" data-type="password">
					<span id="confirm_password_error" style="display:none;color:#FF0000;">Password and confirm password should be same.</span>
				</div>
				  
				<div class="group">
					<label for="pass" class="label">Email Address</label>
					<input id="pass" name="email" required type="email" value="<?php echo $getEmail; ?>" class="input">
				</div>
				
				<div class="group">
					<input id="check" required type="checkbox" class="checks">
					<label for="check" > I'm over age 18 and I agree  to  Globeex's Terms</label>
				</div>
			
				<div class="group">
					<input type="submit" class="button" value="Sign Up">
				</div>
			
				<div class="foot-lnk">
				<a href="/front"> Already Member?</a>
				</div>
			</div>
            <div style="clear:both;"></div>
		</div>
	</div>
</div>
</body>
</html>

<script>
	$(document).ready(function(){
		
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
		
		$("#register_form").submit(function(){
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
