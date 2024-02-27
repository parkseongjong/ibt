<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/api-info.css">
<div class="body-container">
	<div class="coinibt-api-container">
		<div class="coinibt-api">
			<?php echo $this->element('Front2/api_info_left');?>
			<article class="contents-wrap">
				<div class="contents">
					<h2 class="title">
						<span>Ticker</span>
						<span class="bg-skyblue">Public API</span>
						<!--<span class="bg-pink">Private API</span>-->
				    </h2>
					<p class="txt01"><?=__("Provides price information of COINIBT Exchange's virtual asset.");?></p>
					<div class="code-line-box">
						<p>
							<span class="bg-gray">GET</span>
							<span class="tx">https://www.coinibt.io/api/public-api/ticker/{order_currency}_{payment_currency}</span>
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
	"trade_datetime" : "2021-04-28T02:34:13+0900",
	"trade_volume" : "95,760.00",
	"trade_price" : "30.40",
	"opening_price" : "30.40",
	"closing_price" : "30.40",
	"min_price" : "29.00",
	"max_price" : "30.78",,
	"prev_closing_price" : "30.00",
	"change" : "EVEN",
	"change_price" : "0.40",
	"change_rate" : "1.33",
	"acc_trade_value" : "653.11",
	"acc_trade_value_24H" : "5,659.24",
	"units_traded" : "1,146,223.62",
	"units_traded_24H" : "12,898,130.49"
  }
}
</pre>
					</code>
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
								<td>trade_datetime</td>
								<td><?=__("Latest Transaction Time");?></td>
								<td>String</td>
							</tr>
							<tr>
								<td>trade_volume</td>
								<td><?=__("Latest trading volume");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>trade_price</td>
								<td><?=__("Latest Transaction Amount");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>opening_price</td>
								<td><?=__("Opening Price Update time: 00");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>closing_price</td>
								<td><?=__("Closing price (Current Price)");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>min_price</td>
								<td><?=__("Lowest price (24hr)");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>max_price</td>
								<td><?=__("Highest price (24hr)");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>prev_closing_price</td>
								<td><?=__("Prev. Close2");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>change</td>
								<td><?=__("EVEN : EVEN / RISE : RISE / FALL : FALL");?></td>
								<td>String</td>
							</tr>
							<tr>
								<td>change_price</td>
								<td><?=__("Current rate of change from previous day (Current Price-Prev. Close)");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>change_rate</td>
								<td><?=__("Convert current rate of change to % over the previous day");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>acc_trade_value</td>
								<td><?=__("Today's transaction amount from 00:00");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>acc_trade_value_24H</td>
								<td><?=__("24hr Transaction amount");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>units_traded</td>
								<td><?=__("Today's transaction volume from 00:00");?></td>
								<td>Double</td>
							</tr>
							<tr>
								<td>units_traded_24H</td>
								<td><?=__("24hr Currency trading volume");?></td>
								<td>Double</td>
							</tr>
						</tbody>
					</table>
					<p class="table-sub-text"><span>※ <?=__("Current coin price (latest: based on time of call)");?><span></p>
				</div>
			</article>
		</div>
	</div>
</div>
