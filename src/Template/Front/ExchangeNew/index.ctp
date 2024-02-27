<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/new_exchange.css">

<div class="container" style="padding: 12px 0; margin-bottom: 50px;">

	<div class="exchange_frame">

		<div class="section_ex left_info">
			<ul class="coin_tab">
				<li class="on">KRW</li>
				<li>ETH</li>
				<li>BTC</li>
				<li>USDT</li>
			</ul>

			<div class="opt_row">
				<label><input type="radio" name="" value="" /> 보유코인만보기 </label>
			</div>

			<table class="list">
				<thead>
					<tr>
						<td>종목명</td>
						<td></td>
						<td>가격</td>
					</tr>
				</thead>
			</table>

			<div style="background:#fff; height:320px; overflow-x: hidden; overflow-y:auto">
				<table class="list">
				<tbody>
					<tr class="on">
						<td class="left"><span class="bold">USDT</span> (KRW)</td>
						<td></td>
						<td class="right">1.00000000</td>
					</tr>
					<tr>
						<td class="left"><span class="bold">ETH</span> (KRW)</td>
						<td></td>
						<td class="right">248.00000000</td>
					</tr>
					<tr>
						<td class="left"><span class="bold">CTC</span> (KRW)</td>
						<td></td>
						<td class="right">0.200000000</td>
					</tr>
					<tr>
						<td class="left"><span class="bold">TP3</span> (KRW)</td>
						<td></td>
						<td class="right">50.00000000</td>
					</tr>
					<tr>
						<td class="left"><span class="bold">MC</span> (KRW)</td>
						<td></td>
						<td class="right">0.00100000</td>
					</tr>
					<tr>
						<td class="left"><span class="bold">BTC</span> (KRW)</td>
						<td></td>
						<td class="right">9,650.00000000</td>
					</tr>
				</tbody>
				</table>
			</div>

			<div class="box_title">
				마켓 내역
			</div>

			<table class="list">
				<thead>
					<tr>
						<td class="left">날짜</td>
						<td class="left">가격</td>
						<td class="right">금액(원)</td>
					</tr>
				</thead>
			</table>

			<div style="background:#fff; height:616px; overflow-x: hidden; overflow-y:auto">
				<table class="list">
				<tbody>
					<tr>
						<td class="left"><div class="bold">2020-06-20</div>03:30:25</td>
						<td class="left"><div class="red">50.00000000</div></td>
						<td class="right">500.00000000</td>
					</tr>
					<tr>
						<td class="left"><div class="bold">2020-06-20</div>03:30:25</td>
						<td class="left"><div class="red">50.00000000</div></td>
						<td class="right">2,000.00000000</td>
					</tr>
					<tr class="on">
						<td class="left"><div class="bold">2020-06-20</div>03:30:25</td>
						<td class="left"><div class="red">50.00000000</div></td>
						<td class="right">100.00000000</td>
					</tr>
					<tr>
						<td class="left"><div class="bold">2020-06-20</div>03:30:25</td>
						<td class="left"><div class="blue">50.00000000</div></td>
						<td class="right">2,000.00000000</td>
					</tr>
					<tr>
						<td class="left"><div class="bold">2020-06-20</div>03:30:25</td>
						<td class="left"><div class="blue">50.10000000</div></td>
						<td class="right">20.00000000</td>
					</tr>
				</tbody>
				</table>
			</div>

		</div>

		<div class="section_ex contents">
			<div class="base_coin">
				<table>
				<tr>
					<td class="left">
						<span class="token">TP3</span><span class="amount">15.75</span><span class="unit">KRW</span>
					</td>
					<td class="right">
						<ul class="results">
							<li>전일대비<br /><span class="red">+1.07%</span></li>
							<li>최저가<br /><span class="blue">15.66</span></li>
							<li>최고가<br /><span class="red">15.88</span></li>
						</ul>
					</td>
				</tr>
				</table>
			</div>

			<div class="base_graph">
			</div>

			<div class="tranx">
				<table>
				<tr>
					<td class="buy">

						<ul class="opt_row">
							<li>
								<h2>매수</h2>
							</li>
							<li style="text-align:left; margin-left:20px; width: 187px; line-height:4">
								<label><input type="radio" name="buy_control" value="1" checked /> 지정가 </label>
								<label><input type="radio" name="buy_control" value="2" /> 시장가 </label>
							</li>
						</ul>

						<ul class="row">
							<li>
								<span class="bold">주문가능</span>
							</li>
							<li>
								0 <span class="bold unit">원(KRW)</span>
							</li>
						</ul>

						<ul class="row">
							<li>
								가격 (KRW)
							</li>
							<li>
								<div class="price2">
									<input type="text" name="" value="0" />
								</div>
							</li>
						</ul>

						<ul class="row">
							<li>
								수량 (TP3)
							</li>
							<li>
								<div class="price2">
									<input type="text" name="" value="0" />
								</div>
							</li>
						</ul>

						<ul class="row">
							<li>
								<span class="bold">주문금액</span>
							</li>
							<li>
								<span class="bold price">0.000055000</span> (KRW)
							</li>
						</ul>

						<input type="button" name="" class="button buy" value="구매" />

					</td>
					<td class="sell">

						<ul class="opt_row">
							<li>
								<h2>매도</h2>
							</li>
							<li style="text-align:left; margin-left:20px; width: 187px; line-height:4">
								<label><input type="radio" name="sell_control" value="1" /> 지정가 </label>
								<label><input type="radio" name="sell_control" value="2" checked /> 시장가 </label>
							</li>
						</ul>

						<ul class="row">
							<li>
								주문가능
							</li>
							<li>
								0 <span class="bold unit">(TP3)</span>
							</li>
						</ul>

						<ul class="row">
							<li>
								수량 (TP3)
							</li>
							<li>
								<div class="price2">
									<input type="text" name="" value="0" />
								</div>
							</li>
						</ul>

						<!-- empty : need space -->
						<ul class="row">
							<li>&nbsp;</li>
						</ul>
						<ul class="row">
							<li>&nbsp;</li>
						</ul>
						<!-- empty : need space -->

						<input type="button" name="" class="button sell" value="판매" />

					</td>
				</tr>
				</table>

			</div>

		</div>

		<div class="section_ex right_info">
			<table class="list">
				<thead>
					<tr>
						<td style="width: 30%">가격</td>
						<td style="width: 33%">매매수량</td>
						<td style="width: 37%">전체수량</td>
					</tr>
				</thead>
			</table>
			<table class="result">
				<thead>
					<tr class="sell">
						<td>매도 주문잔량</td>
						<td class="right">10,747.4712</td>
					</tr>
				</thead>
			</table>
			<div style="background:#fff; height:730px; overflow-x: hidden; overflow-y:auto">
				<table class="list">
				<tbody>
					<tr>
						<td><div class="bold blue">1,245</div></td>
						<td>103.1281</td>
						<td class="right">10,747.4712</td>
					</tr>
					<tr>
						<td><div class="bold blue">1,245</div></td>
						<td>103.1281</td>
						<td class="right">10,747.4712</td>
					</tr>
					<tr>
						<td><div class="bold blue">1,245</div></td>
						<td>103.1281</td>
						<td class="right">10,747.4712</td>
					</tr>
					<tr>
						<td><div class="bold blue">1,245</div></td>
						<td>103.1281</td>
						<td class="right">10,747.4712</td>
					</tr>
					<tr>
						<td><div class="bold blue">1,245</div></td>
						<td>103.1281</td>
						<td class="right">10,747.4712</td>
					</tr>
					<tr>
						<td><div class="bold blue">1,245</div></td>
						<td>103.1281</td>
						<td class="right">10,747.4712</td>
					</tr>
					<tr>
						<td><div class="bold blue">1,245</div></td>
						<td>103.1281</td>
						<td class="right">10,747.4712</td>
					</tr>
					<tr>
						<td colspan="3">
							<div class="updown_rate">1.24<span class="updown_arrow" style="font-size:16px; line-height: 1;">▲</span></div>
						</td>
					</tr>
					<tr>
						<td><div class="bold red">1,197</div></td>
						<td>103.1281</td>
						<td class="right">3,747.4712</td>
					</tr>
					<tr>
						<td><div class="bold red">1,197</div></td>
						<td>103.1281</td>
						<td class="right">3,747.4712</td>
					</tr>
					<tr>
						<td><div class="bold red">1,197</div></td>
						<td>103.1281</td>
						<td class="right">3,747.4712</td>
					</tr>
					<tr>
						<td><div class="bold red">1,197</div></td>
						<td>103.1281</td>
						<td class="right">3,747.4712</td>
					</tr>
					<tr>
						<td><div class="bold red">1,197</div></td>
						<td>103.1281</td>
						<td class="right">3,747.4712</td>
					</tr>
					<tr>
						<td><div class="bold red">1,197</div></td>
						<td>103.1281</td>
						<td class="right">3,747.4712</td>
					</tr>
					<tr>
						<td><div class="bold red">1,197</div></td>
						<td>103.1281</td>
						<td class="right">3,747.4712</td>
					</tr>
				</tbody>
				</table>
			</div>
			<table class="result">
				<thead>
					<tr class="buy">
						<td>매수 주문잔량</td>
						<td class="right">10,747.4712</td>
					</tr>
				</thead>
			</table>
		</div>

		<div class="section_ex history2">
			<ul class="tab_menu">
				<li class="on">구매 주문</li>
				<li>판매 주문</li>
			</ul>

			<table class="list">
				<thead>
					<tr>
						<td>주문 일시</td>
						<td>TP3당 가격</td>
						<td>TP3 금액</td>
						<td>원화 금액</td>
						<td>상태</td>
					</tr>
				</thead>
			</table>

			<div style="background:#fff; height:170px; overflow-x: hidden; overflow-y:auto">
				<table class="list">
				<tbody>
					<tr>
						<td colspan="5" style="font-size:14px; color:#888888; line-height:3.43">
							거래 내역이 없습니다.
						</td>
					</tr>
				</tbody>
				</table>
			</div>

		</div>

	</div>

</div>
