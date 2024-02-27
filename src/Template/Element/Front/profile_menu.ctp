		<style type="text/css">
		.profile_box {
			width: 950px;
			border-radius: 30px;
			text-align: center;
			margin: 30px auto 180px auto;
		}
		.page_title {
			padding-top: 100px;
			font-size: 24px;
			font-weight: bold;
			line-height: 2;
			text-align: center;
			color: #000000;
		}
		.page_sub_title {
			padding-top: 100px;
			font-size: 28px;
			font-weight: bold;
			line-height: 1.07;
			text-align: left;
			color: #000000;
		}
		.tab_menu {
			width: 100%;
			list-style-type: none;
			overflow: hidden;
		}
		.tab_menu li {
			float: left;
			width: 33%;
			height: 70px;
			line-height: 70px;
			text-align: center;
			margin-top: 20px;
			border-top: solid 1px #b5b5b5;
			border-bottom: solid 1px #b5b5b5;
			border-right: solid 1px #b5b5b5;
			background: #ffffff;
		}
		.tab_menu li:nth-child(1) {
			border-left: solid 1px #b5b5b5;
		}
		.tab_menu li.on {
			height: 68px;
			border-bottom: solid 3px #6738ff;
			background: #f9f9f9;
		}
		.tab_menu li a {
			font-size: 20px;
			font-size: 20px;
			font-weight: bold;
			color: #b8b8b8;
		}
		.tab_menu li.on a {
			color: #6738ff;
		}
		</style>

		<div class="page_title">
			마이페이지
		</div>

		<ul class="tab_menu">
			<li class="profile"><a href="<?php echo $this->Url->build(['controller'=>'usersNew','action'=>'profile']) ?>">내 정보</a></li>
			<li class="security"><a href="<?php echo $this->Url->build(['controller'=>'usersNew','action'=>'security']) ?>">개인정보 관리</a></li>

			<li class="verification"><a href="<?php echo $this->Url->build(['controller'=>'usersNew','action'=>'idVerification']) ?>">인증단계</a></li>
		</ul>

		<script>
		$(document).ready(function(){
		<?php if (isset($kind)) { ?>
			$(".<?=$kind ?>").addClass('on');
		<?php } ?>
		});
		</script>
