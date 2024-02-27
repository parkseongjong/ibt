
		<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/coupons.css" />


		<ul class="tab_menu">
			<li><a href="<?php echo $this->Url->build(['controller'=>'document','action'=>'priceinfo']) ?>"><?=__('Fee Information') ?></a></li>
			<li><a href="<?php echo $this->Url->build(['controller'=>'document','action'=>'commission']) ?>"><?=__('Commission Coupons') ?></a></li>
		</ul>

		<script>
		$(document).ready(function(){
		<?php if (isset($kind)) { ?>
			$(".<?=$kind ?>").addClass('on');
		<?php } ?>
		});
		</script>
