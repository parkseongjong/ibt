<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<div class="container">
	<div class="profile_box">
		<?php echo $this->element('Front2/profile_menu'); ?>
		<div class="page_sub_title">
			<?=__('Security settings') ?>
		</div>
		<div class="my_option2">
			<ul>
				<li class="ul_title">
					<?=__('Change password') ?>
				</li>
				<li>
                    <?= $this->Form->button(__('Change password'), array('name'=>'change', 'onclick'=>'sh()')); ?>
				</li>
			</ul>
			<div class="section_data">
				<?=__('Change pass text') ?>
			</div>
			<div class="section_data">
				<?=__('OTP cert text') ?>
			</div>
			<ul class="inmanagement" style=" border-bottom: solid 2px #eee; padding-bottom: 10px;">
				<li class="ul_title" style="font-weight:bold;">
					<?=__('Information management') ?>
				</li>
				<li style="display:none;">
					<button id="edit" class="edit_info" ><?=__('Edit') ?></button>
				</li>
			</ul>
		</div>
		<div class="secure_info">
            <ul>
                <li>
                    <?=__('Verification') ?>
                </li>
                <li>
					<?php if($user['email_auth'] == 'Y') { ?>
	                    <span class="helpmsg"><?=__('Email verified member') ?> </span>
					<?php } else {?>
						<span class="helpmsg"><?=__('Email veri') ?> <a href="/front2/users/id-verification"><?=__('Go to auth') ?></a></span>
					<?php }?>
				</li>
            </ul>
			<ul>
				<li><?=__('Email') ?></li>
				<li>
					<?php
						if(empty($user['email'])){		
							echo $this->Form->input('email',array('disabled','class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"email"));
						} else {
							echo $user['email'];
						}
					?>
				</li>
			</ul>
			<ul>
				<li><?=__('Name') ?></li>
				<li>
					<?php echo $user['name']; ?>
				</li>
			</ul>
			<ul>
				<li><?=__('Cellphone') ?></li>
				<li>
                    <?php echo $user['phone_number']; ?>
				</li>
			</ul>
			<ul>
				<li><?=__('Bank Name') ?></li>
				<li>
                    <?php echo __($user['bank']); ?>
				</li>
			</ul>
			<ul>
				<li><?=__('Bank account') ?></li>
				<li>
                    <?php echo $this->Decrypt($user['account_number']); ?>
				</li>
			</ul>
			<div class="my_option2" >
				<ul class="inmanagement" >
 					<li class="ul_title" style="font-weight:bold;">
 						Delete Account 
 					</li> 
					<li style="float: right;">
						<button type="button" class="" style="width:22%;" onclick="go_leaving()">Delete Account</button>
					</li>
				</ul>
			</div>
		</div>
    </div>
</div>
<script>
    function sh(){
        document.location.href = "/front2/users/new-change-password";
    }
	function go_leaving(){
		document.location.href = "/front2/leaving/leaving-coinibt";
	}
</script>