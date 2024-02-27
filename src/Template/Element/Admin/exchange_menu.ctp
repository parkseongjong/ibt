<?php
	$active1 = '';
	$active2 = '';
	$active3 = '';

	if(isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'tech'){
		$controller_name = strtolower($this->request->params['controller']);
		$action_name = $this->request->params['action'];
		if (!empty($controller_name) && !empty($action_name)) {
			if ($controller_name == 'exchange' && $action_name == 'buyList') {
				$active1 = 'on';
			} else if ($controller_name == 'exchange' && $action_name == 'sellList') {
				$active2 = 'on';
			} else if ($controller_name == 'exchange' && $action_name == 'transaction') {
				$active3 = 'on';
			} else {
				$active1 = 'on';
			}
		} else {
			$active1 = 'on';
		}
	} else {
		$active1 = 'on';
	}
?>
<div class="inner_content_w3_agile_info">
	<div class="agile-tables">
		<div class="w3l-table-info agile_info_shadow_menu" style="width: 30%;">
			<nav class="nav navbar-default" id="myTab" style="width: fit-content; height: fit-content;background: transparent;margin-bottom: 1%;margin-left: -5%;">
				<div class="container">
					<ul class = "tab_menu">
						<li class="<?=$active1;?>">
							<a href="<?php echo $this->Url->build(['controller'=>'exchange','action'=>'buy-list']);  ?>"><?= __('Buy List') ?></a>
						</li>
						<li class="<?=$active2;?>" id="buysell_on">
							<a href="<?php echo $this->Url->build(['controller'=>'exchange','action'=>'Sell-list']);  ?>"><?= __('Sell List') ?></a>
						</li>
						<li class="<?=$active3;?>">
							<a href="<?php echo $this->Url->build(['controller'=>'exchange','action'=>'transaction']);  ?>"><?= __('Exchange Transactions') ?></a>
						</li>
					</ul>
				</div>
			</nav>
		</div>
	</div>
</div>