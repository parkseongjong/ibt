<style type="text/css">
.person_info {
	margin-top: 80px;
}
.person_info ul {
	width: 560px;
	margin: 0 auto;
	list-style-type: none;
	overflow: hidden;
}
.person_info li {
	float: left;
	text-align: left;
	width: 74%;
	font-size: 26px;
	font-weight: normal;
	line-height: 3.15;
	color: #000000;
	border-bottom: solid 2px #eeeeee;
}
.person_info li:nth-child(1) {
	width: 26%;
	color: #a9a9a9;
}
.coin_in_out_box {
	border: solid 2px #eeeeee;
	background-color: #ffffff;
}

.my_option {
}
.my_option .section_title {
	font-size: 28px;
	font-weight: 500;
	line-height: 2.1;
	text-align: left;
	color: #000000;
	margin-top:32px;
	padding-top:20px;
	border-top: solid 2px #e5e5e5;
}
.my_option ul {
	width: 100%;
	list-style-type: none;
	overflow: hidden;
}
.my_option li {
	float: left;
	text-align: left;
	font-size: 23px;
	font-weight: 500;
	line-height: 2.1;
	text-align: left;
	color: #171717;
}
.my_option li:nth-child(2) {
	float: right;
	text-align: left;
	font-size: 20px;
	font-weight: bold;
	line-height: 2.1;
	color: #ffffff;
}
</style>

<div class="container">

	<div class="profile_box">

		<?php echo $this->element('Front/profile_menu'); ?>

		<div class="person_info">
			<ul>
				<li>이메일</li>
				<li>
					<?php
						if(empty($user['email'])){		
							echo $this->Form->input('email',array('disabled','class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"email"));
						} else {
							echo $user['email'];
						}
					?>
				</li>
			</ul>
			<ul>
				<li>이름</li>
				<li>
					<?php 
						if(empty($user['username'])){
							echo $this->Form->input('username',array('class' => 'form-control col-md-7 col-xs-12','label' =>false,"type"=>"text","disabled"=>true)); 
						} else {
							echo $user['username'];
						}
					?>
				</li>
			</ul>
			<ul>
				<li>휴대폰</li>
				<li>
					<?php
						$showstr = '';
						echo '******'.substr($user['phone_number'],count($user['phone_number'])-5,4);
					?>
				</li>
			</ul>
		</div>

		<div class="page_sub_title">
			입출금 레벨
		</div>

		<div class="coin_in_out_box" style="margin-top: 20px">
			<table style="width: 95%; margin: 15px 0;">
				<tbody>
					<tr>
						<td rowspan="2" style="width:36%;">
							<div style="font-size: 22px; font-weight: bold; line-height: 1.36; color: #000000;">
								<span style="color: #6738ff">코인</span> <span style="color: #000000">입출금 레벨</span>
							</div>
							<div style="font-size: 46px; font-weight: bold; line-height: 1.14; color: #6738ff;">
								Lv.2
							</div>
						</td>
						<td style="width:32%; text-align: left; line-height: 2; font-size: 23px;">
							<div style="font-size: 26px; color: #a9a9a9; line-height: 2.4;">입금한도</div>
							<div style="color: #000000;"><span style="color: #0e2aff;">일</span> 출금 잔여한도</div>
							<div style="color: #000000;"><span style="color: #0e2aff;">월</span> 출금 잔여한도</div>
						</td>
						<td style="width:32%; text-align: right; line-height: 1.8; font-size: 23px;">
							<div style="font-size: 26px; line-height: 2.2; color: #000000;">무제한</div>
							<div style="color: #8d8d8d;"><span style="color: #000000;">10,000,000</span> KRW</div>
							<div style="color: #8d8d8d;"><span style="color: #000000;">30,000,000</span> KRW</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: left; line-height: 3; font-size: 22px;">
							<div style="color: #a9a9a9;">출금한도 : <span style="color: #0e2aff;">일 1천만원 / 월 3천만원 / 1회 1백만원</span></div>
						</td>
					</tr>
				</tbody>
			</table>

		</div>



		<div class="page_sub_title">
			계정 설정
		</div>

		<div class="my_option">

			<div class="section_title">
				로그인 차단
			</div>

			<ul>
				<li class="ul_title">
					해외 로그인 차단 <span class="ul_help">KR 외 국가 접속 차단</span>
				</li>
				<li>
					<div style="overflow:hidden; width: 76px; padding: 0 0 0 11px; border-radius: 20px; background-color: #240978;"><div style="float:left; line-height:35px; ">ON</div><div style="float:right; margin: 4px; width:26px; height:26px; background:#fff; border-radius:20px;">&nbsp;</div></div>
				</li>
			</ul>

			<div class="section_title">
				이벤트/광고 수신/제공동의
			</div>

			<ul>
				<li class="ul_title">
					이벤트/광고 목적 개인정보 제공
				</li>
				<li>
					<div style="overflow:hidden; width: 76px; padding: 0 13px 0 0; border-radius: 20px; background-color: #c7c7c7;"><div style="float:left; margin: 4px; width:26px; height:26px; background:#fff; border-radius:20px;">&nbsp;</div><div style="float:right; line-height:35px; ">OFF</div></div>
				</li>
			</ul>

			<ul>
				<li class="ul_title">
					이벤트/광고 등 혜택 정보 수신 동의 (SMS/Email)
				</li>
				<li>
					<div style="overflow:hidden; width: 76px; padding: 0 13px 0 0; border-radius: 20px; background-color: #c7c7c7;"><div style="float:left; margin: 4px; width:26px; height:26px; background:#fff; border-radius:20px;">&nbsp;</div><div style="float:right; line-height:35px; ">OFF</div></div>
				</li>
			</ul>

			<div class="section_title">
				&nbsp;
			</div>

		</div>

	</div>

</div>
