
		<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/investment2.css?ver=005" />

		<div class="page_title">
			<?=__('Staking/Rental Service') ?>
		</div>

		<div class="page_sub_title">
		<?=__('Raise your profit') ?>
		</div>

		<ul class="tab_menu">
			<li class="investment"><a href="<?php echo $this->Url->build(['controller'=>'investment','action'=>'index']) ?>"><?=__('Staking Service') ?></a></li>
			<li class="loans"><a href="<?php echo $this->Url->build(['controller'=>'investment','action'=>'loans']) ?>"><?=__('Rental Service') ?></a></li>
		</ul>

		<script>
		$(document).ready(function(){
		<?php if (isset($kind)) { ?>
			$(".<?=$kind ?>").addClass('on');
		<?php } ?>
		});
		</script>
