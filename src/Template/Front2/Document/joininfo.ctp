<style type="text/css">
.category {
	font-size: 18px;
	font-weight: 500;
	font-stretch: normal;
	font-style: normal;
	line-height: 3.67;
	letter-spacing: normal;
}

dl {
	margin: 0;
}
dt {
	border: solid 1px #bfbfbf;
	padding: 16px 16px 24px;
	margin-bottom: 20px;
	text-align: center;
}
dd {
	font-size: 18px;
	font-weight: 400;
	font-stretch: normal;
	font-style: normal;
	line-height: 2.67;
	letter-spacing: normal;
	padding-left: 16px;
	margin: 0;
}

@media only screen and (max-width: 767px) {
dd {
    font-size: 15px;
    line-height: 2;
    padding-left: 10px;
    margin: 0;
}
}

</style>

<div class="container">

	<div class="custom_frame document">

		<?php echo $this->element('Front2/customer_left'); ?>

		<div class="contents">

			<li class="title"><?=__('Membership Registration') ?></li>

			<div class="category">
				1. <?=__('joininfo_01')?>
			</div>

				<dl>
					<dt>
						<img class="re_img" src="/wb/imgs/joininfo_01.jpg" />
					</dt>
					<dd>
						A. <?=__('joininfo_01_01')?>
					</dd>
				</dl>

			<div class="category category_mt_80" >
				2. <?=__('joininfo_02')?>
			</div>

				<dl>
					<dt>
						<img class="re_img" src="/wb/imgs/joininfo_02.jpg" />
					</dt>
					<dd>
						A. <?=__('joininfo_02_01')?>
					</dd>
					<dd>
						B. <?=__('joininfo_02_02')?>
					</dd>
					<dd>
						C. <?=__('joininfo_02_03')?>
					</dd>
					<dd>
						D. <?=__('joininfo_02_04')?>
					</dd>
					<dd>
						E. <?=__('joininfo_02_05')?>
					</dd>
					<dd>
						F. <?=__('joininfo_02_06')?>
					</dd>
				</dl>

		</div>
		<div class="cls"></div>

	</div>

</div>
