<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/api-info.css">
<div class="body-container">
	<div class="coinibt-api-container">
		<div class="coinibt-api">
			<?php echo $this->element('Front2/api_info_left');?>
			<article class="contents-wrap">
				<div class="contents">
					<h2 class="title">
						<span><?=__("Error Code");?></span>
				    </h2>
					<p class="txt01"><?=__("COINIBT API request failure incidents will cause an error code.");?></p>
					
					<!-- Error Message -->
					<h3 class="title">Error Message</h3>
					<table class="basic-table">
						<colgroup>
							<col  />
							<col width="40%"/>
							<col width="40%" />
						</colgroup>
						<thead>
							<tr>
								<th><?=__("Error Code");?></th>
								<th><?=__("Error Message");?></th>
								<th><?=__("Description");?></th>
							</tr>
						</thead>
						<tbody>
							<!--<tr>
								<td>5100</td>
								<td>Bad Request(SSL)</td>
								<td>https 호출 URL이 아님</td>
							</tr>-->
							<tr>
								<td>5200</td>
								<td>Not Member</td>
								<td><?=__("Not a member of COINIBT exchange.");?></td>
							</tr>
							<tr>
								<td rowspan="2">5300</td>
								<!-- <td>정상적으로 처리되지 못했습니다. <br>잠시 후 다시 이용해 주십시오.</td> -->
								<td><?=__("error code 5200 error message");?></td>
								<td><?=__("DB error");?></td>
							</tr>
							<tr>
								<!-- <td>시스템이 원활하지 않습니다. 잠시 후 다시 시도해 주세요.</td> -->
								<td><?=__("error code 5200 error message");?></td>
								<td><?=__("Specific value lookup failed");?></td>
							</tr>
							<tr>
								<td>5301</td>
								<td>This coin is not supported</td>
								<td><?=__("This coin is not supported in COINIBT");?></td>
							</tr>
							<tr>
								<td>5302</td>
								<td>This transaction is not supported</td>
								<td><?=__("This transaction is not supported in COINIBT");?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</article>
		</div>
	</div>
</div>
