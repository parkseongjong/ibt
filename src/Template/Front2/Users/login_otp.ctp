<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/login2.css" />

<div class="container">

	<div class="login_box">

		<?php echo $this->Form->create('opt_login');?>

		<div class="welcome">
			<?=__('OTP certification') ?>
		</div>

		<div class="com_logo2">
			<?=__('Enter OTP Confirm') ?>
		</div>

		<div class="form-field">
			<input id="user" name="otp_number" required value="" type="text" class="input" placeholder="<?=__('Enter OTP Number') ?>" />
		</div>

		<div class="form-submit">
			<input type="submit" class="button" value="<?=__('Confirm') ?>" />
		</div>

		<div class="foot-lnk">
			&nbsp;
		</div>

		</form>

	</div>

</div>

<script src="<?php echo $this->request->webroot ?>assets/js/jquery.min.js"></script>
