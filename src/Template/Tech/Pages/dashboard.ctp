<!-- Content Wrapper. Contains page content -->
<style>
	.hot-wallet-area{margin-left : 1px;float:left;color:#191919;padding:10px;background-color:#eee;width:49%;height:100px;text-align:center;/*border: 1px solid #616161;*/font-family: 'Source Sans Pro', sans-serif;}
	.number-area{margin-top: 16px;color: #ff9800;}
</style>
<div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"> <?= __('Dashboard');?> </a></h1>
        <ol class="breadcrumb">
			<li><a href="<?php echo $this->Url->build(['controller'=>'pages','action'=>'dashboard']);  ?>"><i class="fa fa-dashboard"></i> <?= __('Home');?></a></li>
			<li class="active"> <?= __('Dashboard');?></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content"> 
      <!-- Small boxes (Stat box) -->
        <div class="row">
			<div class="col-lg-3 col-xs-6"> 
			<!-- small box -->
				<div class="small-box bg-aqua">
					<div class="inner">
						<h3><?php echo $totalUsers; ?></h3>

						<p> <?= __('Total Users');?></p>
					</div>
					<div class="icon"><i class="fa fa-users" style="color:#fff;font-size:65px"></i></div>
					<a href="<?php echo $this->Url->build(['controller'=>'reports','action'=>'users']);  ?>" class="small-box-footer"> <?= __('More info');?> <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6"> 
			<!-- small box -->
				<div class="small-box bg-yellow">
					<div class="inner">
						<h3><?php echo $totalCoins; ?></h3>
						<p> <?= __('Total Coins');?></p>
					</div>
					<div class="icon"><i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/money.png"></i> </div>
					<a href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'index']);  ?>" class="small-box-footer"> <?= __('More info');?> <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
      <!-- ./col -->
			<div class="col-lg-3 col-xs-6"> 
			<!-- small box -->
				<div class="small-box bg-red">
					<div class="inner">
						<h3><?php echo (int)$totalCoinPairs; ?></h3>
						<p> <?= __('Total Coin Pairs');?></p>
					</div>
					<div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/icon1.png"></i> </div>
					<a href="<?php echo $this->Url->build(['controller'=>'Coin','action'=>'coinpairIndex']);  ?>" class="small-box-footer"> <?= __('More info');?> <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
	  <!-- ./col -->
			<div class="col-lg-3 col-xs-6"> 
			<!-- small box -->
				<div class="small-box bg-green">
					<div class="inner">
						<h3><?php echo $totalExchange; ?></h3>
						<p> <?= __('Total Exchange Records');?></p>
					</div>
					<div class="icon"> <i><img src="<?php echo $this->request->webroot;?>css/Admin/bower_components/dist/img/B-coin.png"></i> </div>
					<a href="<?php echo $this->Url->build(['controller'=>'Exchange','action'=>'transaction']);  ?>" class="small-box-footer"> <?= __('More info');?> <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
        <div class="row">
			<?php 
					foreach($allCoins as $key=>$allCoinsSingle) { 
						$getUserTransactions = $this->Custom->getAllUserBalance($allCoinsSingle);
						$mainBalance  = $getUserTransactions['principalBalance'];
						$tradingBalance  = $getUserTransactions['withdrawBalance'];
						$getTotalBuyAndSell  = $getUserTransactions['getTotalBuyAndSell'];
						$totalBalance = $mainBalance+$tradingBalance+abs($getTotalBuyAndSell);
				?>  
					<a href="<?php echo $this->Url->build(["controller"=>"Reports","action"=>"usercoinbalancefast",$allCoinsSingle]); ?>">
						<div class="col-md-3">
							<div class="button_set_one three one agile_info_shadow">
								<h3 class="w3_inner_tittle two" align="center"> <?= __('Total ');?> <?php echo $allCoinsSingle; ?> </h3>
								<h4 class="text-center" id="total_wallet_<?=$allCoinsSingle;?>"><?php echo number_format($totalBalance,2) .' '. $allCoinsSingle; ?> </h4>
								<div class="hot-wallet-area">핫월렛
									<div class="number-area" id="hot_wallet_<?=$allCoinsSingle;?>">0</div>
								</div>
								<div class="hot-wallet-area">콜드월렛
									<div class="number-area" id="cold_wallet_<?=$allCoinsSingle;?>">0</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="clearfix"></div>
						</div>
					</a>
			<?php	} ?>
				<a href="<?php echo $this->Url->build(["controller"=>"DepositApplication","action"=>"depositapplicationlist"]); ?>">
					<div class="col-md-3">
						<div class="button_set_one three one agile_info_shadow">
							<h3 class="w3_inner_tittle two" align="center"> <?= __('Total Investments:');?> </h3>
							<h4 class="text-center"><?php echo number_format($totalInvestmentsShow,1).' TP3'; ?></h4>
						</div>
						<div class="clearfix"></div>
						<div class="clearfix"></div>
					</div>
				</a>
				<a href="<?php echo $this->Url->build(["controller"=>"DepositApplication","action"=>"walletlist"]); ?>">
					<div class="col-md-3">
						<div class="button_set_one three one agile_info_shadow">
							<h3 class="w3_inner_tittle two" align="center"> <?= __('Total Investments Profits:');?> </h3>
							<h4 class="text-center"><?php echo number_format($totalInvestmentsProfitsShow,1).' KRW'; ?></h4>
						</div>
						<div class="clearfix"></div>
						<div class="clearfix"></div>
					</div>
				</a>
				<a href="javascript:void(0)">
					<div class="col-md-3">
						<div class="button_set_one three one agile_info_shadow">
							<h3 class="w3_inner_tittle two" align="center">월렛 주소</h3>
							<h4 class="w3_inner_tittle two" align="center"><a href="https://etherscan.io/address/0x233a562005ff31c1999253ff28048f4bb01d1887" target="_blank">핫월렛1</a></h4>
							<h4 class="w3_inner_tittle two" align="center"><a href="https://etherscan.io/address/0x1da4a1759ed3e2d59d4ae4303eaf5d408fbb24c6" target="_blank">핫월렛2</a></h4>
							<h4 class="w3_inner_tittle two" align="center"><a href="https://etherscan.io/address/0xec8EB80bCBD632f17fCC3ac7Cb3Ba9b1f8a2B0EC" target="_blank">콜드월렛</a></h4>
						</div>
						<div class="clearfix"></div>
						<div class="clearfix"></div>
					</div>
				</a>
		</div>
	</section>
</div>
<script src="<?php echo $this->request->webroot.'js/tech/web3.min.js'; ?>"></script>
<script>
	// 메인넷 RPC 설정
	const mainnet = "https://mainnet.infura.io/v3/247ea94e13d54cd9a9a7356255473e3e";

	// web3 객체 선언
	const web3 = new Web3(new Web3.providers.HttpProvider(mainnet));
	// 월렛 주소
	// 월렛 주소
	const hotETHAddr = [
	  "0x233A562005ff31C1999253ff28048F4BB01d1887",
	  "0x1Da4a1759eD3e2d59D4ae4303eAf5D408FbB24C6",
	];
	// 20210826 윤종흠 수정: 숨긴 콜드월렛 "0x906d6155c6Ae6b8BADA6D20A2e352bd3bEa436dd" 주석 처리, 조회 x
	const coldETHAddr = [
	  // "0x906d6155c6Ae6b8BADA6D20A2e352bd3bEa436dd",
	  "0xec8EB80bCBD632f17fCC3ac7Cb3Ba9b1f8a2B0EC",
	];
	// 컨트랙트 주소
	const MCcontract = "address"; // hot1 - mwei
	const TP3contract = "address"; // hot1
	const CTCcontract = "0x00b7db6b4431e345eee5cc23d21e8dbc1d5cada3"; // hot2, cold1 cold2
	const USDTcontract = "address"; // cold1 - mwei
	const KRWcontract = "address"; // cold1 

	// contract ABI
	const commonAbi = [
	 {
		constant: true,
		inputs: [{ name: "who", type: "address" }],
		name: "balanceOf",
		outputs: [{ name: "", type: "uint256" }],
		type: "function",
	  },
	];
	// 이더리움 월렛 잔액 조회 (배열)
	async function getETHbalance(addressArr) {
	  let ETHResult = 0;
	  for (const address of addressArr) {
		ETHbalance = await web3.eth.getBalance(address);
		ETHResult += parseFloat(web3.utils.fromWei(ETHbalance, "ether"));
	  }
	  return ETHResult;
	}
	// 코인 월렛 잔액 조회
	async function getCoinBalance(contract, walletAddr, fromWeiType) {
	  let contractWeb3 = new web3.eth.Contract(commonAbi, contract);
	  balance = await contractWeb3.methods.balanceOf(walletAddr).call();
	  return web3.utils.fromWei(balance, fromWeiType);
	}
	// 코인 월렛 잔액 조회 (배열)
	async function getCoinBalanceArr(contract, walletAddrArr, fromWeiType) {
	  let coinResult = 0;
	  for (const address of walletAddrArr) {
		let contractWeb3 = new web3.eth.Contract(commonAbi, contract);
		balance = await contractWeb3.methods.balanceOf(address).call();
		coinResult += parseFloat(web3.utils.fromWei(balance, fromWeiType));
	  }
	  return coinResult;
	}
	// 핫월렛
	// 20210825 윤종흠 수정: getCoinBalance를 getCoinBalanceArr로 배열 조회하도록 수정했습니다.
	// 20210826 윤종흠 수정: USDT, CTC, MC, TP3 모든 코인 조회되도록 추가 
	function hotWallet() {
	  getETHbalance(hotETHAddr).then(function (result) {
		// ETH - hotETHAddr 전체 결과값
		try {
		  showTotalBalance("ETH", "hot", result);
		  console.log("HotWallet ETH balance : " + result + " ETH");
		} catch (err) {
		  console.log(err);
		}
	  });
	  getCoinBalanceArr(USDTcontract, hotETHAddr, "mwei").then(function (result) {
		// hotETHAddr USDT 전체 결과값
		try {
		  console.log("HotWallet USDT balance : " + result + " USDT");
		  showTotalBalance("USDT", "hot", result);
		} catch (err) {
		  console.log(err);
		}
	  });  
	  getCoinBalanceArr(CTCcontract, hotETHAddr, "ether").then(function (result) {
		// hotETHAddr CTC 전체 결과값
		try {
		  console.log("HotWallet CTC balance : " + result + " CTC");
		  showTotalBalance("CTC", "hot", result);
		} catch (err) {
		  console.log(err);
		}
	  });
	  getCoinBalanceArr(TP3contract, hotETHAddr, "ether").then(function (result) {
		// hotETHAddr TP3 전체 결과값
		try {
		  console.log("HotWallet TP3 balance : " + result + " TP3");
		  showTotalBalance("TP3", "hot", result);
		} catch (err) {
		  console.log(err);
		}
	  });  
	  getCoinBalanceArr(MCcontract, hotETHAddr, "mwei").then(function (result) {
		// hotETHAddr MC 전체 결과값
		try {
		  console.log("HotWallet MC balance : " + result + " MC");
		  showTotalBalance("MC", "hot", result);
		} catch (err) {
		  console.log(err);
		}
	  });
	  getCoinBalanceArr(KRWcontract, hotETHAddr, "ether").then(function (result) {
		// hotETHAddr KRW 전체 결과값
		try {
		  console.log("HotWallet KRW balance : " + result + " KRW");
		  showTotalBalance("KRW", "hot", result);
		} catch (err) {
		  console.log(err);
		}
	  });
	}
	/* 콜드 월렛 전체 잔액 조회 */
	// 20210825 윤종흠 수정: getCoinBalance를 getCoinBalanceArr로 배열 조회하도록 수정했습니다.
	// 20210826 윤종흠 수정: USDT, CTC, MC, TP3 모든 코인 조회되도록 추가 
	function coldWallet() {
	  getETHbalance(coldETHAddr).then(function (result) {
		// ETH - coldETHAddr 전체 결과값
		try {
		  showTotalBalance("ETH", "cold", result);
		  console.log("ColdWallet ETH balance : " + result + " ETH");
		} catch (err) {
		  console.log(err);
		}
	  });
	  getCoinBalanceArr(USDTcontract, coldETHAddr, "mwei").then(function (result) {
		// coldETHAddr USDT 전체 결과값
		try {
		  console.log("ColdWallet USDT balance : " + result + " USDT");
		  showTotalBalance("USDT", "cold", result);
		} catch (err) {
		  console.log(err);
		}
	  });
	  getCoinBalanceArr(CTCcontract, coldETHAddr, "ether").then(function (result) {
		// coldETHAddr CTC 전체 결과값
		try {
		  console.log("ColdWallet CTC balance : " + result + " CTC");
		  showTotalBalance("CTC", "cold", result);
		} catch (err) {
		  console.log(err);
		}
	  });
	  getCoinBalanceArr(TP3contract, coldETHAddr, "ether").then(function (result) {
		// ColdETHAddr TP3 전체 결과값
		try {
		  console.log("ColdWallet TP3 balance : " + result + " TP3");
		  showTotalBalance("TP3", "cold", result);
		} catch (err) {
		  console.log(err);
		}
	  });
	  getCoinBalanceArr(MCcontract, coldETHAddr, "mwei").then(function (result) {
		// ColdETHAddr MC 전체 결과값
		try {
		  console.log("ColdWallet MC balance : " + result + " MC");
		  showTotalBalance("MC", "cold", result);
		} catch (err) {
		  console.log(err);
		}
	  });
	  getCoinBalanceArr(KRWcontract, coldETHAddr, "mwei").then(function (result) {
		// ColdETHAddr KRW 전체 결과값
		try {
		  console.log("ColdWallet KRW balance : " + result + " KRW");
		  showTotalBalance("KRW", "cold", result);
		} catch (err) {
		  console.log(err);
		}
	  });
	}
	/* 대시보드에 표시 */
	function showTotalBalance(coinType,walletType, price){
		$('#'+walletType+'_wallet_'+coinType).text(numberWithCommas(parseFloat(price).toFixed(2)) + ' '+coinType);
	}
	
	hotWallet();
	coldWallet();
	setInterval(function(){ hotWallet(); coldWallet();}, 300000);
</script>