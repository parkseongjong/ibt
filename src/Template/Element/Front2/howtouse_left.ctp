<div class="menu_left">
			<h1>사용설명서</h1>
			<p class="item">
				<a href="/front2/howtouse/normaluser" class="normaluser">일반회원/연간회원</a>
			</p>
			<p class="item">
				<a href="/front2/howtouse/normaluser" class="deal-coupon-rules">거래 쿠폰 규정</a>
			</p>
			<p class="item">
				<a href="/front2/howtouse/normaluser" class="deposit">예치</a>
			</p>
			<p class="item">
				<a href="/front2/howtouse/normaluser" class="deal-info">거래안내</a>
			</p>
		</div>

		<script>
		$(document).ready(function(){
    <?php 
      if (isset($kind)) { 
    ?>
			$(".<?=$kind ?>").addClass('on');
      console.log('addclass');
		<?php } ?>
		});
    </script>