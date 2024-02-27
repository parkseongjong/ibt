<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/howtouse.css" />
<style type="text/css">
</style>
<div class="container howtouse-container">
	<div class="custom_frame document">
		<div class="menu_left">
			<h1>How To Use</h1>
			<p class="item"><a href="/front2/howtouse/normaluser" id="menu-normaluser" class="howtouse-menu">General member/annual member</a></p>
			<p class="item"><a href="/front2/howtouse/normaluser#deal-coupon-rules" id="menu-deal-coupon-rules" class="howtouse-menu">Trading Coupon Rules</a></p>
			<p class="item"><a href="/front2/howtouse/normaluser#deposit" id="menu-deposit" class="howtouse-menu">Deposit</a></p>
			<p class="item"><a href="/front2/howtouse/normaluser#deal-info" id="menu-deal-info" class="howtouse-menu">Transaction information</a></p>
			<p class="item"><a href="/front2/howtouse/precautions/1" class="precautions1">Deposit Notes</a></p>
			<p class="item"><a href="/front2/howtouse/precautions/2" class="precautions2">Withdrawal Notes</a></p>
		</div>
		<div class="contents">
			<div class="title">How To Use</div>
			<div class="normal-user">
				<div class="detail-title">
					<ul>
						<li> <strong>General member</strong> Changed from main account to training account</li>
					</ul>
				</div>
				<div class="howtouse-grid">
					<div class="grid-col-1/2">
						<div class="grid-row row-title">
							<img src="<?=$this->request->webroot ?>assets/html/images/tp3-icon.png" alt="tp3-icon" class="token-icon"> <span class="tp3-symbol">TP3</span>
						</div>
						<div class="grid-row"> <span><strong>1 day</strong> 100 TP3</span>
							<span class="divider">|</span>
							<span><strong>1 month</strong> 2,000 TP3</span>
						</div>
					</div>
					<div class="grid-col-1/2">
						<div class="grid-row row-title">
							<img src="<?=$this->request->webroot ?>assets/html/images/ctc-icon.png" alt="ctc-icon" class="token-icon"> <span class="ctc-symbol">CTC</span>
						</div>
						<div class="grid-row"> <span><strong>1 day</strong> 10 CTC</span>
							<span class="divider">|</span>
							<span><strong>1 month</strong> 200 CTC</span>
						</div>
					</div>
				</div>
			</div>
			<div class="year-user">
				<div class="detail-title">
					<ul>
						<li> <strong>Annual member</strong> Changed from main account to training account</li>
					</ul>
				</div>
				<div class="howtouse-grid">
					<div class="grid-col-1/2">
						<div class="grid-row row-title">
							<img src="<?=$this->request->webroot ?>assets/html/images/tp3-icon.png" alt="tp3-icon" class="token-icon"> <span class="tp3-symbol">TP3</span>
						</div>
						<div class="grid-row"> <span><strong>Limited once</strong> 10,000 TP3</span>
						</div>
					</div>
					<div class="grid-col-1/2">
						<div class="grid-row row-title">
							<img src="<?=$this->request->webroot ?>assets/html/images/ctc-icon.png" alt="ctc-icon" class="token-icon"> <span class="ctc-symbol">CTC</span>
						</div>
						<div class="grid-row"> <span><strong>Limited once</strong> 1,000 CTC</span>
						</div>
					</div>
				</div>
			</div>
			<div id="deal-coupon-rules" class="deal-coupon-rules">
				<div class="detail-title"> <strong>Annual membership</strong>
				</div>
				<div class="detail-subtitle">By purchasing a trading coupon, you can additionally switch from the main account to the training account. See below.</div>
				<div class="coupon-rules-box">
					<div class="rule-example">
						<ul>
							<li>Example) 50000 won TP3 coupon 1 piece > Convertible coin 5000TP3</li>
						</ul>
					</div>
					<div class="rule-visualize">
						<img src="<?=$this->request->webroot ?>assets/html/images/tp3-coupon.png" alt="tp3-coupon" class="tp3-coupon">
						<img src="<?=$this->request->webroot ?>assets/html/images/arrow-right.png" alt="arrow-right" class="arrow-right not-width">
						<img src="<?=$this->request->webroot ?>assets/html/images/tp3-50000.png" alt="tp3-50000" class="tp3-50000">
					</div>
				</div>
				<div class="rule-description">
					<div class="text-red">- COIN IBT coin conversion (coupon purchase) and withdrawal conditions are changed.</div>
					<div class="text-red">- Date from June 10, 2021</div>
					<div class="text-gray">- Coin conversion (coupon purchase) can be converted once a day</div>
					<div class="text-gray">- Coin conversion (coupon purchase) possible up to 3 times a week</div>
					<div class="text-gray">- Coin conversion (coupon purchase) is possible up to 12 times per month</div>
					<div class="text-gray">- (This policy may be adjusted later.)</div>
				</div>
			</div>
			<div id="deposit" class="deposit">
				<div class="deposit-left">
					<div class="deposit-title">Deposit</div>
				</div>
				<div class="deposit-right">
					<div class="deposit-content text-red">Deposits can only be made in the main account.</div>
					<div class="deposit-content">Deposit transactions are also available for general members.</div>
					<div class="deposit-content">The maximum amount of deposit is 2 million per account, and additional accounts can be added.</div>
				</div>
			</div>
			<div id="deal-info" class="deal-info">
				<div class="deal-info-left">
					<div class="deal-info-title">Transaction information</div>
				</div>
				<div class="deal-info-right">
					<div class="deal-info-content">Exchange trading of newly listed coins has a limit of up to +10% per day and a limit of -10% down.</div>
					<div class="deal-info-content">COIN IBT is a coin incubator.</div>
					<div class="deal-info-content">There are restrictions on trading until all exchanges are open and listed.</div>
					<div class="deal-info-content">All new coins entering the COIN IBT exchange have a daily maximum +10% increase limit and -10% decrease limit.</div>
					<div class="deal-info-content">There are no transaction restrictions for major coins other than BTC, ETH, and BNB.</div>
				</div>
			</div>
		</div>
		<div class="cls"></div>
	</div>
</div>
<script>
	$(document).ready(function() {
	  function setMenuHighlight() {
	    // 기존 on class 제거
	    const menus = document.querySelectorAll('.howtouse-menu');
	    for(let i=0; i<menus.length; i++) {
	      const menu = menus[i];
	      menu.classList.remove('on');
	    }
	
	    // 선택한 메뉴 on class 추가
	    const urlLocation = window.location.href;
	    const currentIdIndex = urlLocation.split('#')[1] ?? 'normaluser';
	    document.getElementById('menu-' + currentIdIndex).classList.add('on');
	  }
	
	  setMenuHighlight();
	
	  window.addEventListener('popstate', setMenuHighlight);
	});
</script>