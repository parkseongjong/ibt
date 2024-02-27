<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/api-info.css">
<div class="body-container">
	<div class="coinibt-api-container">
		<div class="coinibt-api">
			<?php echo $this->element('Front2/api_info_left');?>
			<article class="contents-wrap">
				<div class="contents">
					<h2 class="title">
						<span>Transaction History</span>
						<span class="bg-skyblue">Public API</span>
<!-- 						<span class="bg-pink">Private API</span> -->
				    </h2>
					<p class="txt01"><?=__("Provides COINIBT Exchange virtual asset concluded transaction history.");?></p>
					<div class="code-line-box">
						<p>
							<span class="bg-gray">GET</span>
							<span class="tx">https://www.coinibt.io/api/public-api/transaction-history/{order_currency}_{payment_currency}</span>
						</p>
					</div>
					<div class="parameter-explain">
						<p class="txt02">
							<span>{order_currency} = <?=__("Order currency(Coin)");?>, <?=__("Default");?> :CTC</span>
						</p>
						<p class="txt02">
							<span>{payment_currency} = <?=__("Payment currency(Market)");?>,
								<span class="color-red"><?=__("Value");?> : KRW, <?=__("Default");?> : KRW</span>
							</span>
						</p>
					</div>
					<!-- Example Response -->
					<h3 class="title">Example Response</h3>
					<code class="code-bg-box">
<pre>
{
  "status" : "200",
  "message": "success",
  "data" : [
  {
    "timestamp" : "1619578283",
    "type" : "bid",
    "amount" : "214.00",
    "price" : "810.00",
    "total" : "173340.00"
  },
  {
    "timestamp" : "1619576014",
    "type" : "ask",
    "amount" : "39.00",
    "price" : "810.00",
    "total" : "31590.00"
  }
 ]
}</pre>
					</code>
					<!-- Request Parameter -->
					<h3 class="title">Request Parameters</h3>
					<table class="basic-table">
						<colgroup>
							<col width="25%" />
							<col />
							<col width="25%" />
						</colgroup>
						<thead>
							<tr>
								<th><?=__("Parameter");?></th>
								<th><?=__("Description");?></th>
								<th><?=__("Type2");?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td rowspan="2">count</td>
								<td>1~100 (<?=__("Coin2");?>)
									<br /><?=__("Default");?> 20</td>
								<td rowspan="2">Integer</td>
							</tr>
							<tr>
								<td>1~5(ALL) <?=__("Default");?>: 5</td>
							</tr>
						</tbody>
					</table>
<!-- 					<p class="table-sub-text sub-text-left"> <span>※ 최근 : API 호출 했을 당시 시간 기준</span></p> -->
					<!-- Response -->
					<h3 class="title">Response</h3>
					<table class="basic-table">
						<colgroup>
							<col width="25%" />
							<col />
							<col width="25%" />
						</colgroup>
						<thead>
							<tr>
								<th><?=__("Field");?></th>
								<th><?=__("Description");?></th>
								<th><?=__("Type2");?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>status</td>
								<td><?=__("Result Status Code");?> (<?=__("Normal2");?>: 200, <?=__("Other2");?> <a href="/front2/api/codeinfo"><?=__("Refer To Error Code");?></a>)</td>
								<td>String</td>
							</tr>
							<tr>
								<td>timestamp</td>
								<td><?=__("Timestamp of transaction");?></td>
								<td>Integer(String)</td>
							</tr>
							<tr>
								<td>type</td>
								<td><?=__("Transaction Type");?> (bid : <?=__("bid");?>, ask : <?=__("ask");?>)</td>
								<td>String</td>
							</tr>
							<tr>
								<td>amount</td>
								<td><?=__("Trading volume");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>price</td>
								<td><?=__("Transaction amount per 1 Currency");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>total</td>
								<td><?=__("Total trading volume");?></td>
								<td>Double</td>
							</tr>
						</tbody>
					</table>
<!-- 					<p class="table-sub-text"> <span>※ 최근 : API 호출 했을 당시 시간 기준</span></p> -->
				</div>
			</article>
		</div>
	</div>
</div>

