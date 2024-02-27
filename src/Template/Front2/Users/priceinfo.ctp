<style type="text/css">
td { width: 50%; height: 40px; line-height: 40px; }
</style>

<div class="container">

	<div class="custom_frame document">

		<?php echo $this->element('Front2/company_left'); ?>

		<div class="contents">
			<ul>
				<li class="title"><?=__('Fee information') ?></li>
			</ul>

			<div class="sub_title" style="margin-top:30px; margin-bottom:10px; font-weight:400">
				· <?=__('PriceInfo Fee1')?>
			</div>

			<table style="margin-left:16px; width:1060px; margin-top:40px">
				<tr>
					<td style="font-weight:bold"><?=__('Transaction fee')?></td>
					<td style="font-weight:bold"><?=__('Conversion fee')?></td>
				</tr>
				<tr>
					<!--td>0 원 (<?=__('Free')?>)</td-->
					<td>0.2 %</td>
					<td><?=__('Conversion fee1')?></td>
				</tr>
			</table>

			<p style="font-size: 16px; font-weight: 300; line-height: 1.5; color: #4b4b4b; margin-left:16px; margin-top: 26px">
				- <?=__('PriceInfo Fee2')?><br />
				- <?=__('PriceInfo Fee3')?><br />
				- <?=__('PriceInfo Fee4')?><br />
			</p>



			<ul style="margin-top:100px">
				<li class="title"><?=__('Annual fee information')?></li>
			</ul>

			<div class="sub_title" style="margin-top:30px; margin-bottom:10px; font-weight:400">
				· <?=__('Annual fee1')?>
			</div>

			<table style="margin-left:16px; width:1060px; margin-top:40px">
				<tr>
					<td style="font-weight:bold"><?=__('Annual fee')?></td>
					<td>50,000 (KRW)</td>
				</tr>
			</table>

		</div>

	</div>

</div>
