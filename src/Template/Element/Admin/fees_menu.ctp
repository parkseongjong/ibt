<?php
	$active1 = '';
	$active2 = '';
	$active3 = '';
	$active4 = '';
	$active5 = '';

	if(isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'tech'){
		$controller_name = strtolower($this->request->params['controller']);
		$action_name = $this->request->params['action'];
		if (!empty($controller_name) && !empty($action_name)) {
			if ($controller_name == 'transactions' && $action_name == 'fees') {
				$active1 = 'on';
			} else if ($controller_name == 'transactions' && $action_name == 'sellfees') {
				$active2 = 'on';
			} else if ($controller_name == 'transactions' && $action_name == 'accounttransferfees') {
				$active3 = 'on';
			} else if ($controller_name == 'transactions' && $action_name == 'withdrawfees') {
				$active4 = 'on';
			} else if ($controller_name == 'transactions' && $action_name == 'loandepositfees') {
				$active5 = 'on';
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

<div class="inner_content_w3_agile_info" >
	<div class="agile-tables">
		<div class="w3l-table-info agile_info_shadow_menu" >
			<nav class="nav navbar-default" id="myTab" style="width: fit-content; height: fit-content;background: transparent;margin-bottom: 1%">
				<div class="container" >
					<ul class="tab_menu">
						<li class="<?=$active1;?>" id="buysell_on">
							<a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'fees']);  ?>"><?= __('Buy Fees') ?></a>
						</li>
						<li class="<?=$active2;?>">
							<a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'sellfees']);  ?>"><?= __('Sell Fees') ?></a>
						</li>
						<li class="<?=$active3;?>"> 
							<a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'accounttransferfees']);  ?>"><?= __('Internal Account Transfer Fees') ?></a>
						</li>
						<li class="<?=$active4;?>">
							<a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'withdrawfees']);  ?>"><?= __('Withdrawal Fees') ?></a>
						</li>
						<!--<li class="<?=$active5;?>">
							<a href="<?php echo $this->Url->build(['controller'=>'transactions','action'=>'loandepositfees']);  ?>"><?= __('Loan Deposit Fees') ?></a>
						</li>-->
					</ul>
				</div>
			</nav>
		</div>
	</div>
</div>