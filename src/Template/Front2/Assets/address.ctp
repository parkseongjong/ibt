
<div class="container">


<div class="assets_box">
<div class="left mycoinleft mycoinleft22">

				<?php echo $this->element('Front2/assets_left'); ?>

</div>
<div class="mycoinrigth">
<div class="mycoinrigth_pp">

				<?php echo $this->element('Front2/assets_menu'); ?>


				<div class="asset_list asset_list_margan" >
				<div class="table_scrool">
					<table>
						<thead>
							<tr>
								<td><input type="checkbox" class="check" name="" value="1" /></td>
								<td><?=__('Wallet Name')?></td>
								<td><?=__('Wallet Address')?></td>
								<td><?=__('Date and time of registration')?></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="4" class="blank">
									<?=__('There is no registered wallet')?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				</div>

				<table style="width:100%">
					<tr>
						<td style="text-align:left">
							<button name="" class="white"><?=__('Delete')?></button>
						</td>
						<td style="text-align:right">
							<button name="" onclick="addAddress()" >+ <?=__('Register withdrawal address')?></button>
						</td>
					</tr>
				</table>


				<div class="desc desc3333" >
					<p>- <?=__('Notes Address Text1')?></p>

					<p>- <?=__('Notes Address Text2')?></p>
				</div>


				<div id="add_address">
					<div style='margin:40px 0 30px; color:#000000; font-size: 22px;'><?=__('Register withdrawal address')?></div>
					<table>
						<tr>
							<td class="title">
								<?=__('Name2')?>
							</td>
							<td>
								<input type="text" name="" value="" />
							</td>
						</tr>
						<tr>
							<td class="title">
								<?=__('Wallet Address')?>
							</td>
							<td>
								<input type="text" name="" value="" />
							</td>
						</tr>
						<tr>
							<td class="title">
								<?=__('Enter OTP Number')?>
							</td>
							<td>
								<input type="text" name="" value="" />
							</td>
						</tr>
					</table>
					<div>
						<button class='white' onclick='hideMsgWindow()'><?=__('Cancel') ?></button>
						<button onclick='hideMsgWindow()'><?=__('Registration')?></button>
					</div>
				</div>


		

	</div>

</div>

<div class="cls"></div>
</div>
</div>


<script>
function addAddress() {
	if (document.getElementById('_hidden_frame')==null) {
		$('<div id="_hidden_frame" style="position:fixed; left:0; top:0; width:100%; height:100%; background:#0c0c00; z-index:11; opacity:0.35"></div>').appendTo('body');
	}
	$("#_hidden_frame").show();

	$("#add_address").fadeIn();
}

function hideMsgWindow() {
	$('#add_address').hide();
	$('#_hidden_frame').hide();
}
</script>