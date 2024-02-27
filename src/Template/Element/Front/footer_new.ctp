		<div id="bottom_menu">
			<div class="container">
				<a href="/front/document/cominfo">회사정보</a>
				<a href="/front/document/priceinfo">수수료 및 요금</a>
				<a href="/front/document/reqdoc">인증자료 제출안내</a>
				<a href="/front/document/usinginfo">이용약관</a>
				<a href="/front/document/privacy">개인정보처리방침</a>
				<a href="/front/customer/notice">고객센터</a>
			</div>
		</div>

		<div id="footer">
			<div class="container">
				<ul>
					<li class="address_block">
						<div class="logo"><img src="/wb/imgs/bottom_logo2.jpg" /></div>
						<div class="address">(주)한마음스마트 | 서울특별시 금천구 가산디지털1로 171, 4층 | 대표 김명희 | 사업자등록번호  849-88-01299</div>
					</li>
					<li class="copyright_block">
						<div class="partner"><img src="/wb/imgs/nice.jpg" /><img src="/wb/imgs/shinhan.jpg" style="margin-left:25px"/></div>
						<div class="copyright">Copyrightⓒ Onemindsmart. All rights reserved.</div>
					</li>
				</ul>
			</div>
		</div>

		<script>
		function goLogin(){
			document.location.href = "/front/UsersNew/login";
		}
		function goLogout() {
			document.location.href = "<?php echo $this->Url->build(['controller'=>'usersNew','action'=>'logout']); ?>";
		}
		function goJoin(){
			document.location.href = "/front";
		}
		</script>
