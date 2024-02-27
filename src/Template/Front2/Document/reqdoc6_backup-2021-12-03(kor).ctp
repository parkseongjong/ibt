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

					<h1>탈퇴 전 유의사항</h1>

					<div class="cate_desc">
						· 사용자가 서비스 회원을 탈퇴할 경우 회사는 부정이용을 방지하기 위하여 1년간 개인정보를 보관합니다.<br /> 
						  &nbsp;&nbsp;&nbsp;&nbsp;이 경우 개인정보는 식별할 수 없는 상태로 보관되며 동일한 ID로 재가입이 불가합니다.<br />
						· 결제 처리에 대한 서비스는 전자상거래 등에서의 소비자보호에 관한 법률 제6조, 특정금융거래정보의 보고 및 이용 등에 관한 법률 제5조의 4 <br />
						  &nbsp;&nbsp;&nbsp;&nbsp;관계법령의 규정에 의거하여, 자산이 남아 있을 경우 자산포기각서 및 수기 서명 작성 후 탈퇴처리가 완료됩니다.<br />
						· 탈퇴한 계정으로 오입금되는 가상 자산은 회수 불가합니다.<br />
						· 이용 중인 유/무료 서비스는 자동 소멸됩니다.<br />
					</div>

				</div>

				<h2>
					<?=__('Documents submitted') ?> 
					<span>
						(각 서류의 주민번호 뒷자리 뒷자리는 가려주시기 바랍니다.)
					</span>
				</h2>

				<div class="person_card person_card_do">
					<h3>
						· 탈퇴 신청 시 자산이 없는 경우는 즉시 탈퇴 됩니다.<br />
						· 자산이 남아 있을 경우, 아래 화면에서 자산포기각서 양식을 다운로드 받으시고 자필 서명 후 <br />
						&nbsp;&nbsp;&nbsp;스캔하여 자산포기각서 파일을 업로드 해주시면 탈퇴 신청이 완료됩니다.
					</h3>

					<div style="border:1px dashed #b5b5b5; padding-top:24px; margin-top:20px; text-align:center" onclick="onClickDownload()">
						<img src="/wb/imgs/download_doc.jpg" />
						<div class="do_do">
							자산포기각서 다운로드
							&nbsp;&nbsp;&nbsp;<img src="/wb/imgs/download_down.png" />
						</div>
					</div>

				</div>

				<h2>
					제출 서류 작성 예시
				</h2>

				<div class="email_ex">
					<p>
						- 올려주신 자료 검토 후 추가적인 확인이 필요할 경우 담당자가 연락을 드립니다. 서류접수 후 1~3일 이상 소요될 수 있습니다.<br />
						- 탈퇴신청 건 외 문의는 1:1 문의하기를 이용해 주세요.
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
						<img src="/wb/imgs/asset-disclaimer-sample.jpg" alt="자산포기각서 샘플" class="asset-disclaimer-sample-img" width="340" height="480" />
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