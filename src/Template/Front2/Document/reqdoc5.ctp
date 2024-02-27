<div class="container">

	<div class="custom_frame">

		<?php echo $this->element('Front2/customer_left'); ?>

		<div class="contents">
			<ul>
				<li class="title"><?=__('Information on submitting certification data') ?></li>
			</ul>

			<?php echo $this->element('Front2/reqdoc_menu'); ?>

			<div class="reqdoc_tab">

				<div class="cate_title">

					<h1><?=__('Notice before change') ?></h1>

					<div class="cate_desc">
						· <?=__('Withdrawal Confirm 1') ?><br />
						· <?=__('Withdrawal Confirm 2') ?><br />
						· <?=__('Withdrawal Confirm 3') ?><br />
						· <?=__('Withdrawal Confirm 4') ?><br />
						· <?=__('Withdrawal Confirm 5') ?><br />
						· <?=__('Withdrawal Confirm 6') ?><br />
						· <?=__('Withdrawal Confirm 7') ?><br />
					</div>

				</div>

				<h2>
					<?=__('Documents submitted') ?> <span>(<?=__('Documents submitted notice') ?>)</span>
				</h2>

				<div class="person_card person_card_do">
					<h3>
						- <?=__('ID photo') ?><br />
						- <?=__('Confirmation letter') ?>
					</h3>

					<div style="border:1px dashed #b5b5b5; padding-top:24px; margin-top:20px; text-align:center">
						<img src="/wb/imgs/download_doc.jpg" />
						<div class="do_do">
							<?=__('Download documents') ?>&nbsp;&nbsp;&nbsp;<img src="/wb/imgs/download_down.png" />
						</div>
					</div>

				</div>

				<h2>
					<?=__('Example email') ?>
				</h2>

				<div class="email_ex">
					<p>
						- <?=__('Example email 1') ?><br />
						- <?=__('Example email 2') ?>
					</p>

					<table>
						<tbody>
							<tr>
								<th style="width:21%"><?=__('Email subject') ?></th>
								<td >
									<?=__('Email subject text5') ?></td>
							</tr>
							<tr>
								<th style="width:21%"><?=__('Email address to send') ?></th>
								<td >
									cs@coinibt.com</td>
							</tr>
							<tr>
								<th style="width:21%"><?=__('Body required') ?></th>
								<td >
									<?=__('Body required text') ?>
								</td>
							</tr>
							<tr>
								<th style="width:21%"><?=__('Attachments') ?></th>
								<td >
									<?=__('Attachments text5') ?>
								</td>
							</tr>
						</tbody>
					</table>

				</div>

			</div>

		</div>
<div class="cls"></div>
	</div>

</div>
