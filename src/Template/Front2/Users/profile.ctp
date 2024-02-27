<style>
    #gnb_menu li input.member{
        width: auto;
        height: auto;
        margin-left: 7px;
        background-color: #ffffff;
        border: 1px solid #6738ff;
        font-size: 15px;
        font-weight: bold;
        text-align: center;
        pointer-events: none;
        outline: none;
        color: #6738ff;
        display: inline-block;
        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<div class="container">
	<div class="profile_box">
		<?php echo $this->element('Front2/profile_menu'); ?>
        <?php echo $this->Form->create('',['method'=>'post']);?>
        <?php echo $this->Form->end();?>
		<div class="person_info">
			<!-- 인증 절차를 먼저 해야함 -->
			<ul>
				<li><?= __('Verification') ?></li>
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
							echo '<span id="emailveriTxt" class="helpmsg">' . __('Email veri') . ' <a id="elink" href="/front2/users/id-verification">' . __('Go to auth') . '</a> </span>';
						} else {
							echo $user['email'];
						}
					?>
				</li>
			</ul>
			<ul>
				<li><?=__('Name') ?></li>
				<li>
					<?= $user['name']; ?>
				</li>
			</ul>
			<ul>
				<li><?=__('Cellphone') ?></li>
				<li>
					<?php echo $this->masking('P',$user['phone_number']);?>
				</li>
			</ul>
		</div>
		<div class="page_sub_title">
			<?=__('DnW level') ?>
		</div>
		<div class="coin_in_out_box" style="margin-top: 30px">
			<table style="width: 95%; margin: 15px 0;" class="profile_table">
				<tbody>
					<tr>
						<td rowspan="2" class="tr1">
							<div style="font-size: 22px; font-weight: bold; line-height: 1.36; color: #000000;">
								<span style="color: #6738ff"><?=__('Coin') ?></span> <span style="color: #000000"><?=__('DnW level short') ?></span>
							</div>
							<div class="li2">
								Lv.<?= $user['user_level']; ?>
							</div>
						</td>
						<td class="tr2">
							<div class="du" style="color: #a9a9a9; "><?=__('Deposit limit') ?></div>
							<div style="color: #000000;"><span style="color: #0e2aff;"><?=__('Day') ?></span> <?=__('Withdrawal remaining limit') ?></div>
							<div style="color: #000000;"><span style="color: #0e2aff;"><?=__('Month') ?></span> <?=__('Withdrawal remaining limit') ?></div>
						</td>
						<td class="tr3">
							<div class="du" style=" color: #000000;"><?=__('Unlimited') ?></div>
							<div style="color: #8d8d8d;"><span style="color: #000000;">10,000,000</span> KRW</div>
							<div style="color: #8d8d8d;"><span style="color: #000000;">30,000,000</span> KRW</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="wd">
							<div style="color: #a9a9a9;"><?=__('Withdrawal limit') ?> : <span style="color: #0e2aff;"><?=__('Day') ?> <?=__('10 million won') ?> / <?=__('Month') ?> <?=__('30 million won') ?> / <?=__('1time') ?> <?=__('1 million won') ?></span></div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="page_sub_title" style="display:none;">
			<?=__('Account settings') ?>
		</div>
		<div class="my_option"  style="display:none;">
			<div class="section_title">
				<?=__('Agree event') ?>
			</div>
			<ul class="radio_check">
				<li class="ul_title">
					<?=__('Agree personal') ?>
				</li>
				<li class="switch">
					<input type="checkbox" id="sw1" />
					<label for="sw1" class="round"><div></div></label>
				</li>
			</ul>
			<ul class="radio_check">
				<li class="ul_title">
					<?=__('Agree Receive') ?>
				</li>
				<li class="switch">
                    <input type="checkbox" id="sw2" />
                    <label for='sw2' class='round'><div></div></label>
				</li>
			</ul>
			<div class="section_title">
				&nbsp;
			</div>
		</div>
	</div>
</div>