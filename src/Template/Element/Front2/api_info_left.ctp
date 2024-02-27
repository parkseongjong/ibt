<?php
	$active1 = '';
	$active2 = '';
	$active3 = '';
	$active4 = '';
	$active5 = '';
	if(isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'front2'){
		$action_name = $this->request->params['action'];
		if (!empty($action_name)) {
			if($action_name == 'index'){
				$active1 = 'on';
			} else if ($action_name == 'ticker'){
				$active2 = 'on';
			} else if ($action_name == 'orderbook'){
				$active3 = 'on';
			} else if ($action_name == 'transactionHistory'){
				$active4 = 'on';
			} else if ($action_name == 'codeinfo'){
				$active5 = 'on';
			}
		}
	}
?>

<aside class="aside-menu">
	<div class="menus">
		<h1 class="menu-title">COIN IBT API</h1>
		<a href="/front2/api" class="<?=$active1;?>"><?=__("API Introduction");?></a>
<!--		<a href="/front2/api">API 등록 안내</a>
 		<a href="#">샘플 코드 다운로드</a> -->
		<a href="/front2/api/ticker">Rest API</a>
		<a href="/front2/api/ticker" class="depth1">Public API</a>
		<ul class="depth2">
			<li><a href="/front2/api/ticker" class="<?=$active2;?>">Ticker</a></li>
			<li><a href="/front2/api/orderbook" class="<?=$active3;?>">Order Book</a></li>
			<li><a href="/front2/api/transaction-history" class="<?=$active4;?>">Transaction History</a></li>
			<!--<li><a href="#">Asset status</a></li>
			<li><a href="#">BTCI</a></li>-->
		</ul>
		<!--
		<a href="#" class="depth1">Private API</a>
		<ul class="depth2">
			<li> <a href="javascript:" class="btn-sub">Info</a>
				<div class="depth3">
					<div class="depth3-inner"> 
						<a href="#">Account</a>
						<a href="#">Balance</a>
						<a href="#">Wallet Address</a>
						<a href="#">Ticker (User)</a>
						<a href="#">Orders</a>
						<a href="#">Orders detail (executed)</a>
						<a href="#">Transactions</a>
					</div>
				</div>
			</li>
			<li> <a href="javascript:" class="btn-sub">Trade</a>
				<div class="depth3">
					<div class="depth3-inner"> <a href="#">Place</a>
						<a href="#">Cancel</a>
						<a href="#">Market Buy</a>
						<a href="#">Market Sell</a>
						<a href="#">Stop-Limit</a>
						<a href="#">Withdrawal (Coin)</a>
						<a href="#">Withdrawal (KRW)</a>
					</div>
				</div>
			</li>
		</ul>
		-->
		<!--<a href="#">Candlestick API</a>
		<a href="#">Rate Limits</a>-->
		<a href="/front2/api/codeinfo" class="<?=$active5;?>"><?=__("Error Code");?></a>
		<!--<a href="#">WebSocket API</a>-->
	</div>
</aside>
<script src="<?php echo $this->request->webroot ?>assets/html/js/api-common.js"></script>