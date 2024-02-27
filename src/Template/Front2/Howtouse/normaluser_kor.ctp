<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/howtouse.css" />
<style type="text/css">
</style>
<div class="container howtouse-container">
	<div class="custom_frame document">
		<div class="menu_left">
			<h1>사용설명서</h1>
			<p class="item"><a href="/front2/howtouse/normaluser" id="menu-normaluser" class="howtouse-menu">일반회원/연간회원</a></p>
			<p class="item"><a href="/front2/howtouse/normaluser#deal-coupon-rules" id="menu-deal-coupon-rules" class="howtouse-menu">거래 쿠폰 규정</a></p>
			<p class="item"><a href="/front2/howtouse/normaluser#deposit" id="menu-deposit" class="howtouse-menu">예치</a></p>
			<p class="item"><a href="/front2/howtouse/normaluser#deal-info" id="menu-deal-info" class="howtouse-menu">거래안내</a></p>
			<p class="item"><a href="/front2/howtouse/precautions/1" class="precautions1">입금 유의사항</a></p>
			<p class="item"><a href="/front2/howtouse/precautions/2" class="precautions2">출금 유의사항</a></p>
		</div>
		<div class="contents">
			<div class="title">사용설명서</div>
			<div class="normal-user">
				<div class="detail-title">
					<ul>
						<li> <strong>일반회원</strong> 메인 계정에서 트레이닝 계정으로 변경</li>
					</ul>
				</div>
				<div class="howtouse-grid">
					<div class="grid-col-1/2">
						<div class="grid-row row-title">
							<img src="<?=$this->request->webroot ?>assets/html/images/tp3-icon.png" alt="tp3-icon" class="token-icon"> <span class="tp3-symbol">TP3</span>
						</div>
						<div class="grid-row"> <span><strong>1일</strong> 100 TP3</span>
							<span class="divider">|</span>
							<span><strong>1개월</strong> 2,000 TP3</span>
						</div>
					</div>
					<div class="grid-col-1/2">
						<div class="grid-row row-title">
							<img src="<?=$this->request->webroot ?>assets/html/images/ctc-icon.png" alt="ctc-icon" class="token-icon"> <span class="ctc-symbol">CTC</span>
						</div>
						<div class="grid-row"> <span><strong>1일</strong> 10 CTC</span>
							<span class="divider">|</span>
							<span><strong>1개월</strong> 200 CTC</span>
						</div>
					</div>
				</div>
			</div>
			<div class="year-user">
				<div class="detail-title">
					<ul>
						<li> <strong>연간회원</strong> 메인 계정에서 트레이닝 계정으로 변경</li>
					</ul>
				</div>
				<div class="howtouse-grid">
					<div class="grid-col-1/2">
						<div class="grid-row row-title">
							<img src="<?=$this->request->webroot ?>assets/html/images/tp3-icon.png" alt="tp3-icon" class="token-icon"> <span class="tp3-symbol">TP3</span>
						</div>
						<div class="grid-row"> <span><strong>1회한정</strong> 10,000 개</span>
						</div>
					</div>
					<div class="grid-col-1/2">
						<div class="grid-row row-title">
							<img src="<?=$this->request->webroot ?>assets/html/images/ctc-icon.png" alt="ctc-icon" class="token-icon"> <span class="ctc-symbol">CTC</span>
						</div>
						<div class="grid-row"> <span><strong>1회한정</strong> 1,000 개</span>
						</div>
					</div>
				</div>
			</div>
			<div id="deal-coupon-rules" class="deal-coupon-rules">
				<div class="detail-title"> <strong>연간회원</strong>
				</div>
				<div class="detail-subtitle">거래 쿠폰을 구매하시면 메인계정에서 트레이닝 계정으로 추가 전환할 수 있습니다. 아래를 참조하세요.</div>
				<div class="coupon-rules-box">
					<div class="rule-example">
						<ul>
							<li>예시) 50000원 TP3 쿠폰1장 > 전환 가능코인 5000TP3</li>
						</ul>
					</div>
					<div class="rule-visualize">
						<img src="<?=$this->request->webroot ?>assets/html/images/tp3-coupon.png" alt="tp3-coupon" class="tp3-coupon">
						<img src="<?=$this->request->webroot ?>assets/html/images/arrow-right.png" alt="arrow-right" class="arrow-right not-width">
						<img src="<?=$this->request->webroot ?>assets/html/images/tp3-50000.png" alt="tp3-50000" class="tp3-50000">
					</div>
				</div>
				<div class="rule-description">
					<div class="text-red">- COIN IBT 코인 전환(쿠폰 구매)과 출금 조건이 변경됩니다.</div>
					<div class="text-red">- 일시 2021 년 6월 10 일부터</div>
					<div class="text-gray">- 코인 전환(쿠폰 구매) 하루 1회 전환 가능</div>
					<div class="text-gray">- 코인 전환(쿠폰 구매) 일주일 최대 3회 가능</div>
					<div class="text-gray">- 코인 전환(쿠폰구매) 한달 최대 12회가능</div>
					<div class="text-gray">- (본 정책은 추후 조정될 수 있습니다.)</div>
				</div>
			</div>
			<div id="deposit" class="deposit">
				<div class="deposit-left">
					<div class="deposit-title">예치</div>
				</div>
				<div class="deposit-right">
					<div class="deposit-content text-red">예치는 메인 계정에서만 할 수 있습니다.</div>
					<div class="deposit-content">예치 거래는 일반회원도 가능합니다.</div>
					<div class="deposit-content">예치 수량은 1구좌 최대 200만개 이며 구좌를 추가할수 있습니다.</div>
				</div>
			</div>
			<div id="deal-info" class="deal-info">
				<div class="deal-info-left">
					<div class="deal-info-title">거래안내</div>
				</div>
				<div class="deal-info-right">
					<div class="deal-info-content">신규상장 코인의 거래소 거래는 하루 최대+10%의 상승 제한과 -10%하락 제한이 있습니다.</div>
					<div class="deal-info-content">COIN IBT는 코인 인큐베이터입니다.</div>
					<div class="deal-info-content">모든 거래소 오픈 상장 전 까지는 거래 제한이 있습니다.</div>
					<div class="deal-info-content">COIN IBT 거래소에 들어오는 신규코인은 모두하루 최대+10%의 상승 제한과 -10%하락 제한이 있습니다.</div>
					<div class="deal-info-content">메이저코인 BTC, ETH, BNB 외 코인은 거래제한이 없습니다.</div>
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