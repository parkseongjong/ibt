		<div class="menu_left">
			<h1><?=__('Commission Coupon') ?></h1>
			<p class="item">
				<a href="/front2/document/commission" class="notice"><?=__('Commission Coupon') ?></a>
			</p>
			<p class="item">
				<a href="/front2/document/coupon" class="faq"><?=__('Coupon Usage Status') ?></a>
			</p>

		</div>

        <script>
            $(document).ready(function(){
                <?php if (isset($kind)) { ?>
                $(".<?=$kind ?>").addClass('on');
                <?php } ?>
            });
        </script>