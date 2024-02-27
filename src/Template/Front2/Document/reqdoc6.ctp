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

					<h1>Notes before withdrawal</h1>

					<div class="cate_desc">
						· When a user withdraws from service membership, the company keeps personal information for one year to prevent illegal use.<br /> 
						  &nbsp;&nbsp;&nbsp;&nbsp;In this case, personal information is kept in a non-identifiable state, and re-registration with the same ID is not possible.<br />
						· In case of Payment processing services are provided,<br />
							&nbsp;&nbsp;&nbsp;&nbsp;According to the provisions of related laws and regulations, if there are any remaining assets, the withdrawal process is completed after writing the asset renunciation memorandum and handwritten signature.<br />
						· Virtual assets that are incorrectly deposited into the account with which they have been withdrawn cannot be recovered.<br />
						· Paid/free services in use will automatically expire.<br />
					</div>

				</div>

				<h2>
					<?=__('Documents submitted') ?> 
					<span>
						(Please hide the last digit of your ID number on each document.)
					</span>
				</h2>

				<div class="person_card person_card_do">
					<h3>
						· If you do not have any assets at the time of application for withdrawal, you will be immediately withdrawn.<br />
						· If there are any assets left, download the asset waiver form from the screen below and sign it by hand <br />
							&nbsp;&nbsp;&nbsp;Scan and upload the asset waiver file to complete the withdrawal request.
					</h3>

					<div style="border:1px dashed #b5b5b5; padding-top:24px; margin-top:20px; text-align:center" onclick="onClickDownload()">
						<img src="/wb/imgs/download_doc.jpg" />
						<div class="do_do">
							Download asset relinquishment document
							&nbsp;&nbsp;&nbsp;<img src="/wb/imgs/download_down.png" />
						</div>
					</div>

				</div>

				<h2>
					Example of filling out documents to be submitted
				</h2>

				<div class="email_ex">
					<p>
						- If additional confirmation is required after reviewing the uploaded data, a person in charge will contact you. It may take 1-3 days or more after receiving the documents.<br />
						- For inquiries other than withdrawal request, please use 1:1 inquiry.
					</p>

					<!-- <table>
						<tbody>
							<tr>
								<th style="width:21%"><?=__('Email subject') ?></th>
								<td >
									<?=__('Email subject text5') ?></td>
							</tr>
							<tr>
								<th style="width:21%"><?=__('Email address to send') ?></th>
								<td >
									cs@coinibt.com</td>
							</tr>
							<tr>
								<th style="width:21%"><?=__('Body required') ?></th>
								<td >
									<?=__('Body required text') ?>
								</td>
							</tr>
							<tr>
								<th style="width:21%"><?=__('Attachments') ?></th>
								<td >
									<?=__('Attachments text5') ?>
								</td>
							</tr>
						</tbody>
					</table> -->
					<div class="asset-disclaimer-sample-wrapper">
<!-- 						<img src="/wb/imgs/asset-disclaimer-sample.png" alt="자산포기각서 샘플" class="asset-disclaimer-sample-img" /> -->
						<!-- 210906 업데이트 -->
						<img src="/wb/imgs/asset-disclaimer-sample.jpg" alt="Sample asset disclaimer" class="asset-disclaimer-sample-img" width="340" height="480" />
					</div>

				</div>

			</div>

		</div>
<div class="cls"></div>
	</div>

</div>
<script>
	function onClickDownload() {
		window.location.href="/front2/leaving/asset-waiver-download";
	}
</script>