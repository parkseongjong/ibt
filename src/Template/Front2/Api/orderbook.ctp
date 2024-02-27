<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/api-info.css">
<div class="body-container">
	<div class="coinibt-api-container">
		<div class="coinibt-api">
			<?php echo $this->element('Front2/api_info_left');?>
			<article class="contents-wrap">
				<div class="contents">
					<h2 class="title">
						<span>Orderbook</span>
						<span class="bg-skyblue">Public API</span>
						<!--<span class="bg-pink">Private API</span>-->
				    </h2>
					<p class="txt01"><?=__("Provides price information of COINIBT Exchange's virtual asset.");?></p>
					<div class="code-line-box">
						<p>
							<span class="bg-gray">GET</span>
							<span class="tx">https://www.coinibt.io/api/public-api/orderbook/{order_currency}_{payment_currency}</span>
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
  "data" : {
    "timestamp" : 1619577879,
    "total_ask_size" : "22,503,164.92",
    "total_bid_size" : "74,336,866.65",
    "asks" : [
    {
      "quantity" : "15089.00",
      "price" : "34.20"
    },
    {
      "quantity" : "4000.00",
      "price" : "34.10"
    }
  ],
    "bids" : [
    {
      "quantity" : "42070.00",
      "price" : "29.00"
    },
    {
      "quantity" : "2050.00",
      "price" : "28.71"
    }
   ]
  }
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
								<td>1~30 (<?=__("Coin2");?>)
									<br /><?=__("Default");?> 30</td>
								<td rowspan="2">Integer</td>
							</tr>
							<tr>
								<td>1~5(ALL)  <?=__("Default");?> 5</td>
							</tr>
						</tbody>
					</table>
					<p class="table-sub-text sub-text-left"><span></span></p>
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
								<td><?=__("Current Timestamp");?></td>
								<td>Integer(String)</td>
							</tr>
							<tr>
								<td>total_ask_size</td>
								<td><?=__("Total Remaining Ask");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>total_bid_size</td>
								<td><?=__("Total Remaining Buy");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>asks</td>
								<td><?=__("Ask request history");?></td>
								<td>Array[Object]</td>
							</tr>
							<tr>
								<td>bids</td>
								<td><?=__("Buy request history");?></td>
								<td>Array[Object]</td>
							</tr>
							<tr>
								<td>quantity</td>
								<td><?=__("Currency quantity");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>price</td>
								<td><?=__("Transacted price per currency");?></td>
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
