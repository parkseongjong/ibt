<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/login2.css" />

<div class="container">

	<div class="login_box">

		<?php echo $this->Form->create('id_find');?>

		<div class="welcome">
			<?=__('Find ID') ?>
		</div>

		<div class="com_logo2">
			<?=__('Find realname') ?>
		</div>

		<div class="form-field">
			<input id="user" name="username" required value="" type="text" class="input" placeholder="<?=__('Name') ?>" />
		</div>

		<div class="form-field">
			<input id="pass" name="phone" required value="" type="text" class="input" placeholder="<?=__('Cell phone') ?>" />
		</div>

		<div class="form-submit">
			<input type="submit" class="button" value="<?=__('Confirm') ?>" />
		</div>

		<div class="foot-lnk">
			<a href="<?php echo $this->Url->Build(['controller'=>'users','action'=>'forgetpass']); ?>"><?=__('Forgot Password?') ?></a>
		</div>

		</form>

	</div>

</div>

<script src="<?php echo $this->request->webroot ?>assets/js/jquery.min.js"></script>
