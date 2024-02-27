<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/login2.css" />

<div class="container">

	<div class="login_box">

		<?php echo $this->Form->create('login');?>

		<div class="welcome">
			<?=__('Password Settings') ?>
		</div>

		<div class="com_logo2">
			<?=__('Please set a new password') ?>
		</div>

		<div class="form-field">
			<input id="pass1" name="password1" required value="" type="password" class="input" data-type="password" placeholder="<?=__('Enter a new password') ?>" />
		</div>

		<div class="form-field">
			<input id="pass2" name="password2" required value="" type="password" class="input" data-type="password" placeholder="<?=__('Password confirm') ?>" />
		</div>

		<div class="form-submit">
			<input type="submit" class="button" value="<?=__('Password authentication') ?>" />
		</div>

		<div class="foot-lnk">
			&nbsp;
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