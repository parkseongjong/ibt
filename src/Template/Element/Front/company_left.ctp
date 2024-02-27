		<div class="menu_left">
			<h1>회사소개</h1>
			<p class="item">
				<a href="/front/document/cominfo" class="cominfo">회사소개</a>
			</p>
			<p class="item">
				<a href="/front/document/usinginfo" class="usinginfo">이용약관</a>
			</p>
			<p class="item">
				<a href="/front/document/privacy" class="privacy">개인정보처리방침</a>
			</p>
			<p class="item">
				<a href="/front/document/sitemap" class="sitemap">사이트맵</a>
			</p>
			<p class="item">
				<a href="/front/document/priceinfo" class="priceinfo">수수료 안내</a>
			</p>
			<p class="item">
				<a href="/front/document/digitalinfo" class="digitalinfo">디지털 자산 소개</a>
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