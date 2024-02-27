
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Globex</title>

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
	margin-right:15px;
	padding-bottom:5px;
	margin:0 15px 10px 0;
	display:inline-block;
	border-bottom:2px solid transparent;
}
.login-html .sign-in:checked + .tab,
.login-html .sign-up:checked + .tab{
	color:#fff;
	border-color:#fff;
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
.login-form .group input[data-type="password"]{
	text-security:circle;
	-webkit-text-security:circle;
}
.login-form .group .label{    text-align: left;

	font-size: 13px;
    margin-bottom: 6px;
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

<!--<div class="alert alert2">
<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">English</a></li>
    <li><a data-toggle="tab" href="#menu1">Russian</a></li>
  </ul>
<div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      <img src="<?php //echo $this->request->webroot ?>popop1.png" class="img-responsive">
    </div>
    <div id="menu1" class="tab-pane fade">
     <img src="<?php //echo $this->request->webroot ?>popop2.png" class="img-responsive">
    </div>
  </div>

</div>-->

<div class="login-wrap">
<?=$this->Flash->render();?>
<!--<div  class="alert alert-danger"> Exchange is under maintenance.
</div>-->
	<div class="login-html">
	<ul class="tab-content">
    <li class="active"><a data-toggle="tab" href="#home">Sign In</a></li>
    <li><a data-toggle="tab" href="#menu1">Sign Up</a></li>
  </ul>
		<div class="login-form tab-content">
		
			
			<div  class="sign-in-htm tab-pane fade in active" id="home">
			<?php echo $this->Form->create('login');?>
				<div class="group loginbox">
					<label for="user" class="label">Username</label>
					<input id="user" name="username" value="<?php if(!empty($username)){ echo $username; } ?>" type="text" class="input">
				</div>
				<div class="group loginbox">
					<label for="pass" class="label">Password</label>
					<input id="pass" name="password" value="<?php if(!empty($password)){ echo $password; } ?>" type="password" class="input" data-type="password">
				</div>
				<div class="group loginbox">
					<input id="check" type="checkbox" class="check" checked>
					<label for="check"> Keep me Signed in</label>
				</div>
				
				<?php if($secondVerification == 1) { ?>
				<div class="form-group has-feedback">
					<input type="text" name="second_verification" class="form-control" placeholder="Verification Code">
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<?php } ?>
				
				
				<?php if($googleAuthVerification == 1) { ?>
				<div class="form-group has-feedback">
					<input type="text" name="google_verification" class="form-control" placeholder="Google Authenticator Code">
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<?php } ?>
				
			
				<div class="group">
					<input type="submit" class="button" value="Sign In">
				</div>
				<div class="foot-lnk">
					<a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'forgetPassword']); ?>">Forgot Password?</a>
					<!--<a style="float:right;" href="/support">Support</a>-->
				</div></form>
				
				
			</div>
			
			 
			<div class="sign-up-htm tab-pane fade " id="menu1"> <?php  echo $this->Form->create('',array('url'=>'front/register','class'=>'form-horizontal form-label-left','novalidate','method'=>'post','id'=>'register_form'));?>
				<div class="group">
					<label for="user" class="label">Referral Code</label>
					<input id="user" name="referral_code_get"  type="text" class="input">
				</div>
				<div class="group">
					<label for="user" class="label">Username</label>
					<input id="user" name="username"  type="text" class="input">
				</div>
				<div class="group">
					<label for="pass" class="label">Password</label>
					<input id="pass" name="password" type="password"  class="input" data-type="password">
				</div>
				<div class="group">
					<label for="pass" class="label">Reenter Password</label>
					<input id="pass" name="password" type="password"  class="input" data-type="password">
				</div>
				
				<div class="group">
					<label for="pass" class="label">Email Address</label>
					<input id="pass" name="email" type="text" class="input">
				</div>
			
				
				<div class="group">
					<input type="submit" class="button" value="Sign Up">
				</div>
				
				<div class="foot-lnk">
				<a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'forgetPassword']); ?>">	Already Member?</a>
				</div>
			</div>
            <div style="clear:both;"></div>
		</div>
	</div>
</div>
</body>
</html>
<?php if($secondVerification == 1 || $googleAuthVerification == 1) { ?>
<style>
.loginbox{ display:none; }
</style>
<?php } ?>

<script src="<?php echo $this->request->webroot ?>assets/js/jquery.min.js"></script>
<script src="<?php echo $this->request->webroot ?>assets/js/bootstrap.js"></script>

