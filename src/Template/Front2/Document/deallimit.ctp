<div class="container">

	<div class="custom_frame document">

		<?php echo $this->element('Front2/customer_left'); ?>

		<div class="contents">
			<ul>
				<li class="title"><?=__('Guide to deposit/withdrawal limits by member level') ?><span class="help"><?=__('Guide help') ?></span></li>
			</ul>

			<div class="sub_title nn_title">
				<?=__('Deposit In Out') ?>
			</div>

			<div class="sub_section">
				<?=__('Korean WON(KRW) deposit and withdrawal levels') ?>
			</div>
<div class="table_scrool">
			<table>
				<tbody>
					<tr>
						<th style="width:200px"><?=__('Level 1') ?></th>
						<td><?=__('Email verified member') ?> <span class="bold"><?=__('Level 1') ?></span></td>
					</tr>
					<tr>
						<th><?=__('Level 2') ?></th>
						<td><?=__('Mobile phone and account verified member') ?> <span class="bold"><?=__('Level 2') ?></span></td>
					</tr>
					<tr>
						<th><?=__('Level 3') ?></th>
						<td><?=__('Member who submitted documents and certification') ?> <span class="bold"><?=__('Level 3') ?></span></td>
					</tr>
					<tr>
						<th><?=__('Level 4') ?></th>
						<td><?=__('Members who have passed self-assessment') ?> <span class="bold"><?=__('Level 4') ?></span></td>
					</tr>
				</tbody>
			</table>
</div>
			<div class="sub_section" style="margin-top:70px">
				<?=__('Deposit in Korean Won') ?>
			</div>
<div class="table_scrool">
			<table>
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th style="width:21%"><?=__('Level 1') ?></th>
						<th style="width:21%"><?=__('Level 2') ?></th>
						<th style="width:21%"><?=__('Level 3') ?></th>
						<th style="width:21%"><?=__('Level 4') ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1 <?=__('day') ?></td>
						<td>0</td>
						<td><?=__('Unlimited') ?></td>
						<td><?=__('Unlimited') ?></td>
						<td><?=__('Unlimited') ?></td>
					</tr>
					<tr>
						<td>1 <?=__('time') ?></td>
						<td>0</td>
						<td><?=__('Unlimited') ?></td>
						<td><?=__('Unlimited') ?></td>
						<td><?=__('Unlimited') ?></td>
					</tr>
				</tbody>
			</table></div>

			<div class="sub_section" style="margin-top:50px">
				<?=__('KRW Withdrawal') ?>
			</div>
<div class="table_scrool">
			<table>
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th style="width:21%"><?=__('Level 1') ?></th>
						<th style="width:21%"><?=__('Level 2') ?></th>
						<th style="width:21%"><?=__('Level 3') ?></th>
						<th style="width:21%"><?=__('Level 4') ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1 <?=__('day') ?></td>
						<td>0</td>
						<td>3000<br />(3 <?=__('thousand') ?>)</td>
						<td>300,000,000<br />(300 <?=__('million') ?>)</td>
						<td>1,000,000,000<br />(1 <?=__('billion') ?>)</td>
					</tr>
					<tr>
						<td><?=__('month') ?></td>
						<td>0</td>
						<td>100,000,000<br />(100 <?=__('million') ?>)</td>
						<td>1,000,000,000<br />(1 <?=__('billion') ?>)</td>
						<td><?=__('Unlimited') ?></td>
					</tr>
					<tr>
						<td>1 <?=__('year') ?></td>
						<td>0</td>
						<td>1,000<br />(1 <?=__('thousand') ?>)</td>
						<td>5,000<br />(5 <?=__('thousand') ?>)</td>
						<td>100,000,000<br />(100 <?=__('million') ?>)</td>
					</tr>
				</tbody>
			</table></div>

			<div class="sub_title nn_title" >
				<?=__('Digital In Out') ?>
			</div>

			<div class="sub_section">
				<?=__('Digital asset deposit and withdrawal levels') ?>
			</div>
<div class="table_scrool">
			<table>
				<tbody>
					<tr>
						<th style="width:200px"><?=__('Level 1') ?></th>
						<td><?=__('Email verification') ?> <span class="bold"><?=__('Level 1') ?></span></td>
					</tr>
					<tr>
						<th><?=__('Level 2') ?></th>
						<td><?=__('OTP authentication') ?> <span class="bold"><?=__('Level 2') ?></span></td>
					</tr>
				</tbody>
			</table>
</div>
			<ul class="digital_inout">
				<li>
					<div class="sub_section" >
						<?=__('Depositing digital assets') ?>
					</div>
<div class="table_scrool">
					<table>
						<thead>
							<tr>
								<th style="width:80px">&nbsp;</th>
								<th><?=__('Level 1') ?></th>
								<th><?=__('Level 2') ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1 <?=__('day') ?></td>
								<td><?=__('Unlimited') ?></td>
								<td><?=__('Unlimited') ?></td>
							</tr>
							<tr>
								<td><?=__('month') ?></td>
								<td><?=__('Unlimited') ?></td>
								<td><?=__('Unlimited') ?></td>
							</tr>
							<tr>
								<td>1 <?=__('year') ?></td>
								<td><?=__('Unlimited') ?></td>
								<td><?=__('Unlimited') ?></td>
							</tr>
						</tbody>
					</table>
					</div>
				</li>
				<li style="width:6%">&nbsp;</li>
				<li>
					<div class="sub_section" >
						<?=__('Digital asset withdrawal') ?>
					</div>
<div class="table_scrool">
					<table>
						<thead>
							<tr>
								<th style="width:80px">&nbsp;</th>
								<th><?=__('Level 1') ?></th>
								<th><?=__('Level 2') ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1 <?=__('day') ?></td>
								<td>1,000<br />(1 <?=__('thousand') ?>)</td>
								<td>500,000,000<br />(500 <?=__('million') ?>)</td>
							</tr>
							<tr>
								<td><?=__('month') ?></td>
								<td>3,000<br />(3 <?=__('thousand') ?>)</td>
								<td>5,000,000,000<br />(5 <?=__('billion') ?>)</td>
							</tr>
							<tr>
								<td>1 <?=__('year') ?></td>
								<td>100<br />(1 <?=__('hundred') ?>)</td>
								<td>100,000,000<br />(100 <?=__('million') ?>)</td>
							</tr>
						</tbody>
					</table>
					</div>
				</li>
			<ul>

		</div>
		<div class="cls"></div>

	</div>

</div>
