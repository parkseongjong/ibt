		
					<div class="rbtc_box">
					    <div class="page_title flleft">
						    Smart Bitcoin (RBTC)
					    </div>
				        <div class="flright">
					        <?=__('Available Digital Assets') ?> <span style="font-weight:bold">100,000,000</span> RBTC
					        <span class="reblock">
					        <?=__('Frozen Digital Assets') ?> <span style="font-weight:bold">50,000,000</span> RBTC
				            </span>
				        </div>
					    <div class="cls"></div>
			        </div>
	

		<ul class="tab_menu">
			<li class="deposit"><a href="<?php echo $this->Url->build(['controller'=>'assets','action'=>'deposit']) ?>"><?=__('Deposit2')?></a></li>
			<li class="withdrawal"><a href="<?php echo $this->Url->build(['controller'=>'assets','action'=>'withdrawal']) ?>"><?=__('Withdrawal')?></a></li>
			<li class="details"><a href="<?php echo $this->Url->build(['controller'=>'assets','action'=>'details']) ?>"><?=__('Breakdown')?></a></li>
			<li class="address"><a href="<?php echo $this->Url->build(['controller'=>'assets','action'=>'address']) ?>"><?=__('Withdrawal address management')?></a></li>
		</ul>

		<script>
		$(document).ready(function(){
		<?php if (isset($kind)) { ?>
			$(".<?=$kind ?>").addClass('on');
		<?php } ?>
		});
		</script>
