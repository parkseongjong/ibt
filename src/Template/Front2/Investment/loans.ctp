
<div class="container">

	<div class="profile_box">

		<?php echo $this->element('Front2/investment_menu'); ?>

		<div class="service_info">

			<div class="img_box img_box2 img_box_a">
				<img src="/wb/imgs/investment_loans1-2.png" alt="investment" class="main_img loan-img" />
				<div class="">
					<p>
						<?=__('Rental Service') ?>
						<!-- <button>
							<span>
								<?=__('Details') ?>
							</span>
							<img src="/wb/imgs/investment_arrow.png" />
						</button> -->
					</p>
					
					<p><?=__('Lease Service always rental') ?></p>
					<button class="details" onclick="openDetailDialog()">
						<span><?=__('Details') ?></span>
						<img src="/wb/imgs/investment_arrow-right-white.svg" />
					</button>
					<p><?=__('Lease Service Borrow') ?></p>
					<p><?=__('Lease Service Borrow and Pay') ?></p>
					<p><?=__('Lease Service Borrowed Amount') ?>
				</div>
				<a class="check-terms-area" href="/front2/document/terms-rental">
					<span><?=__('Go to Terms') ?></span>
					<img src="/wb/imgs/investment_arrow-right-black.svg" />
				</a>
			</div>

			<ul class="ul_ex">
				<li>
					<p><?=__('Usage Fee') ?></p>
					<p><?=__('Free') ?></p>
				</li>
				<li class="bar"></li>
				<li>
					<p><?=__('Maximum Limit') ?></p>
					<p><?=__('Two million coins') ?></p>
				</li>
				<li class="bar"></li>
				<li>
					<p><?=__('Period') ?></p>
					<p><?=__('Lease Service Period') ?></p>
				</li>
			</ul>

			<div class="btn_box">
				<div class="btn">
					<?=__('Rental Service preparation') ?>
				</div>
			</div>

			<div class="text_box2">
				<!-- <?=__('Lease Service Notice') ?> -->
				<ul>
					<li>
						<?=__('rental text 001') ?>
					</li>
					<li>
						<?=__('rental text 002') ?>
					</li>
				</ul>
			</div>


		</div>

	</div>

</div>

<!-- detail-dialog -->
<div class="detail-dialog modal" id="detail-dialog" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<h1 class="modal-title">
				<?= __('Rental Service') ?>
			</h1>
			<h2 class="modal-subtitle">
				<?= __('rental modal 001') ?>
			</h2>

			<ul class="desc-list">
				<li class="desc-list-item">
					<?= __('rental modal 002') ?>
				</li>
				<li class="desc-list-item">
					<?= __('rental modal 003') ?>
				</li>
			</ul>

			<div class="modal-confirm-button" onclick="closeDetailDialog()">
				<?= __('Confirm') ?>
			</div>
		</div>
	</div>
</div>
<!-- /detail-dialog -->

<script>
	function openDetailDialog() {
		$('#detail-dialog').modal('show');
	}
	function closeDetailDialog() {
		$('#detail-dialog').modal('hide');
	}
</script>
