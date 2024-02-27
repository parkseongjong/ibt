<!DOCTYPE html>
<html lang="ko">
    <head>
      <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
      <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
      <title>COIN IBT Exchange</title>
      <meta name="description" content="" />
      <meta content="Responsive Bootstrap Multi-Purpose Crypto Trading User Interface" name="description">
      <meta name="keywords" content="crypto, Bootstrap, bitcoins, ethereum, dogecoin, iota, ripple, siacoin, exchange, trading platform, crypto trading">
      <!-- Bootstrap -->
	  <link href="wb/img/favicon2.ico" rel="icon" />
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
	  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script> 
      <style type="text/css">
        .row:last-child { border-bottom: 1px dashed silver }
        .row > div { border-top: 1px dashed silver; border-left: 1px dashed silver }
        .row > div:last-child { border-right: 1px dashed silver }
      </style>
    </head>
    <body>

        <div class="container-fluid">
            <div class="row">
                <div class="col"></div>
                <div class="col-8">
                    <a href="https://www.cybertronchain.com/beta/index.new.php" target="_blank">회사소개</a>
                    <a href="#">연관서비스</a>
                    <a href="http://barrybarries.kr" target="_blank"><img src="wb/imgs/barrybarries.png" /></a>
                </div>
                <div class="col-2 text-right"><a href="javascript:;" onclick="changeLanguage('ko_KR')">KOR</a> | <a href="javascript:;" onclick="changeLanguage('en_US')">ENG</a></div>
                <div class="col"></div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-9">
                    <a href="#"><img src="wb/imgs/logo_coinibt.jpg" /></a>
                    <a href="/front2/exchange/index/BTC/NTR">거래소</a>
                    <a href="/front2/investment">투자/대출</a>
                    <a href="/front2/assets">자산 입출금</a>
                    <a href="/front2/wallet">자산조회</a>
                    <a href="/front2/customer/notice">고객센터</a>
                </div>
                <div class="col-2 text-right">
                    <input type="button" value="회원가입" class="join button btn" onclick="goJoin()" />
                    <input type="button" value="로그인" class="login button btn" onclick="goLogin()" />
                </div>
                <div class="col"></div>
            </div>
        </div>

        <div class="container">
            <div id="dashboard" class="row">
                <div class="col-sm">
                        <div class="percent"><span class="updown">▲</span> 1.40 %</div>
                        <div>
                            <span class="token">CTC</span><span class="base">/KRW</span>
                        </div>
                        <div>
                            <span class="amount">270,000</span><span class="unit">KRW</span>
                        </div>
                </div>
                <div class="col-sm">
						<div class="percent"><span class="updown">▲</span> 0.57 %</div>
						<div>
							<span class="token">TP3</span><span class="base">/KRW</span>
						</div>
						<div>
							<span class="amount">300,000</span><span class="unit">KRW</span>
						</div>
                </div>
                <div class="col-sm">
						<div class="percent"><span class="updown">▲</span> 0.44 %</div>
						<div>
							<span class="token">MKC</span><span class="base">/KRW</span>
						</div>
						<div>
							<span class="amount">127,000</span><span class="unit">KRW</span>
						</div>
                </div>
                <div class="col-sm">
						<div class="percent2"><span class="updown">▼</span> 0.44 %</div>
						<div>
							<span class="token">ETH</span><span class="base">/KRW</span>
						</div>
						<div>
							<span class="amount2">280,600</span><span class="unit">KRW</span>
						</div>
                </div>
                <div class="col-sm">
						<div class="percent2"><span class="updown">▼</span> 0.03 %</div>
						<div>
							<span class="token">THT</span><span class="base">/USDT</span>
						</div>
						<div>
							<span class="amount2">185,000</span><span class="unit">USDT</span>
						</div>
                </div>
            </div>
        </div>



		<div id="notice_block" style="display: none">
			<div class="container">
				<ul>
					<li class="title">공지사항</li>
					<li class="symbol">▲<br />▼</li>
					<li class="data">CTC 거래소가 2020년 7월 20일 가오픈하였습니다.</li>
					<li class="data_new">New</li>
					<li class="title" style="margin-left:100px">보도자료</li>
					<li class="symbol">▲<br />▼</li>
					<li class="data">CTC거래소는 블록체인 거래소의 신흥강자로써 회원수 60만명...</li>
				</ul>
			</div>
		</div>

		<div id="safety_join">
			<div class="container">
				<div class="row">
					<div class="col phone_bg">
					</div>
					<div class="col">
						<div class="title_1">안전한 우리 서비스와 함께 하세요!</div>
						<div class="title_2">간단한 회원가입</div>
						<ul>
							<li>1</li>
							<li>온라인 신청서를 작성하십시오.</li>
						</ul>
						<ul>
							<li>2</li>
							<li>계정승인이 될 때까지 기다려주세요.</li>
						</ul>
						<ul>
							<li>3</li>
							<li>디지털 자산 또는 Ringgit Malaysia를<br />입금하십시오.</li>
						</ul>
						<ul>
							<li>4</li>
							<li>이제 거래를 시작하세요!</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div id="specific">
			<div class="container">
				<div class="title_1">글로벌 최고 수준의 강력한 보안으로 안전한 거래를 추구합니다</div>
				<div class="title_2">전 세계 어디서든 24시간 거래가능 </div>

				<ul>
					<li>
						<div class="imgs">
							<img src="/wb/imgs/rebait.png" />
						</div>
						<div class="sub_title_1">
							거래수수료 리베이트
						</div>
						<div class="sub_title_2">
							메이커 수수료를 0.25 %<br />
							리베이트 합니다.
						</div>
					</li>
					<li>
						<div class="imgs" style="width:70px;">
							<img src="/wb/imgs/guard.jpg" />
						</div>
						<div class="sub_title_1">
							콜드 스토리지 보안
						</div>
						<div class="sub_title_2">
							디지털 자산의 보안과<br />
							마음의 평화를 보장합니다.
						</div>
					</li>
					<li>
						<div class="imgs" style="width:70px;">
							<img src="/wb/imgs/service.png" />
						</div>
						<div class="sub_title_1">
							실시간 지원
						</div>
						<div class="sub_title_2">
							문의및 요구사항을 지원하는<br />
							전담 상담사가 대기중입니다.
						</div>
					</li>
					<li>
						<div class="imgs" style="width:90px;">
							<img src="/wb/imgs/business.jpg" />
						</div>
						<div class="sub_title_1">
							비즈니스 계정
						</div>
						<div class="sub_title_2">
							디지털 자산 거래에 대한<br />
							탁월한 지원을 제공합니다.
						</div>
					</li>
				</ul>
			</div>
		</div>

		<div id="ctc_wallet">
			<div class="container">
				<div class="row">
					<div class="title_1">
						CTC WALLET<span>APP DOWN</span>
					</div>
					<div class="desc_1">
						손 쉽게 코인을 주고 받을 수 있는<br />
						안전한 지갑 CTC를 지금 다운받으세요.
					</div>
					<div class="imgs">
						<a target='_blank' href="https://play.google.com/store/apps/details?id=com.cybertronchain.wallet2"><img src="/wb/imgs/playstore.png" /></a>
					</div>
				</div>
			</div>
		</div>

		<div id="customer_center">
			<div class="container">
				<ul>
					<li class="phone">
						<div class="title">고객센터 (평일 10:00 ~ 18:00)</div>
						<div class="phone_no">1588-1644</div>
					</li>
					<li class="traninfo">
						<div class="title_2">금융사고 방지를 위한 추가 조치 안내</div>
						<div class="desc_2">금융사고 방지를 위한 추가 조치 안내</div>
					</li>
				</ul>
			</div>
		</div>

		<div id="bottom_menu">
			<div class="container">
				<a href="/front2/document/cominfo">회사정보</a>
				<a href="/front2/document/priceinfo">수수료 및 요금</a>
				<a href="/front2/document/reqdoc">인증자료 제출안내</a>
				<a href="/front2/document/usinginfo">이용약관</a>
				<a href="/front2/document/privacy">개인정보처리방침</a>
				<a href="/front2/customer/notice">고객센터</a>
			</div>
		</div>

		<div id="footer">
			<div class="container">
				<ul>
					<li class="address_block">
						<div class="logo"><img src="/wb/imgs/bottom_logo3.jpg" /></div>
						<div class="address">(주)한마음스마트 | 서울특별시 금천구 가산디지털1로 171, 4층 | 대표 김명희 | 대표전화 1566-1783 | 사업자등록번호  797-81-01586</div>
					</li>
					<li class="copyright_block">
						<div class="partner"><img src="/wb/imgs/nice.jpg" /><img src="/wb/imgs/shinhan.jpg" style="margin-left:25px"/></div>
						<div class="copyright">Copyrightⓒ Onemindsmart. All rights reserved.</div>
					</li>
				</ul>
			</div>
		</div>

		<script>
		function goLogin()  { document.location.href = "/front2/Users/login"; }
		function goLogout() { document.location.href = "/front2/Users/logout"; }
		function goJoin()   { document.location.href = "/front2/Users/signup"; }
		function changeLanguage(val){
			setCookie('Language', val, 365);
			document.location.reload();
		}
		function setCookie(name, val, exp) {
			var d = new Date();
			d.setTime(d.getTime() + exp*24*60*60*1000);
			document.cookie = name + "=" + val + "; path=/; expires=" + d.toUTCString() + ";";
		}
		</script>

    </body>
</html>