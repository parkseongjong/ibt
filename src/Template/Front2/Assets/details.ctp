
<div class="container">


<div class="assets_box">
<div class="left mycoinleft mycoinleft22">
				<?php echo $this->element('Front2/assets_left'); ?>
</div>
<div class="mycoinrigth">
<div class="mycoinrigth_pp">

				<?php echo $this->element('Front2/assets_menu'); ?>

				<div class="filter">
					<ul>
						<li>
							<select id="day" class="select w180">
								<option value="7"> <?=__('All') ?> </option>
							</select>
						</li>
					</ul>

				</div>

				<div class="asset_list">
				<div class="table_scrool">
					<table>
						<thead>
							<tr>
								<td><?=__('Division')?></td>
								<td><?=__('Request amount')?>(RBTC)</td>
								<td><?=__('Fee')?>(RBTC)</td>
								<td><?=__('Amount')?>(RBTC)</td>
								<td><?=__('Date')?></td>
								<td><?=__('State')?></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="6" class="blank">
									<?=__('No transaction details') ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				</div>

		
		
		
		
		

	</div>

</div><div class="cls"></div>
</div>

</div>