
<div class="container">

	<div class="assets_box">
	<div class="left mycoinleft mycoinleft22">
	<?php echo $this->element('Front2/assets_left'); ?>
	</div>
<div class="mycoinrigth">
<div class="mycoinrigth_pp">	
				<?php echo $this->element('Front2/assets_menu'); ?>

				<div class="gda_box">

					<button class="big" onclick="createWallet()"><?=__('Generate Deposit Address')?></button>

					<p class="gda">
						<?=__('Generate Deposit Address Text')?>
					</p>

				</div>

			</div>
			</div>
			
		<div class="cls"></div>

	</div>

</div>


<script>
function createWallet() {
	document.location.href = "<?php echo $this->Url->build(['controller'=>'assets','action'=>'deposit2']) ?>";
}
</script>