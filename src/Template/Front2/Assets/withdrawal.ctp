
<div class="container">

	<div class="assets_box">
<div class="left mycoinleft mycoinleft22">
	<?php echo $this->element('Front2/assets_left'); ?>
	</div>


<div class="mycoinrigth">
<div class="mycoinrigth_pp">

				<?php echo $this->element('Front2/assets_menu'); ?>

				<div class="gda" style="margin-bottom: 15px;">
					<label><input type="radio" name="withdrawal_type" value="OUT" checked /> <?=__('External withdrawal')?> </label>
					<label><input type="radio" name="withdrawal_type" value="IN" /> <?=__('Internal withdrawal')?> </label>
				</div>

		         <div class="table_scrool">
					<table class="withdrawal">
						<tr>
							<td class="title">
								<?=__('Withdrawable amount')?>
							</td>
							<td colspan="3" class="right">
								<span class="amount">100,000,000</span><span class="unit">RBTC</span>
							</td>
						</tr>
						<tr>
							<td class="title height-100">
								<?=__('Withdrawal request amount')?>
							</td>
							<td class="no-border right height-100">
								<input type="text" name="" value="" class="req_amount" placeholder="<?=__('Enter withdrawal request amount')?>" /><span class="unit">RBTC</span>
							</td>
							<td class="title no-border height-100"><img src="/wb/imgs/equal2.png" /></td>
							<td class="right height-100" style="width: 230px">
								<span class="amount">225,000</span><span class="unit">KRW</span>
							</td>
						</tr>
						<tr>
							<td class="title">
								<?=__('Withdrawal fee')?>
							</td>
							<td colspan="3" class="right">
								<span class="amount">20</span><span class="unit">RBTC</span>
							</td>
						</tr>
						<tr>
							<td class="title blue">
								<?=__('Total withdrawal digital assets')?>
							</td>
							<td colspan="3" class="right gray_back">
								<span class="amount blue">180,000</span><span class="unit">RBTC</span>
							</td>
						</tr>
					</table>
				</div>
				
              
              
				<input type="text" name="wallet_address" value="" class="wallet_address input_width95" placeholder="<?=__('No registered wallet address.') ?> <?=__('Please register your wallet address.') ?>" />

				<div class="otp_number input_width95">
					<span><?=__('Enter OTP Number') ?></span><input type="text" name="otp_number" value="" placeholder="<?=__('Please enter the OTP number.') ?>" />
				</div>

				<div style="margin-top:30px; text-align: center;">
					<button name="" class="middle"><?=__('Withdrawal request') ?></button>
				</div>

				<div class="desc_title">
					<?=__('Notes withdrawal')?>
				</div>

				<div class="desc">
					<p>- <?=__('Notes withdrawal1')?></p>

					<p>- <?=__('Notes withdrawal2')?></p>

					<p>- <?=__('Notes withdrawal3')?></p>

					<p>- <?=__('Notes withdrawal4')?></p>

					<p>- <?=__('Notes withdrawal5')?></p>

					<p>- <?=__('Notes withdrawal6')?>
					  <?=__('Notes withdrawal7')?></p>

					<p class="red">- <?=__('Notifications for deposit requests6')?></p>
				</div></div>
</div>
<div class="cls"></div>
			</div>
			

	</div>

</div>
