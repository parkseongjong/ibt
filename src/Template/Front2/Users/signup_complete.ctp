<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/login2.css" />

<div class="container">

	<div class="login_box signup_box">

		<div style="width: 680px; margin: 120px auto">

			<div class="welcome">
				<?=__('Congratulations on registering!') ?>
			</div>

			<div class="after_signup">
				<?=__('After logging') ?>
			</div>

			<div class="form-verify">
				<input type="button" class="button" value="<?=__('Go to login page') ?>" onclick="goLogin()" />
			</div>

		</div>

	</div>

</div>
