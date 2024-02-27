
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Untitled Document</title>

<style>
body{	
margin:0;font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 15px;

}
.alert{ margin-bottom:0}
*,:after,:before{box-sizing:border-box}
.clearfix:after,.clearfix:before{content:'';display:table}
.clearfix:after{clear:both;display:block}
a{text-decoration:none}

.login-wrap{     color: #000;
    overflow: hidden;
    border-radius: 8px;
    width: 100%;
    margin: 50px auto;
    max-width: 525px;
    position: relative;
    box-shadow: 0 12px 15px 0 rgba(0,0,0,.24), 0 17px 50px 0 rgba(0,0,0,.19);
}
.login-html{
	       width: 100%;
    background: rgba(255, 255, 255, 0.90);
    padding: 30px 40px;
	
}

.login-html .sign-in,
.login-html .sign-up,
.login-form .group .check{
	display:none;
}
.login-html .tab,
.login-form .group .label,
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
	color:#000;
	border-color:#000;
}
.login-html .tab-content {
    padding: 0;
}
.login-form{     margin-top: 20px;
}
.login-form .group{
	margin-bottom:15px;
}
.login-form .group .label,
.login-form .group .input{
	width:100%;
	
	display:block;
}
.login-form .group .input,
.login-form .group .button{
	    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    background: #fff;
}
.login-form .group .button{
width:100%;
	display:block;color:#fff;
}
.login-form .group input[data-type="password"]{
	text-security:circle;
	-webkit-text-security:circle;
}
.login-form .group .label{
	color:#212121;
	font-size: 13px;
    margin-bottom: 6px;text-align: left;
}
.login-form .group .button{
	    background: #b951a0;
    cursor: pointer;
    text-align: center;
}
.alert .close {
    color: #000;
    opacity: 1;
}

.foot-lnk{
	text-align:center;
}
@media screen and (max-width: 600px) {
.login-wrap {max-width: 100%;    border-radius: 0;    margin: 0;}	
}
</style></head>
<body>

<div class="login-wrap">
<?=$this->Flash->render();?>
	<div class="login-html">
	
		<input id="tab-2" type="radio" name="tab" class="sign-up" checked><label for="tab-2" class="tab">Verification Code</label>
		
		<div class="login-form">
			
			  <?php  echo $this->Form->create($user,array('url'=>'front/users/verify','class'=>'form-horizontal form-label-left','novalidate','method'=>'post','id'=>'register_form'));?>
			<div class="sign-up-htm">
				<div class="group">
					<label for="user" class="label">Verification Code</label>
					<input id="user" name="verify_code" type="text" class="input">
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

