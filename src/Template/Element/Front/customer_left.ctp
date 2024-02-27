		<div class="menu_left">
			<h1>고객센터</h1>
			<p class="item">
				<a href="/front/customer/notice" class="notice">공지사항</a>
			</p>
			<p class="item">
				<a href="/front/customer/faq" class="faq">자주 묻는 질문(FAQ)</a>
			</p>
			<p class="item">
				<a href="/front/document/deallimit" class="deallimit">회원레벨별 입/출금 한도 안내</a>
			</p>
			<p class="item">
				<a href="/front/document/reqdoc" class="reqdoc">인증자료 제출안내</a>
			</p>
			<p class="item">
				<a href="/front/customer/joininfo" class="joininfo">회원가입안내</a>
			</p>
			<p class="item">
				<a href="/front/customer/authinfo" class="authinfo">인증 방법 안내</a>
			</p>
			<p class="item">
				<a href="/front/customer/membership" class="membership">VIP 멤버십</a>
			</p>

			<div class="buttons">
				<div class="button">1:1 채팅상담</div>
				<div class="button">카카오톡 상담</div>
			</div>

		</div>

		<script>
		$(document).ready(function(){
		<?php if (isset($kind)) { ?>
			$(".<?=$kind ?>").addClass('on');
		<?php } ?>
		});
		</script>