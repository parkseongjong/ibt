<div class="container">

	<div class="custom_frame">

		<?php echo $this->element('Front/customer_left'); ?>

		<div class="contents">
			<ul>
				<li class="title"><?=$page_title ?></li>
				<li class="search">
					<div>
						<input type="text" name="keyword" class="" placeholder="게시판 내 검색" />
					</div>
				</li>
			</ul>

			<table>
				<thead>
					<tr>
						<th style="width:60px">번호</th>
						<th>제목</th>
						<th style="width:120px">작성일시</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>100</td>
						<td>[공지] COIN IBT거래소 오픈 예정</td>
						<td>2020-07-03</td>
					</tr>
					<tr>
						<td>99</td>
						<td>[이벤트] 가입 고객 2000명 한정 에어드랍 당첨자 안내</td>
						<td>2020-07-02</td>
					</tr>
					<tr>
						<td>98</td>
						<td>[공지] 스마트 TRX, HPOT,WIN/BTT 에어드랍 지급 완료 안내</td>
						<td>2020-07-01</td>
					</tr>
					<tr>
						<td>97</td>
						<td>[이벤트] 투자도하고 이자 받고 레벨4까지 갈 수 있는 기회  </td>
						<td>2020-06-28</td>
					</tr>
					<tr>
						<td>96</td>
						<td>[공지] COIN IBT거래소 오픈 예정</td>
						<td>2020-06-27</td>
					</tr>
					<tr>
						<td>95</td>
						<td>[이벤트] 가입 고객 2000명 한정 에어드랍 당첨자 안내</td>
						<td>2020-06-26</td>
					</tr>
									<tr>
						<td>94</td>
						<td>[공지] 스마트 TRX, HPOT,WIN/BTT 에어드랍 지급 완료 안내</td>
						<td>2020-06-25</td>
					</tr>
				</tbody>
			</table>

			<div class="page_nav">
				<a href="#" class="prev"><</a>
				<a href="/front/customer/<?=$kind ?>/1" class="on">1</a>
				<a href="/front/customer/<?=$kind ?>/2">2</a>
				<a href="/front/customer/<?=$kind ?>/3">3</a>
				<a href="/front/customer/<?=$kind ?>/4">4</a>
				<a href="/front/customer/<?=$kind ?>/5">5</a>
				<a href="/front/customer/<?=$kind ?>/6">6</a>
				<a href="/front/customer/<?=$kind ?>/7">7</a>
				<a href="/front/customer/<?=$kind ?>/8">8</a>
				<a href="/front/customer/<?=$kind ?>/9">9</a>
				<a href="/front/customer/<?=$kind ?>/10">10</a>
				<a href="#" class="next">></a>
			</div>

		</div>

	</div>

</div>
