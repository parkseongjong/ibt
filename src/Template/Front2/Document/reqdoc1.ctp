<div class="container">

	<div class="custom_frame">

		<?php echo $this->element('Front2/customer_left'); ?>

		<div class="contents">
			<ul>
				<li class="title"><?=__('Information on submitting certification data') ?></li>
			</ul>

			<?php echo $this->element('Front2/reqdoc_menu'); ?>

			<div class="reqdoc_tab">

				<div class="cate_title">

					<h1><?=__('Notice before change') ?></h1>

					<div class="cate_desc">
						· <?=__('Before change 1') ?><br />
						· <?=__('Before change 2') ?><br />
						· <?=__('Before change 3') ?><br />
						· <?=__('Before change 4') ?><br />
						· <?=__('Before change 5') ?><br />
					</div>

				</div>

				<h2>
					<?=__('Documents submitted') ?> <span>(<?=__('Documents submitted notice') ?>)</span>
				</h2>

				<div class="person_card">
					<h3>1. <?=__('Photo of yourself') ?> </h3>

					<ul class="person">
						<li style="border:1px solid #bfbfbf; padding:25px 39px 0 40px"><img src="/wb/imgs/reqdoc_person1.jpg" /></li>
						<li class="id_li">
							<p>- <?=__('Photo yourself 1') ?></p>
							<p>- <?=__('Photo yourself 2') ?></p>
							<p>- <?=__('Photo yourself 3') ?></p>
						</li>
					</ul>

					<h3>2. <?=__('ID photo') ?></h3>

					<ul class="person">
						<li style="border:1px solid #bfbfbf; padding:70px 19px 16px 20px"><img src="/wb/imgs/reqdoc_person2.jpg" /></li>
						<li style="margin-left:30px; margin-top:0px">
							<p>- <?=__('ID photo 1') ?></p>
							<p>- <?=__('ID photo 2') ?></p>
							<p>- <?=__('ID photo 3') ?></p>
							<p style="color:#ff0000">- <?=__('ID photo 4') ?></p>
						</li>
					</ul>

				</div>

				<h2>
					<?=__('Example email') ?>
				</h2>

				<div class="email_ex">
					<p>
						- <?=__('Example email 1') ?><br />
						- <?=__('Example email 2') ?>
					</p>

					<table>
						<tbody>
							<tr>
								<th style="width:21%"><?=__('Email subject') ?></th>
								<td style="line-height:2.25; font-weight:300">
									<?=__('Email subject text') ?></td>
							</tr>
							<tr>
								<th style="width:21%"><?=__('Email address to send') ?></th>
								<td style="line-height:2.25; font-weight:300">
									cs@coinibt.com</td>
							</tr>
							<tr>
								<th style="width:21%"><?=__('Body required') ?></th>
								<td style="line-height:2.25; font-weight:300">
									<?=__('Body required text') ?>
								</td>
							</tr>
							<tr>
								<th style="width:21%"><?=__('Attachments') ?></th>
								<td style="line-height:2.25; font-weight:300">
									<?=__('Attachments text') ?>
								</td>
							</tr>
						</tbody>
					</table>

				</div>

			</div>

		</div>
<div class="cls"></div>
	</div>

</div>
