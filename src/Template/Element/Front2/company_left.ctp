		<div class="menu_left">
			<h1><?=__('About Us') ?></h1>
			<p class="item">
				<a href="/front2/document/cominfo" class="cominfo"><?=__('About Us') ?></a>
			</p>
			<!-- <p class="item">
				<a href="/front2/document/usinginfo" class="usinginfo"><?=__('Terms of Use') ?></a>
			</p>
			<p class="item">
				<a href="/front2/document/privacy" class="privacy"><?=__('Personal Information Processing Policy') ?></a>
			</p> -->
			<!--p class="item">
				<a href="/front2/document/priceinfo" class="priceinfo"><?=__('Fee Information') ?></a>
			</p-->
			<p class="item">
				<a href="/front2/customer/qna" class="qna"><a href="/front2/customer/qna" class="qna"><?=__('1:1 Inquiries') ?></a>
				<!--<a href="http://pf.kakao.com/_rWxdVK/chat" class="qna" target="_blank"><?/*=__('1:1 Inquiries') */?></a>-->
			</p>

		</div>

		<script>
		$(document).ready(function(){
		<?php if (isset($kind)) { ?>
			$(".<?=$kind ?>").addClass('on');
		<?php } ?>
		});
		</script>